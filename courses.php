<?php
include "includes/header.php";

$user = 'root';
$mdp = '';
$machine = 'localhost';
$bd = 'bdw1';
$connexion = mysqli_connect($machine, $user, $mdp, $bd);


if (mysqli_connect_errno()) // erreur si > 0
    printf("Échec de la connexion : %s", mysqli_connect_error());
else {

    if ((isset($_POST['nameCourse'])) && (isset($_POST['anneeCrea'])) && (isset($_POST['month'])))
    {
        $nameAdd = mysqli_real_escape_string($connexion, $_POST['nameCourse']);
        $anneeAdd = intval($_POST['anneeCrea']);
        $monthAdd = intval($_POST['month']);

        $ajoutTable = "INSERT INTO course (nom, annee_creation, mois)
                        VALUES('$nameAdd', $anneeAdd, $monthAdd);";

        if(mysqli_query($connexion, $ajoutTable) == FALSE)
            print "<script>alert(\"Échec de la requête de l'ajout de la course\")</script>";
    }

    if ((isset($_GET['idcourse'])))
    {
        $toDelete = intval($_GET['idcourse']);


        $requete = "SELECT DISTINCT co.id_course, ed.id_edition, ep.id_epreuve
                    FROM course co JOIN edition ed ON co.id_course = ed.id_course
                                   JOIN epreuve ep ON ep.id_edition = ed.id_edition
                                   JOIN participation pa ON pa.id_epreuve = ep.id_epreuve
                                   JOIN resultat re ON ep.id_epreuve = re.id_epreuve
                                   JOIN tarif ta ON ta.id_epreuve = ep.id_epreuve
                                   JOIN temps_passage tmp ON ep.id_epreuve = tmp.id_epreuve
                    WHERE $toDelete = co.id_course";
        
        $resultat = mysqli_query($connexion, $requete);

        while($nuplet = mysqli_fetch_assoc($resultat))
        {
            $idCourseRem = $nuplet['id_course'];
            $idEdRem = $nuplet['id_edition'];
            $idEpRem = $nuplet['id_epreuve'];

            $requete = "DELETE FROM course WHERE id_course = $idCourseRem";

            $resultatT = mysqli_query($connexion, $requete);
    
            $requete = "DELETE FROM edition WHERE id_course = $idCourseRem";
    
            $resultatT = mysqli_query($connexion, $requete);
    
            $requete = "DELETE FROM epreuve WHERE id_edition = $idEdRem";
    
            $resultatT = mysqli_query($connexion, $requete);
    
            $requete = "DELETE FROM participation WHERE id_epreuve = $idEpRem";
    
            $resultatT = mysqli_query($connexion, $requete);
    
            $requete = "DELETE FROM resultat WHERE id_epreuve = $idEpRem";
    
            $resultatT = mysqli_query($connexion, $requete);
    
            $requete = "DELETE FROM tarif WHERE id_epreuve = $idEpRem";
    
            $resultatT = mysqli_query($connexion, $requete);
    
            $requete = "DELETE FROM temps_passage WHERE id_epreuve = $idEpRem";
    
            $resultatT = mysqli_query($connexion, $requete);
        }





        

        //Ajout de supression des editions liées ?
        
        if(mysqli_query($connexion, $requete) == FALSE)
            print "<script>alert(\"Échec de la requête de suppression de la course\")</script>";
    }

    $requete = "SELECT * FROM course";

    $resultat = mysqli_query($connexion, $requete);

    if($resultat == FALSE)
        print "<script>alert('Échec de la requête de récupération des courses')</script>";
    else {

        print "<section class='listeCourses'>
            <div class='container'>
                <table class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>Id</th>
                            <th scope='col'>Nom</th>
                            <th scope='col'>Année création</th>
                            <th scope='col'>Mois</th>
                            <th scope='col'>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

        while ($nuplet = mysqli_fetch_assoc($resultat))
        {
            $id_course = $nuplet['id_course'];
            $nom = $nuplet['nom'];
            $annee_crea = $nuplet['annee_creation'];
            $mois = $nuplet['mois'];
            print "<tr>
                        <td>$id_course</td>
                        <td>$nom</td>
                        <td>$annee_crea</td>
                        <td>$mois</td>
                        <td>
                            <a href='editions.php?idcourse=$id_course'>Editions</a>
                            <span> / </span> 
                            <a href='courses.php?idcourse=$id_course'>Supprimer</a>
                        </td>
                    </tr>";
        }

        print "             </tbody>
                                </table>
                                </div>
                            </section>";
    }

    mysqli_close($connexion);
}
?>

<div class="container">
    <div class='row mb-4'>
        <button type="button" class="btn btn-primary mx-auto" data-toggle="modal" data-target="#modalAjoutCourse">
            Ajouter une course
        </button>
    </div>
</div>

<!-- Modal du formulaire d'ajout d'édition -->
<div class="modal fade" id="modalAjoutCourse" tabindex="-1" role="dialog" aria-labelledby="modalAjoutCourse" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="courses.php">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="nameCourse">Nom</label>
                            <input type="text" class="form-control" id="nameCourse" name="nameCourse" placeholder="Nom" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="anneeCrea">Année création </label>
                            <input type="text" class="form-control" id="anneeCrea" name="anneeCrea" placeholder="AAAA" required>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button class="btn btn-primary" type="submit">Ajouter Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>