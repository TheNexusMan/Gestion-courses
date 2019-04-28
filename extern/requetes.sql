-- Arnaud DEBRABANT P1707147 - Damien PETITJEAN P1408987

-- Ici se trouvent toutes les requêtes utilisés sur le site

-- ////////////////////Requêtes index.php\\\\\\\\\\\\\\\\\\\\

-- On essait de récupérer le user correspondant au pseudo
SELECT * FROM user WHERE pseudo = '$pseudo'

-- Si le pseudo existe, on essait de récupérer l'user correspondant au pseudo et au mot de passe
SELECT * FROM user WHERE pseudo = '$pseudo' AND mdp = '$pw'



-- ////////////////////Requêtes adherent.php\\\\\\\\\\\\\\\\\\\\

-- Utilisée pour tester si l'adherent est nouveau (si le résultat est vide alors il est nouveau)
SELECT * FROM adherent WHERE id_adherent = $idUser

-- Ajout des informations du nouvel adhérent
INSERT INTO adherent (id_adherent, nom, prenom, date_naissance, sexe, adresse, date_certif_club, club)
VALUES ('$idUser', '$modNom', '$modPrenom', $modNaissance, '$modSexe', '$modAdresse', $modDateClub, '$modNomClub')

-- Modification des informations de l'adhérent
UPDATE adherent
SET nom = '$modNom', prenom = '$modPrenom', date_naissance = $modNaissance, sexe = '$modSexe', adresse = '$modAdresse', date_certif_club = $modDateClub, club = '$modNomClub'
WHERE id_adherent = $idUser

-- Récupération des informations de l'adherent
SELECT * FROM adherent WHERE id_adherent = $idUser

-- Récupération des éditions participées par l'adhérent (ORDER BY ajouté en cas de tri sur le tableau)
SELECT Co.nom, year(Ed.date) AS annee, Ep.distance, Tp.temps AS temps, Ed.id_edition
FROM (SELECT Pa.* FROM participation Pa WHERE Pa.id_adherent = $idUser) AS Part
        NATURAL JOIN epreuve Ep
        NATURAL JOIN edition Ed
        JOIN COURSE Co ON Ed.id_course = Co.id_course
        JOIN (SELECT id_epreuve, dossard, MAX(temps) AS temps
            FROM temps_passage GROUP BY id_epreuve, dossard) AS Tp ON Tp.id_epreuve = Part.id_epreuve AND Tp.dossard = Part.dossard
ORDER BY $order $sensGet



-- ////////////////////Requêtes adherents.php\\\\\\\\\\\\\\\\\\\\

-- Suppression d'un adhérent
DELETE FROM adherent WHERE id_adherent = $deleteAdherent

-- Suppression des participations d'un adhérent
DELETE FROM participation WHERE id_adherent = $deleteAdherent

-- Suppression de l'user lié à un adhérent
DELETE FROM user WHERE id_adherent = $deleteAdherent

-- Récupération du dernier id adherent
SELECT MAX(id.id_adherent) AS id_adherent FROM (select id_adherent from adherent UNION SELECT id_adherent FROM user) AS id

-- Ajout d'un adhérent
INSERT INTO user (id_adherent, type, mdp, pseudo) VALUES ('$id_adherent', 'Adherent', '$mdp', '$pseudo')

-- Récupération des adhérents (ORDER BY ajouté en cas de tri sur le tableau)
SELECT * FROM adherent
ORDER BY $order $sensGet


-- ////////////////////Requêtes courses.php\\\\\\\\\\\\\\\\\\\\
-- Suppression d'une course
DELETE FROM course WHERE id_course = $idCourseToDel

-- Sélection des éditions liées à la course à supprimer
SELECT id_edition FROM edition WHERE id_course = $idCourseToDel

-- Sélection des epreuves liées aux éditions à supprimer
SELECT id_epreuve FROM epreuve WHERE id_edition = $editionToDelete

-- Suppression des tarifs liés à l'épreuve
DELETE FROM tarif WHERE id_epreuve = $idEpreuveToDel

-- Suppression des participations liées à l'épreuve
DELETE FROM participation WHERE id_epreuve = $idEpreuveToDel

-- Suppression des résultats liés à l'épreuve
DELETE FROM resultat WHERE id_epreuve = $idEpreuveToDel

-- Suppression des temps_passage liés à l'épreuve
DELETE FROM temps_passage WHERE id_epreuve = $idEpreuveToDel

-- Suppression des épreuves liées aux éditions à supprimer
DELETE FROM epreuve WHERE id_edition = $editionToDelete

-- Suppression des éditions liées à la course à supprimer
DELETE FROM edition WHERE id_course = $idCourseToDel

-- Ajout d'une nouvelle course
INSERT INTO course (nom, annee_creation, mois, site_url) VALUES('$nameAdd', $anneeAdd, $monthAdd, '$site')

