DROP TABLE IF EXISTS field_options;
DROP TABLE IF EXISTS crop_values;
DROP TABLE IF EXISTS crop_records;
DROP TABLE IF EXISTS spec_fields;
DROP TABLE IF EXISTS crops;
DROP TABLE IF EXISTS group_managers;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS groups;




-- ==========================================
-- GROUPES
-- ==========================================

INSERT INTO groups (id, nom) VALUES
(1, 'Nord'),
(2, 'Sud');

-- ==========================================
-- UTILISATEURS
-- ==========================================

INSERT INTO users (
    id,
    email,
    mdp,
    nom,
    prenom,
    telephone,
    group_id,
    admin
)
VALUES
(1, 'admin1@test.fr', 'abc', 'Martin', 'Jean', '0600000001', 1, TRUE),
(2, 'admin2@test.fr', 'def', 'Bernard', 'Luc', '0600000002', 1, TRUE),
(3, 'admin3@test.fr', 'ghi', 'Robert', 'Paul', '0600000003', 2, TRUE),

(4, 'alice@test.fr', 'aaa', 'Moreau', 'Alice', '0600000004', 1, FALSE),
(5, 'thomas@test.fr', 'bbb', 'Petit', 'Thomas', '0600000005', 1, FALSE),
(6, 'julie@test.fr', 'ccc', 'Durand', 'Julie', '0600000006', 2, FALSE);

-- ==========================================
-- RESPONSABLES DE GROUPES
-- ==========================================

INSERT INTO group_managers (group_id, user_id) VALUES
(1,1),
(1,2),
(2,3);

-- ==========================================
-- CULTURES
-- ==========================================

INSERT INTO crops (id, nom) VALUES
(1,'Blé'),
(2,'Maïs'),
(3,'Colza'),
(4,'Tournesol');

-- ==========================================
-- CHAMPS CONFIGURABLES
-- ==========================================

INSERT INTO spec_fields (
    id,
    nom,
    type,
    default_value,
    unite,
    ordre,
    visible,
    admin_edit_only,
    required
)
VALUES
(1,'Surface','float','0','ha',1,TRUE,FALSE,TRUE),
(2,'Engrais','float','0','kg',2,TRUE,FALSE,FALSE),
(3,'Phyto','float','0','L',3,TRUE,FALSE,FALSE),
(4,'Type Sol','selector',NULL,NULL,4,TRUE,FALSE,FALSE),
(5,'Commentaire','text',NULL,NULL,5,TRUE,FALSE,FALSE);

-- ==========================================
-- OPTIONS DU SELECTOR "TYPE SOL"
-- ==========================================

INSERT INTO field_options (
    field_id,
    value,
    is_default
)
VALUES
(4,'Argileux',TRUE),
(4,'Limoneux',FALSE),
(4,'Sableux',FALSE);

-- ==========================================
-- CULTURES D'ALICE
-- ==========================================

INSERT INTO crop_records (id,user_id,crop_id) VALUES
(1,4,1),
(2,4,2);

-- ==========================================
-- CULTURES DE THOMAS
-- ==========================================

INSERT INTO crop_records (id,user_id,crop_id) VALUES
(3,5,1),
(4,5,3);

-- ==========================================
-- VALEURS D'ALICE - BLE
-- ==========================================

INSERT INTO crop_values (
    record_id,
    field_id,
    value
)
VALUES
(1,1,'12.5'),
(1,2,'120'),
(1,3,'25'),
(1,4,'Argileux'),
(1,5,'Parcelle principale');

-- ==========================================
-- VALEURS D'ALICE - MAIS
-- ==========================================

INSERT INTO crop_values (
    record_id,
    field_id,
    value
)
VALUES
(2,1,'8'),
(2,2,'90'),
(2,3,'15'),
(2,4,'Limoneux');

-- ==========================================
-- VALEURS DE THOMAS - BLE
-- ==========================================

INSERT INTO crop_values (
    record_id,
    field_id,
    value
)
VALUES
(3,1,'20'),
(3,2,'150'),
(3,3,'30'),
(3,4,'Sableux');

-- ==========================================
-- VALEURS DE THOMAS - COLZA
-- ==========================================

INSERT INTO crop_values (
    record_id,
    field_id,
    value
)
VALUES
(4,1,'6'),
(4,2,'80'),
(4,3,'12'),
(4,4,'Argileux');
