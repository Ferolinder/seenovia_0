<?php
## Place where we put the functions that interact with the database
include_once("consts.php");

function dbConnect(){ // connexion avec la base de données

    $dsn =
        'mysql:host=' . db_serveur .
        ';dbname=' . db_name .
        ';port=' . db_port .
        ';charset=utf8mb4';

    try {

        $conn = new PDO(
            $dsn,
            db_user,
            db_password
        );

        // Active les erreurs PDO
        $conn->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        // UTF8
        $conn->exec("SET NAMES utf8mb4");

        return $conn;

    }
    catch (PDOException $e) {

        echo 'Connexion échouée : ' . $e->getMessage();
    }
}

function dbTest($dbb){

    try {

        // getting request data
        $tab = $_GET['tab'] ?? '';

        // request
        $request = 'SELECT * FROM crops';

        $statement = $dbb->prepare($request);

        // executing
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
    catch (PDOException $e) {

        print_r($e);

        echo "<p id='Error'>
                Une erreur s'est produite lors de l'exécution de la requête.
              </p>";
    }
}

function dbConnectUser($dbb){

    try {

        $mail = $_GET['mail'] ?? '';
        $mdp = $_GET['mdp'] ?? '';

        $request =
            'SELECT id, admin
             FROM users
             WHERE adresseMail = :mail
             AND mdp = :mdp
             LIMIT 1';

        $statement = $dbb->prepare($request);

        $statement->bindParam(':mail', $mail);
        $statement->bindParam(':mdp', $mdp);

        $statement->execute();

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Aucun utilisateur
        if ($user === false) {

            return 0;
        }

        // Si admin
        if (
            $user['admin'] == true ||
            $user['admin'] == 1
        ) {

            return true;
        }

        // Sinon utilisateur normal
        return (int)$user['id'];
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

        $findLinkStmt = $dbb->prepare(
            'SELECT id, spec_id
             FROM link
             WHERE user_id = :user_id
             AND crop_id = :crop_id'
        );

        $insertSpecStmt = $dbb->prepare(
            'INSERT INTO spec (surface, engrais, phyto, A, B, C)
             VALUES (0,0,0,0,0,0)'
        );

        $insertLinkStmt = $dbb->prepare(
            'INSERT INTO link (spec_id, crop_id, user_id)
             VALUES (:spec_id, :crop_id, :user_id)'
        );

        $updateLinkStmt = $dbb->prepare(
            'UPDATE link
             SET spec_id = :spec_id
             WHERE id = :link_id'
        );

        foreach ($cropIds as $cropId) {

            $findLinkStmt->execute([
                ':user_id' => $id_user,
                ':crop_id' => $cropId
            ]);

            $link = $findLinkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$link) {

                $insertSpecStmt->execute();

                $spec_id = $dbb->lastInsertId();

                $insertLinkStmt->execute([
                    ':spec_id' => $spec_id,
                    ':crop_id' => $cropId,
                    ':user_id' => $id_user
                ]);
            }
            elseif (empty($link['spec_id'])) {

                $insertSpecStmt->execute();

                $spec_id = $dbb->lastInsertId();

                $updateLinkStmt->execute([
                    ':spec_id' => $spec_id,
                    ':link_id' => $link['id']
                ]);
            }
        }

        $dbb->commit();

        $request =
            'SELECT
                u.nom AS user_nom,
                u.prenom AS user_prenom,
                link.*,
                spec.surface,
                spec.engrais,
                spec.phyto,
                spec.A,
                spec.B,
                spec.C,
                crops.nom AS crop_nom

             FROM link
             JOIN users u ON link.user_id = u.id
             JOIN spec ON link.spec_id = spec.id
             JOIN crops ON link.crop_id = crops.id
             WHERE link.user_id = :id_user
             ORDER BY crops.id';

        $statement = $dbb->prepare($request);

        $statement->bindParam(':id_user', $id_user, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {

        if ($dbb->inTransaction()) {
            $dbb->rollBack();
        }

        return [
            'error' => $e->getMessage()
        ];
    }
}


/* ------------------------------------------------------------------ */

function dbDataAgriAll($dbb){

    try {

        $dbb->beginTransaction();

        $userStmt = $dbb->query(
            "SELECT id
             FROM users
             WHERE admin = false OR admin IS NULL"
        );

        $userIds = $userStmt->fetchAll(PDO::FETCH_COLUMN);

        $cropStmt = $dbb->query('SELECT id FROM crops');
        $cropIds = $cropStmt->fetchAll(PDO::FETCH_COLUMN);

        $findLinkStmt = $dbb->prepare(
            'SELECT id, spec_id
             FROM link
             WHERE user_id = :user_id
             AND crop_id = :crop_id'
        );

        $insertSpecStmt = $dbb->prepare(
            'INSERT INTO spec (surface, engrais, phyto, A, B, C)
             VALUES (0,0,0,0,0,0)'
        );

        $insertLinkStmt = $dbb->prepare(
            'INSERT INTO link (spec_id, crop_id, user_id)
             VALUES (:spec_id, :crop_id, :user_id)'
        );

        $updateLinkStmt = $dbb->prepare(
            'UPDATE link
             SET spec_id = :spec_id
             WHERE id = :link_id'
        );

        foreach ($userIds as $uId) {

            foreach ($cropIds as $cropId) {

                $findLinkStmt->execute([
                    ':user_id' => $uId,
                    ':crop_id' => $cropId
                ]);

                $link = $findLinkStmt->fetch(PDO::FETCH_ASSOC);

                if (!$link) {

                    $insertSpecStmt->execute();

                    $spec_id = $dbb->lastInsertId();

                    $insertLinkStmt->execute([
                        ':spec_id' => $spec_id,
                        ':crop_id' => $cropId,
                        ':user_id' => $uId
                    ]);
                }
                elseif (empty($link['spec_id'])) {

                    $insertSpecStmt->execute();

                    $spec_id = $dbb->lastInsertId();

                    $updateLinkStmt->execute([
                        ':spec_id' => $spec_id,
                        ':link_id' => $link['id']
                    ]);
                }
            }
        }

        $dbb->commit();

        $request =
            'SELECT
                u.id AS user_id,
                u.nom AS user_nom,
                u.prenom AS user_prenom,
                u.adresseMail AS user_email,
                u.telephone AS user_telephone,
                link.*,
                spec.surface,
                spec.engrais,
                spec.phyto,
                spec.A,
                spec.B,
                spec.C,
                crops.nom AS crop_nom

             FROM link
             JOIN users u ON link.user_id = u.id
             JOIN spec ON link.spec_id = spec.id
             JOIN crops ON link.crop_id = crops.id
             WHERE (u.admin = false OR u.admin IS NULL)
             ORDER BY u.id, crops.id';

        $statement = $dbb->prepare($request);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {

        if ($dbb->inTransaction()) {
            $dbb->rollBack();
        }

        return [
            'error' => $e->getMessage()
        ];
    }
}


/* ------------------------------------------------------------------ */

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

        $statement = $dbb->prepare(
            'UPDATE spec
             SET
                surface = :surface,
                engrais = :engrais,
                phyto = :phyto,
                A = :A,
                B = :B,
                C = :C
             WHERE id = :spec_id'
        );

        $updated = 0;

        foreach ($rows as $row) {

            if (empty($row['spec_id'])) {
                continue;
            }

            $statement->execute([

                ':surface' => $row['surface'] ?? 0,
                ':engrais' => $row['engrais'] ?? 0,
                ':phyto' => $row['phyto'] ?? 0,
                ':A' => $row['A'] ?? 0,
                ':B' => $row['B'] ?? 0,
                ':C' => $row['C'] ?? 0,

                ':spec_id' => $row['spec_id']
            ]);

            $updated += $statement->rowCount();
        }

        return [
            'success' => true,
            'updated' => $updated,
            'message' => $updated . ' ligne(s) mises à jour.'
        ];
    }
    catch (PDOException $e) {

        return [
            'error' => 'Erreur lors de la mise à jour des données : ' . $e->getMessage()
        ];
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

        if (empty($data['nom']) || empty($data['prenom']) || empty($data['adresseMail']) || empty($data['mdp'])) {
            return ['error' => 'Les champs nom, prénom, email et mot de passe sont requis.'];
        }

        $checkStmt = $dbb->prepare('SELECT id FROM users WHERE adresseMail = :email');
        $checkStmt->execute([':email' => $data['adresseMail']]);

        if ($checkStmt->fetch()) {
            return ['error' => 'Cet email est déjà utilisé.'];
        }

        $insertStmt = $dbb->prepare(
            'INSERT INTO users (nom, prenom, adresseMail, telephone, mdp, admin)
             VALUES (:nom, :prenom, :email, :telephone, :mdp, FALSE)'
        );

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

function dbCreateAgriUsers($dbb) {

    try {

        $usersData = $_POST['usersData'] ?? '';

        if (!$usersData) {
            return ['error' => 'Aucune donnée reçue.'];
        }

        $users = json_decode($usersData, true);

        if (!is_array($users)) {
            return ['error' => 'Format JSON invalide.'];
        }

        $dbb->beginTransaction();

        $checkUserStmt = $dbb->prepare('SELECT id FROM users WHERE adresseMail = :email');

        $insertUserStmt = $dbb->prepare(
            'INSERT INTO users (nom, prenom, adresseMail, telephone, mdp, admin)
             VALUES (:nom, :prenom, :email, :telephone, :mdp, FALSE)'
        );

        $checkCropStmt = $dbb->prepare('SELECT id FROM crops WHERE nom = :nom');

        $insertCropStmt = $dbb->prepare(
            'INSERT INTO crops (nom) VALUES (:nom)'
        );

        $insertSpecStmt = $dbb->prepare(
            'INSERT INTO spec (surface, engrais, phyto, A, B, C)
             VALUES (:surface, :engrais, :phyto, :A, :B, :C)'
        );

        $insertLinkStmt = $dbb->prepare(
            'INSERT INTO link (spec_id, crop_id, user_id)
             VALUES (:spec_id, :crop_id, :user_id)'
        );

        $created = 0;

        foreach ($users as $userIndex => $user) {

            $nom = trim($user['nom'] ?? '');
            $prenom = trim($user['prenom'] ?? '');
            $email = trim($user['adresseMail'] ?? '');
            $telephone = trim($user['telephone'] ?? '');
            $mdp = trim($user['mdp'] ?? '');

            if ($nom === '' || $prenom === '' || $email === '' || $mdp === '') {
                $dbb->rollBack();
                return [
                    'error' => 'Agriculteur #' . ($userIndex + 1) . ' : informations utilisateur manquantes.'
                ];
            }

            $checkUserStmt->execute([':email' => $email]);

            if ($checkUserStmt->fetch()) {
                $dbb->rollBack();
                return [
                    'error' => 'Agriculteur #' . ($userIndex + 1) . ' : email déjà utilisé.'
                ];
            }

            $insertUserStmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':telephone' => $telephone ?: null,
                ':mdp' => $mdp
            ]);

            $userId = $dbb->lastInsertId();

            $cultures = $user['cultures'] ?? [];

            if (!is_array($cultures) || count($cultures) === 0) {
                $dbb->rollBack();
                return [
                    'error' => 'Agriculteur #' . ($userIndex + 1) . ' : aucune culture renseignée.'
                ];
            }

            foreach ($cultures as $cultureIndex => $culture) {

                $cultureName = trim($culture['Culture'] ?? '');

                if ($cultureName === '') {
                    $dbb->rollBack();
                    return [
                        'error' =>
                            'Agriculteur #' . ($userIndex + 1) .
                            ', culture #' . ($cultureIndex + 1) .
                            ' : nom de culture manquant.'
                    ];
                }

                $surface = floatval($culture['Surface'] ?? 0);
                $engrais = floatval($culture['Engrais'] ?? 0);
                $phyto = floatval($culture['Phyto'] ?? 0);
                $A = floatval($culture['A'] ?? 0);
                $B = floatval($culture['B'] ?? 0);
                $C = floatval($culture['C'] ?? 0);

                $checkCropStmt->execute([':nom' => $cultureName]);
                $crop = $checkCropStmt->fetch(PDO::FETCH_ASSOC);

                if ($crop) {
                    $cropId = $crop['id'];
                } else {
                    $insertCropStmt->execute([':nom' => $cultureName]);
                    $cropId = $dbb->lastInsertId();
                }

                $insertSpecStmt->execute([
                    ':surface' => $surface,
                    ':engrais' => $engrais,
                    ':phyto' => $phyto,
                    ':A' => $A,
                    ':B' => $B,
                    ':C' => $C
                ]);

                $specId = $dbb->lastInsertId();

                $insertLinkStmt->execute([
                    ':spec_id' => $specId,
                    ':crop_id' => $cropId,
                    ':user_id' => $userId
                ]);
            }

            $created++;
        }

        $dbb->commit();

        return [
            'success' => true,
            'created' => $created,
            'message' => $created . ' agriculteur(s) créé(s) avec leurs cultures.'
        ];
    }
    catch (PDOException $e) {

        if ($dbb->inTransaction()) {
            $dbb->rollBack();
        }

        return [
            'error' => 'Erreur SQL : ' . $e->getMessage()
        ];
    }
}

?>