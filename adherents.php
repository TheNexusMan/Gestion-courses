<?php
    include "includes/header.php";

    $user = 'root';
    $mdp = '';
    $machine = 'localhost';
    $bd = 'bdw1';
    $connexion = mysqli_connect($machine, $user, $mdp, $bd);

    if(mysqli_connect_errno()) // erreur si > 0
        printf("Échec de la connexion : %s", mysqli_connect_error());
    else {

        //Ajout d'un utilisateur
        if(isset($_POST['pseudo']) && isset($_POST['mdp']))
        {
            $pseudo = mysqli_real_escape_string($connexion, $_POST['pseudo']);
            $mdp = mysqli_real_escape_string($connexion, $_POST['mdp']);

            $requete = "SELECT MAX(id.id_adherent) AS id_adherent FROM (select id_adherent from adherent UNION SELECT id_adherent FROM user) AS id";

            $resultat = mysqli_query($connexion, $requete);

            if($resultat == FALSE)
                print "<script>alert(\"Échec de la requête de récupération du dernier id_adherent\")</script>";
            else {
                while ($nuplet = mysqli_fetch_assoc($resultat))
                {
                    $id_adherent = $nuplet['id_adherent'];
                }

                $year = substr($id_adherent, 0, 4); // Récupération de l'année dans l'id_adherent

                if($year != date('Y')){
                    $id_adherent = date('Y') . "001"; // Si nouvelle année, le compteur repars à 1
                }else{
                    $finnb = substr($id_adherent, -3) + 1; // Sinon le compteur est incrémenté
                    $id_adherent = sprintf("%d%03d", date('Y'), $finnb); // On met ensemble l'année et le comteur incrémenté
                }

                $requete = "INSERT INTO user (id_adherent, type, mdp, pseudo) VALUES ('$id_adherent', 'Adherent', '$mdp', '$pseudo')";

                $resultat = mysqli_query($connexion, $requete);

                if($resultat == FALSE)
                    print "<script>alert(\"Échec de la requête de l'ajout de l'adhérent\")</script>";
            }
        }

        //Suppression d'un adhérent
        if(isset($_GET['delete_adherent']))
        {
            $deleteAdherent = intval($_GET['delete_adherent']);

            mysqli_begin_transaction($connexion, MYSQLI_TRANS_START_READ_WRITE);

            $requete = "DELETE FROM adherent WHERE id_adherent = $deleteAdherent";

            $resultat = mysqli_query($connexion, $requete);

            $requete = "DELETE FROM participation WHERE id_adherent = $deleteAdherent";

            $resultat = mysqli_query($connexion, $requete);

            $requete = "DELETE FROM user WHERE id_adherent = $deleteAdherent";

            $resultat = mysqli_query($connexion, $requete);

            if(!mysqli_commit($connexion))
                print "<script>alert(\"Échec de la requête de suppression de l'adherent\")</script>";
        }

        //Récupération des éditions participées par l'adhérent en fonction du trie du tableau :

        //Cas où on clic deux fois à la suite sur une colonne (changement de l'ordre du trie)
        if(isset($_GET['order']) && ($_GET['orderSec'] == $_GET['order']))
        {
            $order = mysqli_real_escape_string($connexion, $_GET['order']);
            $orderSec = $_GET['orderSec'];
            $sensGet = mysqli_real_escape_string($connexion, $_GET['sens']);

            $requete = "SELECT * FROM adherent ORDER BY " . $order . " " . $sensGet;

            if($sensGet == "DESC" && $_GET['clic'])
            {
                $sens = "ASC";
            }else if($_GET['clic']){
                $sens = "DESC";
            }else{
                $sens = $sensGet;
            }

        //Cas où c'est le premier clic sur la colonne (ordre croissant)
        }else if(isset($_GET['order']))
        {
            $order = mysqli_real_escape_string($connexion, $_GET['order']);
            $sensGet = $_GET['sens'];
            $orderSec = $_GET['orderSec'];

            $requete = "SELECT * FROM adherent ORDER BY " . $order;

            if($_GET['clic']){
                $sens = "DESC";
            }else{
                $sens = $sensGet;
            }
        }else{
            $requete = "SELECT * FROM adherent";
            $order = "";
            $orderSec = "";
            $sensGet = "";
            $sens = "ASC";
        }

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert('Échec de la requête de récupération des adhérents')</script>";
        else {

            //Affichage de l'entête du tableau
            print "
            <div class='container'>
                <div class='row mb-4'>
                    <button type=\"button\" class=\"btn btn-primary mx-auto\" data-toggle=\"modal\" data-target=\"#modalAjoutAdherent\">
                        Ajouter un utilisateur
                    </button>
                </div>
            </div>
            <section class='listeEditionAdherent'>
                            <div class='container'>
                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <th scope='col'><a id='id_adherentCol' href='?order=id_adherent&orderSec=$order&sens=$sens&clic=1'>Id</a></th>
                                            <th scope='col'><a id='nomCol' href='?order=nom&orderSec=$order&sens=$sens&clic=1'>Nom</a></th>
                                            <th scope='col'><a id='prenomCol' href='?order=prenom&orderSec=$order&sens=$sens&clic=1'>Prénom</a></th>
                                            <th scope='col'><a id='date_naissanceCol' href='?order=date_naissance&orderSec=$order&sens=$sens&clic=1'>Date de naissance</a></th>
                                            <th scope='col'><a id='sexeCol' href='?order=sexe&orderSec=$order&sens=$sens&clic=1s'>Sexe</a></th>
                                            <th scope='col'><a id='clubCol' href='?order=club&orderSec=$order&sens=$sens&clic=1'>Club</a></th>
                                            <th><i class='fas fa-trash-alt'></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>";

            //Affichage des éditions dans le tableau
            while ($nuplet = mysqli_fetch_assoc($resultat))
            {
                $id = $nuplet['id_adherent'];
                $nom = $nuplet['nom'];
                $prenom = $nuplet['prenom'];
                $dateNaissance = $nuplet['date_naissance'];
                $sexe = $nuplet['sexe'];
                $nomClub = $nuplet['club'];

                print "<tr class='ligneTabClic' onclick=\"location.href='adherent.php?id_adherent=$id'\">
                            <td>$id</td>
                            <td>$nom</td>
                            <td>$prenom</td>
                            <td>" . date('d/m/Y', strtotime($dateNaissance)) . "</td>
                            <td>$sexe</td>
                            <td>$nomClub</td>
                            <td class='delete'><a href='?delete_adherent=$id&order=$order&orderSec=$orderSec&sens=$sensGet&clic=0'><i class='fas fa-trash-alt'></i></a></td>
                        </tr>";
            }

            print "             </tbody>
                            </table>
                        </div>
                    </section>";
        }
        
        mysqli_close($connexion);
    }

    //Ajout des chevrons pour le sens du trie des colonnes
    if(isset($_GET['order']) && ($_GET['orderSec'] == $_GET['order']))
    {
        if($_GET['sens'] == "DESC")
        {
            print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-up\"></i>'</script>";
        }else{
            print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-down\"></i>'</script>";
        }
    }else if(isset($_GET['order']))
    {
        print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-down\"></i>'</script>";
    }
    include "includes/footer.php";
?>

<!-- Modal du formulaire d'ajout d'adhérent -->
<div class="modal fade" id="modalAjoutAdherent" tabindex="-1" role="dialog" aria-labelledby="modalAjoutAdherent" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un utilisateur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="adherents.php">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" class="form-control mb-3" name="pseudo" placeholder='Pseudo' required>
                    <label for="mdp">Mot de passe</label>
                    <input type="password" class="form-control" name="mdp" placeholder='Mot de passe' required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</div>