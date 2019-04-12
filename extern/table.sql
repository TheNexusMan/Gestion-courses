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
        id_adherent    Int  AUTO_INCREMENT  NOT NULL,
        nom            Varchar (50) NOT NULL,
        prenom         Varchar (200) NOT NULL,
        num_adherent   Int NOT NULL,
        certif_club    Varchar (200),
        sexe           Varchar (20) NOT NULL,
        adresse        Varchar (200),
        club           Varchar (200),
        date_naissance Date,
        id_user        Int NOT NULL,
	    PRIMARY KEY (id_adherent)
);


#------------------------------------------------------------
# Table: course
#------------------------------------------------------------

CREATE TABLE course(
        id_course      Int  AUTO_INCREMENT  NOT NULL,
        nom            Varchar (100) NOT NULL,
        annee_creation Date NOT NULL,
        mois           Int NOT NULL,
        id_edition     Int NOT NULL,
	    PRIMARY KEY (id_course)
) ;


#------------------------------------------------------------
# Table: edition
#------------------------------------------------------------

CREATE TABLE edition(
        id_edition            Int  AUTO_INCREMENT  NOT NULL,
        annee                 Int NOT NULL,
        id_course             Int NOT NULL,
        nb_participants       Int NOT NULL,
        plan                  Varchar (200) NOT NULL,
        adresse_depart        Varchar (200) NOT NULL,
        date                  Date NOT NULL,
        site_url              Varchar (200) NOT NULL,
        date_inscription      Date NOT NULL,
        date_depot_certificat Date NOT NULL,
        date_recup_dossard    Date NOT NULL,
        id_epreuve            Int NOT NULL,
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
        id_epreuve_A_pour_tarif Int NOT NULL,
	    PRIMARY KEY (id_tarif)
);


#------------------------------------------------------------
# Table: epreuve
#------------------------------------------------------------

CREATE TABLE epreuve(
        id_epreuve   Int  AUTO_INCREMENT  NOT NULL,
        id_edition   Int NOT NULL,
        distance     Int NOT NULL,
        nom          Varchar (200) NOT NULL,
        denivelee    Int NOT NULL,
        type_epreuve Varchar (200) NOT NULL,
        id_tarif     Int NOT NULL,
	    PRIMARY KEY (id_epreuve)
);


#------------------------------------------------------------
# Table: participation
#------------------------------------------------------------

CREATE TABLE participation(
        id_participation    Int NOT NULL,
        dossard             Int NOT NULL,
        id_adherent         Int NOT NULL,
        id_epreuve          Int NOT NULL,
        id_edition          Int NOT NULL,
        id_epreuve_possede  Int NOT NULL,
        id_adherent_possede Int NOT NULL,
        dossard_resultat    Int NOT NULL,
	    PRIMARY KEY (id_participation,dossard)
);


#------------------------------------------------------------
# Table: resultat
#------------------------------------------------------------

CREATE TABLE resultat(
        dossard               Int NOT NULL,
        rang                  Int,
        nom                   Varchar (200) NOT NULL,
        prenom                Varchar (200) NOT NULL,
        sexe                  Varchar (20) NOT NULL,
        id_edition            Int NOT NULL,
        id_epreuve            Int NOT NULL,
        id_participation      Int NOT NULL,
	    PRIMARY KEY (dossard)
);


#------------------------------------------------------------
# Table: temps_passage
#------------------------------------------------------------

CREATE TABLE temps_passage(
        id_temps              Int  AUTO_INCREMENT  NOT NULL,
        dossard               Int NOT NULL,
        km                    Int NOT NULL,
        temps                 Int NOT NULL,
        id_edition            Int NOT NULL,
        id_preuve             Int NOT NULL,
        id_participation      Int NOT NULL,
        dossard_participation Int NOT NULL,
	    PRIMARY KEY (id_temps)
);

ALTER TABLE course
ADD CONSTRAINT id_edition FOREIGN KEY (id_edition) REFERENCES edition;

ALTER TABLE edition
ADD CONSTRAINT id_epreuve FOREIGN KEY (id_epreuve) REFERENCES epreuve;

ALTER TABLE tarif
ADD CONSTRAINT id_epreuve FOREIGN KEY (id_epreuve) REFERENCES epreuve;

ALTER TABLE user
ADD CONSTRAINT id_adherent FOREIGN KEY (id_adherent) REFERENCES epreuve;

ALTER TABLE epreuve
ADD CONSTRAINT id_tarif FOREIGN KEY (id_tarif) REFERENCES tarif;

ALTER TABLE adherent
ADD CONSTRAINT id_user FOREIGN KEY (id_user) REFERENCES user;

ALTER TABLE participation
ADD CONSTRAINT id_epreuve FOREIGN KEY (id_epreuve) REFERENCES epreuve;

ALTER TABLE participation
ADD CONSTRAINT id_adherent FOREIGN KEY (id_adherent) REFERENCES adherent;

ALTER TABLE temps_passage
ADD CONSTRAINT id_participation FOREIGN KEY (id_participation) REFERENCES participation;

ALTER TABLE resultat
ADD CONSTRAINT id_epreuve FOREIGN KEY (id_epreuve) REFERENCES epreuve;

ALTER TABLE resultat
ADD CONSTRAINT id_edition FOREIGN KEY (id_edition) REFERENCES epreuve;