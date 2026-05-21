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

        $request = 'SELECT id, admin FROM users WHERE adresseMail = :mail AND mdp = :mdp LIMIT 1';
        $statement = $dbb->prepare($request);
        $statement->bindParam(':mail', $mail);
        $statement->bindParam(':mdp', $mdp);
        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);
        
        if ($user === false) {
            return 0;
        }
        
        // Si c'est un admin, retourner true pour redirection vers seenovia
        if ($user['admin'] === true || $user['admin'] === 1 || $user['admin'] === 't') {
            return true;
        }
        
        // Sinon retourner l'id pour redirection vers agri
        return (int) $user['id'];
    }
    catch (PDOException $e) {
        return "e0";
    }
}

function dbDataAgri($dbb){
    try {
        $id_user = $_GET['id_user'] ?? 0;
        if (!$id_user) {
            return [];  
        }

        $dbb->beginTransaction();

        $cropStmt = $dbb->query('SELECT id FROM crops');
        $cropIds = $cropStmt->fetchAll(PDO::FETCH_COLUMN);

        $findLinkStmt = $dbb->prepare('SELECT id, spec_id FROM link WHERE user_id = :user_id AND crop_id = :crop_id');
        $insertSpecStmt = $dbb->prepare('INSERT INTO spec (surface, engrais, phyto, A, B, C) VALUES (0, 0, 0, 0, 0, 0) RETURNING id');
        $insertLinkStmt = $dbb->prepare('INSERT INTO link (spec_id, crop_id, user_id) VALUES (:spec_id, :crop_id, :user_id)');
        $updateLinkStmt = $dbb->prepare('UPDATE link SET spec_id = :spec_id WHERE id = :link_id');

        foreach ($cropIds as $cropId) {
            $findLinkStmt->execute([
                ':user_id' => $id_user,
                ':crop_id' => $cropId
            ]);
            $link = $findLinkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$link) {
                $insertSpecStmt->execute();
                $spec_id = $insertSpecStmt->fetchColumn();
                $insertLinkStmt->execute([
                    ':spec_id' => $spec_id,
                    ':crop_id' => $cropId,
                    ':user_id' => $id_user
                ]);
            } elseif (empty($link['spec_id'])) {
                $insertSpecStmt->execute();
                $spec_id = $insertSpecStmt->fetchColumn();
                $updateLinkStmt->execute([
                    ':spec_id' => $spec_id,
                    ':link_id' => $link['id']
                ]);
            }
        }

        $dbb->commit();

        $request = 'SELECT u.nom AS user_nom, u.prenom AS user_prenom, link.*, spec.surface, spec.engrais, spec.phyto, spec.A, spec.B, spec.C, crops.nom AS crop_nom FROM link JOIN users u ON link.user_id = u.id JOIN spec ON link.spec_id = spec.id JOIN crops ON link.crop_id = crops.id WHERE link.user_id = :id_user ORDER BY crops.id';
        $statement = $dbb->prepare($request);
        $statement->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    catch (PDOException $e) {
        if ($dbb->inTransaction()) {
            $dbb->rollBack();
        }
        print $e;
        echo "<p id='Error'>Une erreur s'est produite lors de l'exécution de la requête.</p>";
    }
}

