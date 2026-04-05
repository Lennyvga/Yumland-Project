<?php
session_start();
$json = file_get_contents("plats.json");
$data = json_decode($json, true);
$plats = $data['plats'];
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Bella Ciao Ristorante - Menu</title>
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


    <section class="menu">
        <div class="container">
            <h2 class="section-title">Notre Carte</h2>


            <div class="search">
                <input type="text" placeholder="Rechercher un plat...">
                <button class="btn">Rechercher</button>
            </div>


            <div class="filters">
                <button class="filter-btn">Tous</button>
                <button class="filter-btn">Pizzas</button>
                <button class="filter-btn">Pâtes</button>
                <button class="filter-btn">Desserts</button>
                <button class="filter-btn">Allergènes</button>
            </div>



            <div class="cards">

                <div class="cards">
                    <?php foreach($plats as $plat): ?>
                    <div class="card" data-category="<?php echo $plat['categorie']; ?>">
                        <img src="images/<?php echo $plat['image']; ?>" alt="<?php echo $plat['nom']; ?>">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo $plat['nom']; ?></h3>
                            <p><?php echo $plat['description']; ?></p>
                            <div class="price"><?php echo $plat['prix']; ?>€</div>
                            <form method="POST" action="ajouter.php">
                                <input type="hidden" name="id_produit" value="<?php echo $plat['id']; ?>">
                                <button type="submit" class="btn">Commander</button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>


            </div>

            <div class="send">
                <a class="btn" href="panier.php">Mon panier de commande</a>
            </div>

        </div>
    </section>


    <footer>
        Bella Ciao Ristorante © 2026
    </footer>

</body>

</html>


<footer>
    Bella Ciao Ristorante © 2026
</footer>

</body>

</html>
