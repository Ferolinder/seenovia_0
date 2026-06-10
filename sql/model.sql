DROP TABLE IF EXISTS crop_values;
DROP TABLE IF EXISTS crop_records;
DROP TABLE IF EXISTS field_options;
DROP TABLE IF EXISTS spec_fields;
DROP TABLE IF EXISTS user_groups;
DROP TABLE IF EXISTS manager_groups;
DROP TABLE IF EXISTS crops;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS users;


-- =====================================================
-- UTILISATEURS
-- =====================================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(128) NOT NULL UNIQUE,
    mdp VARCHAR(32) NOT NULL,
    nom VARCHAR(64) NOT NULL,
    prenom VARCHAR(64) NOT NULL,
    telephone VARCHAR(32),
    admin BOOLEAN NOT NULL DEFAULT FALSE
);

-- =====================================================
-- GROUPES
-- =====================================================

CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(64) NOT NULL UNIQUE
);

CREATE TABLE user_groups (
    user_id INT NOT NULL,
    group_id INT NOT NULL,

    PRIMARY KEY (user_id, group_id),

    CONSTRAINT fk_user_groups_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_user_groups_group
        FOREIGN KEY (group_id)
        REFERENCES groups(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE manager_groups (
    group_id INT NOT NULL,
    user_id INT NOT NULL,

    PRIMARY KEY (group_id, user_id),

    CONSTRAINT fk_group_managers_group
        FOREIGN KEY (group_id)
        REFERENCES groups(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_group_managers_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- =====================================================
-- CULTURES
-- =====================================================

CREATE TABLE crops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(64) NOT NULL UNIQUE
);

-- =====================================================
-- DEFINITION DES CHAMPS DYNAMIQUES
-- =====================================================

CREATE TABLE spec_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(64) NOT NULL UNIQUE,
    type VARCHAR(20) NOT NULL,
    default_value TEXT NULL,
    unite VARCHAR(32) NULL,
    ordre INT NOT NULL DEFAULT 0,
    visible BOOLEAN NOT NULL DEFAULT TRUE,
    admin_edit_only BOOLEAN NOT NULL DEFAULT FALSE,
    required BOOLEAN NOT NULL DEFAULT FALSE
);

-- =====================================================
-- OPTIONS DES SELECTORS
-- =====================================================

CREATE TABLE field_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    field_id INT NOT NULL,
    option_value VARCHAR(128) NOT NULL,
    is_default BOOLEAN NOT NULL DEFAULT FALSE,

    CONSTRAINT fk_field_options_field
        FOREIGN KEY (field_id)
        REFERENCES spec_fields(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- =====================================================
-- ENREGISTREMENTS CULTURES UTILISATEURS
-- =====================================================

CREATE TABLE crop_records (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,
    crop_id INT NOT NULL,

    CONSTRAINT fk_crop_records_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_crop_records_crop
        FOREIGN KEY (crop_id)
        REFERENCES crops(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    UNIQUE KEY unique_user_crop (user_id, crop_id)
);

-- =====================================================
-- VALEURS DES CHAMPS
-- =====================================================

CREATE TABLE crop_values (
    id INT AUTO_INCREMENT PRIMARY KEY,

    record_id INT NOT NULL,
    field_id INT NOT NULL,

    field_value TEXT NULL,

    CONSTRAINT fk_crop_values_record
        FOREIGN KEY (record_id)
        REFERENCES crop_records(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_crop_values_field
        FOREIGN KEY (field_id)
        REFERENCES spec_fields(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    UNIQUE KEY unique_record_field (record_id, field_id)
);

