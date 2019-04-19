<?php
    include "includes/header.php";
    
    $user = 'root' ;
    $mdp = '' ;
    $machine = 'localhost' ;
    $bd = 'bdw1' ;
    $connexion = mysqli_connect($machine,$user,$mdp, $bd);


    if(mysqli_connect_errno()) // erreur si > 0
    printf("Échec de la connexion : %s", mysqli_connect_error());
    else {

        if((isset($_POST['nameCourse'])) && (isset($_POST['anneeCrea'])) && (isset($_POST['month'])))
        {
            $nameAdd = $_POST['nameCourse'];
            $anneeAdd = $_POST['anneeCrea'];
            $monthAdd = $_POST['month'];



            $ajoutTable = "INSERT INTO course (nom, annee_creation, mois)
                           VALUES('$nameAdd', $anneeAdd , $monthAdd);";



            mysqli_query($connexion, $ajoutTable);

        }

        $requete = "SELECT *
                    FROM course";


        $resultat = mysqli_query($connexion, $requete);

        print "<section class='listeEditionAdherent'>
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

        while ($nuplet = mysqli_fetch_assoc($resultat)) {
            $id_course = $nuplet['id_course'];
            $nom = $nuplet['nom'];
            $annee_crea = $nuplet['annee_creation'];
            $mois = $nuplet['mois'];
            print "<tr>
                        <td>$id_course</td>
                        <td>$nom</td>
                        <td>$annee_crea</td>
                        <td>$mois</td>";
            print '<td><div class="d-flex flex-row bd-highlight">';
            print ' <div class="p-2 bd-highlight"><form method="POST" action="editions.php">';
            print '<button type="submit" class="btn btn-outline-success" id="$edit" name="$edit">Edit</button></form></div></div></td>
                </tr>';
            
                }


            print "             </tbody>
                            </table>";
    }






?>


<form method="POST" action="courses.php">
    <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="nameCourse">Nom Course</label>
                <input type="text" class="form-control" id="nameCourse" name="nameCourse" placeholder="Nom"  required>
                <div class="valid-feedback">
                     Looks good!
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="anneeCrea"> Année création </label>
                <input type="text" class="form-control" id="anneeCrea" name="anneeCrea" placeholder="AAAA"  required>
            <div class="valid-feedback">
                 Looks good!
            </div>
           </div>
            <div class="col-md-4 mb-3">
                <label for="month"> Mois </label>
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
    <button class="btn btn-primary" type="submit">Ajouter Course</button>
</form>

                    </div>
            </section>



<?php
    mysqli_close($connexion);
    include "includes/footer.php";
?>