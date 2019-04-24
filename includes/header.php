<?php
    //On réinitialise les variables de connexion en cas de déconnexion
    if(isset($_POST['logout']))
    {
        unset($_SESSION['isConnected']);
        unset($_SESSION['id_adherent']);
    }

    // On récupère le nom de la page
    $page = basename($_SERVER['PHP_SELF']);

    // Si l'utilisateur n'est pas sur la page de login (index.php) et que la variable $_SESSION['isConnected'] n'existe pas,
    // ou qu'un adhérent essait d'accéder à une page administrateur, il est redirigé vers la page de login
    if($page != "index.php" && $page != "404.php" && (!isset($_SESSION['isConnected']) || ($page != "adherent.php" && isset($_SESSION['id_adherent']))))
    {
        header('Location: http://localhost/projet-bdw1/index.php');
    }

    $user = 'root';
    $mdp = '';
    $machine = 'localhost';
    $bd = 'bdw1';
    $connexion = mysqli_connect($machine, $user, $mdp, $bd);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Run 4 your life</title>
        <link rel="icon" type="image/jpg" href="data/logo.jpg" />
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="http://localhost/projet-bdw1/style.css"/>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    </head>

    <header>
        <div class="row">
            <div class="col-1"></div>
            <div class="col-1">
                <img src="http://localhost/projet-bdw1/data/logo.jpg" class="img-fluid" alt="Responsive image" height="50px">
            </div>
            <div class="col-2"></div>
            <div class="col-4 divSiteTitre">
                <h1>Run 4 your life</h1>
            </div>

            <?php
                if($page != "index.php")
                {
                    print '<div class="d-flex flex-row-reverse col-3">
                        <form method="POST" action="index.php">
                            <div class="p-2">
                                <button type="submit" class="btn btn-outline-success" id="home">Accueil</button>
                            </div>
                        </form>
                        <form method="POST" action="">
                            <div class="p-2">
                                <input type="hidden" name="logout" value="logout">
                                <button type="submit" class="btn btn-outline-success" id="logout">Déconnexion</button>
                            </div>
                        </form>';
                }else{
                    print '<div class="col-3"></div>';
                }
            ?>
                
            </div>
            <div class="col-1"></div>
        </div>
    </header>