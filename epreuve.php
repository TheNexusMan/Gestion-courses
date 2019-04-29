<!-- Arnaud DEBRABANT P1707147 - Damien PETITJEAN P1408987 -->
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

        // Suppression d'un tarif
        if (isset($_GET['delete_tarif']) && $_SESSION['typeUtilisateur'] == "Admin") {
            $tarifToDelete = intval($_GET['delete_tarif']);

            $requete = "DELETE FROM tarif WHERE id_tarif = $tarifToDelete";

            if(mysqli_query($connexion, $requete) == FALSE){
                print "<script>alert('Échec de la requête de suppression du tarif)</script>";
            }
        }

        // Ajout d'une nouveau tarif
        if (isset($_POST['ageMin']) && isset($_POST['ageMax']) && isset($_POST['tarif']) && $_SESSION['typeUtilisateur'] == "Admin") {
            $ageMin = intval($_POST['ageMin']);
            $ageMax = intval($_POST['ageMax']);
            $tarif = intval($_POST['tarif']);

            $requete = "INSERT INTO tarif (id_epreuve, age_min, age_max, tarif)
                        VALUES ($idEpreuve, $ageMin, $ageMax, $tarif)";

            if (mysqli_query($connexion, $requete) == FALSE)
                print "<script>alert(\"Échec de l'ajout du tarif\")</script>";
        }

        // Ajout des résultats et temps
        if(isset($_FILES['resultatcsv']) && isset($_FILES['tempscsv']))
        {
            // UPLOAD DU FICHIER CSV, vérification et insertion en BASE
            if($_FILES["resultatcsv"]["type"] != "application/vnd.ms-excel" || $_FILES["tempscsv"]["type"] != "application/vnd.ms-excel")
            {
                print "<script>alert(\"Ce n'est pas un fichier de type .csv\")</script>";
            }
            elseif(is_uploaded_file($_FILES['resultatcsv']['tmp_name']) && is_uploaded_file($_FILES['tempscsv']['tmp_name']))
            {
                mysqli_begin_transaction($connexion, MYSQLI_TRANS_START_READ_WRITE);

                // Processe le csv resultat
                $handle = fopen($_FILES['resultatcsv']['tmp_name'], "r");
                $data = fgetcsv($handle, 1000, ","); // Enlève l'en-tête du fichier
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
                {
                    $dossard = intval($data[0]);
                    $rang = intval($data[1]);
                    $nom = mysqli_real_escape_string($connexion, $data[2]);
                    $prenom = mysqli_real_escape_string($connexion, $data[3]);
                    $sexe = mysqli_real_escape_string($connexion, $data[4]);
                    
                    $requete = "INSERT INTO resultat (dossard, id_epreuve, rang, nom, prenom, sexe)
                                VALUES ($dossard, $idEpreuve, $rang, '$nom', '$prenom', '$sexe')";

                    $resultat = mysqli_query($connexion, $requete);
                }

                if ($resultat == FALSE)
                        print "<script>alert(\"Échec de l'ajout des résultats\")</script>";
                
                // Processe le csv temps
                $handle = fopen($_FILES['tempscsv']['tmp_name'], "r");
                $data = fgetcsv($handle, 1000, ","); // Enlève l'en-tête du fichier
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
                {
                    $dossard = intval($data[0]);
                    $km = intval($data[1]);
                    $temps = intval($data[2]);
                    
                    $requete = "INSERT INTO temps_passage (id_epreuve, dossard, km, temps)
                                VALUES ($idEpreuve, $dossard, $km, $temps)";

                    $resultat = mysqli_query($connexion, $requete);
                }

                $requete = "INSERT INTO participation (dossard, id_adherent, id_epreuve)
                                SELECT r.dossard ,a.id_adherent, r.id_epreuve
                                FROM resultat r
                                INNER JOIN adherent a on (a.nom = r.nom and a.prenom = r.prenom and a.sexe = r.sexe)
                                WHERE r.id_epreuve = $idEpreuve";
                
                mysqli_query($connexion, $requete);

                if(!mysqli_commit($connexion))
                    print "<script>alert(\"Échec de l'ajout des résultats et des temps\")</script>";

            } else{
                die("Vous ne devriez pas être là");
            }
        }

        // Modification des informations de l'édition
        if(isset($_POST['nom']) && isset($_POST['distance']) && isset($_POST['denivelee']) && $_SESSION['typeUtilisateur'] == "Admin")
        {
            $modName = mysqli_real_escape_string($connexion, $_POST['nom']);
            $modDistance = intval($_POST['distance']);
            $modAdresse_depart = mysqli_real_escape_string($connexion, $_POST['adresse_depart']);
            $modDenivelee = intval($_POST['denivelee']);
            $modType = mysqli_real_escape_string($connexion, $_POST['type']);

            $requete = "UPDATE epreuve
                        SET nom = '$modName', distance = $modDistance, adresse_depart = '$modAdresse_depart', denivelee = $modDenivelee, type_epreuve = '$modType'";

            if($_FILES['plan']['name'] != "")
            {
                $modPlan = mysqli_real_escape_string($connexion, $_FILES['plan']['name']);
                $tabPlan = explode('.', $modPlan);
                $idTab = sizeof($tabPlan)-1;

                if((strlen($tabPlan[$idTab]) != 3 && strlen($tabPlan[$idTab]) != 4) || (strlen($tabPlan[$idTab]) == 3 && $tabPlan[$idTab] != "png" && $tabPlan[$idTab] != "jpg" && $tabPlan[$idTab] != "PNG") || (strlen($tabPlan[$idTab]) == 4 && $tabPlan[$idTab] != "jpeg"))
                {
                    print "<script>alert(\"Le plan inséré n'est pas un png ou jpg\")</script>";
                }else{
                    $requete .= ", plan = '$modPlan'";
                    move_uploaded_file($_FILES['plan']['tmp_name'], 'data/plan/'.$_FILES['plan']['name']);
                }
            }

            $requete .= "WHERE id_epreuve = $idEpreuve";

            if(mysqli_query($connexion, $requete) == FALSE){
                print "<script>alert('Échec de la requête de modification des informations')</script>";
            }
        }

        // Requête de récupération des infos de l'épreuve
        $requete = "SELECT id_edition, nom, distance, adresse_depart, denivelee, type_epreuve, plan
                    FROM epreuve
                    WHERE id_epreuve = $idEpreuve"; //Modifier 1 via le post/GET, Distance km devra etre recup par une autre requete

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération des infos de l'épreuve'\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);
            $id_edition = $nuplet['id_edition'];
            $name = $nuplet['nom'];
            $distance = $nuplet['distance'];
            $adresse = $nuplet['adresse_depart'];
            $denivelee = $nuplet['denivelee'];
            $typeEp = $nuplet['type_epreuve'];
            $plan = $nuplet['plan'];
        }

        // Requête de test si l'épreuve est terminée
        $requete = "SELECT * FROM resultat WHERE id_epreuve = $idEpreuve";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de test si l'épreuve est terminée'\")</script>";
        else {
            if(mysqli_num_rows($resultat) != 0)
            {
                $epreuveTerminee = 1;
            }else{
                $epreuveTerminee = 0;
            }
        }

        if($epreuveTerminee)
        {
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

            // Requête de récupération du meilleur et pire temps
            $requete = "SELECT ep.id_edition, ep.nom, ep.distance, ep.adresse_depart, ep.denivelee, ep.type_epreuve, ep.plan, MIN(tmp.temps) AS Meilleur, MAX(tmp.temps) AS Nul 
                        FROM epreuve ep JOIN temps_passage tmp ON ep.id_epreuve = tmp.id_epreuve 
                                        JOIN participation pa ON (pa.dossard = tmp.dossard AND pa.id_epreuve = tmp.id_epreuve)
                        WHERE ep.id_epreuve = $idEpreuve AND tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve";

            $resultat = mysqli_query($connexion, $requete);

            if($resultat == FALSE)
                print "<script>alert(\"Échec de la requête du meilleur et pire temps'\")</script>";
            else {
                $nuplet = mysqli_fetch_assoc($resultat);
                $tempsMin = $nuplet['Meilleur'];
                $tempsMax = $nuplet['Nul'];
            }

            // Récupération de la moyenne de temps des hommes
            $requete = "SELECT AVG(tmp.temps) AS tempsH
                        FROM temps_passage tmp JOIN resultat re ON tmp.dossard = re.dossard
                                               JOIN participation pa ON (tmp.dossard = pa.dossard AND tmp.id_epreuve = pa.id_epreuve)
                        WHERE tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve AND re.sexe = 'H'";

            $resultat = mysqli_query($connexion, $requete);

            if($resultat == FALSE)
                print "<script>alert(\"Échec de la requête de récupération de la moyenne de temps des hommes'\")</script>";
            else {
                $nuplet = mysqli_fetch_assoc($resultat);
                $tempsMoyH = ROUND($nuplet['tempsH']);
            }
            
            // Récupération de la moyenne de temps des femmes
            $requete = "SELECT AVG(tmp.temps) AS tempsF
                        FROM temps_passage tmp JOIN resultat re ON tmp.dossard = re.dossard
                                               JOIN participation pa ON (tmp.dossard = pa.dossard AND tmp.id_epreuve = pa.id_epreuve)
                        WHERE tmp.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve AND re.sexe = 'F'";

            $resultat = mysqli_query($connexion, $requete);

            if($resultat == FALSE)
                print "<script>alert(\"Échec de la requête de récupération de la moyenne de temps des femmes'\")</script>";
            else {
                $nuplet = mysqli_fetch_assoc($resultat);
                $tempsMoyF = ROUND($nuplet['tempsF']);
            }

            // Récupération du nombre de clubs et de la moyenne de temps des adhérents
            $requete = "SELECT COUNT(DISTINCT adh.club) AS nbClubs, AVG(tmp.temps) AS tmpMoyenAdh
                        FROM temps_passage tmp JOIN participation pa ON (tmp.id_epreuve = pa.id_epreuve AND tmp.dossard = pa.dossard)
                                JOIN adherent adh ON adh.id_adherent = pa.id_adherent
                        WHERE tmp.km = $distEpreuve AND tmp.id_epreuve = $idEpreuve";

            $resultat = mysqli_query($connexion, $requete);

            if($resultat == FALSE)
                print "<script>alert(\"Échec de la requête de récupération du nombre de clubs et de la moyenne de temps des adhérents'\")</script>";
            else {
                $nuplet = mysqli_fetch_assoc($resultat);
                $nbClubs = $nuplet['nbClubs'];
                $tempsMoyAdh = ROUND($nuplet['tmpMoyenAdh']);
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

            // Récupération du meilleur temps
            $requete = "SELECT MIN(tmp.temps) AS bestTimer
                        FROM resultat re JOIN temps_passage tmp ON re.id_epreuve = tmp.id_epreuve
                        WHERE re.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve";
            $resultat = mysqli_query($connexion, $requete);

            if($resultat == FALSE)
                print "<script>alert(\"Échec de la requête de récupération du meilleur temps'\")</script>";
            else {
                $nuplet = mysqli_fetch_assoc($resultat);
                $bestTime = $nuplet['bestTimer'];
            }

            // Récupération du nombre d'adhérents
            $requete = "SELECT COUNT(DISTINCT re.rang) AS nbrAdh
                        FROM resultat re JOIN participation pa ON (re.id_epreuve = pa.id_epreuve AND re.dossard = pa.dossard)
                        WHERE re.id_epreuve = $idEpreuve";
            $resultat = mysqli_query($connexion,$requete);

            if($resultat == FALSE)
                print "<script>alert(\"Échec de la requête de récupération du nombre d'adhérents'\")</script>";
            else {
                $nuplet = mysqli_fetch_assoc($resultat);
                $nbAdh = $nuplet['nbrAdh'];
            }

            // Récupération des meilleurs/pire rangs
            $requete ="SELECT MIN(re.rang) AS bestRank, MAX(re.rang) AS worstRank
                       FROM resultat re JOIN participation pa ON (re.id_epreuve = pa.id_epreuve AND re.dossard = pa.dossard)
                       WHERE re.id_epreuve = $idEpreuve";
            $resultat = mysqli_query($connexion,$requete);

            if($resultat == FALSE)
                print "<script>alert(\"Échec de la requête de récupération des meilleurs/pire rangs'\")</script>";
            else {
                $nuplet = mysqli_fetch_assoc($resultat);
                $bestRank = $nuplet['bestRank'];
                $worstRank = $nuplet['worstRank'];
            }

        }

        // Récupération de l'édition de l'épreuve
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

        // Récupération de la course de l'épreuve
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

        print "<div class='container' id='hautPage'>
                    <div class='nomCourse row'>
                        <h1 class='mx-auto mb-4'><a href='course.php?idcourse=$id_course'>$nomCourse</a> - <a href='edition.php?idedition=$id_edition'>édition $annee</a></h1>
                    </div>
                </div>
                <section class='sectionInfos'>
                    <h2>Information de l'épreuve</h2>
                    <div class='infos container'>
                        <div id='infosBloc' class='infosBloc container mx-auto col-lg-8 col-md-10 col-xs-12 mw-50'>
                            <form action='epreuve.php?id_epreuve=$idEpreuve' method='POST' enctype='multipart/form-data'>

                                <div class='form-row ligneInfos'>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Nom</p>
                                        <p class='readInfo'>$name</p>
                                        <input type='text' id='nomInput' class='form-control writeInfo' name='nom' value=\"$name\" placeholder='Nom' required>
                                    </div>
                                    <div class='col-md-4'></div>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Distance</p>
                                        <p class='readInfo'>$distance km</p>
                                        <input type='text' id='distanceInput' class='form-control writeInfo' name='distance' value=\"$distance\" placeholder='Distance' required>
                                    </div>
                                </div>

                                <div class='form-row ligneInfos'>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Dénivelée</p>
                                        <p class='readInfo'>$denivelee m</p>
                                        <input type='text' id='deniveleeInput' class='form-control writeInfo' name='denivelee' value=\"$denivelee\" placeholder='Dénivelée' required>
                                    </div>
                                    <div class='col-md-4'></div>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Type d'épreuve</p>
                                        <p class='readInfo'>$typeEp</p>
                                        <input type='text' id='typeInput' class='form-control writeInfo' name='type' value=\"$typeEp\" placeholder=\"Type d'épreuve\" required>
                                    </div>
                                </div>

                                <div class='form-row ligneInfos'>
                                    <div class='col-md-12'>
                                        <p class='nomInfo'>Adresse de départ</p>
                                        <p class='readInfo'>$adresse</p>
                                        <input type='text' id='adresse_departInput' class='form-control writeInfo' name='adresse_depart' value=\"$adresse\" placeholder='Adresse de départ' required>
                                    </div>
                                </div>

                                <div class='form-row ligneInfos'>
                                    <div class='col-md-12'>
                                        <p class='nomInfo'>Plan</p>
                                        <div class='text-center'>
                                            <img src='data/plan/$plan' class='readInfo imgPlan img-fluid img-thumbnail' alt=\"Plan de l'épreuve\">
                                        </div>
                                        <label class='custom-file-label' id='labelFile' for='planInput'>Choisissez un plan... </label>
                                        <input type='file' id='planInput' class='custom-file-input writeInfo' name='plan'>
                                    </div>
                                    <div id='ancreTarif'></div>
                                </div>";

        if($_SESSION['typeUtilisateur'] == "Admin")
        {
            print " <div class='row ligneButton readInfo readInfoFlex' id='modifInfo'>
                        <button type='button' class='btn btn-primary mx-auto'>Modifier</button>
                    </div>
                    <div class='row ligneButton writeInfo writeInfoFlex' id='modifInfo'>
                        <div class='row mx-auto'>
                            <button type='submit' class='btn btn-primary col-md-5'>Valider</button>
                            <div class='col-md-1'></div>
                            <button type='button' id='annulerInfo' class='btn btn-primary col-md-5'>Annuler</button>
                        </div>
                    </div>";
        }

        print"              </form>
                        </div>
                    </div>
                </section>
                
                <section class='sectionInfos'>
                    <h2>Tarifs</h2>
                    <div class='infos container'>
                        <div class='table-responsive'>
                            <table class='table col-md-4 mx-auto table-bordered text-center'>
                                <thead class='thead-dark'>
                                    <tr>
                                        <th scope='col'>
                                            Tranche d'âge
                                        </th>
                                        <th scope='col'>
                                            Tarif
                                        </th>
                                        ".($_SESSION['typeUtilisateur'] == 'Admin' ? '<th>Action</th>' : '')."
                                    </tr>
                                </thead>
                                <tbody>";

        // Récupération des tarifs de l'épreuve
        $requete = "SELECT * FROM tarif WHERE id_epreuve = $idEpreuve";

        $resultat = mysqli_query($connexion, $requete);

        while ($nuplet = mysqli_fetch_assoc($resultat))
        {
            $id_tarif = $nuplet['id_tarif'];
            $ageMin = $nuplet['age_min'];
            $ageMax = $nuplet['age_max'];
            $tarif = $nuplet['tarif'];

            print " <tr>
                        <td class='text-left'>$ageMin-$ageMax ans</td>
                        <td class='text-left'>$tarif €</td>";

            if($_SESSION['typeUtilisateur'] == "Admin")
            {
                print "<td>
                            <form method='GET' action='epreuve.php#ancreTarif' Onsubmit='return attention();'>
                                <input name='id_epreuve' type='hidden' value='$idEpreuve'>
                                <input name='delete_tarif' type='hidden' value='$id_tarif'>
                                <button class='btnDelete' type='submit'>
                                    <i class='fas fa-trash-alt'></i>
                                </button>
                            </form>
                        </td>";
            }
            print "</tr>";
        }

        print "             </tbody>
                        </table>
                    </div>
                </div>";
                if($_SESSION['typeUtilisateur'] == "Admin")
                {
                    print "<div class='container'>
                                <div class='row mb-4'>
                                    <button type='button' class='btn btn-primary mx-auto' data-toggle='modal' data-target='#modalAjout'>
                                        Ajouter un tarif
                                    </button>
                                </div>
                            </div>";
                }
        print "</section>";

        if(!$epreuveTerminee && $_SESSION['typeUtilisateur'] == "Admin")
        {
            print "<section class='sectionInfos'>
                        <h2>Ajouter les résultats</h2>
                        <div class='infos container'>
                            <div id='infosBloc' class='infosBloc container mx-auto col-lg-8 col-md-10 col-xs-12 mw-50'>
                                <form action='epreuve.php?id_epreuve=$idEpreuve' method='POST' enctype='multipart/form-data'>
                                    <div class='form-row ligneInfos'>
                                        <div class='col-md-5'>
                                            <label class='custom-file-label' for='resultatcsv'>CSV des résultats... </label>
                                            <input type='file' id='resultatcsv' class='custom-file-input' name='resultatcsv' required>
                                        </div>
                                        <div class='col-md-2'></div>
                                        <div class='col-md-5'>
                                            <label class='custom-file-label' for='tempscsv'>CSV des temps... </label>
                                            <input type='file' id='tempscsv' class='custom-file-input' name='tempscsv' required>
                                        </div>
                                    </div>
                                    <div class='form-row ligneButton'>
                                        <button type='submit' class='btn btn-primary mx-auto'>Envoyer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>";
        }

        if($epreuveTerminee)
        {
            print "<section class='sectionInfos'>
                        <h2>Statistiques</h2>
                        <div class='infos container'>
                            <div id='infosBloc' class='infosBloc container mx-auto col-lg-8 col-md-10 col-xs-12 mw-50'>

                                <div class='row ligneInfos'>
                                    <div class='col-md-4'>
                                    <p class='nomInfo'>Nombre de clubs</p>
                                    <p id='clubsEpreuve'>$nbClubs clubs</p>
                                    </div>
                                    <div class='col-md-4'></div>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Nombre d'abandons</p>
                                        <p>$nbAbandons</p>
                                    </div>
                                </div>

                                <div class='row ligneInfos'>
                                    <div class='col-md-4'>
                                    <p class='nomInfo'>Moyenne temps hommes adhérents</p>
                                    <p id='tempsHEpreuve'>$tempsMoyH min</p>
                                    </div>
                                    <div class='col-md-4'></div>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Moyenne temps femmes adhérents</p>
                                        <p>$tempsMoyF min</p>
                                    </div>
                                </div>

                                <div class='row ligneInfos'>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Meilleur temps adhérent</p>
                                        <p>$tempsMin min</p>
                                    </div>
                                    <div class='col-md-4'></div>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Dernier temps adhérent</p>
                                        <p>$tempsMax min</p>
                                    </div>
                                </div>

                                <div class='row ligneInfos'>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Moyenne temps adhérents</p>
                                        <p>$tempsMoyAdh min</p>
                                    </div>
                                    <div class='col-md-4'></div>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Temps vainqueur</p>
                                        <p>$bestTime min</p>
                                    </div>

                                </div>

                                <div class='row ligneInfos'>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Nombre d'adhérents </p>
                                        <p>$nbAdh</p>
                                    </div>
                                    <div class='col-md-4'></div>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Meilleur rang adhérent </p>
                                        <p>$bestRank</p>
                                    </div>
                                </div>

                                <div class='row ligneInfos'>
                                    <div class='col-md-4'>
                                        <p class='nomInfo'>Pire rang adhérent</p>
                                        <p>$worstRank</p>
                                    </div>

                                </div>
                                


                            </div>
                        </div>
                        <div id='ancreTri'></div>
                    </section>";

            // Requete de récupération des résultats en fonction du trie du tableau
            // Retourne le rang, le nom, le prénom et leurs temps respectif des participants d'une épreuve y compris les noms adhérents
            $requete = "SELECT re.rang, re.nom, re.prenom, re.sexe, tmp.temps, tmp.km, re.id_epreuve, part.id_adherent
                        FROM resultat re JOIN temps_passage tmp ON (tmp.id_epreuve = re.id_epreuve AND tmp.dossard = re.dossard) JOIN participation part ON (part.id_epreuve = re.id_epreuve AND part.dossard = re.dossard)
                        WHERE re.id_epreuve = $idEpreuve AND tmp.km = $distEpreuve";

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
                print "<script>alert(\"Erreur lors de l'exécution de la requête de récupération des résultats\")</script>";
            else {
                print "<section class='liste'>
                        <h2 class='tableLabel'>Résultats des adhérents</h2>
                        <div class='container'>
                            <div class='table-responsive'>
                                <table class='table table-bordered text-center" . ($_SESSION['typeUtilisateur'] == 'Admin' ? ' table-hover' : '')."'>
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


                    print "<tr ". ($_SESSION['typeUtilisateur'] == 'Admin' ? 'class="ligneTabClic" onclick="location.href=\'adherent.php?id_adherent='.$id_adherent.'\'"' : '') .">
                                <td class='text-left'>$rang</td>
                                <td class='text-left'>$nom</td>
                                <td class='text-left'>$prenom</td>
                                <td class='text-left'>".($sexe == 'H' ? 'Homme' : 'Femme')."</td>
                                <td class='text-left'>$temps min</td>
                            </tr>";
                }

                print "         </tbody>
                            </table>
                        </div>
                    </div>
                </section>";
            }

            mysqli_close($connexion);
        }
    }

    include "includes/footer.php";
