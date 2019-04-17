#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: user
#------------------------------------------------------------

CREATE TABLE user(
        id_user     Int  AUTO_INCREMENT  NOT NULL,
        id_adherent Int,
        type        Varchar (50) NOT NULL,
        mdp         Varchar (50) NOT NULL,
        pseudo      Varchar (50) NOT NULL,
	PRIMARY KEY (id_user)
);


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
# Table: course
#------------------------------------------------------------

CREATE TABLE course(
        id_course      Int  AUTO_INCREMENT  NOT NULL,
        nom            Varchar (100) NOT NULL,
        annee_creation YEAR NOT NULL,
        mois           Int NOT NULL,
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
        plan                  Varchar (200) NOT NULL,
        adresse_depart        Varchar (200) NOT NULL,
        date                  Date NOT NULL,
        site_url              Varchar (200) NOT NULL,
        date_inscription      Date NOT NULL,
        date_depot_certificat Date NOT NULL,
        date_recup_dossard    Date NOT NULL,
	PRIMARY KEY (id_edition)
);


#------------------------------------------------------------
# Table: tarif
#------------------------------------------------------------

CREATE TABLE tarif(
        id_tarif                Int  AUTO_INCREMENT  NOT NULL,
        id_edition              Int NOT NULL,
        id_epreuve              Int NOT NULL,
        age_min                 Int NOT NULL,
        age_max                 Int NOT NULL,
        tarif                   Int NOT NULL,
	PRIMARY KEY (id_tarif)
);


#------------------------------------------------------------
# Table: epreuve
#------------------------------------------------------------

CREATE TABLE epreuve(
        id_epreuve   Int  AUTO_INCREMENT  NOT NULL,
        id_edition   Int NOT NULL,
        id_tarif     Int NOT NULL,
        distance     Int NOT NULL,
        nom          Varchar (200) NOT NULL,
        denivelee    Int NOT NULL,
        type_epreuve Varchar (200) NOT NULL,
	PRIMARY KEY (id_epreuve)
);


#------------------------------------------------------------
# Table: participation
#------------------------------------------------------------

CREATE TABLE participation(
        id_participation    Int AUTO_INCREMENT NOT NULL,
        dossard             Int NOT NULL,
        id_adherent         Int NOT NULL,
        id_epreuve          Int NOT NULL,
	PRIMARY KEY (id_participation)
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
	PRIMARY KEY (dossard, id_epreuve)
);


#------------------------------------------------------------
# Table: temps_passage
#------------------------------------------------------------

CREATE TABLE temps_passage(
        id_epreuve            Int NOT NULL,
        dossard               Int NOT NULL,
        km                    Int NOT NULL,
        temps                 Int NOT NULL,
	PRIMARY KEY (id_epreuve, dossard, km)
);

#------------------------------------------------------------
# ALTER TABLE
#------------------------------------------------------------

ALTER TABLE user
ADD CONSTRAINT id_adherent FOREIGN KEY (id_adherent) REFERENCES adherent(id_adherent);

ALTER TABLE edition
ADD CONSTRAINT id_course FOREIGN KEY (id_course) REFERENCES course(id_course);

ALTER TABLE tarif
ADD CONSTRAINT id_edition FOREIGN KEY (id_edition) REFERENCES edition(id_edition);

ALTER TABLE tarif
ADD CONSTRAINT id_epreuve FOREIGN KEY (id_epreuve) REFERENCES epreuve(id_epreuve);

ALTER TABLE epreuve
ADD CONSTRAINT id_edition FOREIGN KEY (id_edition) REFERENCES edition(id_edition);

ALTER TABLE epreuve
ADD CONSTRAINT id_tarif FOREIGN KEY (id_tarif) REFERENCES tarif(id_tarif);

ALTER TABLE participation
ADD CONSTRAINT id_epreuve FOREIGN KEY (id_epreuve) REFERENCES epreuve(id_epreuve);

ALTER TABLE participation
ADD CONSTRAINT id_adherent FOREIGN KEY (id_adherent) REFERENCES adherent(id_adherent);

ALTER TABLE resultat
ADD CONSTRAINT id_epreuve FOREIGN KEY (id_epreuve) REFERENCES epreuve(id_epreuve);

