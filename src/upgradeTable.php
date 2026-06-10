<!-- this page is meant to allow the user to add new account and their datas -->

<?php
  error_reporting(E_ALL ^ E_NOTICE); // Omit notices
  session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Upgrade Table</title>
  <link rel="icon" type="image/x-icon" href="pictures/logo.png">
  <link href="css/style.css" rel="stylesheet">

  <script src="js/ajax.js" defer></script>
  <script src="js/upgradeTable.js" defer></script>
  <script src="js/utils.js" defer></script>
</head>

<body>
<?php
  require_once("views/header_admin.php");
  require_once("views/content_upgradeTable.php");
  require_once("views/footer.php");
?>
</body>

</html>

