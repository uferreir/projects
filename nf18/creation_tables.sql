
/* Création de tables */
CREATE TABLE Utilisateur (
mail varchar(360) PRIMARY KEY,
nom varchar(15) not NULL,
prénom varchar(15) not NULL,
mdp varchar(15) NOT NULL,
date_naissance date NOT NULL,
admin boolean NOT NULL,
CHECK (mail LIKE '%_@_%._%' AND date_naissance<current_date)
);

CREATE TABLE Licence (
nom varchar(15) PRIMARY KEY
);

CREATE TABLE Catégorie (
nom  varchar(15) PRIMARY KEY
);

CREATE TABLE Thème (
nom  varchar(15) PRIMARY KEY
);
/*  UN auteur peut avoir un pseudonyme qui serait alors qu'un nom ou un prénom.
On peut connaitre le pays d'origine d'une oeuvre sans exactement connaitre on auteur */
CREATE TABLE Auteur (
id  varchar (10) PRIMARY KEY,
nom  varchar(15) NOT NULL,
prénom  varchar(15) NOT NULL,
nationalité varchar(20),
date_naissance  date NOT NULL,
unique(prénom , nom , date_naissance),
CHECK (date_naissance<current_date )
);

CREATE TABLE Edition (
nom  varchar(15) PRIMARY KEY
);

CREATE TABLE Livre (
nom varchar(50) NOT NULL,
date_parution  integer,
lien_contenu  varchar(100) PRIMARY KEY,
nombre_pages  integer,
résumé  varchar(280),
licence  varchar(15) REFERENCES Licence (nom),
catégorie  varchar(15) REFERENCES Catégorie (nom),
édition  varchar(15) REFERENCES Edition (nom),
vedette boolean,
CHECK (nombre_pages>0 AND date_parution<=date_part('year', current_date))
);

CREATE TABLE Transaction (
  id  varchar (10) PRIMARY KEY,
  montant   float NOT NULL,
  date  date NOT NULL,
  livre  varchar(100) REFERENCES Livre(lien_contenu),
  acheteur  varchar(360) REFERENCES Utilisateur(mail),
  CHECK (montant>0 AND date<=current_date)
);

CREATE TABLE Ecrit(
livre  varchar(100) REFERENCES Livre(lien_contenu),
auteur  varchar(10) REFERENCES Auteur(id),
PRIMARY KEY (livre, auteur)
);

CREATE TABLE Appartient(
livre  varchar(100) REFERENCES Livre(lien_contenu),
thème  varchar(15) REFERENCES Thème(nom),
PRIMARY KEY (livre, thème)
);

CREATE TABLE Télécharge (
livre  varchar(100) REFERENCES Livre(lien_contenu),
utilisateur  varchar(360) REFERENCES Utilisateur(mail),
PRIMARY KEY (livre, utilisateur)
);
CREATE VIEW Dons AS
  SELECT id,montant,date,acheteur
  FROM Transaction
  WHERE livre IS NULL;

CREATE VIEW Achats AS
  SELECT id,montant,date,acheteur
  FROM Transaction
  WHERE livre IS NOT NULL;

CREATE VIEW Vedette AS
  SELECT * FROM livre
  WHERE vedette='TRUE';

  CREATE VIEW Recherche AS
  SELECT Auteur.prénom||' '||Auteur.nom||' '||Livre.nom||' '||Auteur.prénom||' '||Livre.nom||' '||Auteur.nom||' '||Auteur.prénom||' '||Auteur.nom||' '||Livre.nom||' '||Auteur.prénom||' '||Auteur.nom as concaténation,
  Auteur.nom as an, Auteur.prénom as ap, Livre.nom as ln, résumé, date_parution, lien_contenu
  FROM Livre LEFT JOIN Ecrit
  ON Livre.lien_contenu=Ecrit.livre
  RIGHT JOIN Auteur
  ON Auteur.id=Ecrit.auteur;
