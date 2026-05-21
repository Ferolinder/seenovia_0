DELETE FROM link;
DELETE FROM spec;
DELETE FROM crops;
DELETE FROM users;

INSERT INTO users (mdp, nom, prenom, adresseMail, telephone, admin) VALUES
  ('pass123', 'Dupont', 'Jean', 'jean.dupont@example.com', '0612345678', FALSE),
  ('secret456', 'Moreau', 'Alice', 'alice.moreau@example.com', '0623456789', FALSE),
  ('azerty789', 'Petit', 'Marc', 'marc.petit@example.com', '0634567890', FALSE),
  ('qwerty321', 'Leroy', 'Sophie', 'sophie.leroy@example.com', '0645678901', FALSE),
  ('zxcvbn654', 'Roux', 'Lucas', 'lucas.roux@example.com', '0656789012', FALSE),
  ('test', 'test', 'test', 'test@test.com', '0000000000', FALSE),
  ('svpass1', 'Maret', 'Pierre-Antoine', 'pierre-antoine.maret@seenovia.fr', '0000000000', TRUE),
  ('svpass2', 'Bernard', 'Nicolas', 'nicolas.bernard@seenovia.fr', '0000000000', TRUE),
  ('twig', 'twig', 'Ferolind', 'ferolind.twig@hilda.fr', '0755616993', TRUE);

INSERT INTO crops (nom) VALUES
  ('Blé'),
  ('Maïs'),
  ('Colza');
