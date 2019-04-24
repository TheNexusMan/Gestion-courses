<?php
    session_start();
    include "includes/header.php"
?>

<?php
    $goodPseudo = 0; //Variable pour savoir si on re-affiche le pseudo dans le champ (cas du pseudo existant mais mauvais mdp)

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

            $pseudo = mysqli_real_escape_string($connexion, $_POST['pseudoInput']);
            $pw = mysqli_real_escape_string($connexion, $_POST['inputPw']);

            $requete = "SELECT * FROM user WHERE pseudo = '$pseudo'";

            $resultat = mysqli_query($connexion, $requete); //Envoie de la requete

            if($resultat == FALSE) //Test si la requete échoue
                print "<script>alert(\"Échec de la récupération du pseudo\")</script>";
            else {

                if(mysqli_num_rows($resultat) != 0) //Test si le pseudo existe
                {
                    $goodPseudo = 1;

                    $requete = "SELECT * FROM user WHERE pseudo = '$pseudo' AND mdp = '$pw'";

                    $resultat = mysqli_query($connexion, $requete); //Envoie de la requete

                    if($resultat == FALSE) //Test si la requete échoue
                        print "<script>alert(\"Échec de la récupération du mot de passe\")</script>";
                    else {
                        if(mysqli_num_rows($resultat) != 0) //Test si le pseudo et le mdp existe
                        {
                            $_SESSION['isConnected'] = 1;

                            while($nuplet = mysqli_fetch_assoc($resultat))
                            {
                                $_SESSION['id_adherent'] = $nuplet['id_adherent'];
                            }
                        } else{
                            print "<div class='erreurAuthentification'>
                                        <p>Le mot de passe est incorrect, veuillez réessayer.</p>
                                    </div>";
                        }
                    }
                }else{
                    print "<div class='erreurAuthentification'>
                                <p>Le pseudo n'existe pas, veuillez réessayer.</p>
                            </div>";
                }
            }

            mysqli_close($connexion);
        }
    }

    //Test si l'utilisateur est connecté et l'oriente sur son espace en fonction de son type (adhérent ou admin)
    if(isset($_SESSION['isConnected']) && isset($_SESSION['id_adherent']))
    {
        //Renvoie sur la page User
        header('Location: http://localhost/projet-bdw1/adherent.php');
    }
    else if(isset($_SESSION['isConnected']) && !isset($_SESSION['id_adherent'])){
        //Renvoie sur la page admin
        header('Location: http://localhost/projet-bdw1/espaceperso.php');
    }

?>

<!-- Le formulaire de login -->
<div>
    <div class="d-flex flex-row justify-content-center">
        <form method="POST" action="">
            <div class="form-group">
                <label for="pseudoInput">Pseudonyme:</label>
                <input type="text" class="form-control" id="pseudoInput" <?php ($goodPseudo ? print "value=\"$pseudo\"" : print "") ?> name="pseudoInput" placeholder="Pseudo...">
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