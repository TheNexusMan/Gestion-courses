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
        $idUser = $_SESSION['id_adherent'];

        if(isset($_POST['nom']))
        {
            $requete = 'UPDATE adherent
                        SET nom = "' . $_POST['nom'] . '", prenom = "' . $_POST['prenom'] . '", date_naissance = "' . $_POST['naissance'] . '", sexe = "' . $_POST['sexe'] . '", adresse = "' . $_POST['adresse'] . '", date_certif_club = "' . $_POST['dateClub'] . '", club = "' . $_POST['nomClub'] . '"
                        WHERE id_adherent = ' . $idUser;

            $resultat = mysqli_query($connexion, $requete);

            if($resultat == FALSE){
                print "<script>alert('Échec de la requête de mise à jour')</script>";
                //printf("Échec de la requête de mise à jour");
            }
        }

        $requete = "SELECT *
                    FROM adherent
                    WHERE id_adherent = $idUser";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert('Échec de la requête de récupération des informations d'adhérent')</script>";
            //printf("Échec de la requête de récupération des informations d'adhérent");
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

                if($sexe == "H")
                {
                    $selectSexe = "<option value='H' selected='selected'>H</option>
                                    <option value='F'>F</option>";
                }else{
                    $selectSexe = "<option value='H''>H</option>
                                    <option value='F' selected='selected'>F</option>";
                }

                print "<section class='adherent'>
                            <div class='adherentInfos container'>
                                <div id='adherentInfosBloc' class='adherentInfosBloc container mx-auto col-8 mw-50'>
                                    <form action='' method='POST'>
                                        <div class='row ligneInfo'>
                                            <div class='col-4'>
                                                <p class='nomInfo'>Nom :</p>
                                                <p id='nomAdherent' class='readInfoAdherent'>$nom</p>
                                                <input type='text' id='nomAdherentInput' class='writeInfoAdherent' name='nom' value='$nom'>
                                            </div>
                                            <div class='col-4'></div>
                                            <div class='col-4'>
                                                <p class='nomInfo'>Prenom :</p>
                                                <p id='prenomAdherent' class='readInfoAdherent'>$prenom</p>
                                                <input type='text' id='prenomAdherentInput' class='writeInfoAdherent' name='prenom' value='$prenom'>
                                            </div>
                                        </div>
                                        <div class='row ligneInfo'>
                                            <div class='col-4'>
                                                <p class='nomInfo'>Naissance :</p>
                                                <p id='naissanceAdherent' class='readInfoAdherent'>$dateNaissance</p>
                                                <input type='date' id='naissanceAdherentInput' class='writeInfoAdherent' name='naissance' value='$dateNaissance'>
                                            </div>
                                            <div class='col-4'></div>
                                            <div class='col-4'>
                                                <p class='nomInfo'>Sexe :</p>
                                                <p id='sexeAdherent' class='readInfoAdherent'>$sexe</p>
                                                <select id='sexeAdherentInput' class='writeInfoAdherent' name='sexe'>
                                                    $selectSexe
                                                </select>
                                            </div>
                                        </div>
                                        <div class='row ligneInfo'>
                                            <div class='col-8'>
                                                <p class='nomInfo'>Adresse :</p>
                                                <p id='adresseAdherent' class='readInfoAdherent'>$adresse</p>
                                                <input type='text' id='adresseAdherentInput' class='writeInfoAdherent' name='adresse' value='$adresse'>
                                            </div>
                                        </div>
                                        <div class='row ligneInfo'>
                                            <div class='col-4'>
                                                <p class='nomInfo'>dateClub :</p>
                                                <p id='dateClubAdherent' class='readInfoAdherent'>$dateClub</p>
                                                <input type='date' id='dateClubAdherentInput' class='writeInfoAdherent' name='dateClub' value='$dateClub'>
                                            </div>
                                            <div class='col-4'></div>
                                            <div class='col-4'>
                                                <p class='nomInfo'>nomClub :</p>
                                                <p id='nomClubAdherent' class='readInfoAdherent'>$nomClub</p>
                                                <input type='text' id='nomClubAdherentInput' class='writeInfoAdherent' name='nomClub' value='$nomClub'>
                                            </div>
                                        </div>
                                        <div class='row ligneButton readInfoAdherent readInfoAdherentFlex' id='modifInfoAdherent'>
                                            
                                            <button type='button' class='btn btn-primary mx-auto'>Modifier</button>
                                            
                                        </div>
                                        <div class='row ligneButton writeInfoAdherent writeInfoAdherentFlex' id='modifInfoAdherent'>
                                            <div class='row mx-auto'>
                                                <button type='submit' class='btn btn-primary col-5'>Valider</button>
                                                <div class='col-1'></div>
                                                <button type='button' id='annulerInfoAdherent' class='btn btn-primary col-5'>Annuler</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>";
            }

            //Affichage des éditions participées par l'adhérent
            $requete = "SELECT Co.nom, year(Ed.date) AS annee, Ep.distance, Tp.temps AS temps
                        FROM (SELECT Pa.* FROM participation Pa WHERE Pa.id_adherent = $idUser) AS Part
                                NATURAL JOIN epreuve Ep
                                NATURAL JOIN edition Ed
                                JOIN COURSE Co ON Ed.id_course = Co.id_course
                                JOIN (SELECT id_epreuve, dossard, MAX(temps) AS temps
                                    FROM temps_passage GROUP BY id_epreuve, dossard) AS Tp ON Tp.id_epreuve = Part.id_epreuve AND Tp.dossard = Part.dossard";

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
                            <td>$distance Km</td>
                            <td>$nom</td>
                            <td>$temps min</td>
                        </tr>";
            }

            print "             </tbody>
                            </table>
                        </div>
                    </section>";
        }
    }
    mysqli_close($connexion);

    include "includes/footer.php";