function dbDataAgriAll($dbb){
    try {
        // Ensure links/spec exist for all non-admin users and all crops
        $dbb->beginTransaction();

        $userStmt = $dbb->query("SELECT id FROM users WHERE admin = false OR admin IS NULL");
        $userIds = $userStmt->fetchAll(PDO::FETCH_COLUMN);

        $cropStmt = $dbb->query('SELECT id FROM crops');
        $cropIds = $cropStmt->fetchAll(PDO::FETCH_COLUMN);

        $findLinkStmt = $dbb->prepare('SELECT id, spec_id FROM link WHERE user_id = :user_id AND crop_id = :crop_id');
        $insertSpecStmt = $dbb->prepare('INSERT INTO spec (surface, engrais, phyto, A, B, C) VALUES (0, 0, 0, 0, 0, 0) RETURNING id');
        $insertLinkStmt = $dbb->prepare('INSERT INTO link (spec_id, crop_id, user_id) VALUES (:spec_id, :crop_id, :user_id)');
        $updateLinkStmt = $dbb->prepare('UPDATE link SET spec_id = :spec_id WHERE id = :link_id');

        foreach ($userIds as $uId) {
            foreach ($cropIds as $cropId) {
                $findLinkStmt->execute([
                    ':user_id' => $uId,
                    ':crop_id' => $cropId
                ]);
                $link = $findLinkStmt->fetch(PDO::FETCH_ASSOC);
                if (!$link) {
                    $insertSpecStmt->execute();
                    $spec_id = $insertSpecStmt->fetchColumn();
                    $insertLinkStmt->execute([
                        ':spec_id' => $spec_id,
                        ':crop_id' => $cropId,
                        ':user_id' => $uId
                    ]);
                } elseif (empty($link['spec_id'])) {
                    $insertSpecStmt->execute();
                    $spec_id = $insertSpecStmt->fetchColumn();
                    $updateLinkStmt->execute([
                        ':spec_id' => $spec_id,
                        ':link_id' => $link['id']
                    ]);
                }
            }
        }

        $dbb->commit();

        // Fetch rows only for non-admin users
        $request = 'SELECT u.id AS user_id, u.nom AS user_nom, u.prenom AS user_prenom, link.*, spec.surface, spec.engrais, spec.phyto, spec.A, spec.B, spec.C, crops.nom AS crop_nom FROM link JOIN users u ON link.user_id = u.id JOIN spec ON link.spec_id = spec.id JOIN crops ON link.crop_id = crops.id WHERE (u.admin = false OR u.admin IS NULL) ORDER BY u.id, crops.id';
        $statement = $dbb->prepare($request);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    catch (PDOException $e) {
        if ($dbb->inTransaction()) {
            $dbb->rollBack();
        }
        print $e;
        echo "<p id='Error'>Une erreur s'est produite lors de l'exécution de la requête.</p>";
    }
}

function dbUpdateAgri($dbb){
    try {
        $updates = $_POST['updates'] ?? '';
        if (!$updates) {
            return ['error' => 'Aucune donnée à mettre à jour.'];
        }

        $rows = json_decode($updates, true);
        if (!is_array($rows)) {
            return ['error' => 'Format de données invalide.'];
        }

        $statement = $dbb->prepare('UPDATE spec SET surface = :surface, engrais = :engrais, phyto = :phyto, A = :A, B = :B, C = :C WHERE id = :spec_id');
        $updated = 0;

        foreach ($rows as $row) {
            if (empty($row['spec_id'])) {
                continue;
            }

            $statement->execute([
                ':surface' => $row['surface'] !== null ? $row['surface'] : 0,
                ':engrais' => $row['engrais'] !== null ? $row['engrais'] : 0,
                ':phyto' => $row['phyto'] !== null ? $row['phyto'] : 0,
                ':A' => $row['A'] !== null ? $row['A'] : 0,
                ':B' => $row['B'] !== null ? $row['B'] : 0,
                ':C' => $row['C'] !== null ? $row['C'] : 0,
                ':spec_id' => $row['spec_id']
            ]);

            $updated += $statement->rowCount();
        }

        return ['success' => true, 'updated' => $updated, 'message' => "$updated ligne(s) mises à jour."];
    }
    catch (PDOException $e) {
        return ['error' => 'Erreur lors de la mise à jour des données.'];
    }
}

function dbCreateAgriUser($dbb){
    try {
        $userData = $_POST['userData'] ?? '';
        if (!$userData) {
            return ['error' => 'Aucune donnée reçue.'];
        }

        $data = json_decode($userData, true);
        if (!is_array($data)) {
            return ['error' => 'Format de données invalide.'];
        }

        // Validate required fields
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['adresseMail']) || empty($data['mdp'])) {
            return ['error' => 'Les champs nom, prénom, email et mot de passe sont requis.'];
        }

        // Check if email already exists
        $checkStmt = $dbb->prepare('SELECT id FROM users WHERE adresseMail = :email');
        $checkStmt->execute([':email' => $data['adresseMail']]);
        if ($checkStmt->fetch()) {
            return ['error' => 'Cet email est déjà utilisé.'];
        }

        // Insert new user
        $insertStmt = $dbb->prepare('INSERT INTO users (nom, prenom, adresseMail, telephone, mdp, admin) VALUES (:nom, :prenom, :email, :telephone, :mdp, FALSE)');
        $insertStmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['adresseMail'],
            ':telephone' => $data['telephone'] ?? null,
            ':mdp' => $data['mdp']
        ]);

        return ['success' => true, 'message' => 'Compte agri créé avec succès.'];
    }
    catch (PDOException $e) {
        return ['error' => 'Erreur lors de la création du compte.'];
    }
}

?>

