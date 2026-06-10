<?php

require_once __DIR__ . '/database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$db = dbConnect();

if (!$db) {
    echo json_encode([
        'error' => 'Impossible de se connecter à la base de données.'
    ]);
    exit();
}

/*
    JSON INPUT
*/
$rawInput = file_get_contents('php://input');
$jsonInput = json_decode($rawInput, true);
if (!is_array($jsonInput)) {
    $jsonInput = [];
}

// Make PUT payload available like PHP superglobals for AJAX-style requests
$_PUT = [];
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str($rawInput, $parsedInput);
    if (!empty($parsedInput)) {
        $_PUT = $parsedInput;
    } else {
        $_PUT = $jsonInput;
    }
}

/*
    REQUEST METHOD
*/
$req = $_SERVER['REQUEST_METHOD'];

/*
    ROUTE
*/
$requestRessource = '';

/*
    PATH_INFO
*/
if (isset($_SERVER['PATH_INFO'])) {
    $request = substr($_SERVER['PATH_INFO'], 1);
    $request = explode('/', $request);
    $requestRessource = array_shift($request);
}

/*
    FALLBACK GET PARAM
    ex:
    request.php?action=test
*/
if (empty($requestRessource) && isset($_GET['action'])) {
    $requestRessource = $_GET['action'];
}

/*
    NO ROUTE
*/
if (empty($requestRessource)) {
    echo json_encode([
        'error' => 'Aucune ressource demandée.'
    ]);

    exit();
}

switch($req){
    // Getting data from the database and sending it to the client
    case "GET" : {
        switch($requestRessource){
            case "test" : {
                $result = dbTest($db);
                echo json_encode($result);
                break;
            }
            case "account" : {
                $result = dbConnectUser($db);
                echo json_encode($result);
                break;
            }
            case "data_agri" : {
                $result = dbDataAgri($db);
                echo json_encode($result);
                break;
            }
            case "data_agri_all" : {
                $result = dbDataAgriAll($db);
                echo json_encode($result);
                break;
            }
            case "table_template" : {
                $result = dbTableTemplate($db);
                echo json_encode($result);
                break;
            }
            case "users" : {
                $result = dbUsers($db);
                echo json_encode($result);
                break;
            }
            case "managers" : {
                $result = dbManagers($db);
                echo json_encode($result);
                break;
            }
            case "groups" : {
                $result = dbGroups($db);
                echo json_encode($result);
                break;
            }
            case "user_search" : {          // looking for a users with a research string (can be his name or surname or email)
                $result = dbUserSearch($db);
                echo json_encode($result);
                break;
            }
            case "group_search" : {         // looking for a group with a research string (can be the group name)
                $result = dbGroupSearch($db);
                echo json_encode($result);
                break;
            }
            default : {
                echo json_encode([
                    'error' => 'Ressource GET non définie : ' . $requestRessource
                ]);
            }
        }
        break;
    }
    // Receiving data from the client and processing it (e.g. inserting into the database)
    case "POST" : {
        $_POST = array_merge($_POST, $jsonInput);
        switch($requestRessource){
            case "datas" : {
                echo json_encode([
                    'success' => true
                ]);
                break;
            }
            case "update_agri" : {
                $result = dbUpdateAgri($db);
                echo json_encode($result);
                break;
            }
            case "create_agri_user" : {
                $result = dbCreateAgriUser($db);
                echo json_encode($result);
                break;
            }
            case "create_agri_users" : {
                $result = dbCreateAgriUsers($db);
                echo json_encode($result);
                break;
            }
            case "create_group" : {
                $result = dbCreateGroup($db);
                echo json_encode($result);
                break;
            }
            default : {
                echo json_encode([
                    'error' => 'Ressource POST non définie : ' . $requestRessource
                ]);
            }
        }
        break;
    }
    // Receiving data from the client and processing it (e.g. updating the database)
    case "PUT" : {
        $_PUT = $jsonInput;
        // echo json_encode($_PUT);
        switch($requestRessource){
            case "datas" : {
                echo json_encode([
                    'success' => true,
                    'data' => $_PUT
                ]);
                break;
            }
            case "add_user_to_group" : {
                $result = dbAddUserToGroup($db, $_PUT);
                echo json_encode($result);
                break;
            }
            case "add_manager_to_group" : {
                $result = dbAddManagerToGroup($db, $_PUT);
                echo json_encode($result);
                break;
            }
            case "remove_user_from_group" : {
                $result = dbRemoveUserFromGroup($db);
                echo json_encode($result);
                break;
            }
            case "remove_manager_from_group" : {
                $result = dbRemoveManagerFromGroup($db);
                echo json_encode($result);
                break;
            }
            case "delete_group" : {
                $result = dbDeleteGroup($db);
                echo json_encode($result);
                break;
            }
            case "change_group_name" : {
                $result = dbChangeGroupName($db);
                echo json_encode($result);
                break;
            }

            default : {
                echo json_encode([
                    'error' => 'Ressource PUT non définie : ' . $requestRessource
                ]);
            }
        }
        break;
    }
    // Receiving data from the client and processing it (e.g. deleting from the database)
    case "DELETE" : {
        $_DELETE = $jsonInput;
        echo json_encode([
            'success' => true,
            'message' => 'DELETE reçu'
        ]);
        break;
    }
    default : {
        echo json_encode([
            'error' => 'Méthode HTTP non supportée.'
        ]);
    }
}

?>