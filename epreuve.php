<?php 

    //Pages affichant une épreuve et toutes les stats nécessaires a cette épreuve
    // -> Nb total adhérent / nb de licnesies / nombre de clubs / temps vainqueur/ meilleur et pire temps par un adhérent de l'assoc / moyenne de temps
    
    
    
    //Tous les adhérents avec leurs temps dans un tableau (rétractable)
    //nbr d'abandons de la course -> rang == NULL ?
include "includes/header.php";


print "FUCK"; 


$requete = "SELECT res.nom, res.prenom, res.rang, res.sexe, tmp.temps, adr.club
            FROM epreuve ep JOIN resultat res ON ep.id_epreuve = res.id_epreuve
                            JOIN temps_passage tmp ON ep.id_epreuve = tmp.id_epreuve
                            JOIN participation pa ON pa.id_epreuve = ep.id_epreuve
                            JOIN adherent adr ON adr.id_adherent = pa.id_adherent
            WHERE ep.id_epreuve = 4 AND adr.nom = res.nom
            GROUP BY res.nom";

    //Permet d'avoir la moyenne de temps en fonction du sexe (H = Homme, F = Femmes)

    $resultat = mysqli_query($connexion, $requete);

    if ($resultat == FALSE)
        print "<script>alert(\"BITEUH\")</script>";
    else {
        print "<section class='liste'>
            <div class='container'>
                <table class='table'>
                    <thead>
                        <tr>
                            <th scope='col'>Nom </th>
                            <th scope='col'>Prénom </th>
                            <th scope='col'>Sexe </th>
                            <th scope='col'> Temps</th>
                            <th scope='col'>Rang </th>
                            <th scope='col'>Club</th>
                        </tr>
                    </thead>
                <tbody>";

        while ($nuplet = mysqli_fetch_assoc($resultat)) {
                //$annee = $nuplet['annee'];


                print "<tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>

                        </tr>";
            }
    }

    mysqli_close($connexion);





include "includes/footer.php";
?>