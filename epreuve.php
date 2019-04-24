<?php

//Pages affichant une épreuve et toutes les stats nécessaires a cette épreuve
// -> Nb total adhérent / nb de licnesies / nombre de clubs / temps vainqueur/ meilleur et pire temps par un adhérent de l'assoc / moyenne de temps



//Tous les adhérents avec leurs temps dans un tableau (rétractable)
//nbr d'abandons de la course -> rang == NULL ?
include "includes/header.php";


$user = 'root';
$mdp = '';
$machine = 'localhost';
$bd = 'bdw1';
$connexion = mysqli_connect($machine, $user, $mdp, $bd);

if (mysqli_connect_errno()) // erreur si > 0
    printf("Échec de la connexion : %s", mysqli_connect_error());
else {

    if (isset($_GET['id_epreuve'])) {
            $idEpreuve = $_GET['id_epreuve'];
        } else {
            $idEpreuve = 1; //Faire la redirection 404 ?
        }

    $requete = "SELECT MAX(tmp.km) as KM
                FROM temps_passage tmp
                WHERE tmp.id_epreuve = $idEpreuve";
    $resultat = mysqli_query($connexion, $requete);

    while ($nuplet = mysqli_fetch_assoc($resultat)) {
            $distEpreuve = $nuplet['KM'];
        }




    $requete = "SELECT ep.nom, ep.distance, ep.denivelee, ep.type_epreuve, MIN(tmp.temps) AS Meilleur, MAX(tmp.temps) AS Nul 
                FROM epreuve ep JOIN temps_passage tmp ON ep.id_epreuve = tmp.id_epreuve
                WHERE ep.id_epreuve = $idEpreuve AND tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve "; //Modifier 1 via le post/GET, Distance km devra etre recup par une autre requete

    $resultat = mysqli_query($connexion, $requete);

    while ($nuplet = mysqli_fetch_assoc($resultat)) {
            $name = $nuplet['nom'];
            $distance = $nuplet['distance'];
            $denivelee = $nuplet['denivelee'];
            $typeEp = $nuplet['type_epreuve'];
            $tempsMin = $nuplet['Meilleur'];
            $tempsMax = $nuplet['Nul'];
        }

    $requete = "SELECT AVG(tmp.temps) AS tempsH
                FROM temps_passage tmp JOIN resultat re ON tmp.dossard = re.dossard
                WHERE tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve AND re.sexe = 'H' ";

    $resultat = mysqli_query($connexion, $requete);

    while ($nuplet = mysqli_fetch_assoc($resultat)) {
            $tempsMoyH = $nuplet['tempsH'];
        }

    $requete = "SELECT AVG(tmp.temps) AS tempsF
                FROM temps_passage tmp JOIN resultat re ON tmp.dossard = re.dossard
                WHERE tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve AND re.sexe = 'F' ";

    $resultat = mysqli_query($connexion, $requete);

    while ($nuplet = mysqli_fetch_assoc($resultat)) {
            $tempsMoyF = $nuplet['tempsF'];
        }

    $requete = "SELECT COUNT(DISTINCT adh.club) AS nbClubs, AVG(tmp.temps) AS tmpMoyenAdh
                FROM temps_passage tmp JOIN participation pa ON tmp.id_epreuve = pa.id_epreuve
                                       JOIN adherent adh ON adh.id_adherent = pa.id_adherent
                WHERE tmp.km = $distEpreuve";

    $resultat = mysqli_query($connexion, $requete);

    while ($nuplet = mysqli_fetch_assoc($resultat)) {
            $nbClubs = $nuplet['nbClubs'];
            $tempsMoyAdh = $nuplet['tmpMoyenAdh'];
        }

    $requete = "SELECT count(re.nom) AS abandons
                FROM resultat re
                WHERE re.rang IS NULL AND re.id_epreuve = $idEpreuve";

    $resultat = mysqli_query($connexion, $requete);

    while ($nuplet = mysqli_fetch_assoc($resultat)) {
            $nbAbandons = $nuplet['abandons'];
        }




    print "<section class='adherent'>
                        <h2>Epreuve de l'édition : </h2>
                        <div class='adherentInfos container'>
                            <div id='adherentInfosBloc' class='adherentInfosBloc container mx-auto col-8 mw-50'>
                                <div class='row ligneInfo'>
                                    <div class='col-4'>
                                        <p class='nomInfo'>Nom Epreuve : </p>
                                        <p id='nomEpreuve' class='readInfoEpreuve'> $name </p>
                                    </div>
                                    <div class='col-4'></div>
                                    <div class='col-4'>
                                        <p class='nomInfo'>Distance : </p>
                                        <p id='distanceEpreuve' class='readInfoEpreuve'> $distance </p>
                                    </div>
                                </div>


                                <div class='row ligneInfo'>
                                    <div class='col-4'>
                                        <p class='nomInfo'>Denivelee : </p>
                                        <p id='deniveleeEpreuve' class='readInfoEpreuve'> $denivelee m</p>
                                    </div>
                                    <div class='col-4'></div>
                                    <div class='col-4'>
                                        <p class='nomInfo'>Type d'epreuve :</p>
                                        <p id='typeEpreuve' class='readInfoEpreuve'> $typeEp </p>
                                    </div>
                                </div>

                                <div class='row ligneInfo'>
                                    <div class='col-4'>
                                      <p class='nomInfo'>Nombre de clubs : </p>
                                      <p id='clubsEpreuve' class='readInfoEpreuve'> $nbClubs clubs </p>
                                    </div>
                                    <div class='col-4'></div>
                                    <div class='col-4'>
                                        <p class='nomInfo'>Nombre abandons :</p>
                                        <p id='abandonsEpreuve' class='readInfoEpreuve'> $nbAbandons </p>
                                    </div>
                                </div>

                                <div class='row ligneInfo'>
                                    <div class='col-4'>
                                      <p class='nomInfo'>Moyenne temps Hommes : </p>
                                      <p id='tempsHEpreuve' class='readInfoEpreuve'> $tempsMoyH min</p>
                                    </div>
                                    <div class='col-4'></div>
                                    <div class='col-4'>
                                        <p class='nomInfo'>Moyenne temps Femme :</p>
                                        <p id='tempsFEpreuve' class='readInfoEpreuve'> $tempsMoyF min</p>
                                    </div>
                                </div>

                                <div class='row ligneInfo'>
                                    <div class='col-4'>
                                        <p class='nomInfo'>Temps Vainqueur :</p>
                                        <p id='tempsWinEpreuve' class='readInfoEpreuve'> $tempsMin min </p>
                                    </div>
                                    <div class='col-4'></div>
                                    <div class='col-4'>
                                        <p class='nomInfo'>Temps Dernier :</p>
                                        <p id='tempsLoseEpreuve' class='readInfoEpreuve'> $tempsMax min </p>
                                    </div>
                                </div>

                                <div class='row ligneInfo'>
                                    <div class='col-4'>
                                        <p class='nomInfo'>Moyenne temps Adherents : </p>
                                        <p id='tempsAdhEpreuve' class='readInfoEpreuve'> $tempsMoyAdh min </p>
                                    </div>
                                </div>


                            </div>
                        </div>
 

            </section>";


     print "<section class='adherent'>
                    <h2>Tarifs </h2>
                        <div class='adherentInfos container'>
                             <div id='adherentInfosBloc' class='adherentInfosBloc container mx-auto col-8 mw-50'>
                                <div class='row ligneInfo'>";
    $requete = "SELECT * FROM tarif WHERE id_epreuve = $idEpreuve";

    $resultat = mysqli_query($connexion, $requete);

    while($nuplet = mysqli_fetch_assoc($resultat))
    {
        $ageMin = $nuplet['age_min'];
        $ageMax = $nuplet['age_max'];
        $tarif = $nuplet['tarif'];

        print " <div class='col-4'>
                    <p class='nomInfo'>";
        print $ageMin . "-" . $ageMax . "</p>";
        print "<p id='tarifEpreuve' class='readInfoEpreuve'> $tarif € </p>
    </div>
    <div class='col-4'></div>";
    }





print "                 </div>
                    </div>                
                </div>
            </section>";


    //Requete pour le tableau
    $requete = "SELECT re.rang, re.nom, re.prenom, re.sexe, tmp.temps
                FROM resultat re JOIN temps_passage tmp ON tmp.dossard = re.dossard
                WHERE re.id_epreuve = $idEpreuve AND re.id_epreuve = $idEpreuve AND tmp.km=$distEpreuve"; //Retourne le rang, le nom, le prénom et leurs temps respectif des participants d'une épreuve y compris les noms adhérents

    $resultat = mysqli_query($connexion, $requete);

    if ($resultat == FALSE)
        print "<script>alert(\"Erreur lors de l'execution de la requete SQL\")</script>";
    else {
        print "<section class='listeEditions'>
            <div class='container'>
                <table class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>Rang </th>
                            <th scope='col'>Nom </th>
                            <th scope='col'>Prénom </th>
                            <th scope='col'>Sexe </th>
                            <th scope='col'> Temps</th>
                        </tr>
                    </thead>
                <tbody>";

        while ($nuplet = mysqli_fetch_assoc($resultat)) {
            //$annee = $nuplet['annee'];
            $rang = $nuplet['rang'];
            $nom = $nuplet['nom'];
            $prenom = $nuplet['prenom'];
            $sexe = $nuplet['sexe'];
            $temps = $nuplet['temps'];


            print "<tr>
                            <td>$rang</td>
                            <td>$nom</td>
                            <td>$prenom</td>";
            if ($sexe == 'H') {
                print "<td>Homme</td>";
            } else print "<td>Femme</td>";
            print "<td>$temps min</td>

                        </tr>";
        }
    }

    mysqli_close($connexion);
}



include "includes/footer.php";
