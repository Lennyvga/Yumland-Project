<?php
session_start();



if (isset($_POST['id_produit'])) {
    $id = $_POST['id_produit'];

    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
    }

    if (isset($_POST['qte_choisie'])) {
        
        $_SESSION['panier'][$id] = $_POST['qte_choisie'];
    } 
    else {
        
        if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]++;
    } 
    
    else {
        $_SESSION['panier'][$id] = 1;
    }
    }
}

    if (isset($_POST['provenance']) && $_POST['provenance'] == 'panier') {
        header("Location: panier.php");
} 
    else {
    header("Location: menu.php");
    exit();
 }

?>



