<?php
session_start(); 

$email = $_POST['Email'];
$mdp= $_POST['Mdp'];

    $_SESSION['auth'] = array(
        'Email' => $email,
        'Mdp' => $mdp,
    );

    header("Location: panier.php");
    exit();

?>