#Arnaud DEBRABANT P1707147 - Damien PETITJEAN P1408987

#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: adherent
#------------------------------------------------------------

CREATE TABLE adherent(
    id_adherent    Int NOT NULL,
    nom            Varchar (50) NOT NULL,
    prenom         Varchar (50) NOT NULL,
    date_naissance Date,
    sexe           Varchar (20) NOT NULL,
    adresse        Varchar (200),
    date_certif_club    Date,
    club           Varchar (200),
	PRIMARY KEY (id_adherent)
);


#------------------------------------------------------------
# Table: user
#------------------------------------------------------------

CREATE TABLE user(
    id_user     Int  AUTO_INCREMENT  NOT NULL,
    id_adherent Int,
    type        Varchar (50) NOT NULL,
    mdp         Varchar (50) NOT NULL,
    pseudo      Varchar (50) NOT NULL,
	PRIMARY KEY (id_user),
    FOREIGN KEY (id_adherent) REFERENCES adherent(id_adherent)
    ON DELETE CASCADE
);


#------------------------------------------------------------
# Table: course
#------------------------------------------------------------

CREATE TABLE course(
    id_course      Int  AUTO_INCREMENT  NOT NULL,
    nom            Varchar (100) NOT NULL,
    annee_creation Int NOT NULL,
    mois           Int NOT NULL,
    site_url       Varchar (200) NOT NULL,
	PRIMARY KEY (id_course)
) ;


#------------------------------------------------------------
# Table: edition
#------------------------------------------------------------

CREATE TABLE edition(
    id_edition            Int  AUTO_INCREMENT  NOT NULL,
    id_course             Int NOT NULL,
    annee                 Int NOT NULL,
    nb_participants       Int NOT NULL,
    date                  Date NOT NULL,
    date_inscription      Date NOT NULL,
    date_depot_certificat Date NOT NULL,
    date_recup_dossard    Date NOT NULL,
	PRIMARY KEY (id_edition),
    FOREIGN KEY (id_course) REFERENCES course(id_course)
    ON DELETE CASCADE
);


#------------------------------------------------------------
# Table: epreuve
#------------------------------------------------------------

CREATE TABLE epreuve(
    id_epreuve   Int  AUTO_INCREMENT  NOT NULL,
    id_edition   Int NOT NULL,
    nom          Varchar (200) NOT NULL,
    distance     Int NOT NULL,
    adresse_depart      Varchar (200) NOT NULL,
    denivelee    Int NOT NULL,
    type_epreuve Varchar (200) NOT NULL,
    plan         Varchar (200) NOT NULL,
	PRIMARY KEY (id_epreuve),
    FOREIGN KEY (id_edition) REFERENCES edition(id_edition)
    ON DELETE CASCADE
);


#------------------------------------------------------------
# Table: tarif
#------------------------------------------------------------

CREATE TABLE tarif(
    id_tarif                Int  AUTO_INCREMENT  NOT NULL,
    id_epreuve              Int NOT NULL,
    age_min                 Int NOT NULL,
    age_max                 Int NOT NULL,
    tarif                   Int NOT NULL,
	PRIMARY KEY (id_tarif),
    FOREIGN KEY (id_epreuve) REFERENCES epreuve(id_epreuve)
    ON DELETE CASCADE
);


#------------------------------------------------------------
# Table: participation
#------------------------------------------------------------

CREATE TABLE participation(
    id_participation    Int AUTO_INCREMENT NOT NULL,
    dossard             Int NOT NULL,
    id_adherent         Int NOT NULL,
    id_epreuve          Int NOT NULL,
	PRIMARY KEY (id_participation),
    FOREIGN KEY (id_epreuve) REFERENCES epreuve(id_epreuve),
    FOREIGN KEY (id_adherent) REFERENCES adherent(id_adherent)
    ON DELETE CASCADE
);


#------------------------------------------------------------
# Table: resultat
#------------------------------------------------------------

CREATE TABLE resultat(
    dossard               Int NOT NULL,
    id_epreuve            Int NOT NULL,
    rang                  Int,
    nom                   Varchar (200) NOT NULL,
    prenom                Varchar (200) NOT NULL,
    sexe                  Varchar (20) NOT NULL,
	PRIMARY KEY (dossard, id_epreuve),
    FOREIGN KEY (id_epreuve) REFERENCES epreuve(id_epreuve),
    FOREIGN KEY (dossard) REFERENCES participation(dossard)
    ON DELETE CASCADE
);


#------------------------------------------------------------
# Table: temps_passage
#------------------------------------------------------------

CREATE TABLE temps_passage(
    id_epreuve            Int NOT NULL,
    dossard               Int NOT NULL,
    km                    Int NOT NULL,
    temps                 Int NOT NULL,
	PRIMARY KEY (id_epreuve, dossard, km),
    FOREIGN KEY (id_epreuve) REFERENCES epreuve(id_epreuve),
    FOREIGN KEY (dossard) REFERENCES participation(dossard)
    ON DELETE CASCADE
);


#------------------------------------------------------------
# INSERTIONS
#------------------------------------------------------------

#COURSES
INSERT INTO course (id_course, nom, annee_creation, mois, site_url)
VALUES (1, 'Marathon de Paris', 1976, 6, 'http://www.schneiderelectricparismarathon.com/fr/');

