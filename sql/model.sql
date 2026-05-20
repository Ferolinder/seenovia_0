
DROP TABLE IF EXISTS patient CASCADE;
DROP TABLE IF EXISTS medecin CASCADE;
DROP TABLE IF EXISTS rdv CASCADE;

-- Table user
CREATE TABLE user (
    id          SERIAL PRIMARY KEY,
    mdp         VARCHAR(64) NOT NULL,
    nom         VARCHAR(64) NOT NULL,
    prenom      VARCHAR(64) NOT NULL,
    adresseMail VARCHAR(64),
    telephone   VARCHAR(64),
);

-- Table seenovia
CREATE TABLE seenovia (
    id SERIAL PRIMARY KEY,
    mdp         VARCHAR(64) NOT NULL,
    nom         VARCHAR(64) NOT NULL,
    prenom      VARCHAR(64) NOT NULL,
    telephone   VARCHAR(64) NOT NULL,
    adresseMail VARCHAR(64) NOT NULL,
);

-- Table crops
CREATE TABLE crops (
  id          SERIAL PRIMARY KEY,
  nom         VARCHAR(64) NOT NULL,
);

-- Table spec
CREATE TABLE spec (
  id          SERIAL PRIMARY KEY,
  surface     FLOAT,
  engrais     FLOAT,
  phyto       FLOAT,
  A           FLOAT,
  B           FLOAT,
  C           FLOAT
); 

-- Table link
CREATE TABLE link (
  id          SERIAL PRIMARY KEY,
  spec_id     INTEGER,
  crop_id     INTEGER,
  user_id     INTEGER,
  FOREIGN KEY(spec_id) REFERENCES spec(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY(crop_id) REFERENCES crops(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY(user_id) REFERENCES user(id)
    ON UPDATE CASCADE ON DELETE CASCADE
);