?>

<!-- Modal du formulaire d'ajout de tarif -->
<div class="modal fade" id="modalAjout" tabindex="-1" role="dialog" aria-labelledby="modalAjout" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un tarif</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="#ancreTarif">
                    <div class='container'>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="ageMin">Âge minimum</label>
                                <input type="text" class="form-control" id="ageMin" name="ageMin" placeholder="Âge minimum" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ageMax">Âge maximum</label>
                                <input type="text" class="form-control" id="ageMax" name="ageMax" placeholder="Âge maximum" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="tarif">Tarif</label>
                                <input type="text" class="form-control" id="tarif" name="tarif" placeholder="Tarif" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button class="btn btn-primary" type="submit">Ajouter tarif</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
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

<script>
    // Gestion de l'affichage du formulaire de modification des informations de l'édition

    if(document.getElementById("modifInfo"))
    {
        // Appelle des fonction en cas de clic sur les boutons
        document.getElementById("modifInfo").onclick = afficheForm;
        document.getElementById("annulerInfo").onclick = annulerForm;

        // Sauvegarde des champs
        const nom = document.getElementById('nomInput').value;
        const distance = document.getElementById('distanceInput').value;
        const denivelee = document.getElementById('deniveleeInput').value;
        const adresse_depart = document.getElementById('adresse_departInput').value;
        const type = document.getElementById('typeInput').value;
        const plan = document.getElementById('planInput').value;
    }

    // Fonction qui affiche le formulaire de modification
    function afficheForm()
    {
        location.href="#hautPage";
        Array.from(document.getElementsByClassName('writeInfo')).forEach(n => n.style.display = "inline-block");
        Array.from(document.getElementsByClassName('writeInfoFlex')).forEach(n => n.style.display = "flex");
        document.getElementById('labelFile').style.display = "block";
        Array.from(document.getElementsByClassName('readInfo')).forEach(n => n.style.display = "none");
    }

    // Fonction qui cache le formulaire de modification et remet les valeures initiales
    function annulerForm()
    {
        Array.from(document.getElementsByClassName('writeInfo')).forEach(n => n.style.display = "none");
        Array.from(document.getElementsByClassName('readInfo')).forEach(n => n.style.display = "inline-block");
        document.getElementById('labelFile').style.display = "none";
        Array.from(document.getElementsByClassName('readInfoFlex')).forEach(n => n.style.display = "flex");

        // Re-mise en place des valeures initiales
        document.getElementById('nomInput').value = nom;
        document.getElementById('distanceInput').value = distance;
        document.getElementById('deniveleeInput').value = denivelee;
        document.getElementById('adresse_departInput').value = adresse_depart;
        document.getElementById('typeInput').value = type;
        document.getElementById('planInput').value = plan;
    }

    // Fonction de confirmation de suppression d'un tarif
    function attention()
    {
        resultat=window.confirm('Voulez-vous vraiment supprimer ce tarif ?');
        if (resultat==1)
        {
        }
        else
        {
            return false;
        }
    }
</script>