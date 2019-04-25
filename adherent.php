<?php
    session_start();

    // Récupération de l'id_adherent, redirection si non existant
    if(isset($_SESSION['id_adherent']))
    {
        $idUser = intval($_SESSION['id_adherent']);
    }else if(isset($_GET['id_adherent'])){
        $idUser = intval($_GET['id_adherent']);
    }else{
        header('Location: http://localhost/projet-bdw1/index.php');
    }

    include "includes/header.php";

    if(mysqli_connect_errno())
        printf("Échec de la connexion : %s", mysqli_connect_error());
    else {

        // Test si l'adhérent est nouveau
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

        // Modification des informations de l'adhérent ou ajout des informations du nouvel adhérent
        if(isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['sexe']))
        {
            $modNom = mysqli_real_escape_string($connexion, $_POST['nom']);
            $modPrenom = mysqli_real_escape_string($connexion, $_POST['prenom']);
            $modNaissance = mysqli_real_escape_string($connexion, $_POST['naissance']);
            $modNaissance = ($_POST['naissance'] == NULL ? 'NULL' : "'".$modNaissance."'");
            $modSexe = mysqli_real_escape_string($connexion, $_POST['sexe']);
            $modAdresse = mysqli_real_escape_string($connexion, $_POST['adresse']);
            $modDateClub = mysqli_real_escape_string($connexion, $_POST['dateClub']);
            $modDateClub = ($_POST['dateClub'] == NULL ? 'NULL' : "'".$modDateClub."'");
            $modNomClub = mysqli_real_escape_string($connexion, $_POST['nomClub']);

            if($nouvelAdherent)
            {
                $requete = "INSERT INTO adherent (id_adherent, nom, prenom, date_naissance, sexe, adresse, date_certif_club, club)
                            VALUES ('$idUser', '$modNom', '$modPrenom', $modNaissance, '$modSexe', '$modAdresse', $modDateClub, '$modNomClub')";

                $alert = "<script>alert('Échec de la requête de l'ajout des nouvelles informations)</script>";
            }else{
                $requete = "UPDATE adherent
                            SET nom = '$modNom', prenom = '$modPrenom', date_naissance = $modNaissance, sexe = '$modSexe', adresse = '$modAdresse', date_certif_club = $modDateClub, club = '$modNomClub'
                            WHERE id_adherent = $idUser";

                $alert = "<script>alert('Échec de la requête de modification des informations')</script>";
            }

            if(mysqli_query($connexion, $requete) == FALSE){
                print $alert;
            }
        }

        // Récupération et affichage des informations de l'adhérent
        $requete = "SELECT * FROM adherent WHERE id_adherent = $idUser";

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération des informations d'adhérent\")</script>";
        else {
            if(mysqli_num_rows($resultat) != 0)
            {
                $nuplet = mysqli_fetch_assoc($resultat);

                $nom = $nuplet['nom'];
                $prenom = $nuplet['prenom'];
                $dateNaissance = $nuplet['date_naissance'];
                $sexe = $nuplet['sexe'];
                $adresse = $nuplet['adresse'];
                $dateClub = $nuplet['date_certif_club'];
                $nomClub = $nuplet['club'];

                $nouvelAdherent = 0;
                
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
                $selectSexe = "<option value='H' selected='selected'>Homme</option>
                                <option value='F'>Femme</option>";
            }else{
                $selectSexe = "<option value='H''>Homme</option>
                                <option value='Femme' selected='selected'>Femme</option>";
            }

            if($nouvelAdherent) print "<p class='textNouvelAdherent'>Nouveau compte, veuillez renseigner les champs marqués d'une *</p>";

            print "<section class='sectionInfos'>
                        <h2>Informations personnelles</h2>
                        <div class='info container'>
                            <div id='infosBloc' class='infosBloc container mx-auto col-8 mw-50'>
                                <form action='' method='POST'>
                                    <div class='row ligneInfo'>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Nom :</p>
                                            <p class='readInfo'>$nom</p>
                                            <input type='text' id='nomInput' class='form-control writeInfo' name='nom' value=\"$nom\" placeholder='Nom' required>
                                        </div>
                                        <div class='col-4'></div>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Prénom :</p>
                                            <p class='readInfo'>$prenom</p>
                                            <input type='text' id='prenomInput' class='form-control writeInfo' name='prenom' value=\"$prenom\" placeholder='Prenom' required>
                                        </div>
                                    </div>
                                    <div class='row ligneInfo'>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Date de naissance :</p>
                                            <p class='readInfo'>" . ($dateNaissance == NULL ? "" : date('d/m/Y', strtotime($dateNaissance))) . "</p>
                                            <input type='date' id='naissanceInput' class='form-control writeInfo' name='naissance' value=\"$dateNaissance\">
                                        </div>
                                        <div class='col-4'></div>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Sexe :</p>
                                            <p class='readInfo'>" . ($sexe == 'H' ? 'Homme' : 'Femme') . "</p>
                                            <select id='sexeInput' class='custom-select writeInfo' name='sexe' required>
                                                $selectSexe
                                            </select>
                                        </div>
                                    </div>
                                    <div class='row ligneInfo'>
                                        <div class='col-6'>
                                            <p class='nomInfo'>Adresse :</p>
                                            <p class='readInfo'>$adresse</p>
                                            <input type='text' id='adresseInput' class='form-control writeInfo' name='adresse' value=\"$adresse\" placeholder='Adresse'>
                                        </div>
                                    </div>
                                    <div class='row ligneInfo'>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Date de certification du club :</p>
                                            <p class='readInfo'>" . ($dateClub == NULL ? "" : date('d/m/Y', strtotime($dateClub))) . "</p>
                                            <input type='date' id='dateClubInput' class='form-control writeInfo' name='dateClub' value=\"$dateClub\">
                                        </div>
                                        <div class='col-4'></div>
                                        <div class='col-4'>
                                            <p class='nomInfo'>Nom du club :</p>
                                            <p class='readInfo'>$nomClub</p>
                                            <input type='text' id='nomClubInput' class='form-control writeInfo' name='nomClub' value=\"$nomClub\" placeholder='Nom du club'>
                                        </div>
                                    </div>
                                    <div class='row ligneButton readInfo readInfoFlex' id='modifInfo'>
                                        <button type='button' class='btn btn-primary mx-auto'>Modifier</button>
                                    </div>
                                    <div class='row ligneButton writeInfo writeInfoFlex' id='modifInfo'>
                                        <div class='row mx-auto'>
                                            <button type='submit' id='btnSbmtInfoAdh' class='btn btn-primary col-5'>Valider</button>
                                            <div id='separationBouton' class='col-1'></div>
                                            <button type='button' id='annulerInfo' class='btn btn-primary col-5'>Annuler</button>
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

            print "<section id='liste' class='liste'>
                        <h2 class='tableLabel'>Liste des éditions participées</h2>
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

            while($nuplet = mysqli_fetch_assoc($resultat))
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
    // Gestion de l'affichage du formulaire de modification des informations de l'adhérent

    document.getElementById("modifInfo").onclick = afficheForm;
    document.getElementById("annulerInfo").onclick = annulerForm;

    const nom = document.getElementById('nomInput').value;
    const prenom = document.getElementById('prenomInput').value;
    const naissance = document.getElementById('naissanceInput').value;
    const sexe = document.getElementById('sexeInput').value;
    const adresse = document.getElementById('adresseInput').value;
    const dateClub = document.getElementById('dateClubInput').value;
    const nomClub = document.getElementById('nomClubInput').value;

    function afficheForm()
    {
        Array.from(document.getElementsByClassName('writeInfo')).forEach(n => n.style.display = "inline-block");
        Array.from(document.getElementsByClassName('writeInfoFlex')).forEach(n => n.style.display = "flex");
        Array.from(document.getElementsByClassName('readInfo')).forEach(n => n.style.display = "none");
    }

    function annulerForm()
    {
        Array.from(document.getElementsByClassName('writeInfo')).forEach(n => n.style.display = "none");
        Array.from(document.getElementsByClassName('readInfo')).forEach(n => n.style.display = "inline-block");
        Array.from(document.getElementsByClassName('readInfoFlex')).forEach(n => n.style.display = "flex");

        document.getElementById('nomInput').value = nom;
        document.getElementById('prenomInput').value = prenom;
        document.getElementById('naissanceInput').value = naissance;
        document.getElementById('sexeInput').value = sexe;
        document.getElementById('adresseInput').value = adresse;
        document.getElementById('dateClubInput').value = dateClub;
        document.getElementById('nomClubInput').value = nomClub;
    }
</script>

<?php
    if($nouvelAdherent){
        print "<script>";
        print "     afficheForm();";
        print "     document.getElementById('annulerInfo').style.display = 'none';";
        print "     document.getElementById('separationBouton').style.display = 'none';";
        print "     document.getElementById('btnSbmtInfoAdh').classList.remove('col-5');";
        print "     document.getElementById('liste').style.display = 'none';";
        print "</script>";
    }
?>