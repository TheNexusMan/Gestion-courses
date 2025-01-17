<!-- Arnaud DEBRABANT P1707147 - Damien PETITJEAN P1408987 -->
<?php
    session_start();

    // Récupération de l'id_adherent, redirection si non existant
    if(isset($_SESSION['id_adherent']))
    {
        $idUser = intval($_SESSION['id_adherent']);
    }else if(isset($_GET['id_adherent'])){
        $idUser = intval($_GET['id_adherent']);
    }else{
        header('Location: index.php');
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

            if($nouvelAdherent) print "<p class='textNouvelAdherent'>Nouveau compte, veuillez compléter votre profil.</p>";

            print "<section class='sectionInfos'>
                        <h2>Informations personnelles</h2>
                        <div class='infos container'>
                            <div id='infosBloc' class='infosBloc container mx-auto col-lg-8 col-md-10 col-xs-12 mw-50'>
                                <form action='' method='POST'>
                                    <div class='row ligneInfos'>
                                        <div class='col-md-4'>
                                            <p class='nomInfo'>Nom</p>
                                            <p class='readInfo'>$nom</p>
                                            <input type='text' id='nomInput' class='form-control writeInfo' name='nom' value=\"$nom\" placeholder='Nom' required>
                                        </div>
                                        <div class='col-md-4'></div>
                                        <div class='col-md-4'>
                                            <p class='nomInfo'>Prénom</p>
                                            <p class='readInfo'>$prenom</p>
                                            <input type='text' id='prenomInput' class='form-control writeInfo' name='prenom' value=\"$prenom\" placeholder='Prenom' required>
                                        </div>
                                    </div>
                                    <div class='row ligneInfos'>
                                        <div class='col-md-4'>
                                            <p class='nomInfo'>Date de naissance</p>
                                            <p class='readInfo'>" . ($dateNaissance == NULL ? "" : date('d/m/Y', strtotime($dateNaissance))) . "</p>
                                            <input type='date' id='naissanceInput' class='form-control writeInfo' name='naissance' value=\"$dateNaissance\">
                                        </div>
                                        <div class='col-md-4'></div>
                                        <div class='col-md-4'>
                                            <p class='nomInfo'>Sexe</p>
                                            <p class='readInfo'>" . ($sexe == 'H' ? 'Homme' : 'Femme') . "</p>
                                            <select id='sexeInput' class='custom-select writeInfo' name='sexe' required>
                                                $selectSexe
                                            </select>
                                        </div>
                                    </div>
                                    <div class='row ligneInfos'>
                                        <div class='col-md-6'>
                                            <p class='nomInfo'>Adresse</p>
                                            <p class='readInfo'>$adresse</p>
                                            <input type='text' id='adresseInput' class='form-control writeInfo' name='adresse' value=\"$adresse\" placeholder='Adresse'>
                                        </div>
                                    </div>
                                    <div class='row ligneInfos'>
                                        <div class='col-md-4'>
                                            <p class='nomInfo'>Date de certification du club</p>
                                            <p class='readInfo'>" . ($dateClub == NULL ? "" : date('d/m/Y', strtotime($dateClub))) . "</p>
                                            <input type='date' id='dateClubInput' class='form-control writeInfo' name='dateClub' value=\"$dateClub\">
                                        </div>
                                        <div class='col-md-4'></div>
                                        <div class='col-md-4'>
                                            <p class='nomInfo'>Nom du club</p>
                                            <p class='readInfo'>$nomClub</p>
                                            <input type='text' id='nomClubInput' class='form-control writeInfo' name='nomClub' value=\"$nomClub\" placeholder='Nom du club'>
                                        </div>
                                    </div>
                                    <div class='row ligneButton readInfo readInfoFlex' id='modifInfo'>
                                        <button type='button' class='btn btn-primary mx-auto'>Modifier</button>
                                    </div>
                                    <div class='row ligneButton writeInfo writeInfoFlex' id='modifInfo'>
                                        <div class='row mx-auto'>
                                            <button type='submit' id='btnSbmtInfoAdh' class='btn btn-primary col-md-5'>Valider</button>
                                            <div id='separationBouton' class='col-md-1'></div>
                                            <button type='button' id='annulerInfo' class='btn btn-primary col-md-5'>Annuler</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id='ancreTri'></div>
                    </section>";
        }

        // Récupération et affichage des éditions participées par l'adhérent
        // Récupération des éditions en fonction du trie du tableau
        $requete = "SELECT Co.nom, year(Ed.date) AS annee, Ep.distance, Tp.temps AS temps, Ed.id_edition
                    FROM (SELECT Pa.* FROM participation Pa WHERE Pa.id_adherent = $idUser) AS Part
                            NATURAL JOIN epreuve Ep
                            NATURAL JOIN edition Ed
                            JOIN COURSE Co ON Ed.id_course = Co.id_course
                            JOIN (SELECT id_epreuve, dossard, MAX(temps) AS temps
                                FROM temps_passage GROUP BY id_epreuve, dossard) AS Tp ON Tp.id_epreuve = Part.id_epreuve AND Tp.dossard = Part.dossard";

        // Cas où on clic deux fois à la suite sur une colonne (changement de l'ordre du trie)
        if(!empty($_GET['order']) && ($_GET['orderSec'] == $_GET['order']))
        {
            $order = mysqli_real_escape_string($connexion, $_GET['order']);
            $orderSec = $_GET['orderSec'];
            $sensGet = mysqli_real_escape_string($connexion, $_GET['sens']);

            $requete .= " ORDER BY $order $sensGet";

            if($sensGet == "DESC" && $_GET['clic'])
            {
                $sens = "ASC";
            }else if($_GET['clic']){
                $sens = "DESC";
            }else{
                $sens = $sensGet;
            }

        // Cas où c'est le premier clic sur la colonne (ordre croissant)
        }else if(!empty($_GET['order']))
        {
            $order = mysqli_real_escape_string($connexion, $_GET['order']);
            $sensGet = $_GET['sens'];
            $orderSec = $_GET['orderSec'];

            $requete .= " ORDER BY $order";

            if($_GET['clic']){
                $sens = "DESC";
            }else{
                $sens = $sensGet;
            }
        }else{
            $order = "";
            $orderSec = "";
            $sensGet = "";
            $sens = "ASC";
        }

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert(\"Échec de la requête de récupération des édition courues par l'adhérent\")</script>";
        else {

            print "<section id='liste' class='liste'>
                        <h2 class='tableLabel'>Liste des éditions participées</h2>
                        <div class='container'>
                            <div class='table-responsive'>
                                <table class='table table-bordered table-hover text-center'>
                                    <thead class='thead-dark'>
                                        <tr>
                                            <th id='anneeCol' scope='col'>
                                                <a href='?id_adherent=$idUser&order=annee&orderSec=$order&sens=$sens&clic=1#ancreTri'>Année</a>
                                            </th>
                                            <th id='distanceCol' scope='col'>
                                                <a href='?id_adherent=$idUser&order=distance&orderSec=$order&sens=$sens&clic=1#ancreTri'>Distance</a>
                                            </th>
                                            <th id='nomCol' scope='col'>
                                                <a href='?id_adherent=$idUser&order=nom&orderSec=$order&sens=$sens&clic=1#ancreTri'>Nom</a>
                                            </th>
                                            <th id='tempsCol' scope='col'>
                                                <a href='?id_adherent=$idUser&order=temps&orderSec=$order&sens=$sens&clic=1#ancreTri'>Temps</a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>";

            while($nuplet = mysqli_fetch_assoc($resultat))
            {
                $id_edition = $nuplet['id_edition'];
                $nom = $nuplet['nom'];
                $annee = $nuplet['annee'];
                $distance = $nuplet['distance'];
                $temps = $nuplet['temps'];
                print "<tr class='ligneTabClic' onclick=\"location.href='edition.php?idedition=$id_edition'\">
                            <td class='text-left'>$annee</td>
                            <td class='text-left'>$distance Km</td>
                            <td class='text-left'>$nom</td>
                            <td class='text-left'>$temps min</td>
                        </tr>";
            }

            print "                 </tbody>
                                </table>
                            </div>
                        </div>
                    </section>";
        }
        
        mysqli_close($connexion);
    }

    // Ajout des chevrons pour le sens du trie des colonnes
    if(!empty($_GET['order']) && ($_GET['orderSec'] == $_GET['order'])) // Si deuxième clic sur la même colone, on inverse le sens du chevron
    {
        if($_GET['sens'] == "DESC")
        {
            print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-up\"></i>'</script>";
        }else{
            print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-down\"></i>'</script>";
        }
    }else if(!empty($_GET['order'])) // Si clic sur colonne, on affiche le chevron croissant
    {
        print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-down\"></i>'</script>";
    }

    include "includes/footer.php";
