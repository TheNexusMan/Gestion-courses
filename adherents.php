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

        //Ajout d'un adhérent utilisateur
        if(isset($_POST['pseudo']) && isset($_POST['mdp']))
        {
            $pseudo = $_POST['pseudo'];
            $mdp = $_POST['mdp'];

            $requete = "SELECT MAX(id_adherent) AS id_adherent FROM adherent";

            $resultat = mysqli_query($connexion, $requete);

            if($resultat == FALSE)
                print "<script>alert(\"Échec de la requête de récupération du dernier id_adherent\")</script>";
            else {
                while ($nuplet = mysqli_fetch_assoc($resultat))
                {
                    $id_adherent = $nuplet['id_adherent'];
                }

                $year = substr($id_adherent, 0, 4);

                if($year != date('Y')){
                    $id_adherent = date('Y') . "001";
                }else{
                    $finnb = substr($tempid, -3) + 1;
                    $id_adherent = sprintf("%d%03d", date('Y'), $finnb);
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
            $deleteAdherent = $_GET['delete_adherent'];

            mysqli_begin_transaction($connexion, MYSQLI_TRANS_START_READ_WRITE);

            $requete = "DELETE FROM adherent WHERE id_adherent = $deleteAdherent";

            $resultat = mysqli_query($connexion, $requete);

            $requete = "DELETE FROM participation WHERE id_adherent = $deleteAdherent";

            $resultat = mysqli_query($connexion, $requete);

            $requete = "DELETE FROM user WHERE id_adherent = $deleteAdherent";

            $resultat = mysqli_query($connexion, $requete);

            if(!mysqli_commit($connexion))
            {
                print "<script>alert(\"Échec de la requête de suppression de l'adherent\")</script>";
                //printf("Échec de la requête de suppression de l'adhérent");
            }
        }

        //Affichage des éditions participées par l'adhérent et trie du tableau
        if(isset($_GET['order']) && ($_GET['orderSec'] == $_GET['order']))
        {
            $order = $_GET['order'];

            $requete = "SELECT *
                    FROM adherent
                    ORDER BY " . $order . " " . $_GET['sens'];

            if($_GET['sens'] == "DESC")
            {
                $sens = "ASC";
            }else{
                $sens = "DESC";
            }
        }else if(isset($_GET['order']))
        {
            $order = $_GET['order'];

            $requete = "SELECT *
                    FROM adherent
                    ORDER BY " . $order;
            $sens = "DESC";
        }else{
            $requete = "SELECT * FROM adherent";
            $order = "";
            $sens = "ASC";
        }

        $resultat = mysqli_query($connexion, $requete);

        if($resultat == FALSE)
            print "<script>alert('Échec de la requête de récupération des adhérents')</script>";
        else {

            print "
            <div class='row mb-4'>
                <button type=\"button\" class=\"btn btn-primary mx-auto\" data-toggle=\"modal\" data-target=\"#modalAjoutAdherent\">
                    Ajouter un utilisateur
                </button>
            </div>
            <section class='listeEditionAdherent'>
                            <div class='container'>
                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <th id='id_adherentCol' scope='col'><a href='?order=id_adherent&orderSec=$order&sens=$sens'>Id</a></th>
                                            <th id='nomCol' scope='col'><a href='?order=nom&orderSec=$order&sens=$sens'>Nom</a></th>
                                            <th id='prenomCol' scope='col'><a href='?order=prenom&orderSec=$order&sens=$sens'>Prénom</a></th>
                                            <th id='date_naissanceCol' scope='col'><a href='?order=date_naissance&orderSec=$order&sens=$sens'>Date de naissance</a></th>
                                            <th id='sexeCol' scope='col'><a href='?order=sexe&orderSec=$order&sens=$sens'>Sexe</a></th>
                                            <th id='clubCol' scope='col'><a href='?order=club&orderSec=$order&sens=$sens'>Club</a></th>
                                            <th><i class='fas fa-trash-alt'></i></th>
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

                print "<tr class='ligneTabClic' onclick=\"location.href='adherent.php?id_adherent=$id'\">
                            <td>$id</td>
                            <td>$nom</td>
                            <td>$prenom</td>
                            <td>" . date('d/m/Y', strtotime($dateNaissance)) . "</td>
                            <td>$sexe</td>
                            <td>$nomClub</td>
                            <td class='delete'><a href='?delete_adherent=$id'><i class='fas fa-trash-alt'></i></a></td>
                        </tr>";
            }

            print "             </tbody>
                            </table>
                        </div>
                    </section>";
        }
        
        mysqli_close($connexion);
    }

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

<!-- Modal -->
<div class="modal fade" id="modalAjoutAdherent" tabindex="-1" role="dialog" aria-labelledby="modalAjoutAdherent" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ajouter un adhérent utilisateur</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="">
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