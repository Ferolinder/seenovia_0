<?php
## Place where we put the functions that interact with the database
require_once('consts.php');

/**
 * Create and return a PDO database connection.
 *
 * @return PDO|null
 */
function db_connect(): ?PDO {
  $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', DB_SERVER, DB_PORT, DB_NAME);

  try {
    $db = new PDO($dsn, DB_USER, DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $db;
  } catch (PDOException $exception) {
    return null;
  }
}

/**
 * Check credentials in users first, then seenovia.
 * Returns the redirect page on success or null on failure.
 *
 * @param PDO $db
 * @param string $username
 * @param string $password
 * @return string|null
 */
function get_login_redirect(PDO $db, string $username, string $password): ?string {
  $sql = 'SELECT id FROM users WHERE nom = :username AND mdp = :password LIMIT 1';
  $stmt = $db->prepare($sql);
  $stmt->execute([':username' => $username, ':password' => $password]);

  if ($stmt->fetch()) {
    return 'agri.php';
  }

  $sql = 'SELECT id FROM seenovia WHERE nom = :username AND mdp = :password LIMIT 1';
  $stmt = $db->prepare($sql);
  $stmt->execute([':username' => $username, ':password' => $password]);

  if ($stmt->fetch()) {
    return 'seenovia.php';
  }

  return null;
}

?>

