<?php
## Place where we put the functions that interact with the database
include_once("consts.php");

function dbConnect(){ //connexion avec la base de données
    $dsn = 'pgsql:dbname='.db_name.';host='.db_serveur.';port='.db_port;
    try {
        $conn = new PDO($dsn, db_user, db_password);
        return $conn;
    } catch (PDOException $e) {
        echo 'Connexion échouée : ' . $e->getMessage();
    }
}

function dbTest($dbb){
    try {
        //getting the request data
        $tab = $_GET['tab'];

        //request
        $request = 'SELECT * FROM crops';
        $statement = $dbb->prepare($request);

        //binding the datas
        // $statement->bindParam(':tab', $tab);

        //executing
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    catch (PDOException $e) {
        print $e;
        echo "<p id='Error'>Une erreur s'est produite lors de l'exécution de la requête.</p>";
    }
}

function dbConnectUser($dbb){
    try {
        $mail = $_GET['mail'] ?? '';
        $mdp = $_GET['mdp'] ?? '';

        $request = 'SELECT id FROM users WHERE adresseMail = :mail AND mdp = :mdp LIMIT 1';
        $statement = $dbb->prepare($request);
        $statement->bindParam(':mail', $mail);
        $statement->bindParam(':mdp', $mdp);
        $statement->execute();

        $id = $statement->fetchColumn();
        return $id !== false ? (int) $id : 0;
    }
    catch (PDOException $e) {
        return "e0";
    }
}

function dbConnectSeenovia($dbb){
    try {
        $mail = $_GET['mail'] ?? '';
        $mdp = $_GET['mdp'] ?? '';

        $request = 'SELECT 1 FROM seenovia WHERE adresseMail = :mail AND mdp = :mdp LIMIT 1';
        $statement = $dbb->prepare($request);
        $statement->bindParam(':mail', $mail);
        $statement->bindParam(':mdp', $mdp);
        $statement->execute();

        $exists = $statement->fetchColumn();
        return $exists !== false ? true : 0;
    }
    catch (PDOException $e) {
        return "e0";
    }
}


?>

