<?php session_start(); ?>
<!DOCTYPE html>

<head>
    <title>Mes informations</title>
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
    <div class="form-wrapper">
        <div class="nomrestaurant" id="entete-blanc">Mes Informations:</div>
        <br><br>

        <div class="ligne">
            <div class="sousligne">
                <label>Nom</label><br>
                <span>Dupont</span>
                <span><a href="inscription.php" class="edit-link">✎</a></span>
            </div>
            <div class="sousligne">
                <label>Prénom</label><br>
                <span>Jean</span>
                <span><a href="inscription.php" class="edit-link">✎</a></span>
            </div>
        </div>

        <div class="ligne">
            <div class="sousligne">
                <label>Email</label><br>
                <span>jean.dupont@email.com</span>
                <span><a href="inscription.php" class="edit-link">✎</a></span>
            </div>
            <div class="sousligne">
                <label>Téléphone</label><br>
                <span>06 01 02 03 04</span>
                <span><a href="inscription.php" class="edit-link">✎</a></span>
            </div>
        </div>

        <div class="ligne">
            <div class="sousligne">
                <label>Code postal</label><br>
                <span>75001</span>
                <span><a href="inscription.php" class="edit-link">✎</a></span>
            </div>
            <div class="sousligne">
                <label>Adresse de livraison</label><br>
                <span>12 rue de la Paix, Paris</span>
                <span><a href="inscription.php" class="edit-link">✎</a></span>
            </div>
        </div>

        <div class="sousligne">
            <label>Éléments supplémentaires</label>
            <span>Pas d'oignons dans la pizza, merci !</span>
            <span><a href="inscription.php" class="edit-link">✎</a></span>
        </div>

    </div>

    <footer>
        <p>© 2026 Bella Ciao - Tous droits réservés</p>
    </footer>
</body>

</html>
