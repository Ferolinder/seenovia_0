

TRUNCATE TABLE link, spec, crops, users, seenovia RESTART IDENTITY CASCADE;

INSERT INTO users (mdp, nom, prenom, adresseMail, telephone) VALUES
  ('pass123', 'Dupont', 'Jean', 'jean.dupont@example.com', '0612345678'),
  ('secret456', 'Moreau', 'Alice', 'alice.moreau@example.com', '0623456789'),
  ('azerty789', 'Petit', 'Marc', 'marc.petit@example.com', '0634567890'),
  ('qwerty321', 'Leroy', 'Sophie', 'sophie.leroy@example.com', '0645678901'),
  ('zxcvbn654', 'Roux', 'Lucas', 'lucas.roux@example.com', '0656789012'),
  ('test', 'test', 'test', 'test@test.com', '0000000000');

INSERT INTO seenovia (mdp, nom, prenom, telephone, adresseMail) VALUES
  ('svpass1', 'Maret', 'Pierre-Antoine', '0616444547', 'pierre-antoine.maret@seenovia.fr'),
  ('svpass2', 'Bernard', 'Nicolas', '0678901234', 'nicolas.bernard@seenovia.fr');

INSERT INTO crops (nom) VALUES
  ('Blé'),
  ('Maïs'),
  ('Colza');

INSERT INTO spec (surface, engrais, phyto, A, B, C) VALUES
  (10.5, 50.0, 20.0, 1.0, 2.0, 3.0),
  (15.0, 60.0, 25.0, 1.5, 2.5, 3.5),
  (20.0, 70.0, 30.0, 2.0, 3.0, 4.0);

INSERT INTO link (spec_id, crop_id, user_id) VALUES
  (1, 1, 1),
  (2, 2, 2),
  (2, 1, 1),
  (2, 3, 3),
  (3, 3, 3);

