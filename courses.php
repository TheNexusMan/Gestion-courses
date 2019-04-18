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
            print ' <div class="p-2 bd-highlight">';
            print '<button type="button" class="btn btn-outline-success" id="edit">Edit</button></div></div></td>
                </tr>';
            
                }


            print "             </tbody>
                            </table>
                        </div>
                </section>";
    }
    mysqli_close($connexion);












    include "includes/footer.php";
?>