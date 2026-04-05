<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Bella Ciao Ristorante</title>
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

            </div>
        </div>
    </nav>


    <section class="hero">
        <div class="container">
            <div class="hero-overlay">

                <h1>Authentique cuisine italienne</h1>

                <p>Des plats faits maison avec passion</p>

            </div>
        </div>
    </section>



    <div class="container">

        <div class="search">

            <input type="text" placeholder="Rechercher un plat...">

            <button class="btn">Rechercher</button>

        </div>

    </div>


    <div class="container">

        <h2 class="section-title">Nos spécialités</h2>

        <div class="cards">

            <div class="card">
                <img src="images/pizza-margherita.jpg">
                <div class="card-body">
                    <div class="card-title">Pizza Margherita</div>
                    <div class="price">12€</div>
                    <form method="POST" action="ajouter.php">
                        <input type="hidden" name="id_produit" value="p1">
                        <button type="submit" class="btn">Commander</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <img src="images/pasta.jpg">
                <div class="card-body">
                    <div class="card-title">Pasta Carbonara</div>
                    <div class="price">14€</div>
                    <form method="POST" action="ajouter.php">
                        <input type="hidden" name="id_produit" value="p6">
                        <button type="submit" class="btn">Commander</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <img src="images/tiramisu.jpg">
                <div class="card-body">
                    <div class="card-title">Tiramisu</div>
                    <div class="price">7€</div>
                    <form method="POST" action="ajouter.php">
                        <input type="hidden" name="id_produit" value="p8">
                        <button type="submit" class="btn">Commander</button>
                    </form>
                </div>
            </div>

        </div>

    </div>

    <footer class="footer-black">

        Bella Ciao Ristorante © 2026

    </footer>

</body>

</html>
