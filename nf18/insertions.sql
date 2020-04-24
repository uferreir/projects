INSERT INTO Auteur VALUES
('1', 'Hugo', 'Victor', 'France', '1802-02-26'),
('2', 'Flaubert', 'Gustave', 'France', '1821-12-12'),
('3', 'Dickens', 'Charles', 'Angleterre', '1812-02-07'),
('4', 'Zweig', 'Stefan', 'Autriche', '1881-11-28');

INSERT INTO Edition VALUES
('Gallimard'),
('Hachette');

INSERT INTO Catégorie VALUES
  ('Roman'),
  ('Nouvelle');

INSERT INTO Thème VALUES
    ('Société'),
    ('Pauvreté'),
    ('Folie'),
    ('Ennui');

  INSERT INTO Licence VALUES
    ('Art Libre'),
    ('cc-by-sa'),
    ('GNU FDL');

INSERT INTO Livre VALUES
  ('Les Misérables', '1862','/~nf17p159/ressources/misérables.pdf', '992', 'Les aventures de Jean Valjean et Cosette dans le Paris du XIXème siècle', 'Art Libre', 'Roman', 'Hachette','TRUE'),
  ('Oliver Twist', '1837','/~nf17p159/ressources/oliver_twist.pdf', '736', 'Les péripéties d''un enfant abandonné et livré à lui même', 'Art Libre', 'Roman', 'Gallimard','TRUE'),
  ('Mme Bovary', '1857','/~nf17p159/ressources/bovary.pdf', '576', 'L''ennui d''une femme de la classe moyenne qui subit sa vie plus q''elle ne la vit', 'cc-by-sa', 'Roman', NULL,'FALSE'),
  ('David Copperfield','1850','/~nf17p159/ressources/copperfield.pdf', '624','La jeunesse difficile d''un orphelin', 'cc-by-sa', 'Roman',NULL,'FALSE'),
  ('Le joueur d''échecs', '1943','/~nf17p159/ressources/joueur_echecs.pdf', '128', 'La partie très mentale de deux joueurs que tout oppose sur un paquebot', 'GNU FDL', 'Nouvelle', 'Gallimard','FALSE');

INSERT INTO Utilisateur VALUES
  ('john.doe@gmail.com', 'Doe', 'John', '123456', '1996-10-10', 'FALSE'),
  ('ulysse.ferreira@gmail.com', 'Ferreira', 'Ulysse', '987654', '1997-06-25', 'TRUE');

  INSERT INTO Ecrit VALUES
    ('/~nf17p159/ressources/misérables.pdf','1'),
    ('/~nf17p159/ressources/oliver_twist.pdf','3'),
    ('/~nf17p159/ressources/bovary.pdf','2'),
    ('/~nf17p159/ressources/joueur_echecs.pdf','4'),
    ('/~nf17p159/ressources/copperfield.pdf','3');

  INSERT INTO Appartient VALUES
    ('/~nf17p159/ressources/misérables.pdf','Société'),
    ('/~nf17p159/ressources/misérables.pdf','Pauvreté'),
    ('/~nf17p159/ressources/oliver_twist.pdf','Pauvreté'),
    ('/~nf17p159/ressources/bovary.pdf','Ennui'),
    ('/~nf17p159/ressources/joueur_echecs.pdf','Folie'),
    ('/~nf17p159/ressources/copperfield.pdf','Société'),
    ('/~nf17p159/ressources/copperfield.pdf','Pauvreté');

  INSERT INTO Télécharge VALUES
    ('/~nf17p159/ressources/misérables.pdf','john.doe@gmail.com'),
    ('/~nf17p159/ressources/misérables.pdf','ulysse.ferreira@gmail.com'),
    ('/~nf17p159/ressources/oliver_twist.pdf','john.doe@gmail.com');

  INSERT INTO Transaction VALUES
    ('1', '10', '2018-06-09',NULL, 'john.doe@gmail.com'),
    ('2', '25.2', '2018-06-10','/~nf17p159/ressources/misérables.pdf', 'john.doe@gmail.com');
