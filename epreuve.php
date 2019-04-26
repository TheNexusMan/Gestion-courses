<?php
    session_start();
    //Pages affichant une épreuve et toutes les stats nécessaires a cette épreuve
    // -> Nb total adhérent / nb de licnesies / nombre de clubs / temps vainqueur/ meilleur et pire temps par un adhérent de l'assoc / moyenne de temps
    //Tous les adhérents avec leurs temps dans un tableau (rétractable)
    //nbr d'abandons de la course -> rang == NULL ?

    if (isset($_GET['id_epreuve'])) {
        $idEpreuve = intval($_GET['id_epreuve']);
    } else {
        header('Location: http://localhost/projet-bdw1/index.php');
    }

    include "includes/header.php";

    if (mysqli_connect_errno())
        printf("Échec de la connexion : %s", mysqli_connect_error());
    else {
        // Requête de récupération de la distance du dernier temps (la distance de
        // l'épreuve dans la bdd ne correspond pas au km du dernier temps)
        $requete = "SELECT MAX(tmp.km) as KM
                    FROM temps_passage tmp
                    WHERE tmp.id_epreuve = $idEpreuve";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération du temps du dernier'\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);
            $distEpreuve = $nuplet['KM'];
        }

        // Requête de récupération des infos de l'épreuve et du meilleur et pire temps
        $requete = "SELECT ep.id_edition, ep.nom, ep.distance, ep.adresse_depart, ep.denivelee, ep.type_epreuve, MIN(tmp.temps) AS Meilleur, MAX(tmp.temps) AS Nul 
                    FROM epreuve ep JOIN temps_passage tmp ON ep.id_epreuve = tmp.id_epreuve
                    WHERE ep.id_epreuve = $idEpreuve AND tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve"; //Modifier 1 via le post/GET, Distance km devra etre recup par une autre requete

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération des infos de l'épreuve et du meilleur et pire temps'\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);
            $id_edition = $nuplet['id_edition'];
            $name = $nuplet['nom'];
            $distance = $nuplet['distance'];
            $adresse = $nuplet['adresse_depart'];
            $denivelee = $nuplet['denivelee'];
            $typeEp = $nuplet['type_epreuve'];
            $tempsMin = $nuplet['Meilleur'];
            $tempsMax = $nuplet['Nul'];
        }

        // Récupération de la moyenne de temps des hommes
        $requete = "SELECT AVG(tmp.temps) AS tempsH
                    FROM temps_passage tmp JOIN resultat re ON tmp.dossard = re.dossard
                    WHERE tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve AND re.sexe = 'H' ";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération de la moyenne de temps des hommes'\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);
            $tempsMoyH = $nuplet['tempsH'];
        }
        
        // Récupération de la moyenne de temps des femmes
        $requete = "SELECT AVG(tmp.temps) AS tempsF
                    FROM temps_passage tmp JOIN resultat re ON tmp.dossard = re.dossard
                    WHERE tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve AND re.sexe = 'F' ";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération de la moyenne de temps des femmes'\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);
            $tempsMoyF = $nuplet['tempsF'];
        }

        // Récupération du nombre de clubs et de la moyenne de temps des adhérents
        $requete = "SELECT COUNT(DISTINCT adh.club) AS nbClubs, AVG(tmp.temps) AS tmpMoyenAdh
                    FROM temps_passage tmp JOIN participation pa ON tmp.id_epreuve = pa.id_epreuve
                                        JOIN adherent adh ON adh.id_adherent = pa.id_adherent
                    WHERE tmp.km = $distEpreuve";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération du nombre de clubs et de la moyenne de temps des adhérents'\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);
            $nbClubs = $nuplet['nbClubs'];
            $tempsMoyAdh = $nuplet['tmpMoyenAdh'];
        }
        
        // Récupération du nombre d'abandon
        $requete = "SELECT COUNT(re.nom) AS abandons
                    FROM resultat re
                    WHERE re.rang IS NULL AND re.id_epreuve = $idEpreuve";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération du nombre d'abandon'\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);
            $nbAbandons = $nuplet['abandons'];
        }

        // Récupération de l'édition
        $requete = "SELECT annee, id_course
                    FROM edition
                    WHERE id_edition = $id_edition";
        
        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération de l'édition'\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);
            $annee = $nuplet['annee'];
            $id_course = $nuplet['id_course'];
        }

        // Récupération de la course
        $requete = "SELECT id_course, nom
                    FROM course
                    WHERE id_course = $id_course";
        
        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération de la course'\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);
            $id_course = $nuplet['id_course'];
            $nomCourse = $nuplet['nom'];
        }

        print "<div class='container'>
                    <div class='nomCourse row'>
                        <h1 class='mx-auto mb-4'><a href='course.php?idcourse=$id_course'>$nomCourse</a> - <a href='edition.php?idedition=$id_edition'>édition $annee</a></h1>
                    </div>
                </div>
                <section class='sectionInfos'>
                    <h2>Information de l'épreuve</h2>
                    <div class='infos container'>
                        <div id='infosBloc' class='infosBloc container mx-auto col-8 mw-50'>

                            <div class='row ligneInfos'>
                                <div class='col-4'>
                                    <p class='nomInfo'>Nom épreuve</p>
                                    <p class='readInfo'>$name</p>
                                </div>
                                <div class='col-4'></div>
                                <div class='col-4'>
                                    <p class='nomInfo'>Distance</p>
                                    <p class='readInfo'>$distance km</p>
                                </div>
                            </div>

                            <div class='row ligneInfos'>
                                <div class='col-4'>
                                    <p class='nomInfo'>Dénivelée</p>
                                    <p class='readInfo'>$denivelee m</p>
                                </div>
                                <div class='col-4'></div>
                                <div class='col-4'>
                                    <p class='nomInfo'>Type d'épreuve</p>
                                    <p class='readInfo'>$typeEp</p>
                                </div>
                            </div>

                            <div class='row ligneInfos'>
                                <div class='col-12'>
                                    <p class='nomInfo'>Adresse de départ</p>
                                    <p class='readInfo'>$adresse</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>

                <section class='sectionInfos'>
                    <h2>Statistiques</h2>
                    <div class='infos container'>
                        <div id='infosBloc' class='infosBloc container mx-auto col-8 mw-50'>

                            <div class='row ligneInfos'>
                                <div class='col-4'>
                                <p class='nomInfo'>Nombre de clubs</p>
                                <p id='clubsEpreuve' class='readInfo'>$nbClubs clubs</p>
                                </div>
                                <div class='col-4'></div>
                                <div class='col-4'>
                                    <p class='nomInfo'>Nombre d'abandons</p>
                                    <p class='readInfo'>$nbAbandons</p>
                                </div>
                            </div>

                            <div class='row ligneInfos'>
                                <div class='col-4'>
                                <p class='nomInfo'>Moyenne temps hommes</p>
                                <p id='tempsHEpreuve' class='readInfo'>$tempsMoyH min</p>
                                </div>
                                <div class='col-4'></div>
                                <div class='col-4'>
                                    <p class='nomInfo'>Moyenne temps femmes</p>
                                    <p class='readInfo'>$tempsMoyF min</p>
                                </div>
                            </div>

                            <div class='row ligneInfos'>
                                <div class='col-4'>
                                    <p class='nomInfo'>Temps vainqueur</p>
                                    <p class='readInfo'>$tempsMin min</p>
                                </div>
                                <div class='col-4'></div>
                                <div class='col-4'>
                                    <p class='nomInfo'>Temps dernier</p>
                                    <p class='readInfo'>$tempsMax min</p>
                                </div>
                            </div>

                            <div class='row ligneInfos'>
                                <div class='col-4'>
                                    <p class='nomInfo'>Moyenne temps adhérents</p>
                                    <p class='readInfo'>$tempsMoyAdh min</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>

                <section class='sectionInfos'>
                        <h2>Tarifs</h2>
                            <div class='infos container'>
                                <div id='infosBloc' class='infosBloc container mx-auto col-8 mw-50'>
                                    <div class='row'>";


        $requete = "SELECT * FROM tarif WHERE id_epreuve = $idEpreuve";

        $resultat = mysqli_query($connexion, $requete);

        while ($nuplet = mysqli_fetch_assoc($resultat)) {
                $ageMin = $nuplet['age_min'];
                $ageMax = $nuplet['age_max'];
                $tarif = $nuplet['tarif'];

                print " <div class='col-4'>
                            <p class='nomInfo'>$ageMin-$ageMax ans</p>
                            <p id='tarifEpreuve' class='readInfo'>$tarif €</p>
                        </div>
                    <div class='col-4'></div>";
        }
        print "     </div>
                </div>                
            </div>
            <div id='ancreTri'></div>
        </section>";


        // Requete de récupération des résultats en fonction du trie du tableau
        // Retourne le rang, le nom, le prénom et leurs temps respectif des participants d'une épreuve y compris les noms adhérents
        $requete = "SELECT re.rang, re.nom, re.prenom, re.sexe, tmp.temps, tmp.km, re.id_epreuve, part.id_adherent
                    FROM resultat re JOIN temps_passage tmp ON (tmp.id_epreuve = re.id_epreuve AND tmp.dossard = re.dossard) JOIN participation part ON (part.id_epreuve = re.id_epreuve AND part.dossard = re.dossard)
                    WHERE re.id_epreuve = 7 AND tmp.km = 10";

        // Cas où on clic deux fois à la suite sur une colonne (changement de l'ordre du trie)
        if(!empty($_GET['order']) && ($_GET['orderSec'] == $_GET['order']))
        {
            $order = mysqli_real_escape_string($connexion, $_GET['order']);
            $orderSec = $_GET['orderSec'];
            $sensGet = mysqli_real_escape_string($connexion, $_GET['sens']);
            
            $requete .= " ORDER BY $order $sensGet";

            if($sensGet == "DESC" && $_GET['clic'])
            {
                $sens = "ASC";
            }else if($_GET['clic']){
                $sens = "DESC";
            }else{
                $sens = $sensGet;
            }

        // Cas où c'est le premier clic sur la colonne (ordre croissant)
        }else if(!empty($_GET['order']))
        {
            $order = mysqli_real_escape_string($connexion, $_GET['order']);
            $sensGet = $_GET['sens'];
            $orderSec = $_GET['orderSec'];

            $requete .= " ORDER BY $order";

            if($_GET['clic']){
                $sens = "DESC";
            }else{
                $sens = $sensGet;
            }
        }else{
            $order = "";
            $orderSec = "";
            $sensGet = "";
            $sens = "ASC";
        }

        $resultat = mysqli_query($connexion, $requete);

        if ($resultat == FALSE)
            print "<script>alert(\"Erreur lors de l'exécution de la requete de récupération des résultats\")</script>";
        else {
            print "<section class='liste'>
                    <h2 class='tableLabel'>Résultats des adhérents</h2>
                    <div class='container'>
                        <table class='table table-bordered table-hover text-center'>
                            <thead class='thead-dark'>
                                <tr>
                                    <th id='rangCol' scope='col'>
                                        <a href='?id_epreuve=$idEpreuve&order=rang&orderSec=$order&sens=$sens&clic=1#ancreTri'>Rang</a>
                                    </th>
                                    <th id='nomCol' scope='col'>
                                        <a href='?id_epreuve=$idEpreuve&order=nom&orderSec=$order&sens=$sens&clic=1#ancreTri'>Nom</a>
                                    </th>
                                    <th id='prenomCol' scope='col'>
                                        <a href='?id_epreuve=$idEpreuve&order=prenom&orderSec=$order&sens=$sens&clic=1#ancreTri'>Prénom</a>
                                    </th>
                                    <th id='sexeCol' scope='col'>
                                        <a href='?id_epreuve=$idEpreuve&order=sexe&orderSec=$order&sens=$sens&clic=1#ancreTri'>Sexe</a>
                                    </th>
                                    <th id='tempsCol' scope='col'>
                                        <a href='?id_epreuve=$idEpreuve&order=temps&orderSec=$order&sens=$sens&clic=1#ancreTri'>Temps</a>
                                    </th>
                                </tr>
                            </thead>
                        <tbody>";

            while ($nuplet = mysqli_fetch_assoc($resultat)) {
                $id_adherent = $nuplet['id_adherent'];
                $rang = $nuplet['rang'];
                $nom = $nuplet['nom'];
                $prenom = $nuplet['prenom'];
                $sexe = $nuplet['sexe'];
                $temps = $nuplet['temps'];


                print "<tr class='ligneTabClic'>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id_adherent'\"  class='text-left'>$rang</td>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id_adherent'\"  class='text-left'>$nom</td>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id_adherent'\"  class='text-left'>$prenom</td>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id_adherent'\"  class='text-left'>".($sexe == 'H' ? 'Homme' : 'Femme')."</td>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id_adherent'\"  class='text-left'>$temps min</td>
                        </tr>";
            }
        }

        mysqli_close($connexion);
    }

    include "includes/footer.php";

    // Ajout des chevrons pour le sens du trie des colonnes
    if(!empty($_GET['order']) && ($_GET['orderSec'] == $_GET['order'])) // Si deuxième clic sur la même colone, on inverse le sens du chevron
    {
        if($_GET['sens'] == "DESC")
        {
            print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-up\"></i>'</script>";
        }else{
            print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-down\"></i>'</script>";
        }
    }else if(!empty($_GET['order'])) // Si clic sur colonne, on affiche le chevron croissant
    {
        print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-down\"></i>'</script>";
    }
?>