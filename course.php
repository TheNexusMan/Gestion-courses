<!-- Arnaud DEBRABANT P1707147 - Damien PETITJEAN P1408987 -->
<?php
    session_start();

    // Récupération de l'idcourse, redirection si non existante
    if (isset($_GET['idcourse']))
    {
        $idCourse = intval($_GET['idcourse']);
    } else{
        header('Location: http://localhost/projet-bdw1/index.php');
    }

    include "includes/header.php";

    if (mysqli_connect_errno())
        printf("Échec de la connexion : %s", mysqli_connect_error());
    else {

        // Suppression d'une édition
        if (isset($_GET['delete_edition']) && $_SESSION['typeUtilisateur'] == "Admin")
        {
            $editionToDelete = intval($_GET['delete_edition']);

            mysqli_begin_transaction($connexion, MYSQLI_TRANS_START_READ_WRITE);

            $requete = "DELETE FROM edition WHERE id_edition = $editionToDelete";
            mysqli_query($connexion, $requete);

            $requete = "SELECT id_epreuve FROM epreuve WHERE id_edition = $editionToDelete";
            $resultatIdEpreuve = mysqli_query($connexion, $requete);

            while($nupletIdEpreuve = mysqli_fetch_assoc($resultatIdEpreuve))
            {
                $idEpreuveToDel = $nupletIdEpreuve['id_epreuve'];

                $requete = "DELETE FROM tarif WHERE id_epreuve = $idEpreuveToDel";
                mysqli_query($connexion, $requete);

                $requete = "DELETE FROM participation WHERE id_epreuve = $idEpreuveToDel";
                mysqli_query($connexion, $requete);

                $requete = "DELETE FROM resultat WHERE id_epreuve = $idEpreuveToDel";
                mysqli_query($connexion, $requete);

                $requete = "DELETE FROM temps_passage WHERE id_epreuve = $idEpreuveToDel";
                mysqli_query($connexion, $requete);
            }

            $requete = "DELETE FROM epreuve WHERE id_edition = $editionToDelete";
            mysqli_query($connexion, $requete);

            if(!mysqli_commit($connexion))
                print "<script>alert(\"Échec de la requête de suppression de l'édition\")</script>";
        }

        // Ajout d'une nouvelle édition
        if (isset($_POST['anneeEd']) && $_SESSION['typeUtilisateur'] == "Admin")
        {
            $anneeEd = mysqli_real_escape_string($connexion, $_POST['anneeEd']);
            $nbParti = mysqli_real_escape_string($connexion, $_POST['nbPart']);
            $dateAdd = mysqli_real_escape_string($connexion, $_POST['dateAdd']);
            $dateIns = mysqli_real_escape_string($connexion, $_POST['dateIns']);
            $dateDepot = mysqli_real_escape_string($connexion, $_POST['dateDepot']);
            $dateDossard = mysqli_real_escape_string($connexion, $_POST['dateDossard']);

            $requete = "INSERT INTO edition (id_course, annee, nb_participants, date, date_inscription, date_depot_certificat, date_recup_dossard)
                        VALUES ('$idCourse', '$anneeEd', '$nbParti', '$dateAdd', '$dateIns', '$dateDepot', '$dateDossard')";

            if (mysqli_query($connexion, $requete) == FALSE)
                print "<script>alert(\"Échec de l'ajout de l'edition\")</script>";
        }

        // Modification des informations de la course
        if(isset($_POST['anneeCreation']) && isset($_POST['mois']) && isset($_POST['site']) && $_SESSION['typeUtilisateur'] == "Admin")
        {
            $modAnneeCreation = intval($_POST['anneeCreation']);
            $modMois = intval($_POST['mois']);
            $site = mysqli_real_escape_string($connexion, $_POST['site']);

            $requete = "UPDATE course
                        SET annee_creation = $modAnneeCreation, mois = $modMois, site_url = '$site'
                        WHERE id_course = $idCourse";

            if(mysqli_query($connexion, $requete) == FALSE){
                print "<script>alert('Échec de la requête de modification des informations')</script>";
            }
        }

        // Récupération et affichages des informations de la course
        $requete = "SELECT * FROM course WHERE id_course = $idCourse";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération des informations de la course\")</script>";
        else {
            $nuplet = mysqli_fetch_assoc($resultat);

            $nom = $nuplet['nom'];
            $annee_creation = $nuplet['annee_creation'];
            $mois = $nuplet['mois'];
            $selectMois = "";
            $site = $nuplet['site_url'];

            for($i = 1; $i <= 12; $i++)
            {
                if($i == $mois)
                {
                    $selectMois .= "<option value='$i' selected='selected'>$i</option>";
                }else{
                    $selectMois .= "<option value='$i'>$i</option>";
                }
            }

            print "<div class='container'>
                        <div class='nomCourse row'>
                            <h1 class='mx-auto mb-4'>$nom</h1>
                        </div>
                    </div>
                    <section class='sectionInfos'>
                        <h2>Informations de la course</h2>
                        <div class='infos container'>
                            <div id='infosBloc' class='infosBloc container mx-auto col-lg-8 col-md-10 col-xs-12 mw-50'>
                                <form action='' method='POST'>
                                    <div class='form-row ligneInfos'>
                                        <div class='col-md-4'>
                                            <p class='nomInfo'>Nom</p>
                                            <p>$nom</p>
                                        </div>
                                        <div class='col-md-1'></div>
                                        <div class='col-md-3'>
                                            <p class='nomInfo'>Année de création</p>
                                            <p class='readInfo'>$annee_creation</p>
                                            <input type='text' id='anneeCreationInput' class='form-control writeInfo' name='anneeCreation' value=\"$annee_creation\" maxlength='4' placeholder='AAAA' required>
                                        </div>
                                        <div class='col-md-2'></div>
                                        <div class='col-md-2'>
                                            <p class='nomInfo'>Mois</p>
                                            <p class='readInfo'>$mois</p>
                                            <select id='moisInput' class='custom-select writeInfo' name='mois' required>
                                                $selectMois
                                            </select>
                                        </div>
                                    </div>
                                    <div class='form-row ligneInfos'>
                                        <div class='col-md-12'>
                                            <p class='nomInfo'>Site</p>
                                            <a href='$site' target='_blank' class='readInfo'>Site web de la course</a>
                                            <input type='text' id='siteInput' class='form-control writeInfo' name='site' value=\"$site\" placeholder='https://www.sitedelacourse.fr/' required>
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
        
        // Récupération des éditions en fonction du trie du tableau
        $requete = "SELECT ed.annee, ed.nb_participants, ed.id_edition
                    FROM edition ed
                    WHERE ed.id_course = $idCourse";

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
            print "<script>alert(\"Échec de l'ajout de l'édition\")</script>";
        else{
            print "<section class='liste'>
                        <h2 class='tableLabel'>Liste des éditions</h2>
                        <div class='container'>
                            <div class='table-responsive'>
                                <table class='table col-md-6 mx-auto table-bordered table-hover text-center'>
                                    <thead class='thead-dark'>
                                        <tr>
                                            <th id='anneeCol' scope='col'>
                                                <a href='?idcourse=$idCourse&order=annee&orderSec=$order&sens=$sens&clic=1#ancreTri'>Année</a>
                                            </th>
                                            <th id='nb_participantsCol' scope='col'>
                                                <a href='?idcourse=$idCourse&order=nb_participants&orderSec=$order&sens=$sens&clic=1#ancreTri'>Nombre de participants</a>
                                            </th>
                                            ".($_SESSION['typeUtilisateur'] == 'Admin' ? '<th>Action</th>' : '')."
                                        </tr>
                                    </thead>
                                <tbody>";

            while ($nuplet = mysqli_fetch_assoc($resultat))
            {
                $annee = $nuplet['annee'];
                $nb_participants = $nuplet['nb_participants'];
                $idEdition = $nuplet['id_edition'];

                print "<tr class='ligneTabClic'>
                            <td onclick=\"location.href='edition.php?idedition=$idEdition'\" class='text-left'>$annee</td>
                            <td onclick=\"location.href='edition.php?idedition=$idEdition'\" class='text-left'>$nb_participants</td>";
                if($_SESSION['typeUtilisateur'] == "Admin")
                {
                    print "<td>
                                <form method='GET' action='course.php#ancreTri' Onsubmit='return attention();'>
                                    <input name='idcourse' type='hidden' value='$idCourse'>
                                    <input name='delete_edition' type='hidden' value='$idEdition'>
                                    <input name='order' type='hidden' value='$order'>
                                    <input name='orderSec' type='hidden' value='$orderSec'>
                                    <input name='sens' type='hidden' value='$sensGet'>
                                    <input name='clic' type='hidden' value='0'>
                                    <button class='btnDelete' type='submit'>
                                        <i class='fas fa-trash-alt'></i>
                                    </button>
                                </form>
                            </td>";
                }
                print "</tr>";
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
                        <button type='button' class='btn btn-primary mx-auto' data-toggle='modal' data-target='#modalAjout'>
                            Ajouter une édition
                        </button>
                    </div>
                </div>";
    }

    include "includes/footer.php";
?>

<!-- Modal du formulaire d'ajout d'édition -->
<div class="modal fade" id="modalAjout" tabindex="-1" role="dialog" aria-labelledby="modalAjout" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une édition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="course.php<?php print "?idcourse=$idCourse&order=$order&orderSec=$orderSec&sens=$sens&clic=0" ?>">
                    <div class='container'>
                        <div class="form-row">
                            <div class="col-md-2 mb-3">
                                <label for="anneeEd">Année édition</label>
                                <input type="text" class="form-control" id="anneeEd" name="anneeEd" maxlength="4" placeholder="AAAA" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="nbPart">Nombre de participants</label>
                                <input type="text" class="form-control" id="nbPart" name="nbPart" placeholder="1234" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dateAdd">Date</label>
                                <input type="date" class="form-control" id="dateAdd" name="dateAdd" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="dateIns">Date inscription</label>
                                <input type="date" class="form-control" id="dateIns" name="dateIns" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="dateDepot">Date dépôt des certificats</label>
                                <input type="date" class="form-control" id="dateDepot" name="dateDepot" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="dateDossard">Date récupération des dossards</label>
                                <input type="date" class="form-control" id="dateDossard" name="dateDossard" required>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button class="btn btn-primary" type="submit">Ajouter édition</button>
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
    // Gestion de l'affichage du formulaire de modification des informations de la course

    if(document.getElementById("modifInfo"))
    {
        // Appelle des fonction en cas de clic sur les boutons
        document.getElementById("modifInfo").onclick = afficheForm;
        document.getElementById("annulerInfo").onclick = annulerForm;

        // Sauvegarde des champs
        const anneeCreation = document.getElementById('anneeCreationInput').value;
        const mois = document.getElementById('moisInput').value;
        const site = document.getElementById('siteInput').value;
    }

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
        document.getElementById('anneeCreationInput').value = anneeCreation;
        document.getElementById('moisInput').value = mois;
        document.getElementById('siteInput').value = site;
    }

    // Fonction de confirmation de suppression d'une édition
    function attention()
    {
        resultat=window.confirm('Voulez-vous vraiment supprimer cette édition ?');
        if (resultat==1)
        {
        }
        else
        {
            return false;
        }
    }
</script>