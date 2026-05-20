<?php
  error_reporting(E_ALL ^ E_NOTICE); // Omit notices
  session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accueil</title>
  <link rel="icon" type="image/x-icon" href="image/logo.png">
  <link href="css/style.css" rel="stylesheet">

  <script src="js/ajax.js" defer></script>
</head>

<body>
<?php
  require_once("views/header.php");
  require_once("views/contentseenovia.php");
  require_once("views/footer.php");
?>
</body>

</html>

