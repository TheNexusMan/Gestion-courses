<!-- Arnaud DEBRABANT P1707147 - Damien PETITJEAN P1408987 -->
<?php
    $user = 'root';
    $mdp = '';
    $machine = 'localhost';
    $bd = 'bdw1';
    $connexion = mysqli_connect($machine, $user, $mdp, $bd);
    mysqli_set_charset($connexion, 'utf8');
?>