-- Récupération des courses (ORDER BY ajouté en cas de tri sur le tableau)
SELECT * FROM course
ORDER BY $order $sensGet



-- ////////////////////Requêtes course.php\\\\\\\\\\\\\\\\\\\\

-- Suppression d'une édition
DELETE FROM edition WHERE id_edition = $editionToDelete

-- Sélection des epreuves liées à l'édition à supprimer
SELECT id_epreuve FROM epreuve WHERE id_edition = $editionToDelete

-- Suppression des tarifs liés à l'épreuve
DELETE FROM tarif WHERE id_epreuve = $idEpreuveToDel

-- Suppression des participations liées à l'epreuve
DELETE FROM participation WHERE id_epreuve = $idEpreuveToDel

-- Suppression des résultats liés à l'épreuve
DELETE FROM resultat WHERE id_epreuve = $idEpreuveToDel

-- Suppression des temps_passage liés à l'épreuve
DELETE FROM temps_passage WHERE id_epreuve = $idEpreuveToDel

-- Suppression des épreuves liées à l'édition à supprimer
DELETE FROM epreuve WHERE id_edition = $editionToDelete

-- Ajout d'une nouvelle édition
INSERT INTO edition (id_course, annee, nb_participants, date, date_inscription, date_depot_certificat, date_recup_dossard)
VALUES ('$idCourse', '$anneeEd', '$nbParti', '$dateAdd', '$dateIns', '$dateDepot', '$dateDossard')

-- Modification des informations de la course
UPDATE course
SET annee_creation = $modAnneeCreation, mois = $modMois, site_url = '$site'
WHERE id_course = $idCourse

-- Récupération des informations de la course
SELECT * FROM course WHERE id_course = $idCourse

-- Récupération des éditions en fonction du trie du tableau (ORDER BY ajouté en cas de tri sur le tableau)
SELECT ed.annee, ed.nb_participants, ed.id_edition
FROM edition ed
WHERE ed.id_course = $idCourse
ORDER BY $order $sensGet



-- ////////////////////Requêtes edition.php\\\\\\\\\\\\\\\\\\\\

-- Suppression d'une épreuve
DELETE FROM epreuve WHERE id_epreuve = $epreuveToDelete

-- Suppression des tarifs liés à l'épreuve
DELETE FROM tarif WHERE id_epreuve = $idEpreuveToDel

-- Suppression des participations liées à l'epreuve
DELETE FROM participation WHERE id_epreuve = $idEpreuveToDel

-- Suppression des résultats liés à l'épreuve
DELETE FROM resultat WHERE id_epreuve = $idEpreuveToDel

-- Suppression des temps_passage liés à l'épreuve
DELETE FROM temps_passage WHERE id_epreuve = $idEpreuveToDel

-- Ajout d'une nouvelle épreuve
INSERT INTO epreuve (id_edition, nom, distance, adresse_depart, denivelee, type_epreuve, plan)
VALUES ($idEdition, '$nom', $distance, '$adresseDepart', $denivelee, '$typeEpreuve', '$plan')

-- Modification des informations de l'édition
UPDATE edition
SET nb_participants = $modNbParticipants, date = $modDate, date_inscription = $modDateInscription, date_depot_certificat = $modDateDepotCertificat, date_recup_dossard = $modDateRecupDossard
WHERE id_edition = $idEdition

-- Récupération des informations de l'édition
SELECT * FROM edition WHERE id_edition = $idEdition

-- Récupération du nom de la course de l'édition
SELECT nom FROM course WHERE id_course = $id_course

-- Récupération des épreuves (ORDER BY ajouté en cas de tri sur le tableau)
SELECT id_epreuve, nom, distance, denivelee, type_epreuve
FROM epreuve
WHERE id_edition = $idEdition
ORDER BY $order $sensGet



-- ////////////////////Requêtes epreuve.php\\\\\\\\\\\\\\\\\\\\

-- Suppression d'un tarif
DELETE FROM tarif WHERE id_tarif = $tarifToDelete

-- Ajout d'un nouveau tarif
INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
VALUES ($idEpreuve, $ageMin, $ageMax, $tarif)

-- Ajout des résultats du fichier CSV
INSERT INTO resultat (dossard, id_epreuve, rang, nom, prenom, sexe)
VALUES ($dossard, $idEpreuve, $rang, '$nom', '$prenom', '$sexe')

-- Ajout des temps du fichier CSV
INSERT INTO temps_passage (id_epreuve, dossard, km, temps)
VALUES ($idEpreuve, $dossard, $km, $temps)

-- Création des participations à partir des résultats ajoutés
INSERT INTO participation (dossard, id_adherent, id_epreuve)
SELECT r.dossard ,a.id_adherent, r.id_epreuve
FROM resultat r INNER JOIN adherent a on (a.nom = r.nom and a.prenom = r.prenom and a.sexe = r.sexe)
WHERE r.id_epreuve = $idEpreuve

