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
    admin
)
VALUES
(1, 'admin1@test.fr', 'abc', 'Martin', 'Jean', '0600000001', 1),
(2, 'admin2@test.fr', 'def', 'Bernard', 'Luc', '0600000002', 1),
(3, 'twig@hilda.fr', 'hil', 'Twig', 'Hilda', '0600000003', 1),

(4, 'alice@test.fr', 'aaa', 'Moreau', 'Alice', '0600000004', 0),
(5, 'thomas@test.fr', 'bbb', 'Petit', 'Thomas', '0600000005', 0),
(6, 'julie@test.fr', 'ccc', 'Durand', 'Julie', '0600000006', 0);

-- ==========================================
-- GROUPES
-- ==========================================

INSERT INTO groups (id, nom) VALUES
(1, 'Nord'),
(2, 'Sud');

INSERT INTO user_groups (
    user_id,
    group_id
)
VALUES
(1,1), -- admin1 -> Nord
(2,1), -- admin2 -> Nord
(3,2), -- Twig -> Sud

(4,1), -- Alice -> Nord
(5,1), -- Thomas -> Nord
(6,2); -- Julie -> Sud

-- ==========================================
-- RESPONSABLES DE GROUPES
-- ==========================================

INSERT INTO manager_groups (
    group_id,
    user_id
)
VALUES
(1,1),
(1,2),
(2,3);

-- ==========================================
-- CULTURES
-- ==========================================

INSERT INTO crops (
    id,
    nom
)
VALUES
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
(1,'Surface','float','0','ha',1,1,0,1),
(2,'Engrais','float','0','kg',2,1,0,0),
(3,'Phyto','float','0','L',3,1,0,0),
(4,'Type Sol','selector',NULL,NULL,4,1,0,0),
(5,'Commentaire','text',NULL,NULL,5,1,0,0);

-- ==========================================
-- OPTIONS DU SELECTOR TYPE SOL
-- ==========================================

INSERT INTO field_options (
    field_id,
    option_value,
    is_default
)
VALUES
(4,'Argileux',1),
(4,'Limoneux',0),
(4,'Sableux',0);

-- ==========================================
-- ENREGISTREMENTS DE CULTURES
-- ==========================================

INSERT INTO crop_records (
    id,
    user_id,
    crop_id
)
VALUES
(1,4,1), -- Alice / Blé
(2,4,2), -- Alice / Maïs
(3,5,1), -- Thomas / Blé
(4,5,3), -- Thomas / Colza
(5,6,4); -- Julie / Tournesol

-- ==========================================
-- ALICE - BLE
-- ==========================================

INSERT INTO crop_values (
    record_id,
    field_id,
    field_value
)
VALUES
(1,1,'12.5'),
(1,2,'120'),
(1,3,'25'),
(1,4,'Argileux'),
(1,5,'Parcelle principale');

-- ==========================================
-- ALICE - MAIS
-- ==========================================

INSERT INTO crop_values (
    record_id,
    field_id,
    field_value
)
VALUES
(2,1,'8'),
(2,2,'90'),
(2,3,'15'),
(2,4,'Limoneux');

-- ==========================================
-- THOMAS - BLE
-- ==========================================

INSERT INTO crop_values (
    record_id,
    field_id,
    field_value
)
VALUES
(3,1,'20'),
(3,2,'150'),
(3,3,'30'),
(3,4,'Sableux');

-- ==========================================
-- THOMAS - COLZA
-- ==========================================

INSERT INTO crop_values (
    record_id,
    field_id,
    field_value
)
VALUES
(4,1,'6'),
(4,2,'80'),
(4,3,'12'),
(4,4,'Argileux');

-- ==========================================
-- JULIE - TOURNESOL
-- ==========================================

INSERT INTO crop_values (
    record_id,
    field_id,
    field_value
)
VALUES
(5,1,'14'),
(5,2,'95'),
(5,3,'18'),
(5,4,'Limoneux'),
(5,5,'Parcelle proche de la rivière');

