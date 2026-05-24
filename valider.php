<?php
session_start();

if (!isset($_SESSION['auth'])) {
    header('Location: connexion.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Valider ma commande - Bella Ciao</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="page-inscription">

    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">Bella Ciao</div>
            <div class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="menu.php">Menu</a>
                <a href="panier.php">Panier</a>

                <button id="bouton-theme" class="btn-theme">🌙</button>
            </div>
        </div>
    </nav>

    <div class="form-wrapper">
        <div class="nomrestaurant" id="entete-blanc">Bella Ciao</div>
        <br><br>

        <h2 class="section-title">Finaliser ma commande</h2>

        <form action="traitement_commande.php" method="POST">

            <div class="ligne">
                <div class="sousligne">
                    <label>Mode de commande</label><br><br>
                    <select name="mode">
                        <option value="sur_place">Sur place</option>
                        <option value="emporter">À emporter</option>
                        <option value="livraison">Livraison</option>
                    </select>
                </div>
            </div>

            <br>

            <div class="ligne">
                <div class="sousligne">
                    <label>Date et heure souhaitée</label><br><br>
                    <input type="datetime-local" name="datetime">
                </div>
            </div>

            <br>

            <div class="ligne">
                <div class="sousligne">
                    <label>Adresse de livraison (si livraison)</label><br><br>
                    <input type="text" name="adresse" placeholder="Votre adresse complète">
                </div>
            </div>

            <br>

            <div class="send">
                <button type="submit" class="btn">Payer ma commande</button>
            </div>

        </form>
    </div>

    <footer>
        Bella Ciao Ristorante © 2026
    </footer>

         <script type="text/javascript" src="theme.js"></script>
</body>

</html>
