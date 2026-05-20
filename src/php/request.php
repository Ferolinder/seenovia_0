<?php

## Place where we handle the requests and route them to the right function

// Typage strict
declare(strict_types=1); 

require_once('send.php');
require_once('database.php');
require_once('login.php');


// Lsp is going insane without this
$_PUT = null;
$_DELETE = null;

// Connect to db
$db = db_connect();
if (! $db) {
  header('HTTP/1.1 503 Service Unavailable');
  exit();
}


// No requested ressource
if (! isset($_SERVER['PATH_INFO']))
  bad();

// Extract url
$method = $_SERVER['REQUEST_METHOD'];
$request = substr($_SERVER['PATH_INFO'], 1);
$request = explode('/', $request);
$ressource = array_shift($request);

// GET REQUEST
if ($method == 'GET'){

  
}


// POST REQUEST
else if ($method == 'POST'){

}


// PUT REQUEST
else if ($method == 'PUT'){
  
}

// DELETE REQUEST
else if ($method === 'DELETE'){

}


// Wrong method
else bad();

?>
