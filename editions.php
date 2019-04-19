<?php
    include "includes/header.php";
    
    $user = 'root' ;
    $mdp = '' ;
    $machine = 'localhost' ;
    $bd = 'bdw1' ;
    $connexion = mysqli_connect($machine,$user,$mdp, $bd);


   if(isset($_GET['idcourse']))
   {
       $idCourse = $_GET['idcourse'];
   }
    //créer un bouton pour changer le sexe choisis


    if(isset($_POST['genderSelect']))
    {
        $chosenSex = $_POST['genderSelect'];
      //  print $_POST['genderSelect'] . "Valeur apres form";
    }else
    {
        $chosenSex = "H";
      //  print $chosenSex . " Valeur par défaut";
    }

    if(isset($_POST['$id_course']))
    {
       // $idCourse = $_POST['$id_course'];
        print $_POST['$id_course'];
    }else
    {
        
    }
  
    if($chosenSex == "H")
    {
        print "<div class='container'>
                    Sexe sélectionnée : Hommes
                <div>";
    } else
    {
        print "<div class='container'>
                 Sexe sélectionnée : Femmes
                <div>";
    }


    if(mysqli_connect_errno()) // erreur si > 0
    printf("Échec de la connexion : %s", mysqli_connect_error());
    else {


        $requete = "SELECT ed.annee, ed.nb_participants, MIN(tmp.temps) AS bestScore, COUNT(DISTINCT adh.club) AS nb_club, AVG(tmp.temps) AS moyenne, ed.id_course
                    FROM edition ed JOIN epreuve ep ON ed.id_edition = ep.id_edition 
                                    JOIN temps_passage tmp ON tmp.id_epreuve = ep.id_epreuve
                                    JOIN participation pa ON pa.dossard = tmp.dossard
                                    JOIN adherent adh ON pa.id_adherent = adh.id_adherent
                                    JOIN resultat res ON res.id_epreuve = ep.id_epreuve
                    WHERE ed.id_course = $idCourse AND adh.sexe = '$chosenSex'
                    GROUP BY ed.annee";
        //A definir $idCourse pour savoir quelle course est selectionée
        //Permet d'avoir la moyenne de temps en fonction du sexe (H = Homme, F = Femmes)

        $resultat = mysqli_query($connexion, $requete);
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

        while ($nuplet = mysqli_fetch_assoc($resultat)) {
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
                        <td>$nb_club</td>;
                        <td>$moyenneTmp min</td>
                    </tr>";
            // print '<td><div class="d-flex flex-row bd-highlight">';
            // print ' <div class="p-2 bd-highlight">';
            // print '<button type="button" class="btn btn-outline-success" id="edit">Edit</button></div></div></td>
            //     </tr>';
            
        

        }


    }





?>
                    </tbody>
                </table>
                </div>
                
        <form method="POST" action="editions.php">
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

<section class="formulaireAjout">
        <form method="POST" action="editions.php">
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
                        <input type="date" class="form-control" id="dateIns" name="dateIns"  required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="dateDepot">Date Depot Certif : </label>
                        <input type="date" class="form-control" id="dateDepot" name="dateDepot"  required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="dateDossard">Date Recup Dossard : </label>
                        <input type="date" class="form-control" id="dateDossard" name="dateDossard"  required>
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







        <button class="btn btn-primary" type="submit">Ajouter Edition</button>


        </form>
</section>





<?php




    mysqli_close($connexion);

    include "includes/footer.php";
?>