INSERT INTO course (id_course, nom, annee_creation, mois, site_url)
VALUES (2, 'Run in Lyon', 2010, 5, 'http://www.runinlyon.com/fr');

#EDITIONS
INSERT INTO edition (id_edition, id_course, annee, nb_participants, date, date_inscription, date_depot_certificat, date_recup_dossard)
VALUES (1, 1, 2017, 45, '2017-04-09', '2017-01-01', '2017-02-01', '2017-04-01');

INSERT INTO edition (id_edition, id_course, annee, nb_participants, date, date_inscription, date_depot_certificat, date_recup_dossard)
VALUES (2, 1, 2018, 45, '2018-04-08', '2018-01-01', '2018-02-01', '2018-04-01');

INSERT INTO edition (id_edition, id_course, annee, nb_participants, date, date_inscription, date_depot_certificat, date_recup_dossard)
VALUES (3, 2, 2018, 32, '2018-10-07', '2018-01-07', '2018-08-01', '2018-10-01');

#EPREUVES
INSERT INTO epreuve (id_epreuve, id_edition, nom, adresse_depart, distance, denivelee, type_epreuve, plan)
VALUES (1, 1, 'Paris centre by Nike', 'Avenue des Champs-Elysées, Paris', 10, 0, '10 Km', 'mpplan.jpg');

INSERT INTO epreuve (id_epreuve, id_edition, nom, adresse_depart, distance, denivelee, type_epreuve, plan)
VALUES (2, 1, 'Marathon de Paris', 'Avenue des Champs-Elysées, Paris', 42.195, 0, 'Marathon', 'mpplan.jpg');

INSERT INTO epreuve (id_epreuve, id_edition, nom, adresse_depart, distance, denivelee, type_epreuve, plan)
VALUES (3, 1, 'Semi-Marathon de Paris', 'Avenue des Champs-Elysées, Paris', 21.097, 0, 'Semi-Marathon', 'mpplan.jpg');

INSERT INTO epreuve (id_epreuve, id_edition, nom, adresse_depart, distance, denivelee, type_epreuve, plan)
VALUES (4, 2, 'Adidas 10 km Paris', 'Quai Tilsitt, Lyon', 10, 0, '10 Km', 'mpplan.jpg');

INSERT INTO epreuve (id_epreuve, id_edition, nom, adresse_depart, distance, denivelee, type_epreuve, plan)
VALUES (5, 2, 'Marathon de Paris', 'Quai Tilsitt, Lyon', 42.195, 0, 'Marathon', 'mpplan.jpg');

INSERT INTO epreuve (id_epreuve, id_edition, nom, adresse_depart, distance, denivelee, type_epreuve, plan)
VALUES (6, 2, 'Semi-Marathon de Paris', 'Quai Tilsitt, Lyon', 21.097, 0, 'Semi-Marathon', 'mpplan.jpg');

INSERT INTO epreuve (id_epreuve, id_edition, nom, adresse_depart, distance, denivelee, type_epreuve, plan)
VALUES (7, 3, 'Run in Lyon 10 km', 'Quai Tilsitt, Lyon', 10, 0, '10 Km', 'runlyon.jpg');

INSERT INTO epreuve (id_epreuve, id_edition, nom, adresse_depart, distance, denivelee, type_epreuve, plan)
VALUES (8, 3, 'Run in Lyon Marathon', 'Quai Tilsitt, Lyon', 42.195, 0, 'Marathon', 'runlyon.jpg');

INSERT INTO epreuve (id_epreuve, id_edition, nom, adresse_depart, distance, denivelee, type_epreuve, plan)
VALUES (9, 3, 'Run in Lyon Semi-Marathon', 'Quai Tilsitt, Lyon', 21.097, 0, 'Semi-Marathon', 'runlyon.jpg');

#USERS
INSERT INTO user (id_adherent, type, mdp, pseudo)
VALUES (2016004, 'Adherent', '1234', 'Mat');

INSERT INTO user (id_adherent, type, mdp, pseudo)
VALUES (2016008, 'Adherent', 'DSA22', 'Chris');

INSERT INTO user (id_adherent, type, mdp, pseudo)
VALUES (2017001, 'Adherent', 'SDAZ13', 'Bruno');

INSERT INTO user (type, mdp, pseudo)
VALUES ('Admin', 'aze', 'Arnaud');

INSERT INTO user (type, mdp, pseudo)
VALUES ('Admin', 'azerty', 'Damien');

#TARIFS
INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (1, 12, 25, 15);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (1, 25, 99, 20);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (2, 12, 25, 20);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (2, 25, 99, 25);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (3, 18, 25, 30);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (3, 25, 99, 35);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (4, 12, 99, 21);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (5, 18, 99, 26);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (6, 18, 99, 31);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (7, 18, 25, 10);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (7, 25, 99, 15);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (8, 18, 95, 20);

INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES (9, 18, 95, 25);


#Requête pour créer les participations à partir des résultats (à exécuter après avoir importer les adhérents et les résultats)
#PARTICIPATIONS
INSERT INTO participation (dossard, id_adherent, id_epreuve)
    SELECT r.dossard ,a.id_adherent, r.id_epreuve
    FROM resultat r
    INNER JOIN adherent a on (a.nom = r.nom and a.prenom = r.prenom and a.sexe = r.sexe);