ALTER TABLE resultat
ADD CONSTRAINT dossard FOREIGN KEY (dossard) REFERENCES participation(dossard);

ALTER TABLE temps_passage
ADD CONSTRAINT id_epreuve FOREIGN KEY (id_epreuve) REFERENCES epreuve(id_epreuve);

ALTER TABLE temps_passage
ADD CONSTRAINT dossard FOREIGN KEY (dossard) REFERENCES participation(dossard);

#------------------------------------------------------------
# INSERTIONS
#------------------------------------------------------------

#COURSES
INSERT INTO course (id_course, nom, annee_creation, mois)
VALUES (1, 'Marathon de Paris', 1976, 6);

INSERT INTO course (id_course, nom, annee_creation, mois)
VALUES (2, 'Run in Lyon', 2010, 5);

#EDITIONS
INSERT INTO edition (id_edition, id_course, annee, nb_participants, plan, adresse_depart, date, site_url, date_inscription, date_depot_certificat, date_recup_dossard)
VALUES (1, 1, 2017, 45, 'mpplan.jpg', 'Avenue des Champs-Elysées, Paris', '2017-04-09', 'http://www.schneiderelectricparismarathon.com/fr/', '2017-01-01', '2017-02-01', '2017-04-01');

INSERT INTO edition (id_edition, id_course, annee, nb_participants, plan, adresse_depart, date, site_url, date_inscription, date_depot_certificat, date_recup_dossard)
VALUES (2, 1, 2018, 45, 'mpplan.jpg', 'Avenue des Champs-Elysées, Paris', '2018-04-08', 'http://www.schneiderelectricparismarathon.com/fr/', '2018-01-01', '2018-02-01', '2018-04-01');

INSERT INTO edition (id_edition, id_course, annee, nb_participants, plan, adresse_depart, date, site_url, date_inscription, date_depot_certificat, date_recup_dossard)
VALUES (3, 2, 2018, 32, 'runlyon.jpg', 'Quai Tilsitt, Lyon', '2018-10-07', 'http://www.runinlyon.com/fr', '2018-01-07', '2018-08-01', '2018-10-01');

#EPREUVES
INSERT INTO epreuve (id_epreuve, id_edition, id_tarif, nom, distance, denivelee, type_epreuve)
VALUES (1, 1, 1, 'Paris centre by Nike', 10, 0, '10 Km');

INSERT INTO epreuve (id_epreuve, id_edition, id_tarif, nom, distance, denivelee, type_epreuve)
VALUES (2, 1, 2, 'Marathon de Paris', 42.195, 0, 'Marathon');

INSERT INTO epreuve (id_epreuve, id_edition, id_tarif, nom, distance, denivelee, type_epreuve)
VALUES (3, 1, 3, 'Semi-Marathon de Paris', 21.097, 0, 'Semi-Marathon');

INSERT INTO epreuve (id_epreuve, id_edition, id_tarif, nom, distance, denivelee, type_epreuve)
VALUES (4, 2, 1, 'Adidas 10 km Paris', 10, 0, '10 Km');

INSERT INTO epreuve (id_epreuve, id_edition, id_tarif, nom, distance, denivelee, type_epreuve)
VALUES (5, 2, 2, 'Marathon de Paris', 42.195, 0, 'Marathon');

INSERT INTO epreuve (id_epreuve, id_edition, id_tarif, nom, distance, denivelee, type_epreuve)
VALUES (6, 2, 3, 'Semi-Marathon de Paris', 21.097, 0, 'Semi-Marathon');

INSERT INTO epreuve (id_epreuve, id_edition, id_tarif, nom, distance, denivelee, type_epreuve)
VALUES (7, 3, 4, 'Run in Lyon 10 km', 10, 0, '10 Km');

INSERT INTO epreuve (id_epreuve, id_edition, id_tarif, nom, distance, denivelee, type_epreuve)
VALUES (8, 3, 5, 'Run in Lyon Marathon', 42.195, 0, 'Marathon');

INSERT INTO epreuve (id_epreuve, id_edition, id_tarif, nom, distance, denivelee, type_epreuve)
VALUES (9, 3, 6, 'Run in Lyon Semi-Marathon', 21.097, 0, 'Semi-Marathon');
