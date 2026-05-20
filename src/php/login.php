<?php


/**
 * Save the user credentials if they are valid and return bool depending on success
 *
 * @param PDO $db
 * @param string $username
 * @param string $password
 * @return bool
 */
function sign_in(PDO $db, string $username, string $password): bool {
  $success = is_pwd_valid($db, $username, $password);

  if ($success){
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
  }

  return $success;
}


/**
 * Register a new user if no user have the same username, and return bool depending on success
 *
 * @param PDO $db
 * @param string $username
 * @param string $password
 * @return bool
 */
function register(PDO $db, string $username, string $password): bool {
  $success = add_user($db, $username, $password);
  return $success;
}


/**
 * Unset the session variable, login out the user. Always returns true
 *
 * @return bool
 */
function logout(): bool {
  session_unset();
  return true;
}


/**
 * Check if session variables are set and return a bool
 *
 * @return bool
 */
function check_logged_in(): bool {
  return (isset($_SESSION['username']) && isset($_SESSION['password']));
}


// Start the session when file required / included
session_start();

?>
