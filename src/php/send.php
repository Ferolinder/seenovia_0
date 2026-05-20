<?php

/**
 * Send data the the client and json_encode it
 *
 * @param string $data
 * @return void
 */
function send($data): void {
  header('Content-Type: text/plain');
  header('Cache-control: no-store, no-cache, must-revalidate');
  header('Pragma: no-cache');
  header('HTTP/1.1 200 OK');
  echo(json_encode($data));
}


/**
 * Respond to the client with a generic 'bad request'
 *
 * @return void
 */
function bad(): void {
  header('HTTP/1.1 400 Bad Request');
  die();
}


/**
 * Respond to the client with a generic 'unauthorized'
 *
 * @return void
 */
function no_allowed(): void {
  header('HTTP/1.1 401 Unauthorized');
  die();
}

?>
