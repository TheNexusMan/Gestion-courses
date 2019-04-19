<?php include "includes/header.php" ?>

<?php

    if((isset($_POST['pseudoInput'])) && (isset($_POST['inputPw'])))
    {
        $user = 'root';
        $mdp = '';
        $machine = 'localhost';
        $bd = 'bdw1';

        $connexion = mysqli_connect($machine, $user, $mdp, $bd); //On se connecte

        if(mysqli_connect_errno()) //erreur si > 0
        {
            printf("Echec de la connexion : %s", mysqli_connect_errno());
        } else {

            $pseudo = '"'.$_POST['pseudoInput'].'"';
            $pw = '"'.$_POST['inputPw'].'"';

            $requete = "SELECT * FROM user WHERE pseudo = $pseudo AND mdp = $pw";
            //$requete = 'SELECT * FROM user WHERE pseudo = "' . $_POST['pseudoInput'] . '" AND mdp = "' . $_POST['inputPw'] . '"';

            $resultat = mysqli_query($connexion, $requete); //Envoie de la requete

            if(mysqli_num_rows($resultat) != 0) //Test si le nom && mdp existe
            {
                $_SESSION['isConnected'] = 1;

                while($nuplet = mysqli_fetch_assoc($resultat))
                {
                    $_SESSION['id_adherent'] = $nuplet['id_adherent'];
                    $type = $nuplet['type'];
                }

                if($type == "Admin")
                {
                    //Renvoie sur la page admin
                    header('Location: http://localhost/projet-bdw1/espaceperso.php');
                }
                else {
                    //Renvoie sur la page User
                    header('Location: http://localhost/projet-bdw1/adherent.php');
                }
            } else{
                print "<div class='erreurAuthentification'>
                    <p>Erreur d'authentification, veuillez réessayer.</p>
                </div>";
            }

            mysqli_close($connexion);
        }
    }

?>

<div>
    <div class="d-flex flex-row justify-content-center">
        <form method="POST" action="">
            <div class="form-group">
                <label for="pseudoInput"> Pseudonyme:</label>
                <input type="text" class="form-control" id="pseudoInput" name="pseudoInput" placeholder="Pseudo...">
            </div>
            <div class="form-group">
                <label for="inputPw">Mot de passe:</label>
                <input type="password" class="form-control" id="inputPw" name="inputPw" placeholder="Mot de passe...">
            </div>
            <button type="submit" class="btn btn-success" name="connexionAcc">Connexion</button>
        </form>
    </div>
</div>

<?php include "includes/footer.php" ?>