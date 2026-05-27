<?php
require_once __DIR__ . '/database.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db = dbConnect();

if (!$db) {
  echo json_encode(['error' => 'Impossible de se connecter à la base de données. Vérifiez que le driver PostgreSQL est installé.']);
  exit();
}

$req = $_SERVER['REQUEST_METHOD'];

if (!isset($_SERVER['PATH_INFO'])) {
  echo json_encode(['error' => 'PATH_INFO manquant']);
  exit();
}

$request = substr($_SERVER['PATH_INFO'], 1);
$request = explode('/', $request);
$requestRessource = array_shift($request);

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
        echo json_encode(['error' => 'Ressource GET non définie: ' . $requestRessource]);
      }
    }
    break;
  }
    
  case "POST" : {
    switch($requestRessource){
      case "datas" : {
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
        echo json_encode(['error' => 'Ressource POST non définie: ' . $requestRessource]);
      }
    }
    break;
  }           
      
  case "PUT" : {
    parse_str(file_get_contents('php://input'), $_PUT);
    switch($requestRessource){
      case "datas" : {
        break;
      }
      default : {
        echo json_encode(['error' => 'Ressource PUT non définie: ' . $requestRessource]);
      }
    }
    break;
  }
        
  case "DELETE" : {
    break;        
  }
}
?>