?>

<script>
    document.getElementById("modifInfoAdherent").onclick = afficheFormAdherent;
    document.getElementById("annulerInfoAdherent").onclick = annulerFormAdherent;

    const nom = document.getElementById('nomAdherent').innerHTML;
    const prenom = document.getElementById('prenomAdherent').innerHTML;
    const naissance = document.getElementById('naissanceAdherent').innerHTML;
    const sexe = document.getElementById('sexeAdherent').innerHTML;
    const adresse = document.getElementById('adresseAdherent').innerHTML;
    const dateClub = document.getElementById('dateClubAdherent').innerHTML;
    const nomClub = document.getElementById('nomClubAdherent').innerHTML;

    function afficheFormAdherent()
    {
        Array.from(document.getElementsByClassName('writeInfoAdherent')).forEach(n => n.style.display = "inline-block");
        Array.from(document.getElementsByClassName('writeInfoAdherentFlex')).forEach(n => n.style.display = "flex");
        Array.from(document.getElementsByClassName('readInfoAdherent')).forEach(n => n.style.display = "none");
    }

    function annulerFormAdherent()
    {
        Array.from(document.getElementsByClassName('writeInfoAdherent')).forEach(n => n.style.display = "none");
        Array.from(document.getElementsByClassName('readInfoAdherent')).forEach(n => n.style.display = "inline-block");
        Array.from(document.getElementsByClassName('readInfoAdherentFlex')).forEach(n => n.style.display = "flex");

        document.getElementById('nomAdherentInput').value = nom;
        document.getElementById('prenomAdherentInput').value = prenom;
        document.getElementById('naissanceAdherentInput').value = naissance;
        document.getElementById('sexeAdherentInput').value = sexe;
        document.getElementById('adresseAdherentInput').value = adresse;
        document.getElementById('dateClubAdherentInput').value = dateClub;
        document.getElementById('nomClubAdherentInput').value = nomClub;
    }
</script>