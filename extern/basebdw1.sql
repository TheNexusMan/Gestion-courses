-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 25 avr. 2019 à 16:02
-- Version du serveur :  5.7.24
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bdw1`
--

-- --------------------------------------------------------

--
-- Structure de la table `adherent`
--

DROP TABLE IF EXISTS `adherent`;
CREATE TABLE IF NOT EXISTS `adherent` (
  `id_adherent` int(11) NOT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `sexe` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_certif_club` date DEFAULT NULL,
  `club` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_adherent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `adherent`
--

INSERT INTO `adherent` (`id_adherent`, `nom`, `prenom`, `date_naissance`, `sexe`, `adresse`, `date_certif_club`, `club`) VALUES
(2016001, 'Dupont', 'Alice', '1995-01-01', 'F', '28 rue du boulevard Lyon', '2019-01-01', 'ClubLyon'),
(2016002, 'Dupout', 'Bernard', '1950-01-01', 'H', '2 rue du avenue Lyon', '2018-09-01', 'ClubLyon'),
(2016003, 'Durand', 'Olivier', '1955-01-01', 'H', '7 rue du avenue Paris', '2017-09-01', 'ClubParis'),
(2016004, 'Drand', 'Mat', '1975-01-01', 'H', '7 rue du avenue Paris', '2017-09-10', 'ClubParis'),
(2016005, 'Durad', 'Louis', '1971-01-01', 'H', '7 rue du avenue Paris', '2017-09-21', 'ClubParis'),
(2016006, 'Drad', 'Louise', '2000-01-01', 'F', '7 rue du avenue Paris', '2017-08-01', 'ClubParis'),
(2016007, 'Rasde', 'nico', '1955-07-01', 'H', '7 rue du avenue Paris', '2017-09-01', 'ClubParis'),
(2016008, 'Polain', 'Chris', '1899-01-01', 'H', '7 rue du avenue Paris', '2019-09-01', 'ClubParis'),
(2017001, 'Paso', 'Bruno', '1970-01-01', 'H', '28 rue du avenue Paris', '2018-09-21', 'ClubParis'),
(2017002, 'Paso', 'Celine', '1970-01-01', 'F', '28 rue du avenue Lyon', '2018-09-21', 'ClubLyon'),
(2017003, 'Durand', 'Celine', '1975-01-01', 'F', '28 rue du avenue Paris', '2018-09-21', 'ClubParis'),
(2017004, 'Tuile', 'Mathilde', '1999-01-01', 'F', '28 rue du avenue Paris', '2018-09-21', 'ClubParis'),
(2017005, 'Roli', 'Lou', '2010-01-01', 'F', '28 rue du avenue Paris', '2018-09-21', 'ClubParis'),
(2017006, 'Roula', 'Celine', '2001-01-01', 'F', '28 rue du avenue Paris', '2018-09-21', 'ClubParis'),
(2018001, 'Laple', 'Marie', '1989-01-01', 'F', '28 rue du avenue Lyon', '2018-09-11', 'ClubLyon'),
(2018002, 'Laplo', 'Bernard', '2000-01-01', 'H', '15 rue du avenue Lyon', '2018-07-01', 'ClubLyon'),
(2018003, 'Oplia', 'Blandine', '1899-01-01', 'F', '17 rue du rue Lyon', '2018-12-01', 'ClubLyon'),
(2018004, 'Horil', 'Manu', '1963-01-01', 'H', '28 rue du avenue Lyon', '2018-12-01', 'ClubLyon'),
(2018005, 'Plios', 'Caroline', '1978-01-01', 'F', '28 rue du avenue Lyon', '2018-09-01', 'ClubLyon');

-- --------------------------------------------------------

--
-- Structure de la table `course`
--

