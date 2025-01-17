<!-- Arnaud DEBRABANT P1707147 - Damien PETITJEAN P1408987 -->
<?php
    session_start();
    include "includes/header.php";

    if(mysqli_connect_errno())
        printf("Échec de la connexion : %s", mysqli_connect_error());
    else {

        // Suppression d'un adhérent
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

        // Ajout d'un utilisateur
        if(isset($_POST['pseudo']) && isset($_POST['mdp']))
        {
            $pseudo = mysqli_real_escape_string($connexion, $_POST['pseudo']);
            $mdp = mysqli_real_escape_string($connexion, $_POST['mdp']);

            // Récupération du dernier id adherent
            $requete = "SELECT MAX(id.id_adherent) AS id_adherent FROM (select id_adherent from adherent UNION SELECT id_adherent FROM user) AS id";

            $resultat = mysqli_query($connexion, $requete);

            if($resultat == FALSE)
                print "<script>alert(\"Échec de la requête de récupération du dernier id_adherent\")</script>";
            else {
                $nuplet = mysqli_fetch_assoc($resultat);
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

                if(mysqli_query($connexion, $requete) == FALSE)
                    print "<script>alert(\"Échec de la requête de l'ajout de l'adhérent\")</script>";
            }
        }

        // Récupération des adhérents en fonction du trie du tableau
        $requete = "SELECT * FROM adherent";

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
            print "<script>alert('Échec de la requête de récupération des adhérents')</script>";
        else {

            // Affichage de l'entête du tableau
            print "
            <div class='container'>
                <div class='row mb-4'>
                    <button type=\"button\" class=\"btn btn-primary mx-auto\" data-toggle=\"modal\" data-target=\"#modalAjout\">
                        Ajouter un utilisateur
                    </button>
                </div>
            </div>
            <section class='liste'>
                <h2 class='tableLabel'>Liste des adhérents</h2>
                <div class='container'>
                    <div class='table-responsive'>
                        <table class='table table-bordered table-hover text-center'>
                            <thead class='thead-dark'>
                                <tr>
                                    <th scope='col'>
                                        <a id='id_adherentCol' href='?order=id_adherent&orderSec=$order&sens=$sens&clic=1'>Id</a>
                                    </th>
                                    <th scope='col'>
                                        <a id='nomCol' href='?order=nom&orderSec=$order&sens=$sens&clic=1'>Nom</a>
                                    </th>
                                    <th scope='col'>
                                        <a id='prenomCol' href='?order=prenom&orderSec=$order&sens=$sens&clic=1'>Prénom</a>
                                    </th>
                                    <th scope='col'>
                                        <a id='date_naissanceCol' href='?order=date_naissance&orderSec=$order&sens=$sens&clic=1'>Date de naissance</a>
                                    </th>
                                    <th scope='col'>
                                        <a id='sexeCol' href='?order=sexe&orderSec=$order&sens=$sens&clic=1s'>Sexe</a>
                                    </th>
                                    <th scope='col'>
                                        <a id='clubCol' href='?order=club&orderSec=$order&sens=$sens&clic=1'>Club</a>
                                    </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>";

            // Affichage des éditions dans le tableau
            while ($nuplet = mysqli_fetch_assoc($resultat))
            {
                $id = $nuplet['id_adherent'];
                $nom = $nuplet['nom'];
                $prenom = $nuplet['prenom'];
                $dateNaissance = $nuplet['date_naissance'];
                $sexe = $nuplet['sexe'];
                $nomClub = $nuplet['club'];

                print "<tr class='ligneTabClic'>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id'\" class='text-left'>$id</td>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id'\" class='text-left'>$nom</td>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id'\" class='text-left'>$prenom</td>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id'\" class='text-left'>" . date('d/m/Y', strtotime($dateNaissance)) . "</td>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id'\" class='text-left'>".($sexe == 'H' ? 'Homme' : 'Femme')."</td>
                            <td onclick=\"location.href='adherent.php?id_adherent=$id'\" class='text-left'>$nomClub</td>
                            <td>
                                <form method='GET' action='adherents.php' Onsubmit='return attention();'>
                                    <input name='delete_adherent' type='hidden' value='$id'>
                                    <input name='order' type='hidden' value='$order'>
                                    <input name='orderSec' type='hidden' value='$orderSec'>
                                    <input name='sens' type='hidden' value='$sensGet'>
                                    <input name='clic' type='hidden' value='0'>
                                    <button class='btnDelete' type='submit'>
                                        <i class='fas fa-trash-alt'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>";
            }

            print "             </tbody>
                            </table>
                        </div>
                    </div>
                </section>";
        }
        
        mysqli_close($connexion);
    }

    include "includes/footer.php";
?>

<!-- Modal du formulaire d'ajout d'adhérent -->
<div class="modal fade" id="modalAjout" tabindex="-1" role="dialog" aria-labelledby="modalAjout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un utilisateur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="adherents.php<?php print "?order=$order&orderSec=$orderSec&sens=$sens&clic=0" ?>">
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

<?php
    // Ajout des chevrons pour le sens du trie des colonnes
    if(!empty($_GET['order']) && ($_GET['orderSec'] == $_GET['order'])) // Si deuxième clic sur la même colone, on inverse le sens du chevron
    {
        if($_GET['sens'] == "DESC")
        {
            print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-up\"></i>'</script>";
        }else{
            print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-down\"></i>'</script>";
        }
    }else if(!empty($_GET['order'])) // Si clic sur colonne, on affiche le chevron 
    {
        print "<script>document.getElementById('". $order ."Col').innerHTML += ' <i class=\"fas fa-chevron-down\"></i>'</script>";
    }
?>
<script>
    // Fonction de confirmation de suppression d'un adhérent
    function attention()
    {
        resultat=window.confirm('Voulez-vous vraiment supprimer cet adhérent ?');
        if (resultat==1)
        {
        }
        else
        {
            return false;
        }
    }
</script>