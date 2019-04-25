<?php
    session_start();
    include "includes/header.php";

    if (mysqli_connect_errno())
        printf("Échec de la connexion : %s", mysqli_connect_error());
    else {
        mysqli_set_charset($connexion, 'utf8');

        // Suppression d'une course
        if (isset($_GET['delete_course'])) {
            $idCourseToDel = intval($_GET['delete_course']);

            mysqli_begin_transaction($connexion, MYSQLI_TRANS_START_READ_WRITE);

            $requete = "DELETE FROM course WHERE id_course = $idCourseToDel";

            mysqli_query($connexion, $requete);

            $requete = "SELECT id_edition FROM edition WHERE id_course = $idCourseToDel";

            $resultat = mysqli_query($connexion, $requete);

            while($nuplet = mysqli_fetch_assoc($resultat))
            {
                $idEditionToDel = $nuplet['id_edition'];

                $requete = "SELECT id_epreuve FROM epreuve WHERE id_edition = $idEditionToDel";
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

                $requete = "DELETE FROM epreuve WHERE id_edition = $idEditionToDel";
                mysqli_query($connexion, $requete);
            }

            $requete = "DELETE FROM edition WHERE id_course = $idCourseToDel";
            mysqli_query($connexion, $requete);

            if(!mysqli_commit($connexion))
                print "<script>alert(\"Échec de la requête de suppression de la course\")</script>";
        }

        // Ajout d'une nouvelle course
        if ((isset($_POST['nom'])) && (isset($_POST['anneeCrea'])) && (isset($_POST['month']))) {
            $nameAdd = mysqli_real_escape_string($connexion, $_POST['nom']);
            $anneeAdd = intval($_POST['anneeCrea']);
            $monthAdd = intval($_POST['month']);
            $site = mysqli_real_escape_string($connexion, $_POST['siteURL']);

            $requete = "INSERT INTO course (nom, annee_creation, mois, site_url) VALUES('$nameAdd', $anneeAdd, $monthAdd, '$site');";

            if (mysqli_query($connexion, $requete) == FALSE)
                print "<script>alert(\"Échec de la requête de l'ajout de la course\")</script>";
        }

        // Récupération des courses en fonction du trie du tableau :

        // Cas où on clic deux fois à la suite sur une colonne (changement de l'ordre du trie)
        if(!empty($_GET['order']) && ($_GET['orderSec'] == $_GET['order']))
        {
            $order = mysqli_real_escape_string($connexion, $_GET['order']);
            $orderSec = $_GET['orderSec'];
            $sensGet = mysqli_real_escape_string($connexion, $_GET['sens']);

            $requete = "SELECT * FROM course ORDER BY $order $sensGet";

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

            $requete = "SELECT * FROM course ORDER BY $order";

            if($_GET['clic']){
                $sens = "DESC";
            }else{
                $sens = $sensGet;
            }
        }else{
            $requete = "SELECT * FROM course";
            $order = "";
            $orderSec = "";
            $sensGet = "";
            $sens = "ASC";
        }

        $resultat = mysqli_query($connexion, $requete);

        if ($resultat == FALSE)
            print "<script>alert('Échec de la requête de récupération des courses')</script>";
        else {

            print "<section class='liste'>
                    <h2 class='tableLabel'>Liste des courses</h2>
                    <div class='container'>
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th id='id_courseCol' scope='col'>
                                        <a href='?order=id_course&orderSec=$order&sens=$sens&clic=1'>Id</a>
                                    </th>
                                    <th id='nomCol' scope='col'>
                                        <a href='?order=nom&orderSec=$order&sens=$sens&clic=1'>Nom</a>
                                    </th>
                                    <th id='annee_creationCol' scope='col'>
                                        <a href='?order=annee_creation&orderSec=$order&sens=$sens&clic=1'>Année création</a>
                                    </th>
                                    <th id='moisCol' scope='col'>
                                        <a href='?order=mois&orderSec=$order&sens=$sens&clic=1'>Mois</a>
                                    </th>
                                    <th scope='col'>Action</th>
                                </tr>
                            </thead>
                            <tbody>";

            while ($nuplet = mysqli_fetch_assoc($resultat)) {
                $id_course = $nuplet['id_course'];
                $nom = $nuplet['nom'];
                $annee_crea = $nuplet['annee_creation'];
                $mois = $nuplet['mois'];

                print "<tr class='ligneTabClic'>
                            <td onclick=\"location.href='course.php?idcourse=$id_course'\">$id_course</td>
                            <td onclick=\"location.href='course.php?idcourse=$id_course'\">$nom</td>
                            <td onclick=\"location.href='course.php?idcourse=$id_course'\">$annee_crea</td>
                            <td onclick=\"location.href='course.php?idcourse=$id_course'\">$mois</td>
                            <td class='delete'>
                                <form method='GET' action='courses.php' Onsubmit='return attention();'>
                                    <input name='delete_course' type='hidden' value='$id_course'>
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

            print "     </tbody>
                    </table>
                </div>
            </section>";
        }

        mysqli_close($connexion);
    }

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

<!-- Bouton d'ajout de course -->
<div class="container">
    <div class='row mb-4'>
        <button type="button" class="btn btn-primary mx-auto" data-toggle="modal" data-target="#modalAjout">
            Ajouter une course
        </button>
    </div>
</div>

<!-- Modal du formulaire d'ajout d'édition -->
<div class="modal fade" id="modalAjout" tabindex="-1" role="dialog" aria-labelledby="modalAjout" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="courses.php<?php print "?order=$order&orderSec=$orderSec&sens=$sens&clic=0" ?>">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="nom">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="anneeCrea">Année création </label>
                            <input type="text" class="form-control" id="anneeCrea" name="anneeCrea" maxlength="4" placeholder="AAAA" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="month">Mois</label>
                            <select class="custom-select" id="month" name="month" required>
                                <option selected> Mois... </option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="siteURL">Website : </label>
                            <input type="text" class="form-control" id="siteURL" name="siteURL" placeholder="https://www.sitedelacourse.fr/" required>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button class="btn btn-primary" type="submit">Ajouter Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonction de confirmation de suppression d'une course
    function attention()
    {
        resultat=window.confirm('Voulez-vous vraiment supprimer cette course ?');
        if (resultat==1)
        {
        }
        else
        {
            return false;
        }
    }
</script>

<?php include "includes/footer.php"; ?>