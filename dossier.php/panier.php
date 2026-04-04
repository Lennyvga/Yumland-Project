<?php
session_start();


function ajouterArticle($id, $nom, $prix, $quantite) {
    
   
    if (!isset($_SESSION["panier"])) {
        $_SESSION["panier"] = [];
    }

    $dejaPresent = false;

   
    foreach ($_SESSION["panier"] as $index => $article) {
        if ($article["id"] == $id) {
            $_SESSION["panier"][$index]["quantite"] += $quantite;
            $dejaPresent = true;
            break; 
        }
    }

    if (!$dejaPresent) {
        $_SESSION["panier"][] = [
            "id" => $id,
            "nom" => $nom,
            "prix" => $prix,
            "quantite" => $quantite
        ];
    }
}


?>