<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mes Commandes - Bella Ciao</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
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

                <button id="bouton-theme" class="btn-theme">🌙</button>

            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="section-title">Historique de mes commandes:</h1>

        <div class="orders-container">
            <div class="order-column">

                <div class="card order-card">
                    <div class="card-body">
                        <div>
                            <h3>Commande #104</h3>
                            <span class="badge">En préparation</span>
                        </div>
                        <p>2x Pizza Regina, 1x Tiramisu</p>
                        <p><small>Passée le : 22/02/2026 à 18h30</small></p>
                    </div>
                </div>

                <div class="card order-card">
                    <div class="card-body">
                        <div>
                            <h3>Commande #102</h3>
                            <span class="badge">Livrée</span>
                        </div>
                        <p>1x Pizza Calzone </p>
                        <p><small>Passée le : 20/02/2026 à 12h15</small></p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="footer-black">
        <p>© 2026 Bella Ciao - Tous droits réservés</p>
    </footer>

                  <script type="text/javascript" src="theme.js"></script>
</body>

</html>
