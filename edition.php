<?php
    session_start();

    // Récupération de l'id_edition, redirection si non existante
    if (isset($_GET['idedition']))
    {
        $idEdition = intval($_GET['idedition']);
    } else{
        header('Location: http://localhost/projet-bdw1/index.php');
    }

    include "includes/header.php";

    if (mysqli_connect_errno())
        printf("Échec de la connexion : %s", mysqli_connect_error());
    else {

        // Suppression d'une épreuve
        if (isset($_GET['delete_epreuve']) && $_SESSION['typeUtilisateur'] == "Admin") {
            $epreuveToDelete = intval($_GET['delete_epreuve']);

            mysqli_begin_transaction($connexion, MYSQLI_TRANS_START_READ_WRITE);

            $requete = "DELETE FROM epreuve WHERE id_epreuve = $epreuveToDelete";
            mysqli_query($connexion, $requete);

            $requete = "DELETE FROM tarif WHERE id_epreuve = $epreuveToDelete";
            mysqli_query($connexion, $requete);

            $requete = "DELETE FROM participation WHERE id_epreuve = $epreuveToDelete";
            mysqli_query($connexion, $requete);

            $requete = "DELETE FROM resultat WHERE id_epreuve = $epreuveToDelete";
            mysqli_query($connexion, $requete);

            $requete = "DELETE FROM temps_passage WHERE id_epreuve = $epreuveToDelete";
            mysqli_query($connexion, $requete);

            if(!mysqli_commit($connexion))
                print "<script>alert(\"Échec de la requête de suppression de l'épreuve\")</script>";
        }

        // Ajout d'une nouvelle épreuve
        if (isset($_POST['nom']) && $_SESSION['typeUtilisateur'] == "Admin") {
            $nom = mysqli_real_escape_string($connexion, $_POST['nom']);
            $distance = intval($_POST['distance']);
            $adresseDepart = mysqli_real_escape_string($connexion, $_POST['adresse_depart']);
            $denivelee = intval($_POST['denivelee']);
            $typeEpreuve = mysqli_real_escape_string($connexion, $_POST['type_epreuve']);
            $plan = mysqli_real_escape_string($connexion, $_POST['plan']);

            $requete = "INSERT INTO epreuve (id_edition, nom, distance, adresse_depart, denivelee, type_epreuve, plan)
                        VALUES ($idEdition, '$nom', $distance, '$adresseDepart', $denivelee, '$typeEpreuve', '$plan')";

            if (mysqli_query($connexion, $requete) == FALSE)
                print "<script>alert(\"Échec de l'ajout de l'épreuve\")</script>";
        }

        // Modification des informations de l'édition
        if(isset($_POST['annee']) && isset($_POST['nb_participants']) && isset($_POST['date']) && $_SESSION['typeUtilisateur'] == "Admin")
        {
            $modAnnee = intval($_POST['annee']);
            $modNbParticipants = intval($_POST['nb_participants']);
            $modDate = mysqli_real_escape_string($connexion, $_POST['date']);
            $modDate = ($_POST['date'] == NULL ? 'NULL' : "'".$modDate."'");
            $modDateInscription = mysqli_real_escape_string($connexion, $_POST['date_inscription']);
            $modDateInscription = ($_POST['date'] == NULL ? 'NULL' : "'".$modDateInscription."'");
            $modDateDepotCertificat = mysqli_real_escape_string($connexion, $_POST['date_depot_certificat']);
            $modDateDepotCertificat = ($_POST['date'] == NULL ? 'NULL' : "'".$modDateDepotCertificat."'");
            $modDateRecupDossard = mysqli_real_escape_string($connexion, $_POST['date_recup_dossard']);
            $modDateRecupDossard = ($_POST['date'] == NULL ? 'NULL' : "'".$modDateRecupDossard."'");

            $requete = "UPDATE edition
                        SET annee = $modAnnee, nb_participants = $modNbParticipants, date = $modDate, date_inscription = $modDateInscription, date_depot_certificat = $modDateDepotCertificat, date_recup_dossard = $modDateRecupDossard
                        WHERE id_edition = $idEdition";

            if(mysqli_query($connexion, $requete) == FALSE){
                print "<script>alert('Échec de la requête de modification des informations')</script>";
            }
        }

        // Récupération et affichages des informations de l'édition
        $requete = "SELECT * FROM edition WHERE id_edition = $idEdition";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération des informations de l'édition'\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);

            $id_course = $nuplet['id_course'];
            $annee = $nuplet['annee'];
            $nbParticipants = $nuplet['nb_participants'];
            $date = $nuplet['date'];
            $dateInscription = $nuplet['date_inscription'];
            $dateDepotCertificat = $nuplet['date_depot_certificat'];
            $dateRecupDossard = $nuplet['date_recup_dossard'];

            $requete = "SELECT nom FROM course WHERE id_course = $id_course";

            $resultat = mysqli_query($connexion, $requete);
            if($resultat == FALSE)
            {
                print "<script>alert(\"Échec de la requête de récupération du nom de la course'\")</script>";
                $nomCourse = "Course";
            } else {
                $nuplet = mysqli_fetch_assoc($resultat);
                $nomCourse = $nuplet['nom'];
            }

            print "<div class='container'>
                        <div class='nomCourse row'>
                            <h1 class='mx-auto mb-4'><a href='course.php?idcourse=$id_course'>$nomCourse</a> - édition $annee</h1>
                        </div>
                    </div>
                    <section class='sectionInfos'>
                        <h2>Informations de l'édition</h2>
                        <div class='infos container col-12'>
                            <div id='infosBloc' class='infosBloc container mx-auto col-lg-8 col-md-10 col-xs-12 mw-50'>
                                <form action='' method='POST'>
                                    <div class='form-row ligneInfos'>
                                        <div class='col-md-3'>
                                            <p class='nomInfo'>Année</p>
                                            <p class='readInfo'>$annee</p>
                                            <input type='text' id='anneeInput' class='form-control writeInfo' name='annee' value=\"$annee\" maxlength='4' placeholder='AAAA' required>
                                        </div>
                                        <div class='col-md-1'></div>
                                        <div class='col-md-3'>
                                            <p class='nomInfo'>Nombre de participants</p>
                                            <p class='readInfo'>$nbParticipants</p>
                                            <input type='text' id='nbParticipantsInput' class='form-control writeInfo' name='nb_participants' value=\"$nbParticipants\" placeholder='1234' required>
                                        </div>
                                        <div class='col-md-1'></div>
                                        <div class='col-md-3'>
                                            <p class='nomInfo'>Date</p>
                                            <p class='readInfo'>".date('d/m/Y', strtotime($date))."</p>
                                            <input type='date' id='dateInput' class='form-control writeInfo' name='date' value=\"$date\" required>
                                        </div>
                                    </div>
                                    <div class='form-row ligneInfos'>
                                        <div class='col-md-3'>
                                            <p class='nomInfo'>Date d'inscription</p>
                                            <p class='readInfo'>".date('d/m/Y', strtotime($dateInscription))."</p>
                                            <input type='date' id='dateInscriptionInput' class='form-control writeInfo' name='date_inscription' value=\"$dateInscription\" required>
                                        </div>
                                        <div class='col-md-1'></div>
                                        <div class='col-md-3'>
                                            <p class='nomInfo'>Date dépôt des certificats</p>
                                            <p class='readInfo'>".date('d/m/Y', strtotime($dateDepotCertificat))."</p>
                                            <input type='date' id='dateDepotCertificatInput' class='form-control writeInfo' name='date_depot_certificat' value=\"$dateDepotCertificat\" required>
                                        </div>
                                        <div class='col-md-1'></div>
                                        <div class='col-md-3'>
                                            <p class='nomInfo'>Date récupération des dossard</p>
                                            <p class='readInfo'>".date('d/m/Y', strtotime($dateRecupDossard))."</p>
                                            <input type='date' id='dateRecupDossardInput' class='form-control writeInfo' name='date_recup_dossard' value=\"$dateRecupDossard\" required>
                                        </div>
                                    </div>";

            if($_SESSION['typeUtilisateur'] == "Admin")
            {
                print "             <div class='row ligneButton readInfo readInfoFlex' id='modifInfo'>
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
                                    
            print "             </form>
                            </div>
                        </div>
                        <div id='ancreTri'></div>
                    </section>";
        }
        
        // Récupération des épreuves en fonction du trie du tableau
        $requete = "SELECT id_epreuve, nom, distance, denivelee, type_epreuve
                    FROM epreuve
                    WHERE id_edition = $idEdition";

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

            if($resultat == FALSE)
                print "<script>alert(\"Échec de l'ajout de l'épreuve\")</script>";
            else{
                print "<section class='liste'>
                            <h2 class='tableLabel'>Liste des épreuves</h2>
                            <div class='container'>
                                <div class='table-responsive'>
                                    <table class='table col-md-10 mx-auto table-bordered table-hover text-center'>
                                        <thead class='thead-dark'>
                                            <tr>
                                                <th id='nomCol' scope='col'>
                                                    <a href='?idedition=$idEdition&order=nom&orderSec=$order&sens=$sens&clic=1#ancreTri'>Nom</a>
                                                </th>
                                                <th id='distanceCol' scope='col'>
                                                    <a href='?idedition=$idEdition&order=distance&orderSec=$order&sens=$sens&clic=1#ancreTri'>Distance</a>
                                                </th>
                                                <th id='deniveleeCol' scope='col'>
                                                    <a href='?idedition=$idEdition&order=denivelee&orderSec=$order&sens=$sens&clic=1#ancreTri'>Dénivelée</a>
                                                </th>
                                                <th id='type_epreuveCol' scope='col'>
                                                    <a href='?idedition=$idEdition&order=type_epreuve&orderSec=$order&sens=$sens&clic=1#ancreTri'>Type</a>
                                                </th>
                                                ".($_SESSION['typeUtilisateur'] == 'Admin' ? '<th>Action</th>' : '')."
                                            </tr>
                                        </thead>
                                    <tbody>";

            while ($nuplet = mysqli_fetch_assoc($resultat)) {
                $id_epreuve = $nuplet['id_epreuve'];
                $nom = $nuplet['nom'];
                $distance = $nuplet['distance'];
                $denivelee = $nuplet['denivelee'];
                $type_epreuve = $nuplet['type_epreuve'];

                print "<tr class='ligneTabClic'>
                            <td onclick=\"location.href='epreuve.php?id_epreuve=$id_epreuve'\" class='text-left'>$nom</td>
                            <td onclick=\"location.href='epreuve.php?id_epreuve=$id_epreuve'\" class='text-left'>$distance</td>
                            <td onclick=\"location.href='epreuve.php?id_epreuve=$id_epreuve'\" class='text-left'>$denivelee</td>
                            <td onclick=\"location.href='epreuve.php?id_epreuve=$id_epreuve'\" class='text-left'>$type_epreuve</td>";
                if($_SESSION['typeUtilisateur'] == "Admin")
                {
                    print "<td>
                                <form method='GET' action='edition.php#ancreTri' Onsubmit='return attention();'>
                                    <input name='idedition' type='hidden' value='$idEdition'>
                                    <input name='delete_epreuve' type='hidden' value='$id_epreuve'>
                                    <input name='order' type='hidden' value='$order'>
                                    <input name='orderSec' type='hidden' value='$orderSec'>
                                    <input name='sens' type='hidden' value='$sensGet'>
                                    <input name='clic' type='hidden' value='0'>
                                    <button class='btnDelete' type='submit'>
                                        <i class='fas fa-trash-alt'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>";
                }
            }
        }

        mysqli_close($connexion);
    }
