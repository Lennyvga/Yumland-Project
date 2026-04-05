<!DOCTYPE html>
<html>
   
  <head>
        <title>Connexion</title>
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

    </body>

      

<div class="form-wrapper header-premium">
    <h1 class="nomrestaurant">Bella Ciao <span class="plus-symbol">+</span></h1>
    <p>Devenez un membre VIP et profitez de la Dolce Vita en illimité!</p>
    
    <br>
    <div class="status-box">
        <p>Votre statut actuel : <strong>Membre Gratuit</strong></p>
        <button class="btn btn-premium">Passer à Bella Ciao +</button>
    </div>
</div>

</div>

    <footer class="footer-white">
        <p>© 2026 Bella Ciao - Tous droits réservés</p>
    </footer>

    </body>
</html>
