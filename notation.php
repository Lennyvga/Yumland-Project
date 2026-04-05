<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Notation - Bella Ciao</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="page-inscription">
    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">Bella Ciao</div>
            <div class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="menu.php">Menu</a>

                <?php if (isset($_SESSION['auth'])) { ?>

                <?php if ($_SESSION['auth']['role'] === 'admin') { ?>
                <a href="admin.php">Administration</a>
                <?php } ?>

                <?php if ($_SESSION['auth']['role'] === 'restaurateur') { ?>
                <a href="restaurateur.php">Commandes</a>
                <?php } ?>

                <?php if ($_SESSION['auth']['role'] === 'livreur') { ?>
                <a href="livreur.php">Ma livraison</a>
                <?php } ?>

                <?php if ($_SESSION['auth']['role'] === 'client') { ?>
                <a href="notation.php">Notation</a>
                <div class="dropdown">
                    <a href="#" class="nav-links">Profil ⏷</a>
                    <div class="dropdown-content">
                        <a href="informations.php">Mes informations</a>
                        <a href="commandes.php">Mes commandes</a>
                        <a href="compte+.php">Mon compte Bella Ciao +</a>
                    </div>
                </div>
                <?php } ?>

                <a href="deconnexion.php">Se déconnecter</a>

                <?php } else { ?>
                <a href="connexion.php">Se connecter</a>
                <a href="inscription.php">S'inscrire</a>
                <?php } ?>

            </div>
        </div>
    </nav>

    <div class="notation-wrapper">

        <h1 class="section-title">Votre avis compte! ⭐</h1>
        <p class="notation-subtitle">Dites-nous ce que vous avez pensé de votre expérience chez Bella Ciao.</p>
        <div class="rating">
            <form action="" method="POST" class="notation-form"></form>
            <input type="radio" id="star5" name="rating" value="5">
            <label for="star5">★</label>

            <input type="radio" id="star4" name="rating" value="4">
            <label for="star4">★</label>

            <input type="radio" id="star3" name="rating" value="3">
            <label for="star3">★</label>

            <input type="radio" id="star2" name="rating" value="2">
            <label for="star2">★</label>

            <input type="radio" id="star1" name="rating" value="1">
            <label for="star1">★</label>
        </div>
        <textarea placeholder="Écrivez votre commentaire ici..."></textarea>

        <div class="send">
            <a href="merci.php" class="btn">Envoyer</a>

        </div>
        </form>

    </div>
    <footer>
        © 2026 Bella Ciao - Tous droits réservés
    </footer>
</body>

</html>
