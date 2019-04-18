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


        $requete = "SELECT ed.annee, ed.nb_participants, MIN(tmp.temps) AS bestScore, COUNT(DISTINCT adh.club) AS nb_club, AVG(tmp.temps) AS moyenne
                    FROM edition ed JOIN epreuve ep ON ed.id_edition = ep.id_edition 
                                    JOIN temps_passage tmp ON tmp.id_epreuve = ep.id_epreuve
                                    JOIN participation pa ON pa.dossard = tmp.dossard
                                    JOIN adherent adh ON pa.id_adherent = adh.id_adherent
                                    JOIN resultat res ON res.id_epreuve = ep.id_epreuve
                    WHERE ed.id_course = 1 AND adh.sexe = 'H'
                    GROUP BY ed.annee ";
        //A definir $idCourse pour savoir quelle course est selectionée
        //Permet d'avoir la moyenne de temps en fonction du sexe (H = Homme, F = Femmes)

        $resultat = mysqli_query($connexion, $requete);

        print "<section class='listeEditionAdherent'>
        <div class='container'>
            <table class='table'>
                <thead>
                    <tr>
                        <th scope='col'>Annee</th>
                        <th scope='col'>Nombre de participants Total</th>
                        <th scope='col'>Meilleur temps</th>
                        <th scope='col'>Nombre de club représenté</th>
                    </tr>
                </thead>
                <tbody>";

        while ($nuplet = mysqli_fetch_assoc($resultat)) {
            $annee = $nuplet['annee'];
            $nb_participants = $nuplet['nb_participants'];
            $meilleur_temps = $nuplet['bestScore'];
            $nb_club = $nuplet['nb_club'];
            $moyenneTmp = $nuplet['moyenne'];

            

            print "<tr>
                        <td>$annee</td>
                        <td>$nb_participants</td>
                        <td>$meilleur_temps min</td>
                        <td>$nb_club</td>;
                    </tr>"
            // print '<td><div class="d-flex flex-row bd-highlight">';
            // print ' <div class="p-2 bd-highlight">';
            // print '<button type="button" class="btn btn-outline-success" id="edit">Edit</button></div></div></td>
            //     </tr>';
            
                }


            print "             </tbody>
                            </table>
                        </div>
                </section>";
    }
    mysqli_close($connexion);












    include "includes/footer.php";
?>