?>

<script>
    // Gestion de l'affichage du formulaire de modification des informations de l'adhérent

    if(document.getElementById("modifInfo"))
    {
        // Appelle des fonction en cas de clic sur les boutons
        document.getElementById("modifInfo").onclick = afficheForm;
        document.getElementById("annulerInfo").onclick = annulerForm;

        // Sauvegarde des champs
        const nom = document.getElementById('nomInput').value;
        const prenom = document.getElementById('prenomInput').value;
        const naissance = document.getElementById('naissanceInput').value;
        const sexe = document.getElementById('sexeInput').value;
        const adresse = document.getElementById('adresseInput').value;
        const dateClub = document.getElementById('dateClubInput').value;
        const nomClub = document.getElementById('nomClubInput').value;
    }

    // Fonction qui affiche le formulaire de modification
    function afficheForm()
    {
        Array.from(document.getElementsByClassName('writeInfo')).forEach(n => n.style.display = "inline-block");
        Array.from(document.getElementsByClassName('writeInfoFlex')).forEach(n => n.style.display = "flex");
        Array.from(document.getElementsByClassName('readInfo')).forEach(n => n.style.display = "none");
    }

    // Fonction qui cache le formulaire de modification et remet les valeures initiales
    function annulerForm()
    {
        Array.from(document.getElementsByClassName('writeInfo')).forEach(n => n.style.display = "none");
        Array.from(document.getElementsByClassName('readInfo')).forEach(n => n.style.display = "inline-block");
        Array.from(document.getElementsByClassName('readInfoFlex')).forEach(n => n.style.display = "flex");

        // Re-mise en place des valeures initiales
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
        print "     document.getElementById('btnSbmtInfoAdh').classList.remove('col-md-5');";
        print "     document.getElementById('liste').style.display = 'none';";
        print "</script>";
    }
?>