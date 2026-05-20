<?php
  error_reporting(E_ALL ^ E_NOTICE); // Omit notices
  session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agri</title>
  <link rel="icon" type="images/x-icon" href="pictures/logo.png">

  <link href="css/style.css" rel="stylesheet">

  <script src="js/ajax.js" defer></script>
  <!-- <script src="js/agri.js" defer></script> -->
</head>

<body>
<?php
  require_once("views/header.php");+

  require_once("views/footer.php");
?>
</body>

</html>

