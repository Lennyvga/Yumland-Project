<?php
session_start();
require_once("getapikey.php");

// 1. CHARGEMENT DES DEUX FICHIERS (Mets les vrais noms renvoyés par ton terminal à la place de fichier1 et fichier2)
$json1 = json_decode(file_get_contents("commandes.json"), true);
$json2 = json_decode(file_get_contents("plats.json"), true);

// 2. On fusionne toutes les données de manière intelligente pour créer une grosse liste de plats
$toutes_les_donnees = array_merge((array)$json1, (array)$json2);

$liste_plats = [];

// On extrait les tableaux de plats peu importe si la clé s'appelle 'produits', 'plats', 'menu' ou si c'est un tableau direct
foreach ($toutes_les_donnees as $cle => $valeur) {
    if (is_array($valeur)) {
        if ($cle === 'produits' || $cle === 'plats' || $cle === 'menu') {
            $liste_plats = array_merge($liste_plats, $valeur);
        } else {
            // Si le fichier contient un tableau direct sans clé spécifique
            $liste_plats[] = $valeur;
        }
    }
}

// 3. CALCUL DU MONTANT TOTAL
$montant = 0;

if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $id_produit => $quantite) {
        foreach ($liste_plats as $plat) {
            // On vérifie que la ligne a bien un ID et qu'il correspond à notre panier
            if (isset($plat['id']) && $plat['id'] == $id_produit) {
                $montant += $plat['prix'] * $quantite;
            }
        }
    }
}

// 4. LE RESTE DE TON CODE DE PAIEMENT (CYBANK)
$vendeur = "MI-3_D"; 
$transaction = strval(time()); 
$retour = "http://localhost:8080/merci.php"; 

$api_key = getAPIKey($vendeur);

$chaine = $api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#";
$control = md5($chaine);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement - Bella Ciao</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-inscription"> <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">Bella Ciao</div>
            <div class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="menu.php">Menu</a>
                <button id="bouton-theme" class="btn-theme">🌙</button>
            </div>
        </div>
    </nav>

    <div class="form-wrapper" style="max-width: 500px; margin: 60px auto; text-align: center;">
        <div class="nomrestaurant">Bella Ciao</div>
        <br>
        <h2 class="section-title">Paiement Sécurisé</h2>
        <br>

        <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST" id="form_paiement">
            <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
            <input type="hidden" name="montant" value="<?php echo $montant; ?>">
            <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
            <input type="hidden" name="retour" value="<?php echo $retour; ?>">
            <input type="hidden" name="control" value="<?php echo $control; ?>">
            
            <p style="font-size: 18px; font-weight: 500;">Montant à payer : <strong style="color: #008C45; font-size: 22px;"><?php echo $montant; ?> €</strong></p>
            <br><br>
            <button type="submit" class="btn" style="width: 100%;">Procéder au paiement via Cybank</button>
        </form>
    </div>

    <footer>
        Bella Ciao Ristorante © 2026
    </footer>

    <script type="text/javascript" src="theme.js"></script>
</body>
</html>