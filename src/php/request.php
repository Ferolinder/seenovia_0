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

if (
    empty($requestRessource) &&
    isset($_GET['action'])
) {

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

/*
    GET
*/

switch($req){

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

            default : {

                echo json_encode([
                    'error' => 'Ressource GET non définie : ' . $requestRessource
                ]);
            }
        }

        break;
    }

    /*
        POST
    */

    case "POST" : {

        /*
            Inject JSON into $_POST compatibility
        */

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

            default : {

                echo json_encode([
                    'error' => 'Ressource POST non définie : ' . $requestRessource
                ]);
            }
        }

        break;
    }

    /*
        PUT
    */

    case "PUT" : {

        $_PUT = $jsonInput;

        switch($requestRessource){

            case "datas" : {

                echo json_encode([
                    'success' => true,
                    'data' => $_PUT
                ]);

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

    /*
        DELETE
    */

    case "DELETE" : {

        $_DELETE = $jsonInput;

        echo json_encode([
            'success' => true,
            'message' => 'DELETE reçu'
        ]);

        break;
    }

    /*
        UNKNOWN METHOD
    */

    default : {

        echo json_encode([
            'error' => 'Méthode HTTP non supportée.'
        ]);
    }
}

?>