-- Modification des informations de l'édition (avec le plan si le fichier est existant et au bon format)
UPDATE epreuve
SET nom = '$modName', distance = $modDistance, adresse_depart = '$modAdresse_depart', denivelee = $modDenivelee, type_epreuve = '$modType', plan = '$modPlan'
WHERE id_epreuve = $idEpreuve

-- Récupération des infos de l'épreuve
SELECT id_edition, nom, distance, adresse_depart, denivelee, type_epreuve, plan
FROM epreuve
WHERE id_epreuve = $idEpreuve

-- Requête de test si l'épreuve est terminée (si le résultat est vide alors elle n'est pas terminée)
SELECT * FROM resultat WHERE id_epreuve = $idEpreuve

-- Requête de récupération de la distance du dernier temps (la distance de l'épreuve dans la bdd ne correspond pas au km du dernier temps)
SELECT MAX(tmp.km) as KM
FROM temps_passage tmp
WHERE tmp.id_epreuve = $idEpreuve

-- Requête de récupération du meilleur et pire temps
SELECT ep.id_edition, ep.nom, ep.distance, ep.adresse_depart, ep.denivelee, ep.type_epreuve, ep.plan, MIN(tmp.temps) AS Meilleur, MAX(tmp.temps) AS Nul 
FROM epreuve ep JOIN temps_passage tmp ON ep.id_epreuve = tmp.id_epreuve 
                JOIN participation pa ON (pa.dossard = tmp.dossard AND pa.id_epreuve = tmp.id_epreuve)
WHERE ep.id_epreuve = $idEpreuve AND tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve

-- Récupération de la moyenne de temps des hommes
SELECT AVG(tmp.temps) AS tempsH
FROM temps_passage tmp JOIN resultat re ON tmp.dossard = re.dossard
                        JOIN participation pa ON (tmp.dossard = pa.dossard AND tmp.id_epreuve = pa.id_epreuve)
WHERE tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve AND re.sexe = 'H'

-- Récupération de la moyenne de temps des femmes
SELECT AVG(tmp.temps) AS tempsF
FROM temps_passage tmp JOIN resultat re ON tmp.dossard = re.dossard
                        JOIN participation pa ON (tmp.dossard = pa.dossard AND tmp.id_epreuve = pa.id_epreuve)
WHERE tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve AND re.sexe = 'F'

-- Récupération du nombre de clubs et de la moyenne de temps des adhérents
SELECT COUNT(DISTINCT adh.club) AS nbClubs, AVG(tmp.temps) AS tmpMoyenAdh
FROM temps_passage tmp JOIN participation pa ON (tmp.id_epreuve = pa.id_epreuve AND tmp.dossard = pa.dossard)
        JOIN adherent adh ON adh.id_adherent = pa.id_adherent
WHERE tmp.km = $distEpreuve AND tmp.id_epreuve = $idEpreuve

-- Récupération du nombre d'abandon
SELECT COUNT(re.nom) AS abandons
FROM resultat re
WHERE re.rang IS NULL AND re.id_epreuve = $idEpreuve

-- Récupération du meilleur temps
SELECT MIN(tmp.temps) AS bestTimer
FROM resultat re JOIN temps_passage tmp ON re.id_epreuve = tmp.id_epreuve
WHERE re.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve

-- Récupération du nombre d'adhérents
SELECT COUNT(DISTINCT re.rang) AS nbrAdh
FROM resultat re JOIN participation pa ON (re.id_epreuve = pa.id_epreuve AND re.dossard = pa.dossard)
WHERE re.id_epreuve = $idEpreuve

-- Récupération des meilleurs/pire rangs
SELECT MIN(re.rang) AS bestRank, MAX(re.rang) AS worstRank
FROM resultat re JOIN participation pa ON (re.id_epreuve = pa.id_epreuve AND re.dossard = pa.dossard)
WHERE re.id_epreuve = $idEpreuve

-- Récupération de l'édition de l'épreuve
SELECT annee, id_course
FROM edition
WHERE id_edition = $id_edition


-- Récupération de la course de l'épreuve
SELECT id_course, nom
FROM course
WHERE id_course = $id_course

-- Récupération des tarifs de l'épreuve
SELECT * FROM tarif WHERE id_epreuve = $idEpreuve

-- Requete de récupération des résultats en fonction du trie du tableau (ORDER BY ajouté en cas de tri sur le tableau)
-- Retourne le rang, le nom, le prénom et leurs temps respectif des participants d'une épreuve y compris les noms adhérents
SELECT re.rang, re.nom, re.prenom, re.sexe, tmp.temps, tmp.km, re.id_epreuve, part.id_adherent
FROM resultat re JOIN temps_passage tmp ON (tmp.id_epreuve = re.id_epreuve AND tmp.dossard = re.dossard) JOIN participation part ON (part.id_epreuve = re.id_epreuve AND part.dossard = re.dossard)
WHERE re.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve
ORDER BY $order $sensGet