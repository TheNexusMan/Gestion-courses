<?php
    session_start();
    include "./header.php";

    $user = 'root' ;
    $mdp = '' ;
    $machine = 'localhost' ;
    $bd = 'bdw1' ;
    $connexion = mysqli_connect($machine,$user,$mdp, $bd);

    if(mysqli_connect_errno()) // erreur si > 0
        printf("Échec de la connexion : %s", mysqli_connect_error());
    else {
        $idUser = 2016008;

        $requete = "SELECT *
                    FROM adherent
                    WHERE id_adherent = $idUser";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            printf("Échec de la requête");
        else {
            //Affichage des informations de l'adhérent
            while ($nuplet = mysqli_fetch_assoc($resultat)) {
                $nom = $nuplet['nom'];
                $prenom = $nuplet['prenom'];
                $dateNaissance = $nuplet['date_naissance'];
                $sexe = $nuplet['sexe'];
                $adresse = $nuplet['adresse'];
                $dateClub = $nuplet['date_certif_club'];
                $nomClub = $nuplet['club'];
                print "<section class='adherent'>
                            <div class='adherentInfos container'>
                                <div class='adherentInfosBloc container mx-auto col-8 mw-50'>
                                    <div class='row ligneInfo'>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Nom :</p>
                                            <p>$nom</p>
                                        </div>
                                        <div class='col-4'></div>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Prenom :</p>
                                            <p>$prenom</p>
                                        </div>
                                    </div>
                                    <div class='row ligneInfo'>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Naissance :</p>
                                            <p>$dateNaissance</p>
                                        </div>
                                        <div class='col-4'></div>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Sexe :</p>
                                            <p>$sexe</p>
                                        </div>
                                    </div>
                                    <div class='row ligneInfo'>
                                        <div class='col-8'>
                                            <p class='nomInfo'>Adresse :</p>
                                            <p>$adresse</p>
                                        </div>
                                    </div>
                                    <div class='row ligneInfo'>
                                        <div class='col-4'>
                                            <p class='nomInfo'>dateClub :</p>
                                            <p>$dateClub</p>
                                        </div>
                                        <div class='col-4'></div>
                                        <div class='col-4'>
                                            <p class='nomInfo'>nomClub :</p>
                                            <p>$nomClub</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>";
            }

            //Affichage des éditions participées par l'adhérent
            $requete = "SELECT Co.nom, year(Ed.date) AS annee, Ep.distance, Tp.temps AS temps
                        FROM (SELECT Pa.* FROM participation Pa WHERE Pa.id_adherent = 2016008) AS Part
                                NATURAL JOIN epreuve Ep
                                NATURAL JOIN edition Ed
                                JOIN COURSE Co ON Ed.id_course = Co.id_course
                                JOIN (SELECT  dossard, MAX(temps) AS temps, id_participation
                                    FROM temps_passage GROUP BY id_participation) AS Tp ON Tp.id_participation = Part.id_participation";

            $resultat = mysqli_query($connexion, $requete);

            print "<section class='listeEditionAdherent'>
                            <div class='container'>
                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <th scope='col'>Année</th>
                                            <th scope='col'>Distance</th>
                                            <th scope='col'>Nom</th>
                                            <th scope='col'>Temps</th>
                                        </tr>
                                    </thead>
                                    <tbody>";

            while ($nuplet = mysqli_fetch_assoc($resultat)) {
                $nom = $nuplet['nom'];
                $annee = $nuplet['annee'];
                $distance = $nuplet['distance'];
                $temps = $nuplet['temps'];
                print "<tr>
                            <td>$annee</td>
                            <td>$distance</td>
                            <td>$nom</td>
                            <td>$temps</td>
                        </tr>";
            }

            print "             </tbody>
                            </table>
                        </div>
                    </section>";
        }
    }
    mysqli_close($connexion);

    include "./footer.php";
?>