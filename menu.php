<?php
session_start();
$json = file_get_contents("plats.json");
$data = json_decode($json, true);
$plats = $data['plats'];

$json_menus = file_get_contents("menus.json");
$data_menus = json_decode($json_menus, true);
$menus = $data_menus['menus'];

$plats_par_id = [];
foreach ($plats as $plat) {
    $plats_par_id[$plat['id']] = $plat;
} 

$categorie_active = isset($_GET['categorie']) ? $_GET['categorie'] : 'tous';
$recherche = isset($_GET['recherche']) ? strtolower(trim($_GET['recherche'])) : '';

$plats_temp = [];
foreach ($plats as $plat) {
    $match_categorie = ($categorie_active === 'tous' || $plat['categorie'] === $categorie_active);
    $match_recherche = ($recherche === '' || strpos(strtolower($plat['nom']), $recherche) !== false || strpos(strtolower($plat['description']), $recherche) !== false);
    if ($match_categorie && $match_recherche) {
        $plats_temp[] = $plat;
    }
}
$plats = $plats_temp;
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
            <h2 class="section-title">Nos Menus</h2>
            <div class="cards">
                <?php foreach($menus as $menu) { ?>
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($menu['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($menu['description']); ?></p>
                        <p><strong>Pour :</strong> <?php echo $menu['nombre_personnes_min']; ?> personne(s) minimum</p>
                        <p><strong>Disponible :</strong> <?php echo implode(', ', $menu['disponible_le']); ?></p>
                        <p><strong>Créneaux :</strong> <?php echo implode(' • ', $menu['creneaux']); ?></p>
                        <p><strong>Plats inclus :</strong></p>
                        <ul>
                            <?php foreach($menu['plats'] as $id_plat) { ?>
                            <?php if (isset($plats_par_id[$id_plat])) { ?>
                            <li><?php echo htmlspecialchars($plats_par_id[$id_plat]['nom']); ?> —
                                <?php echo $plats_par_id[$id_plat]['prix']; ?>€</li>
                            <?php } ?>
                            <?php } ?>
                        </ul>
                        <div class="price"><?php echo $menu['prix_total']; ?>€</div>
                    </div>
                </div>
                <?php } ?>
            </div>

            <h2 class="section-title">Notre Carte</h2>
            <form method="GET" action="menu.php" class="search">
                <input type="hidden" name="categorie" value="<?php echo htmlspecialchars($categorie_active); ?>">
                <input type="text" name="recherche" placeholder="Rechercher un plat..."
                    value="<?php echo htmlspecialchars($recherche); ?>">
                <button type="submit" class="btn">Rechercher</button>
            </form>


            <div class="filters">
                <a href="menu.php?categorie=tous&recherche=<?php echo urlencode($recherche); ?>"
                    class="filter-btn <?php echo $categorie_active === 'tous' ? 'active' : ''; ?>">Tous</a>
                <a href="menu.php?categorie=entrees&recherche=<?php echo urlencode($recherche); ?>"
                    class="filter-btn <?php echo $categorie_active === 'entrees' ? 'active' : ''; ?>">Entrées</a>
                <a href="menu.php?categorie=pizzas&recherche=<?php echo urlencode($recherche); ?>"
                    class="filter-btn <?php echo $categorie_active === 'pizzas' ? 'active' : ''; ?>">Pizzas</a>
                <a href="menu.php?categorie=pates&recherche=<?php echo urlencode($recherche); ?>"
                    class="filter-btn <?php echo $categorie_active === 'pates' ? 'active' : ''; ?>">Pâtes</a>
                <a href="menu.php?categorie=desserts&recherche=<?php echo urlencode($recherche); ?>"
                    class="filter-btn <?php echo $categorie_active === 'desserts' ? 'active' : ''; ?>">Desserts</a>
                <a href="menu.php?categorie=boissons&recherche=<?php echo urlencode($recherche); ?>"
                    class="filter-btn <?php echo $categorie_active === 'boissons' ? 'active' : ''; ?>">Boissons</a>
            </div>


            <div class="cards">

                <div class="cards">
                    <?php foreach($plats as $plat): ?>
                    <div class="card" data-category="<?php echo $plat['categorie']; ?>">
                        <img src="images/<?php echo $plat['image']; ?>" alt="<?php echo $plat['nom']; ?>">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo $plat['nom']; ?></h3>
                            <p><?php echo $plat['description']; ?></p>
                            <?php if (!empty($plat['allergenes'])): ?>
                            <div class="allergenes">
                                <span class="allergenes-label">⚠️ Allergènes :</span>
                                <?php foreach($plat['allergenes'] as $allergene): ?>
                                <span class="allergene-tag"><?php echo ucfirst($allergene); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php else: ?>
                            <div class="allergenes">
                                <span class="allergenes-label">✅ Sans allergènes</span>
                            </div>
                            <?php endif; ?>
                            <div class="price"><?php echo $plat['prix']; ?>€</div>
                            <form method="POST" action="ajouter.php">
                                <input type="hidden" name="id_produit" value="<?php echo $plat['id']; ?>">
                                <button type="submit" class="btn">Commander</button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($plats)): ?>
                    <p>Aucun plat trouvé.</p>
                    <?php endif; ?>
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
