<?php
    include "includes/header.php";

    //CONNEXION A LA BASE DE DONNEE
    $user = 'root';
    $mdp = '';
    $machine = 'localhost';
    $bd = 'bdw1';
    $connexion = mysqli_connect($machine, $user ,$mdp, $bd);

    if(mysqli_connect_errno())
        printf("Échec de la connexion : %s", mysqli_connect_error());
    else {
        if(isset($_SESSION['id_adherent']))
        {
            $idUser = intval($_SESSION['id_adherent']);
        }else if(isset($_GET['id_adherent'])){
            $idUser = intval($_GET['id_adherent']);
        }else{
            header('Location: http://localhost/projet-bdw1/404.php');
        }

        //TEST SI L'ADHERENT EST NOUVEAU
        $requete = "SELECT * FROM adherent WHERE id_adherent = $idUser";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête qui test si l'adherent est nouveau\")</script>";
        else {
            if(mysqli_num_rows($resultat) != 0)
            {
                $nouvelAdherent = 0;
            }else{
                $nouvelAdherent = 1;
            }
        }

        //AJOUT DES INFORMATIONS DU NOUVEL ADHERENT
        if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['sexe']))
        {
            $naNom = mysqli_real_escape_string($connexion, $_POST['nom']);
            $naPrenom = mysqli_real_escape_string($connexion, $_POST['prenom']);
            $naNaissance = ($_POST['naissance'] == NULL ? 'NULL' : "'".$_POST['naissance']."'");
            $naSexe = mysqli_real_escape_string($connexion, $_POST['sexe']);
            $naAdresse = mysqli_real_escape_string($connexion, $_POST['adresse']);
            $naDateClub = ($_POST['dateClub'] == NULL ? 'NULL' : "'".$_POST['dateClub']."'");
            $naNomClub = mysqli_real_escape_string($connexion, $_POST['nomClub']);

            if($nouvelAdherent)
            {
                $requete = "INSERT INTO adherent (id_adherent, nom, prenom, date_naissance, sexe, adresse, date_certif_club, club)
                            VALUES ('$idUser', '$naNom', '$naPrenom', $naNaissance, '$naSexe', '$naAdresse', $naDateClub, '$naNomClub')";

                $alert = "<script>alert('Échec de la requête de l'ajout des nouvelles informations)</script>";
            }else{
                $requete = "UPDATE adherent
                            SET nom = '$naNom', prenom = '$naPrenom', date_naissance = $naNaissance, sexe = '$naSexe', adresse = '$naAdresse', date_certif_club = $naDateClub, club = '$naNomClub'
                            WHERE id_adherent = $idUser";

                $alert = "<script>alert('Échec de la requête de modification des informations')</script>";
            }

            $resultat = mysqli_query($connexion, $requete);

            if($resultat == FALSE){
                print $alert;
            }
        }

        //RECUPERATION ET AFFICHAGE DES INFORMATIONS DE L'ADHERENT
        $requete = "SELECT * FROM adherent WHERE id_adherent = $idUser";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération des informations d'adhérent\")</script>";
        else {
            if(mysqli_num_rows($resultat) != 0)
            {
                while ($nuplet = mysqli_fetch_assoc($resultat))
                {
                    $nom = $nuplet['nom'];
                    $prenom = $nuplet['prenom'];
                    $dateNaissance = $nuplet['date_naissance'];
                    $sexe = $nuplet['sexe'];
                    $adresse = $nuplet['adresse'];
                    $dateClub = $nuplet['date_certif_club'];
                    $nomClub = $nuplet['club'];

                    $nouvelAdherent = 0;
                }
            }else{
                $nom = "";
                $prenom = "";
                $dateNaissance = "";
                $sexe = "F";
                $adresse = "";
                $dateClub = "";
                $nomClub = "";
            }

            if($sexe == "H")
            {
                $selectSexe = "<option value='H' selected='selected'>H</option>
                                <option value='F'>F</option>";
            }else{
                $selectSexe = "<option value='H''>H</option>
                                <option value='F' selected='selected'>F</option>";
            }

            if($nouvelAdherent) print "<p class='textNouvelAdherent'>Nouveau compte, veuillez renseigner les champs marqués d'une *</p>";

            print "<section class='adherent'>
                        <h2>Informations personnelles</h2>
                        <div class='adherentInfos container'>
                            <div id='adherentInfosBloc' class='adherentInfosBloc container mx-auto col-8 mw-50'>
                                <form action='' method='POST'>
                                    <div class='row ligneInfo'>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Nom* :</p>
                                            <p id='nomAdherent' class='readInfoAdherent'>$nom</p>
                                            <input type='text' id='nomAdherentInput' class='form-control writeInfoAdherent' name='nom' value=\"$nom\" placeholder='Nom' required>
                                        </div>
                                        <div class='col-4'></div>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Prenom* :</p>
                                            <p id='prenomAdherent' class='readInfoAdherent'>$prenom</p>
                                            <input type='text' id='prenomAdherentInput' class='form-control writeInfoAdherent' name='prenom' value=\"$prenom\" placeholder='Prenom' required>
                                        </div>
                                    </div>
                                    <div class='row ligneInfo'>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Date de naissance :</p>
                                            <p id='naissanceAdherent' class='readInfoAdherent'>" . ($dateNaissance == NULL ? "" : date('d/m/Y', strtotime($dateNaissance))) . "</p>
                                            <input type='date' id='naissanceAdherentInput' class='form-control writeInfoAdherent' name='naissance' value=\"$dateNaissance\">
                                        </div>
                                        <div class='col-4'></div>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Sexe* :</p>
                                            <p id='sexeAdherent' class='readInfoAdherent'>$sexe</p>
                                            <select id='sexeAdherentInput' class='custom-select writeInfoAdherent' name='sexe' required>
                                                $selectSexe
                                            </select>
                                        </div>
                                    </div>
                                    <div class='row ligneInfo'>
                                        <div class='col-6'>
                                            <p class='nomInfo'>Adresse :</p>
                                            <p id='adresseAdherent' class='readInfoAdherent'>$adresse</p>
                                            <input type='text' id='adresseAdherentInput' class='form-control writeInfoAdherent' name='adresse' value=\"$adresse\" placeholder='Adresse'>
                                        </div>
                                    </div>
                                    <div class='row ligneInfo'>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Date de certification du club :</p>
                                            <p id='dateClubAdherent' class='readInfoAdherent'>" . ($dateClub == NULL ? "" : date('d/m/Y', strtotime($dateClub))) . "</p>
                                            <input type='date' id='dateClubAdherentInput' class='form-control writeInfoAdherent' name='dateClub' value=\"$dateClub\">
                                        </div>
                                        <div class='col-4'></div>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Nom du club :</p>
                                            <p id='nomClubAdherent' class='readInfoAdherent'>$nomClub</p>
                                            <input type='text' id='nomClubAdherentInput' class='form-control writeInfoAdherent' name='nomClub' value=\"$nomClub\" placeholder='Nom du club'>
                                        </div>
                                    </div>
                                    <div class='row ligneButton readInfoAdherent readInfoAdherentFlex' id='modifInfoAdherent'>
                                        
                                        <button type='button' class='btn btn-primary mx-auto'>Modifier</button>
                                        
                                    </div>
                                    <div class='row ligneButton writeInfoAdherent writeInfoAdherentFlex' id='modifInfoAdherent'>
                                        <div class='row mx-auto'>
                                            <button type='submit' id='btnSbmtInfoAdh' class='btn btn-primary col-5'>Valider</button>
                                            <div id='separationBouton' class='col-1'></div>
                                            <button type='button' id='annulerInfoAdherent' class='btn btn-primary col-5'>Annuler</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>";
        }

        //RECUPERATION ET AFFICHAGE DES EDITIONS PARTICIPEES PAR L'ADHERENT
        $requete = "SELECT Co.nom, year(Ed.date) AS annee, Ep.distance, Tp.temps AS temps
                    FROM (SELECT Pa.* FROM participation Pa WHERE Pa.id_adherent = $idUser) AS Part
                            NATURAL JOIN epreuve Ep
                            NATURAL JOIN edition Ed
                            JOIN COURSE Co ON Ed.id_course = Co.id_course
                            JOIN (SELECT id_epreuve, dossard, MAX(temps) AS temps
                                FROM temps_passage GROUP BY id_epreuve, dossard) AS Tp ON Tp.id_epreuve = Part.id_epreuve AND Tp.dossard = Part.dossard";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération des édition courues par l'adhérent\")</script>";
        else {

            print "<section id='listeEditionAdherent' class='listeEditionAdherent'>
                            <h2>Liste des éditions participées</h2>
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

            while ($nuplet = mysqli_fetch_assoc($resultat))
            {
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
        
        mysqli_close($connexion);
    }

    include "includes/footer.php";
?>

<script>
    //GESTION DE L'AFFICHAGE DU FORMULAIRE DE MODIFICATION DES INFORMATIONS DE L'ADHERENT
    document.getElementById("modifInfoAdherent").onclick = afficheFormAdherent;
    document.getElementById("annulerInfoAdherent").onclick = annulerFormAdherent;

    const nom = document.getElementById('nomAdherentInput').value;
    const prenom = document.getElementById('prenomAdherentInput').value;
    const naissance = document.getElementById('naissanceAdherentInput').value;
    const sexe = document.getElementById('sexeAdherentInput').value;
    const adresse = document.getElementById('adresseAdherentInput').value;
    const dateClub = document.getElementById('dateClubAdherentInput').value;
    const nomClub = document.getElementById('nomClubAdherentInput').value;

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

<?php
    if($nouvelAdherent){
        print "<script>";
        print "     afficheFormAdherent();";
        print "     document.getElementById('annulerInfoAdherent').style.display = 'none';";
        print "     document.getElementById('separationBouton').style.display = 'none';";
        print "     document.getElementById('btnSbmtInfoAdh').classList.remove('col-5');";
        print "     document.getElementById('listeEditionAdherent').style.display = 'none';";
        print "</script>";
    }
?>