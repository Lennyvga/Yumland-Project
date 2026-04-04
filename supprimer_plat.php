<?php
session_start();

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if (isset($_SESSION['panier'][$id])) {
        unset($_SESSION['panier'][$id]);
    }
}

header('Location: panier.php');
exit();
?>
