<?php
    session_start();
    
    // Récupération de l'idcourse, redirection si non existante
    if (isset($_GET['idcourse']))
    {
        $idCourse = $_GET['idcourse'];
    } else if(isset($_POST['idCoursePost']))
    {
        $idCourse = $_POST['idCoursePost'];
    } else{
        header('Location: http://localhost/projet-bdw1/index.php');
    }

    include "includes/header.php";

    $user = 'root';
    $mdp = '';
    $machine = 'localhost';
    $bd = 'bdw1';
    $connexion = mysqli_connect($machine, $user, $mdp, $bd);

    if (mysqli_connect_errno()) // erreur si > 0
        printf("Échec de la connexion : %s", mysqli_connect_error());
    else {
 
        if(isset($_POST['anneeEd']))
        {
            $anneeEd = mysqli_real_escape_string($connexion, $_POST['anneeEd']);
            $nbParti = mysqli_real_escape_string($connexion, $_POST['nbPart']);
            $adresseDepa = mysqli_real_escape_string($connexion, $_POST['adresseDep']);
            $dateAdd = mysqli_real_escape_string($connexion, $_POST['dateAdd']);
            $site = mysqli_real_escape_string($connexion, $_POST['siteURL']);
            $dateIns = mysqli_real_escape_string($connexion, $_POST['dateIns']);
            $dateDepot = mysqli_real_escape_string($connexion, $_POST['dateDepot']);
            $dateDossard = mysqli_real_escape_string($connexion, $_POST['dateDossard']);
            $plan = mysqli_real_escape_string($connexion, $_POST['planAdd']);
    
            $resultat = "INSERT INTO edition (id_course, annee, nb_participants, plan, adresse_depart, date, site_url, date_inscription, date_depot_certificat, date_recup_dossard)
                        VALUES ('$idCourse', '$anneeEd', '$nbParti', '$plan', '$adresseDepa', '$dateAdd', '$site', '$dateIns', '$dateDepot', '$dateDossard')";

            if(mysqli_query($connexion, $resultat) == FALSE)
                print "<script>alert(\"Échec de l'ajout d'edition\")</script>";
        }

        if (isset($_POST['genderSelect'])) {
            $chosenSex = mysqli_real_escape_string($connexion, $_POST['genderSelect']);
            //  print $_POST['genderSelect'] . "Valeur apres form";
        } else {
            $chosenSex = "H";
            //  print $chosenSex . " Valeur par défaut";
        }
    
        if ($chosenSex == "H") {
            print "<div class='container'>
                        Sexe sélectionnée : Hommes
                    </div>";
        } else {
            print "<div class='container'>
                        Sexe sélectionnée : Femmes
                    </div>";
        }

        $requete = "SELECT ed.annee, ed.nb_participants, MIN(tmp.temps) AS bestScore, COUNT(DISTINCT adh.club) AS nb_club, AVG(tmp.temps) AS moyenne, ed.id_course
                        FROM edition ed JOIN epreuve ep ON ed.id_edition = ep.id_edition 
                                        JOIN temps_passage tmp ON tmp.id_epreuve = ep.id_epreuve
                                        JOIN participation pa ON pa.dossard = tmp.dossard
                                        JOIN adherent adh ON pa.id_adherent = adh.id_adherent
                                        JOIN resultat res ON res.id_epreuve = ep.id_epreuve
                        WHERE ed.id_course = $idCourse AND adh.sexe = '$chosenSex'
                        GROUP BY ed.annee";

        //Permet d'avoir la moyenne de temps en fonction du sexe (H = Homme, F = Femmes)

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de l'ajout d'edition\")</script>";
        else{
            print "<section class='listeEditions'>
            <div class='container'>
                <table class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>Annee</th>
                            <th scope='col'>Nombre de participants Total</th>
                            <th scope='col'>Meilleur temps</th>
                            <th scope='col'>Nombre de club représenté</th>
                            <th scope='col'>Moyenne de temps </th>
                        </tr>
                    </thead>
                <tbody>";

            while ($nuplet = mysqli_fetch_assoc($resultat))
            {
                $annee = $nuplet['annee'];
                $nb_participants = $nuplet['nb_participants'];
                $meilleur_temps = $nuplet['bestScore'];
                $nb_club = $nuplet['nb_club'];
                $moyenneTmp = $nuplet['moyenne'];
                $idCourse = $nuplet['id_course'];

                print "<tr>
                            <td>$annee</td>
                            <td>$nb_participants</td>
                            <td>$meilleur_temps min</td>
                            <td>$nb_club</td>
                            <td>$moyenneTmp min</td>
                        </tr>";
            }
        }

        mysqli_close($connexion);
    }
?>
            </tbody>
        </table>
    </div>
</section>

<div class="container">
    <div class='row mb-4'>
        <button type="button" class="btn btn-primary mx-auto" data-toggle="modal" data-target="#modalAjoutEdition">
            Ajouter une édition
        </button>
    </div>
</div>

<section class="formulaireFiltre">
    <div class='container'>
        <form method="POST" action="<?php print "?idcourse=".$idCourse ?>">
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label for="genderSelect">Filtrer par genre :</label>
                    <select class="form-control" id="genderSelect" name="genderSelect">
                        <option>H</option>
                        <option>F</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success" name="changeGender">Valider</button>
        </form>
    </div>
</section>

<?php include "includes/footer.php"; ?>

<!-- Modal du formulaire d'ajout d'édition -->
<div class="modal fade" id="modalAjoutEdition" tabindex="-1" role="dialog" aria-labelledby="modalAjoutEdition" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une édition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="editions.php<?php print "?idcourse=".$idCourse ?>">
                    <div class='container'>
                        <div class="form-row">
                            <div class="col-md-2 mb-3">
                                <label for="anneeEd">Année Edition :</label>
                                <input type="text" class="form-control" id="anneeEd" name="anneeEd" placeholder="AAAA" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="nbPart">Nombres Participants :</label>
                                <input type="text" class="form-control" id="nbPart" name="nbPart" placeholder="1234" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="adresseDep">Adresse départ : </label>
                                <input type="text" class="form-control" id="adresseDep" name="adresseDep" placeholder="124 Rue de machin, 69699 Bidule" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mb-3">
                                <label for="dateAdd">Date :</label>
                                <input type="date" class="form-control" id="dateAdd" name="dateAdd" required>
                            </div>
                            <div class="col-md-9 mb-3">
                                <label for="siteURL">Website : </label>
                                <input type="text" class="form-control" id="siteURL" name="siteURL" placeholder="https://www.BDW1cTr0Gnial.fr/" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="dateIns">Date Inscription : </label>
                                <input type="date" class="form-control" id="dateIns" name="dateIns" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="dateDepot">Date Depot Certif : </label>
                                <input type="date" class="form-control" id="dateDepot" name="dateDepot" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="dateDossard">Date Recup Dossard : </label>
                                <input type="date" class="form-control" id="dateDossard" name="dateDossard" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <div class="custom-file">
                                    <label class="custom-file-label" for="planAdd">Choisissez un plan... </label>
                                    <input type="file" class="custom-file-input" id="planAdd" name="planAdd" required>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button class="btn btn-primary" type="submit">Ajouter Edition</button>
                </form>
            </div>
        </div>
    </div>
</div>