?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php
    // Bouton d'ajout d'épreuve
    if($_SESSION['typeUtilisateur'] == "Admin")
    {
        print "<div class='container'>
                    <div class='row mb-4'>
                        <button type='button' class='btn btn-primary mx-auto' data-toggle='modal' data-target='#modalAjoutEdition'>
                            Ajouter une épreuve
                        </button>
                    </div>
                </div>";
    }

    include "includes/footer.php";
?>

<!-- Modal du formulaire d'ajout d'épreuve -->
<div class="modal fade" id="modalAjoutEdition" tabindex="-1" role="dialog" aria-labelledby="modalAjoutEdition" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une épreuve</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="edition.php<?php print "?idedition=$idEdition&order=$order&orderSec=$orderSec&sens=$sens&clic=0" ?>">
                    <div class='container'>
                        <div class="form-row">
                            <div class="col-md-md-6 mb-3">
                                <label for="nom">Nom :</label>
                                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
                            </div>
                            <div class="col-md-md-6 mb-3">
                                <label for="typeEpreuve">Type :</label>
                                <input type="text" class="form-control" id="typeEpreuve" name="type_epreuve" placeholder="Type d'épreuve" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-md-6 mb-3">
                                <label for="distance">Distance : </label>
                                <input type="text" class="form-control" id="distance" name="distance" placeholder="1234" required>
                            </div>
                            <div class="col-md-md-6 mb-3">
                                <label for="denivelee">Dénivelée : </label>
                                <input type="text" class="form-control" id="denivelee" name="denivelee" placeholder="1234" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-md-12 mb-3">
                                <label for="adresse">Adresse de départ : </label>
                                <input type="text" class="form-control" id="adresse" name="adresse_depart" placeholder="Adresse de départ" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-md-12 mb-3">
                                <div class="custom-file">
                                    <label class="custom-file-label" for="plan">Choisissez un plan... </label>
                                    <input type="file" class="custom-file-input" id="plan" name="plan" required>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button class="btn btn-primary" type="submit">Ajouter épreuve</button>
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

    // Appelle des fonction en cas de clic sur les boutons
    document.getElementById("modifInfo").onclick = afficheForm;
    document.getElementById("annulerInfo").onclick = annulerForm;

    // Sauvegarde des champs
    const annee = document.getElementById('anneeInput').value;
    const nbParticipants = document.getElementById('nbParticipantsInput').value;
    const date = document.getElementById('dateInput').value;
    const dateInscription = document.getElementById('dateInscriptionInput').value;
    const dateDepotCertificat = document.getElementById('dateDepotCertificatInput').value;
    const dateRecupDossard = document.getElementById('dateRecupDossardInput').value;

    // Fonction qui affiche le formulaire de modification
    function afficheForm()
    {
        Array.from(document.getElementsByClassName('writeInfo')).forEach(n => n.style.display = "inline-block");
        Array.from(document.getElementsByClassName('writeInfoFlex')).forEach(n => n.style.display = "flex");
        Array.from(document.getElementsByClassName('readInfo')).forEach(n => n.style.display = "none");
    }

    // Fonction qui cache le formulaire de modification et remet les valeures initiales
    function annulerForm()
    {
        Array.from(document.getElementsByClassName('writeInfo')).forEach(n => n.style.display = "none");
        Array.from(document.getElementsByClassName('readInfo')).forEach(n => n.style.display = "inline-block");
        Array.from(document.getElementsByClassName('readInfoFlex')).forEach(n => n.style.display = "flex");

        // Re-mise en place des valeures initiales
        document.getElementById('anneeInput').value = annee;
        document.getElementById('nbParticipantsInput').value = nbParticipants;
        document.getElementById('dateInput').value = date;
        document.getElementById('dateInscriptionInput').value = dateInscription;
        document.getElementById('dateDepotCertificatInput').value = dateDepotCertificat;
        document.getElementById('dateRecupDossardInput').value = dateRecupDossard;
    }

    // Fonction de confirmation de suppression d'une édition
    function attention()
    {
        resultat=window.confirm('Voulez-vous vraiment supprimer cette épreuve ?');
        if (resultat==1)
        {
        }
        else
        {
            return false;
        }
    }
</script>