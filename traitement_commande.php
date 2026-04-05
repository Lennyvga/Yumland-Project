<?php
session_start();

if (!isset($_SESSION['auth'])) {
    header('Location: connexion.php');
    exit();
}

$mode = $_POST['mode'];
$datetime = $_POST['datetime'];
$adresse = $_POST['adresse'];

$panier = $_SESSION['panier'];


$json_plats = file_get_contents("plats.json");
$data_plats = json_decode($json_plats, true);
$plats = $data_plats['plats'];

$total = 0;
$articles = [];

foreach ($panier as $id => $quantite) {
    foreach ($plats as $plat) {
        if ($plat['id'] == $id) {
            $sous_total = $plat['prix'] * $quantite;
            $total += $sous_total;
            $articles[] = [
                "id" => $id,
                "nom" => $plat['nom'],
                "quantite" => $quantite,
                "prix" => $plat['prix'],
                "sous_total" => $sous_total
            ];
            break;
        }
    }
}

$commande = [
    "id" => uniqid(),
    "utilisateur" => $_SESSION['auth']['email'],
    "articles" => $articles,
    "total" => $total,
    "mode" => $mode,
    "datetime" => $datetime,
    "adresse" => $adresse,
    "statut" => "en_attente_paiement",
    "date_commande" => date("Y-m-d H:i:s")
];

if (file_exists("commandes.json")) {
    $json_commandes = file_get_contents("commandes.json");
    $data_commandes = json_decode($json_commandes, true);
} else {
    $data_commandes = ["commandes" => []];
}

$data_commandes["commandes"][] = $commande;
file_put_contents("commandes.json", json_encode($data_commandes, JSON_PRETTY_PRINT));

$_SESSION['commande_id'] = $commande['id'];


header('Location: paiement.php');
exit();
?>
