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

        //Affichage des éditions participées par l'adhérent
        if(isset($_GET['orderSec']))
        {

        }else if(isset($_GET['order']))
        {
            $order = $_GET['order'];

            $requete = "SELECT *
                    FROM adherent
                    ORDER BY " . $_GET['order'];
        }else{
            $requete = "SELECT *
                    FROM adherent";

            $order = "0";
        }

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert('Échec de la requête de récupération des édition courues par l'adhérent')</script>";
            //printf("Échec de la requête de récupération des édition courues par l'adhérent");
        else {

            print "<section class='listeEditionAdherent'>
                            <div class='container'>
                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <form method="POST">
                                            <th scope='col'><a href='?order=id_adherent&orderSec=$order'>Id</a></th>
                                            <th scope='col'><a href='?order=nom&orderSec=$order'>Nom</a></th>
                                            <th scope='col'><a href='?order=prenom&orderSec=$order'>Prénom</a></th>
                                            <th scope='col'><a href='?order=date_naissance&orderSec=$order'>Date de naissance</a></th>
                                            <th scope='col'><a href='?order=sexe&orderSec=$order'>Sexe</a></th>
                                            <th scope='col'><a href='?order=club&orderSec=$order'>Club</a></th>
                                        </tr>
                                    </thead>
                                    <tbody>";

            while ($nuplet = mysqli_fetch_assoc($resultat))
            {
                $id = $nuplet['id_adherent'];
                $nom = $nuplet['nom'];
                $prenom = $nuplet['prenom'];
                $dateNaissance = $nuplet['date_naissance'];
                $sexe = $nuplet['sexe'];
                $nomClub = $nuplet['club'];

                print "<tr>
                            <td>$id</td>
                            <td>$nom</td>
                            <td>$prenom Km</td>
                            <td>" . date('d/m/Y', strtotime($dateNaissance)) . "</td>
                            <td>$sexe</td>
                            <td>$nomClub</td>
                        </tr>";
            }

            print "             </tbody>
                            </table>
                        </div>
                    </section>";
        }
        
        mysqli_close($connexion);
    }

    include "includes/footer.php";
?>