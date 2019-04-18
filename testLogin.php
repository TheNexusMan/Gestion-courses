<?php
session_start();

$user = 'root';
$mdp = '';
$machine = 'localhost';
$bd = 'bdw1';

if((isset($_POST['pseudoInput'])) && (isset($_POST['inputPw'])))
{
    $connexion = mysqli_connect($machine,$user,$mdp,$bd); //On se connecte

    if(mysqli_connect_errno()) //erreur si > 0
    {
        printf("Echec de la connexion : %s", mysqli_connect_errno());
    } else {

        $pseudo = '"'.$_POST['pseudoInput'].'"';
        $pw = '"'.$_POST['inputPw'].'"';

        $requete = "SELECT * FROM user WHERE pseudo = $pseudo AND mdp = $pw";
        //$requete = 'SELECT * FROM user WHERE pseudo = "' . $_POST['pseudoInput'] . '" AND mdp = "' . $_POST['inputPw'] . '"';

        $resultat = mysqli_query($connexion, $requete); //Envoie de la requete

        if(mysqli_num_rows($resultat) == 0) //Test si le nom && mdp existe
        {
            //Si non => Erreur
            //printf("Echec de la requête");
            //Renvoie sur la page de connexion
            header('Location: http://localhost/projet-bdw1/index.php');
            //exit();
        } else{

            while($nuplet = mysqli_fetch_assoc($resultat))
            {
                $_SESSION['id_adherent'] = $nuplet['id_adherent'];
                $type = $nuplet['type'];
            }

            if($type == "Admin")
            {
                //Renvoie sur la page admin
                header('Location: http://localhost/projet-bdw1/courses.php');
            }
            else {
                //Renvoie sur la page User
                header('Location: http://localhost/projet-bdw1/adherent.php');
            }
        }
   }
} 

?>