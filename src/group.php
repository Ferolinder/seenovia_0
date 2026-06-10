<!-- page dedicated to see, create and manage the different groups on the database, only meant to be edited by administrators.  -->
 <?php
  error_reporting(E_ALL ^ E_NOTICE); // Omit notices
  session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Group Manager</title>
  <link rel="icon" type="image/x-icon" href="pictures/logo.png">
  <link href="css/style.css" rel="stylesheet">

  <script src="js/ajax.js" defer></script>
  <script src="js/group.js" defer></script>
  <script src="js/utils.js" defer></script>
</head>

<body>
<?php
  require_once("views/header_admin.php");
  require_once("views/content_group.php");
  require_once("views/footer.php");
?>
</body>

</html>