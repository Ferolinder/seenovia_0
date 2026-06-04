-- ==========================================
-- UTILISATEURS
-- ==========================================

CREATE TABLE users (
    id SERIAL PRIMARY KEY,

    email VARCHAR(128) NOT NULL UNIQUE,
    mdp VARCHAR(32) NOT NULL,

    nom VARCHAR(64) NOT NULL,
    prenom VARCHAR(64) NOT NULL,

    telephone VARCHAR(32),

    group_id INTEGER,

    admin BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (group_id)
        REFERENCES groups(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

-- ==========================================
-- GROUPES
-- ==========================================

CREATE TABLE groups (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(64) NOT NULL UNIQUE
);

-- Responsables d'un groupe
CREATE TABLE group_managers (
    group_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,

    PRIMARY KEY (group_id, user_id),

    FOREIGN KEY (group_id)
        REFERENCES groups(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- ==========================================
-- CULTURES
-- ==========================================

CREATE TABLE crops (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(64) NOT NULL UNIQUE
);

-- ==========================================
-- DEFINITIONS DES CHAMPS
-- ==========================================

CREATE TABLE spec_fields (
    id SERIAL PRIMARY KEY,

    nom VARCHAR(64) NOT NULL UNIQUE,

    type VARCHAR(20) NOT NULL,

    default_value TEXT,

    unite VARCHAR(32),

    ordre INTEGER DEFAULT 0,

    visible BOOLEAN DEFAULT TRUE,

    admin_edit_only BOOLEAN DEFAULT FALSE,

    required BOOLEAN DEFAULT FALSE
);

-- ==========================================
-- OPTIONS DES SELECTORS
-- ==========================================

CREATE TABLE field_options (
    id SERIAL PRIMARY KEY,

    field_id INTEGER NOT NULL,

    value VARCHAR(128) NOT NULL,

    is_default BOOLEAN DEFAULT FALSE,

    FOREIGN KEY (field_id)
        REFERENCES spec_fields(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- ==========================================
-- ENREGISTREMENTS CULTURES
-- ==========================================

CREATE TABLE crop_records (
    id SERIAL PRIMARY KEY,

    user_id INTEGER NOT NULL,
    crop_id INTEGER NOT NULL,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    FOREIGN KEY (crop_id)
        REFERENCES crops(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    UNIQUE(user_id, crop_id)
);

-- ==========================================
-- VALEURS DES CHAMPS
-- ==========================================

CREATE TABLE crop_values (
    id SERIAL PRIMARY KEY,

    record_id INTEGER NOT NULL,
    field_id INTEGER NOT NULL,

    value TEXT,

    FOREIGN KEY (record_id)
        REFERENCES crop_records(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    FOREIGN KEY (field_id)
        REFERENCES spec_fields(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    UNIQUE(record_id, field_id)
);