DROP TABLE IF EXISTS `course`;
CREATE TABLE IF NOT EXISTS `course` (
  `id_course` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `annee_creation` int(11) NOT NULL,
  `mois` int(11) NOT NULL,
  `site_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_course`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `course`
--

INSERT INTO `course` (`id_course`, `nom`, `annee_creation`, `mois`, `site_url`) VALUES
(1, 'Marathon de Paris', 1976, 6, 'http://www.schneiderelectricparismarathon.com/fr/'),
(2, 'Run in Lyon', 2010, 5, 'http://www.runinlyon.com/fr');

-- --------------------------------------------------------

--
-- Structure de la table `edition`
--

DROP TABLE IF EXISTS `edition`;
CREATE TABLE IF NOT EXISTS `edition` (
  `id_edition` int(11) NOT NULL AUTO_INCREMENT,
  `id_course` int(11) NOT NULL,
  `annee` int(11) NOT NULL,
  `nb_participants` int(11) NOT NULL,
  `date` date NOT NULL,
  `date_inscription` date NOT NULL,
  `date_depot_certificat` date NOT NULL,
  `date_recup_dossard` date NOT NULL,
  PRIMARY KEY (`id_edition`),
  KEY `id_course` (`id_course`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `edition`
--

INSERT INTO `edition` (`id_edition`, `id_course`, `annee`, `nb_participants`, `date`, `date_inscription`, `date_depot_certificat`, `date_recup_dossard`) VALUES
(1, 1, 2017, 45, '2017-04-09', '2017-01-01', '2017-02-01', '2017-04-01'),
(2, 1, 2018, 45, '2018-04-08', '2018-01-01', '2018-02-01', '2018-04-01'),
(3, 2, 2018, 32, '2018-10-07', '2018-01-07', '2018-08-01', '2018-10-01');

-- --------------------------------------------------------

--
-- Structure de la table `epreuve`
--

DROP TABLE IF EXISTS `epreuve`;
CREATE TABLE IF NOT EXISTS `epreuve` (
  `id_epreuve` int(11) NOT NULL AUTO_INCREMENT,
  `id_edition` int(11) NOT NULL,
  `nom` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `distance` int(11) NOT NULL,
  `adresse_depart` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `denivelee` int(11) NOT NULL,
  `type_epreuve` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `plan` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_epreuve`),
  KEY `id_edition` (`id_edition`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `epreuve`
--

INSERT INTO `epreuve` (`id_epreuve`, `id_edition`, `nom`, `distance`, `adresse_depart`, `denivelee`, `type_epreuve`, `plan`) VALUES
(1, 1, 'Paris centre by Nike', 10, 'Avenue des Champs-Elysées, Paris', 0, '10 Km', 'mpplan.jpg'),
(2, 1, 'Marathon de Paris', 42, 'Avenue des Champs-Elysées, Paris', 0, 'Marathon', 'mpplan.jpg'),
(3, 1, 'Semi-Marathon de Paris', 21, 'Avenue des Champs-Elysées, Paris', 0, 'Semi-Marathon', 'mpplan.jpg'),
(4, 2, 'Adidas 10 km Paris', 10, 'Quai Tilsitt, Lyon', 0, '10 Km', 'mpplan.jpg'),
(5, 2, 'Marathon de Paris', 42, 'Quai Tilsitt, Lyon', 0, 'Marathon', 'mpplan.jpg'),
(6, 2, 'Semi-Marathon de Paris', 21, 'Quai Tilsitt, Lyon', 0, 'Semi-Marathon', 'mpplan.jpg'),
(7, 3, 'Run in Lyon 10 km', 10, 'Quai Tilsitt, Lyon', 0, '10 Km', 'runlyon.jpg'),
(8, 3, 'Run in Lyon Marathon', 42, 'Quai Tilsitt, Lyon', 0, 'Marathon', 'runlyon.jpg'),
(9, 3, 'Run in Lyon Semi-Marathon', 21, 'Quai Tilsitt, Lyon', 0, 'Semi-Marathon', 'runlyon.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `participation`
--

DROP TABLE IF EXISTS `participation`;
CREATE TABLE IF NOT EXISTS `participation` (
  `id_participation` int(11) NOT NULL AUTO_INCREMENT,
  `dossard` int(11) NOT NULL,
  `id_adherent` int(11) NOT NULL,
  `id_epreuve` int(11) NOT NULL,
  PRIMARY KEY (`id_participation`),
  KEY `id_epreuve` (`id_epreuve`),
  KEY `id_adherent` (`id_adherent`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `participation`
--

INSERT INTO `participation` (`id_participation`, `dossard`, `id_adherent`, `id_epreuve`) VALUES
(1, 2017000032, 2016005, 1),
(2, 2017000035, 2017002, 1),
(3, 2017000036, 2016002, 1),
(4, 2017000038, 2017001, 1),
(5, 2017000040, 2016003, 1),
(6, 2017000001, 2016008, 2),
(7, 2017000006, 2018002, 2),
(8, 2017000007, 2016001, 2),
(9, 2017000013, 2017006, 2),
(10, 2017000014, 2017004, 2),
(11, 2017000018, 2017005, 3),
(12, 2017000019, 2016007, 3),
(13, 2017000020, 2018001, 3),
(14, 2017000021, 2016004, 3),
(15, 2017000026, 2017003, 3),
(16, 2017000027, 2018003, 3),
(17, 2018000031, 2016006, 4),
(18, 2018000032, 2017001, 4),
(19, 2018000038, 2016004, 4),
(20, 2018000041, 2016008, 4),
(21, 2018000044, 2018004, 4),
(22, 2018000000, 2016007, 5),
(23, 2018000003, 2016002, 5),
(24, 2018000005, 2018002, 5),
(25, 2018000008, 2016003, 5),
(26, 2018000011, 2018004, 5),
(27, 2018000016, 2017005, 6),
(28, 2018000017, 2018005, 6),
(29, 2018000021, 2017003, 6),
(30, 2018000023, 2017002, 6),
(31, 2018000024, 2017006, 6),
(32, 2018000027, 2018001, 6),
(33, 2018000032, 2018002, 7),
(34, 2018000033, 2016007, 7),
(35, 2018000036, 2016008, 7),
(36, 2018000039, 2018004, 7),
(37, 2018000000, 2016001, 8),
(38, 2018000001, 2017003, 8),
(39, 2018000004, 2018005, 8),
(40, 2018000008, 2016005, 8),
(41, 2018000009, 2016003, 8),
(42, 2018000010, 2016006, 8),
(43, 2018000011, 2017005, 8),
(44, 2018000013, 2017004, 8),
(45, 2018000014, 2017006, 8),
(46, 2018000045, 2017001, 8),
(47, 2018000019, 2016004, 9),
(48, 2018000020, 2018001, 9),
(49, 2018000046, 2017002, 9);

-- --------------------------------------------------------

--
-- Structure de la table `resultat`
--

DROP TABLE IF EXISTS `resultat`;
CREATE TABLE IF NOT EXISTS `resultat` (
  `dossard` int(11) NOT NULL,
  `id_epreuve` int(11) NOT NULL,
  `rang` int(11) DEFAULT NULL,
  `nom` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `sexe` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`dossard`,`id_epreuve`),
  KEY `id_epreuve` (`id_epreuve`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `resultat`
--

INSERT INTO `resultat` (`dossard`, `id_epreuve`, `rang`, `nom`, `prenom`, `sexe`) VALUES
(2017000030, 1, 1, 'UGU', 'YJO', 'H'),
(2017000031, 1, 2, 'MUZ', 'GRU', 'H'),
(2017000032, 1, 3, 'Durad', 'Louis', 'H'),
(2017000033, 1, 4, 'GOS', 'SBQ', 'F'),
(2017000034, 1, 5, 'TKP', 'GZT', 'H'),
(2017000035, 1, 6, 'Paso', 'Celine', 'F'),
(2017000036, 1, 7, 'Dupout', 'Bernard', 'H'),
(2017000037, 1, 8, 'CAV', 'SHB', 'H'),
(2017000038, 1, 9, 'Paso', 'Bruno', 'H'),
(2017000039, 1, 10, 'NRV', 'UCP', 'H'),
(2017000040, 1, 11, 'Durand', 'Olivier', 'H'),
(2017000041, 1, 12, 'CSI', 'FDL', 'F'),
(2017000042, 1, 13, 'EYI', 'UUS', 'F'),
(2017000043, 1, 14, 'YTM', 'YQZ', 'H'),
(2017000044, 1, 15, 'LHM', 'LVP', 'F'),
(2017000000, 2, 1, 'NSP', 'IEW', 'F'),
(2017000001, 2, 2, 'Polain', 'Chris', 'H'),
(2017000002, 2, 3, 'YUE', 'EGY', 'F'),
(2017000003, 2, 4, 'FWC', 'BLH', 'F'),
(2017000004, 2, 5, 'DNH', 'ZQO', 'H'),
(2017000005, 2, 6, 'IYI', 'SCJ', 'H'),
(2017000006, 2, 7, 'Laplo', 'Bernard', 'H'),
(2017000007, 2, 8, 'Dupont', 'Alice', 'F'),
(2017000008, 2, 9, 'AKH', 'SEY', 'H'),
(2017000009, 2, 10, 'MHH', 'WME', 'H'),
(2017000010, 2, 11, 'JKF', 'BCQ', 'F'),
(2017000011, 2, 12, 'WVJ', 'JFX', 'H'),
(2017000012, 2, 13, 'WUW', 'JIX', 'H'),
(2017000013, 2, 14, 'Roula', 'Celine', 'F'),
(2017000014, 2, 15, 'Tuile', 'Mathilde', 'F'),
(2017000015, 3, 1, 'UIF', 'LDU', 'H'),
(2017000016, 3, 2, 'EJN', 'VEJ', 'F'),
(2017000017, 3, 3, 'UOM', 'FCT', 'F'),
(2017000018, 3, 4, 'Roli', 'Lou', 'F'),
(2017000019, 3, 5, 'Rasde', 'nico', 'H'),
(2017000020, 3, 6, 'Laple', 'Marie', 'F'),
(2017000021, 3, 7, 'Drand', 'Mat', 'H'),
(2017000022, 3, 8, 'CGM', 'KBO', 'H'),
(2017000023, 3, 9, 'UOB', 'KHG', 'H'),
(2017000024, 3, 10, 'GYR', 'XFC', 'F'),
(2017000025, 3, 11, 'LVS', 'FKK', 'H'),
(2017000026, 3, 12, 'Durand', 'Celine', 'F'),
(2017000027, 3, 13, 'Oplia', 'Blandine', 'F'),
(2017000028, 3, 14, 'BAJ', 'HJC', 'H'),
(2017000029, 3, 15, 'UVT', 'MOW', 'F'),
(2018000030, 4, 1, 'PAT', 'CAI', 'H'),
(2018000031, 4, 2, 'Drad', 'Louise', 'F'),
(2018000032, 4, 3, 'Paso', 'Bruno', 'H'),
(2018000033, 4, 4, 'QIC', 'YWB', 'H'),
(2018000034, 4, 5, 'PRO', 'JBG', 'F'),
(2018000035, 4, 6, 'OQN', 'EGK', 'F'),
(2018000036, 4, 7, 'KEP', 'JUP', 'F'),
(2018000037, 4, 8, 'KMV', 'RDL', 'H'),
(2018000038, 4, 9, 'Drand', 'Mat', 'H'),
(2018000039, 4, 10, 'ALB', 'KOA', 'H'),
(2018000040, 4, 11, 'SEN', 'SER', 'F'),
(2018000041, 4, 12, 'Polain', 'Chris', 'H'),
(2018000042, 4, 13, 'JNJ', 'TUW', 'F'),
(2018000043, 4, 14, 'NUX', 'DGH', 'H'),
(2018000044, 4, 15, 'Horil', 'Manu', 'H'),
(2018000000, 5, 1, 'Rasde', 'nico', 'H'),
(2018000001, 5, 2, 'GFN', 'SXZ', 'H'),
(2018000002, 5, 3, 'ESG', 'FTI', 'H'),
(2018000003, 5, 4, 'Dupout', 'Bernard', 'H'),
(2018000004, 5, 5, 'BLW', 'UEK', 'F'),
(2018000005, 5, 6, 'Laplo', 'Bernard', 'H'),
(2018000006, 5, 7, 'UIB', 'PLB', 'F'),
(2018000007, 5, 8, 'LDT', 'TWM', 'H'),
(2018000008, 5, 9, 'Durand', 'Olivier', 'H'),
(2018000009, 5, 10, 'OSJ', 'NDV', 'F'),
(2018000010, 5, 11, 'GUJ', 'NRZ', 'F'),
(2018000011, 5, 12, 'Horil', 'Manu', 'H'),
(2018000012, 5, 13, 'VRT', 'HVU', 'H'),
(2018000013, 5, 14, 'LMS', 'UVB', 'F'),
(2018000014, 5, 15, 'MEZ', 'CFA', 'F'),
(2018000015, 6, 1, 'YDU', 'AWD', 'F'),
(2018000016, 6, 2, 'Roli', 'Lou', 'F'),
(2018000017, 6, 3, 'Plios', 'Caroline', 'F'),
(2018000018, 6, 4, 'VVY', 'QAT', 'H'),
(2018000019, 6, 5, 'XRU', 'IXZ', 'H'),
(2018000020, 6, 6, 'UVB', 'RLX', 'F'),
(2018000021, 6, 7, 'Durand', 'Celine', 'F'),
(2018000022, 6, 8, 'LLF', 'YKQ', 'H'),
(2018000023, 6, 9, 'Paso', 'Celine', 'F'),
(2018000024, 6, 10, 'Roula', 'Celine', 'F'),
(2018000025, 6, 11, 'JGR', 'YYS', 'H'),
(2018000026, 6, 12, 'OWI', 'XUO', 'F'),
(2018000027, 6, 13, 'Laple', 'Marie', 'F'),
(2018000028, 6, 14, 'ZGL', 'PIJ', 'F'),
(2018000029, 6, 15, 'NXW', 'WGL', 'H'),
(2018000030, 7, 1, 'CFW', 'GBE', 'F'),
(2018000031, 7, 2, 'RJH', 'MCE', 'F'),
(2018000032, 7, 3, 'Laplo', 'Bernard', 'H'),
(2018000033, 7, 4, 'Rasde', 'nico', 'H'),
(2018000034, 7, 5, 'RYE', 'CFF', 'H'),
(2018000035, 7, 6, 'WKT', 'OKH', 'H'),
(2018000036, 7, 7, 'Polain', 'Chris', 'H'),
(2018000037, 7, 8, 'QDB', 'WLJ', 'H'),
(2018000038, 7, 9, 'VJT', 'MGJ', 'F'),
(2018000039, 7, 10, 'Horil', 'Manu', 'H'),
(2018000040, 7, 11, 'TRF', 'SQI', 'H'),
(2018000041, 7, 12, 'YKF', 'YSQ', 'H'),
(2018000042, 7, 13, 'JAA', 'IME', 'F'),
(2018000043, 7, 14, 'YYO', 'GTJ', 'F'),
(2018000044, 7, 15, 'QNM', 'IJM', 'F'),
(2018000000, 8, 1, 'Dupont', 'Alice', 'F'),
(2018000001, 8, 2, 'Durand', 'Celine', 'F'),
(2018000002, 8, 3, 'UHD', 'PNA', 'H'),
(2018000003, 8, 4, 'GAA', 'YHU', 'H'),
(2018000004, 8, 5, 'Plios', 'Caroline', 'F'),
(2018000005, 8, 6, 'PTC', 'TOK', 'H'),
(2018000006, 8, 7, 'GMY', 'ULH', 'H'),
(2018000007, 8, 8, 'QWO', 'LFH', 'H'),
(2018000008, 8, 9, 'Durad', 'Louis', 'H'),
(2018000009, 8, 10, 'Durand', 'Olivier', 'H'),
(2018000010, 8, 11, 'Drad', 'Louise', 'F'),
(2018000011, 8, 12, 'Roli', 'Lou', 'F'),
(2018000012, 8, 13, 'WNL', 'ZOV', 'F'),
(2018000013, 8, 14, 'Tuile', 'Mathilde', 'F'),
(2018000014, 8, 15, 'Roula', 'Celine', 'F'),
(2018000045, 8, NULL, 'Paso', 'Bruno', 'H'),
(2018000015, 9, 1, 'GCL', 'ESR', 'H'),
(2018000016, 9, 2, 'CCV', 'TWU', 'F'),
(2018000017, 9, 3, 'GMT', 'PAZ', 'H'),
(2018000018, 9, 4, 'XAR', 'YYQ', 'F'),
(2018000019, 9, 5, 'Drand', 'Mat', 'H'),
(2018000020, 9, 6, 'Laple', 'Marie', 'F'),
(2018000021, 9, 7, 'FBV', 'HPA', 'F'),
(2018000022, 9, 8, 'XXZ', 'NRS', 'F'),
(2018000023, 9, 9, 'QEA', 'USW', 'F'),
(2018000024, 9, 10, 'CRL', 'HKQ', 'H'),
(2018000025, 9, 11, 'CMJ', 'FGP', 'F'),
(2018000026, 9, 12, 'KEF', 'WIG', 'H'),
(2018000027, 9, 13, 'PWQ', 'XDE', 'H'),
(2018000028, 9, 14, 'MVX', 'WPX', 'H'),
(2018000029, 9, 15, 'AQR', 'XRD', 'F'),
(2018000046, 9, NULL, 'Paso', 'Celine', 'F');

-- --------------------------------------------------------

--
-- Structure de la table `tarif`
--

DROP TABLE IF EXISTS `tarif`;
CREATE TABLE IF NOT EXISTS `tarif` (
  `id_tarif` int(11) NOT NULL AUTO_INCREMENT,
  `id_epreuve` int(11) NOT NULL,
  `age_min` int(11) NOT NULL,
  `age_max` int(11) NOT NULL,
  `tarif` int(11) NOT NULL,
  PRIMARY KEY (`id_tarif`),
  KEY `id_epreuve` (`id_epreuve`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `tarif`
--

INSERT INTO `tarif` (`id_tarif`, `id_epreuve`, `age_min`, `age_max`, `tarif`) VALUES
(1, 1, 12, 25, 15),
(2, 1, 25, 99, 20),
(3, 2, 12, 25, 20),
(4, 2, 25, 99, 25),
(5, 3, 18, 25, 30),
(6, 3, 25, 99, 35),
(7, 4, 12, 99, 21),
(8, 5, 18, 99, 26),
(9, 6, 18, 99, 31),
(10, 7, 18, 25, 10),
(11, 7, 25, 99, 15),
(12, 8, 18, 95, 20),
(13, 9, 18, 95, 25);

-- --------------------------------------------------------

--
-- Structure de la table `temps_passage`
--

DROP TABLE IF EXISTS `temps_passage`;
CREATE TABLE IF NOT EXISTS `temps_passage` (
  `id_epreuve` int(11) NOT NULL,
  `dossard` int(11) NOT NULL,
  `km` int(11) NOT NULL,
  `temps` int(11) NOT NULL,
  PRIMARY KEY (`id_epreuve`,`dossard`,`km`),
  KEY `dossard` (`dossard`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `temps_passage`
--

INSERT INTO `temps_passage` (`id_epreuve`, `dossard`, `km`, `temps`) VALUES
(1, 2017000030, 5, 16),
(1, 2017000030, 10, 31),
(1, 2017000031, 5, 16),
(1, 2017000031, 10, 32),
(1, 2017000032, 5, 16),
(1, 2017000032, 10, 32),
(1, 2017000033, 5, 16),
(1, 2017000033, 10, 33),
(1, 2017000034, 5, 18),
(1, 2017000034, 10, 36),
(1, 2017000035, 5, 18),
(1, 2017000035, 10, 36),
(1, 2017000036, 5, 18),
(1, 2017000036, 10, 36),
(1, 2017000037, 5, 24),
(1, 2017000037, 10, 48),
(1, 2017000038, 5, 24),
(1, 2017000038, 10, 49),
(1, 2017000039, 5, 25),
(1, 2017000039, 10, 49),
(1, 2017000040, 5, 25),
(1, 2017000040, 10, 51),
(1, 2017000041, 5, 25),
(1, 2017000041, 10, 51),
(1, 2017000042, 5, 26),
(1, 2017000042, 10, 51),
(1, 2017000043, 5, 28),
(1, 2017000043, 10, 56),
(1, 2017000044, 5, 28),
(1, 2017000044, 10, 56),
(2, 2017000000, 5, 16),
(2, 2017000000, 10, 32),
(2, 2017000000, 15, 47),
(2, 2017000000, 20, 63),
(2, 2017000000, 25, 79),
(2, 2017000000, 30, 95),
(2, 2017000000, 35, 110),
(2, 2017000000, 40, 126),
(2, 2017000000, 41, 130),
(2, 2017000001, 5, 17),
(2, 2017000001, 10, 34),
(2, 2017000001, 15, 51),
(2, 2017000001, 20, 68),
(2, 2017000001, 25, 85),
(2, 2017000001, 30, 101),
(2, 2017000001, 35, 118),
(2, 2017000001, 40, 135),
(2, 2017000001, 41, 139),
(2, 2017000002, 5, 17),
(2, 2017000002, 10, 34),
(2, 2017000002, 15, 51),
(2, 2017000002, 20, 68),
(2, 2017000002, 25, 85),
(2, 2017000002, 30, 102),
(2, 2017000002, 35, 119),
(2, 2017000002, 40, 136),
(2, 2017000002, 41, 140),
(2, 2017000003, 5, 17),
(2, 2017000003, 10, 35),
(2, 2017000003, 15, 52),
(2, 2017000003, 20, 70),
(2, 2017000003, 25, 87),
(2, 2017000003, 30, 104),
(2, 2017000003, 35, 122),
(2, 2017000003, 40, 139),
(2, 2017000003, 41, 143),
(2, 2017000004, 5, 17),
(2, 2017000004, 10, 35),
(2, 2017000004, 15, 52),
(2, 2017000004, 20, 70),
(2, 2017000004, 25, 87),
(2, 2017000004, 30, 105),
(2, 2017000004, 35, 122),
(2, 2017000004, 40, 140),
(2, 2017000004, 41, 144),
(2, 2017000005, 5, 19),
(2, 2017000005, 10, 37),
(2, 2017000005, 15, 56),
(2, 2017000005, 20, 75),
(2, 2017000005, 25, 93),
(2, 2017000005, 30, 112),
(2, 2017000005, 35, 131),
(2, 2017000005, 40, 150),
(2, 2017000005, 41, 154),
(2, 2017000006, 5, 19),
(2, 2017000006, 10, 38),
(2, 2017000006, 15, 57),
(2, 2017000006, 20, 77),
(2, 2017000006, 25, 96),
(2, 2017000006, 30, 115),
(2, 2017000006, 35, 134),
(2, 2017000006, 40, 153),
(2, 2017000006, 41, 158),
(2, 2017000007, 5, 20),
(2, 2017000007, 10, 39),
(2, 2017000007, 15, 59),
(2, 2017000007, 20, 79),
(2, 2017000007, 25, 98),
(2, 2017000007, 30, 118),
(2, 2017000007, 35, 137),
(2, 2017000007, 40, 157),
(2, 2017000007, 41, 162),
(2, 2017000008, 5, 22),
(2, 2017000008, 10, 43),
(2, 2017000008, 15, 65),
(2, 2017000008, 20, 86),
(2, 2017000008, 25, 108),
(2, 2017000008, 30, 129),
(2, 2017000008, 35, 151),
(2, 2017000008, 40, 172),
(2, 2017000008, 41, 177),
(2, 2017000009, 5, 22),
(2, 2017000009, 10, 43),
(2, 2017000009, 15, 65),
(2, 2017000009, 20, 86),
(2, 2017000009, 25, 108),
(2, 2017000009, 30, 130),
(2, 2017000009, 35, 151),
(2, 2017000009, 40, 173),
(2, 2017000009, 41, 178),
(2, 2017000010, 5, 22),
(2, 2017000010, 10, 44),
(2, 2017000010, 15, 65),
(2, 2017000010, 20, 87),
(2, 2017000010, 25, 109),
(2, 2017000010, 30, 131),
(2, 2017000010, 35, 153),
(2, 2017000010, 40, 174),
(2, 2017000010, 41, 180),
(2, 2017000011, 5, 22),
(2, 2017000011, 10, 45),
(2, 2017000011, 15, 67),
(2, 2017000011, 20, 90),
(2, 2017000011, 25, 112),
(2, 2017000011, 30, 135),
(2, 2017000011, 35, 157),
(2, 2017000011, 40, 180),
(2, 2017000011, 41, 185),
(2, 2017000012, 5, 24),
(2, 2017000012, 10, 48),
(2, 2017000012, 15, 71),
(2, 2017000012, 20, 95),
(2, 2017000012, 25, 119),
(2, 2017000012, 30, 143),
(2, 2017000012, 35, 166),
(2, 2017000012, 40, 190),
(2, 2017000012, 41, 196),
(2, 2017000013, 5, 25),
(2, 2017000013, 10, 51),
(2, 2017000013, 15, 76),
(2, 2017000013, 20, 102),
(2, 2017000013, 25, 127),
(2, 2017000013, 30, 153),
(2, 2017000013, 35, 178),
(2, 2017000013, 40, 204),
(2, 2017000013, 41, 210),
(2, 2017000014, 5, 26),
(2, 2017000014, 10, 53),
(2, 2017000014, 15, 79),
(2, 2017000014, 20, 105),
(2, 2017000014, 25, 131),
(2, 2017000014, 30, 158),
(2, 2017000014, 35, 184),
(2, 2017000014, 40, 210),
(2, 2017000014, 41, 216),
(3, 2017000015, 5, 16),
(3, 2017000015, 10, 32),
(3, 2017000015, 15, 48),
(3, 2017000015, 20, 64),
(3, 2017000015, 21, 67),
(3, 2017000016, 5, 16),
(3, 2017000016, 10, 33),
(3, 2017000016, 15, 49),
(3, 2017000016, 20, 66),
(3, 2017000016, 21, 69),
(3, 2017000017, 5, 17),
(3, 2017000017, 10, 33),
(3, 2017000017, 15, 50),
(3, 2017000017, 20, 66),
(3, 2017000017, 21, 70),
(3, 2017000018, 5, 18),
(3, 2017000018, 10, 35),
(3, 2017000018, 15, 53),
(3, 2017000018, 20, 70),
(3, 2017000018, 21, 74),
(3, 2017000019, 5, 19),
(3, 2017000019, 10, 38),
(3, 2017000019, 15, 57),
(3, 2017000019, 20, 77),
(3, 2017000019, 21, 81),
(3, 2017000020, 5, 21),
(3, 2017000020, 10, 41),
(3, 2017000020, 15, 62),
(3, 2017000020, 20, 82),
(3, 2017000020, 21, 87),
(3, 2017000021, 5, 22),
(3, 2017000021, 10, 45),
(3, 2017000021, 15, 67),
(3, 2017000021, 20, 90),
(3, 2017000021, 21, 95),
(3, 2017000022, 5, 23),
(3, 2017000022, 10, 45),
(3, 2017000022, 15, 68),
(3, 2017000022, 20, 90),
(3, 2017000022, 21, 95),
(3, 2017000023, 5, 24),
(3, 2017000023, 10, 48),
(3, 2017000023, 15, 72),
(3, 2017000023, 20, 96),
(3, 2017000023, 21, 101),
(3, 2017000024, 5, 24),
(3, 2017000024, 10, 48),
(3, 2017000024, 15, 72),
(3, 2017000024, 20, 96),
(3, 2017000024, 21, 101),
(3, 2017000025, 5, 26),
(3, 2017000025, 10, 51),
(3, 2017000025, 15, 77),
(3, 2017000025, 20, 102),
(3, 2017000025, 21, 108),
(3, 2017000026, 5, 26),
(3, 2017000026, 10, 52),
(3, 2017000026, 15, 78),
(3, 2017000026, 20, 104),
(3, 2017000026, 21, 110),
(3, 2017000027, 5, 26),
(3, 2017000027, 10, 52),
(3, 2017000027, 15, 78),
(3, 2017000027, 20, 104),
(3, 2017000027, 21, 110),
(3, 2017000028, 5, 26),
(3, 2017000028, 10, 53),
(3, 2017000028, 15, 79),
(3, 2017000028, 20, 105),
(3, 2017000028, 21, 111),
(3, 2017000029, 5, 27),
(3, 2017000029, 10, 54),
(3, 2017000029, 15, 81),
(3, 2017000029, 20, 108),
(3, 2017000029, 21, 113),
(4, 2018000030, 5, 15),
(4, 2018000030, 10, 31),
(4, 2018000031, 5, 16),
(4, 2018000031, 10, 32),
(4, 2018000032, 5, 16),
(4, 2018000032, 10, 33),
(4, 2018000033, 5, 17),
(4, 2018000033, 10, 33),
(4, 2018000034, 5, 17),
(4, 2018000034, 10, 33),
(4, 2018000035, 5, 17),
(4, 2018000035, 10, 34),
(4, 2018000036, 5, 17),
(4, 2018000036, 10, 34),
(4, 2018000037, 5, 19),
(4, 2018000037, 10, 38),
(4, 2018000038, 5, 20),
(4, 2018000038, 10, 41),
(4, 2018000039, 5, 23),
(4, 2018000039, 10, 45),
(4, 2018000040, 5, 23),
(4, 2018000040, 10, 47),
(4, 2018000041, 5, 24),
(4, 2018000041, 10, 47),
(4, 2018000042, 5, 27),
(4, 2018000042, 10, 53),
(4, 2018000043, 5, 28),
(4, 2018000043, 10, 57),
(4, 2018000044, 5, 28),
(4, 2018000044, 10, 57),
(5, 2018000000, 5, 15),
(5, 2018000000, 10, 30),
(5, 2018000000, 15, 45),
(5, 2018000000, 20, 60),
(5, 2018000000, 25, 75),
(5, 2018000000, 30, 90),
(5, 2018000000, 35, 105),
(5, 2018000000, 40, 120),
(5, 2018000000, 41, 124),
(5, 2018000001, 5, 16),
(5, 2018000001, 10, 31),
(5, 2018000001, 15, 47),
(5, 2018000001, 20, 63),
(5, 2018000001, 25, 79),
(5, 2018000001, 30, 94),
(5, 2018000001, 35, 110),
(5, 2018000001, 40, 126),
(5, 2018000001, 41, 129),
(5, 2018000002, 5, 17),
(5, 2018000002, 10, 34),
(5, 2018000002, 15, 51),
(5, 2018000002, 20, 68),
(5, 2018000002, 25, 85),
(5, 2018000002, 30, 103),
(5, 2018000002, 35, 120),
(5, 2018000002, 40, 137),
(5, 2018000002, 41, 141),
(5, 2018000003, 5, 17),
(5, 2018000003, 10, 35),
(5, 2018000003, 15, 52),
(5, 2018000003, 20, 69),
(5, 2018000003, 25, 87),
(5, 2018000003, 30, 104),
(5, 2018000003, 35, 122),
(5, 2018000003, 40, 139),
(5, 2018000003, 41, 143),
(5, 2018000004, 5, 18),
(5, 2018000004, 10, 36),
(5, 2018000004, 15, 54),
(5, 2018000004, 20, 72),
(5, 2018000004, 25, 90),
(5, 2018000004, 30, 108),
(5, 2018000004, 35, 126),
(5, 2018000004, 40, 144),
(5, 2018000004, 41, 148),
(5, 2018000005, 5, 20),
(5, 2018000005, 10, 40),
(5, 2018000005, 15, 60),
(5, 2018000005, 20, 80),
(5, 2018000005, 25, 100),
(5, 2018000005, 30, 121),
(5, 2018000005, 35, 141),
(5, 2018000005, 40, 161),
(5, 2018000005, 41, 166),
(5, 2018000006, 5, 26),
(5, 2018000006, 10, 51),
(5, 2018000006, 15, 77),
(5, 2018000006, 20, 103),
(5, 2018000006, 25, 128),
(5, 2018000006, 30, 154),
(5, 2018000006, 35, 179),
(5, 2018000006, 40, 205),
(5, 2018000006, 41, 211),
(5, 2018000007, 5, 26),
(5, 2018000007, 10, 51),
(5, 2018000007, 15, 77),
(5, 2018000007, 20, 103),
(5, 2018000007, 25, 128),
(5, 2018000007, 30, 154),
(5, 2018000007, 35, 179),
(5, 2018000007, 40, 205),
(5, 2018000007, 41, 211),
(5, 2018000008, 5, 26),
(5, 2018000008, 10, 52),
(5, 2018000008, 15, 78),
(5, 2018000008, 20, 104),
(5, 2018000008, 25, 130),
(5, 2018000008, 30, 156),
(5, 2018000008, 35, 182),
(5, 2018000008, 40, 208),
(5, 2018000008, 41, 214),
(5, 2018000009, 5, 26),
(5, 2018000009, 10, 53),
(5, 2018000009, 15, 79),
(5, 2018000009, 20, 105),
(5, 2018000009, 25, 132),
(5, 2018000009, 30, 158),
(5, 2018000009, 35, 184),
(5, 2018000009, 40, 210),
(5, 2018000009, 41, 217),
(5, 2018000010, 5, 26),
(5, 2018000010, 10, 53),
(5, 2018000010, 15, 79),
(5, 2018000010, 20, 106),
(5, 2018000010, 25, 132),
(5, 2018000010, 30, 158),
(5, 2018000010, 35, 185),
(5, 2018000010, 40, 211),
(5, 2018000010, 41, 217),
(5, 2018000011, 5, 27),
(5, 2018000011, 10, 54),
(5, 2018000011, 15, 81),
(5, 2018000011, 20, 107),
(5, 2018000011, 25, 134),
(5, 2018000011, 30, 161),
(5, 2018000011, 35, 188),
(5, 2018000011, 40, 215),
(5, 2018000011, 41, 221),
(5, 2018000012, 5, 28),
(5, 2018000012, 10, 56),
(5, 2018000012, 15, 83),
(5, 2018000012, 20, 111),
(5, 2018000012, 25, 139),
(5, 2018000012, 30, 167),
(5, 2018000012, 35, 194),
(5, 2018000012, 40, 222),
(5, 2018000012, 41, 229),
(5, 2018000013, 5, 28),
(5, 2018000013, 10, 57),
(5, 2018000013, 15, 85),
(5, 2018000013, 20, 114),
(5, 2018000013, 25, 142),
(5, 2018000013, 30, 170),
(5, 2018000013, 35, 199),
(5, 2018000013, 40, 227),
(5, 2018000013, 41, 234),
(5, 2018000014, 5, 30),
(5, 2018000014, 10, 59),
(5, 2018000014, 15, 89),
(5, 2018000014, 20, 118),
(5, 2018000014, 25, 148),
(5, 2018000014, 30, 177),
(5, 2018000014, 35, 207),
(5, 2018000014, 40, 236),
(5, 2018000014, 41, 244),
(6, 2018000015, 5, 15),
(6, 2018000015, 10, 31),
(6, 2018000015, 15, 46),
(6, 2018000015, 20, 62),
(6, 2018000015, 21, 65),
(6, 2018000016, 5, 16),
(6, 2018000016, 10, 32),
(6, 2018000016, 15, 49),
(6, 2018000016, 20, 65),
(6, 2018000016, 21, 68),
(6, 2018000017, 5, 17),
(6, 2018000017, 10, 33),
(6, 2018000017, 15, 50),
(6, 2018000017, 20, 66),
(6, 2018000017, 21, 70),
(6, 2018000018, 5, 17),
(6, 2018000018, 10, 34),
(6, 2018000018, 15, 51),
(6, 2018000018, 20, 68),
(6, 2018000018, 21, 72),
(6, 2018000019, 5, 17),
(6, 2018000019, 10, 35),
(6, 2018000019, 15, 52),
(6, 2018000019, 20, 69),
(6, 2018000019, 21, 73),
(6, 2018000020, 5, 18),
(6, 2018000020, 10, 36),
(6, 2018000020, 15, 54),
(6, 2018000020, 20, 72),
(6, 2018000020, 21, 76),
(6, 2018000021, 5, 18),
(6, 2018000021, 10, 36),
(6, 2018000021, 15, 54),
(6, 2018000021, 20, 73),
(6, 2018000021, 21, 77),
(6, 2018000022, 5, 18),
(6, 2018000022, 10, 36),
(6, 2018000022, 15, 54),
(6, 2018000022, 20, 73),
(6, 2018000022, 21, 77),
(6, 2018000023, 5, 20),
(6, 2018000023, 10, 39),
(6, 2018000023, 15, 59),
(6, 2018000023, 20, 78),
(6, 2018000023, 21, 83),
(6, 2018000024, 5, 20),
(6, 2018000024, 10, 40),
(6, 2018000024, 15, 60),
(6, 2018000024, 20, 80),
(6, 2018000024, 21, 85),
(6, 2018000025, 5, 23),
(6, 2018000025, 10, 47),
(6, 2018000025, 15, 70),
(6, 2018000025, 20, 94),
(6, 2018000025, 21, 99),
(6, 2018000026, 5, 24),
(6, 2018000026, 10, 47),
(6, 2018000026, 15, 71),
(6, 2018000026, 20, 95),
(6, 2018000026, 21, 100),
(6, 2018000027, 5, 24),
(6, 2018000027, 10, 47),
(6, 2018000027, 15, 71),
(6, 2018000027, 20, 95),
(6, 2018000027, 21, 100),
(6, 2018000028, 5, 25),
(6, 2018000028, 10, 49),
(6, 2018000028, 15, 74),
(6, 2018000028, 20, 99),
(6, 2018000028, 21, 104),
(6, 2018000029, 5, 29),
(6, 2018000029, 10, 59),
(6, 2018000029, 15, 88),
(6, 2018000029, 20, 117),
(6, 2018000029, 21, 124),
(7, 2018000030, 5, 15),
(7, 2018000030, 10, 30),
(7, 2018000031, 5, 17),
(7, 2018000031, 10, 34),
(7, 2018000032, 5, 18),
(7, 2018000032, 10, 35),
(7, 2018000033, 5, 18),
(7, 2018000033, 10, 36),
(7, 2018000034, 5, 18),
(7, 2018000034, 10, 36),
(7, 2018000035, 5, 19),
(7, 2018000035, 10, 38),
(7, 2018000036, 5, 19),
(7, 2018000036, 10, 39),
(7, 2018000037, 5, 20),
(7, 2018000037, 10, 39),
(7, 2018000038, 5, 23),
(7, 2018000038, 10, 46),
(7, 2018000039, 5, 23),
(7, 2018000039, 10, 47),
(7, 2018000040, 5, 24),
(7, 2018000040, 10, 47),
(7, 2018000041, 5, 24),
(7, 2018000041, 10, 49),
(7, 2018000042, 5, 25),
(7, 2018000042, 10, 50),
(7, 2018000043, 5, 25),
(7, 2018000043, 10, 51),
(7, 2018000044, 5, 26),
(7, 2018000044, 10, 51),
(8, 2018000000, 5, 15),
(8, 2018000000, 10, 31),
(8, 2018000000, 15, 46),
(8, 2018000000, 20, 61),
(8, 2018000000, 25, 77),
(8, 2018000000, 30, 92),
(8, 2018000000, 35, 107),
(8, 2018000000, 40, 123),
(8, 2018000000, 41, 126),
(8, 2018000001, 5, 15),
(8, 2018000001, 10, 31),
(8, 2018000001, 15, 46),
(8, 2018000001, 20, 62),
(8, 2018000001, 25, 77),
(8, 2018000001, 30, 92),
(8, 2018000001, 35, 108),
(8, 2018000001, 40, 123),
(8, 2018000001, 41, 127),
(8, 2018000002, 5, 17),
(8, 2018000002, 10, 35),
(8, 2018000002, 15, 52),
(8, 2018000002, 20, 69),
(8, 2018000002, 25, 86),
(8, 2018000002, 30, 104),
(8, 2018000002, 35, 121),
(8, 2018000002, 40, 138),
(8, 2018000002, 41, 142),
(8, 2018000003, 5, 17),
(8, 2018000003, 10, 35),
(8, 2018000003, 15, 52),
(8, 2018000003, 20, 69),
(8, 2018000003, 25, 87),
(8, 2018000003, 30, 104),
(8, 2018000003, 35, 121),
(8, 2018000003, 40, 138),
(8, 2018000003, 41, 143),
(8, 2018000004, 5, 19),
(8, 2018000004, 10, 38),
(8, 2018000004, 15, 56),
(8, 2018000004, 20, 75),
(8, 2018000004, 25, 94),
(8, 2018000004, 30, 113),
(8, 2018000004, 35, 132),
(8, 2018000004, 40, 150),
(8, 2018000004, 41, 155),
(8, 2018000005, 5, 20),
(8, 2018000005, 10, 40),
(8, 2018000005, 15, 60),
(8, 2018000005, 20, 81),
(8, 2018000005, 25, 101),
(8, 2018000005, 30, 121),
(8, 2018000005, 35, 141),
(8, 2018000005, 40, 161),
(8, 2018000005, 41, 166),
(8, 2018000006, 5, 20),
(8, 2018000006, 10, 41),
(8, 2018000006, 15, 61),
(8, 2018000006, 20, 82),
(8, 2018000006, 25, 102),
(8, 2018000006, 30, 123),
(8, 2018000006, 35, 143),
(8, 2018000006, 40, 164),
(8, 2018000006, 41, 169),
(8, 2018000007, 5, 21),
(8, 2018000007, 10, 41),
(8, 2018000007, 15, 62),
(8, 2018000007, 20, 82),
(8, 2018000007, 25, 103),
(8, 2018000007, 30, 123),
(8, 2018000007, 35, 144),
(8, 2018000007, 40, 165),
(8, 2018000007, 41, 169),
(8, 2018000008, 5, 21),
(8, 2018000008, 10, 43),
(8, 2018000008, 15, 64),
(8, 2018000008, 20, 85),
(8, 2018000008, 25, 107),
(8, 2018000008, 30, 128),
(8, 2018000008, 35, 149),
(8, 2018000008, 40, 171),
(8, 2018000008, 41, 176),
(8, 2018000009, 5, 22),
(8, 2018000009, 10, 43),
(8, 2018000009, 15, 65),
(8, 2018000009, 20, 86),
(8, 2018000009, 25, 108),
(8, 2018000009, 30, 129),
(8, 2018000009, 35, 151),
(8, 2018000009, 40, 172),
(8, 2018000009, 41, 177),
(8, 2018000010, 5, 22),
(8, 2018000010, 10, 45),
(8, 2018000010, 15, 67),
(8, 2018000010, 20, 90),
(8, 2018000010, 25, 112),
(8, 2018000010, 30, 134),
(8, 2018000010, 35, 157),
(8, 2018000010, 40, 179),
(8, 2018000010, 41, 185),
(8, 2018000011, 5, 22),
(8, 2018000011, 10, 45),
(8, 2018000011, 15, 67),
(8, 2018000011, 20, 90),
(8, 2018000011, 25, 112),
(8, 2018000011, 30, 135),
(8, 2018000011, 35, 157),
(8, 2018000011, 40, 180),
(8, 2018000011, 41, 185),
(8, 2018000012, 5, 25),
(8, 2018000012, 10, 51),
(8, 2018000012, 15, 76),
(8, 2018000012, 20, 102),
(8, 2018000012, 25, 127),
(8, 2018000012, 30, 153),
(8, 2018000012, 35, 178),
(8, 2018000012, 40, 204),
(8, 2018000012, 41, 210),
(8, 2018000013, 5, 27),
(8, 2018000013, 10, 53),
(8, 2018000013, 15, 80),
(8, 2018000013, 20, 107),
(8, 2018000013, 25, 134),
(8, 2018000013, 30, 160),
(8, 2018000013, 35, 187),
(8, 2018000013, 40, 214),
(8, 2018000013, 41, 220),
(8, 2018000014, 5, 30),
(8, 2018000014, 10, 60),
(8, 2018000014, 15, 90),
(8, 2018000014, 20, 119),
(8, 2018000014, 25, 149),
(8, 2018000014, 30, 179),
(8, 2018000014, 35, 209),
(8, 2018000014, 40, 239),
(8, 2018000014, 41, 246),
(8, 2018000045, 5, 26),
(8, 2018000045, 10, 32),
(8, 2018000045, 15, 59),
(8, 2018000045, 20, 117),
(8, 2018000045, 25, 142),
(8, 2018000045, 30, 101),
(8, 2018000045, 35, 137),
(8, 2018000045, 40, 205),
(9, 2018000015, 5, 16),
(9, 2018000015, 10, 32),
(9, 2018000015, 15, 48),
(9, 2018000015, 20, 64),
(9, 2018000015, 21, 67),
(9, 2018000016, 5, 18),
(9, 2018000016, 10, 36),
(9, 2018000016, 15, 53),
(9, 2018000016, 20, 71),
(9, 2018000016, 21, 75),
(9, 2018000017, 5, 18),
(9, 2018000017, 10, 36),
(9, 2018000017, 15, 54),
(9, 2018000017, 20, 72),
(9, 2018000017, 21, 75),
(9, 2018000018, 5, 19),
(9, 2018000018, 10, 38),
(9, 2018000018, 15, 56),
(9, 2018000018, 20, 75),
(9, 2018000018, 21, 79),
(9, 2018000019, 5, 19),
(9, 2018000019, 10, 39),
(9, 2018000019, 15, 58),
(9, 2018000019, 20, 78),
(9, 2018000019, 21, 82),
(9, 2018000020, 5, 19),
(9, 2018000020, 10, 39),
(9, 2018000020, 15, 58),
(9, 2018000020, 20, 78),
(9, 2018000020, 21, 82),
(9, 2018000021, 5, 20),
(9, 2018000021, 10, 40),
(9, 2018000021, 15, 60),
(9, 2018000021, 20, 79),
(9, 2018000021, 21, 84),
(9, 2018000022, 5, 22),
(9, 2018000022, 10, 43),
(9, 2018000022, 15, 65),
(9, 2018000022, 20, 86),
(9, 2018000022, 21, 91),
(9, 2018000023, 5, 22),
(9, 2018000023, 10, 44),
(9, 2018000023, 15, 65),
(9, 2018000023, 20, 87),
(9, 2018000023, 21, 92),
(9, 2018000024, 5, 22),
(9, 2018000024, 10, 44),
(9, 2018000024, 15, 66),
(9, 2018000024, 20, 88),
(9, 2018000024, 21, 92),
(9, 2018000025, 5, 24),
(9, 2018000025, 10, 47),
(9, 2018000025, 15, 71),
(9, 2018000025, 20, 94),
(9, 2018000025, 21, 99),
(9, 2018000026, 5, 24),
(9, 2018000026, 10, 48),
(9, 2018000026, 15, 73),
(9, 2018000026, 20, 97),
(9, 2018000026, 21, 102),
(9, 2018000027, 5, 26),
(9, 2018000027, 10, 51),
(9, 2018000027, 15, 77),
(9, 2018000027, 20, 103),
(9, 2018000027, 21, 108),
(9, 2018000028, 5, 28),
(9, 2018000028, 10, 55),
(9, 2018000028, 15, 83),
(9, 2018000028, 20, 110),
(9, 2018000028, 21, 116),
(9, 2018000029, 5, 28),
(9, 2018000029, 10, 57),
(9, 2018000029, 15, 85),
(9, 2018000029, 20, 113),
(9, 2018000029, 21, 119),
(9, 2018000046, 5, 27),
(9, 2018000046, 10, 30),
(9, 2018000046, 15, 80),
(9, 2018000046, 20, 87);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `id_adherent` int(11) DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mdp` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pseudo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_user`),
  KEY `id_adherent` (`id_adherent`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `id_adherent`, `type`, `mdp`, `pseudo`) VALUES
(1, 2016004, 'Adherent', '1234', 'Mat'),
(2, 2016008, 'Adherent', 'DSA22', 'Chris'),
(3, 2017001, 'Adherent', 'SDAZ13', 'Bruno'),
(4, NULL, 'Admin', 'aze', 'Arnaud'),
(5, NULL, 'Admin', 'azerty', 'Damien');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
