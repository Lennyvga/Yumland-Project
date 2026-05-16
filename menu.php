<?php
session_start();

// Chargement des menus
$json_menus = file_get_contents("menus.json");
$data_menus = json_decode($json_menus, true);
$menus = $data_menus['menus'];

// Chargement des plats
$json = file_get_contents("plats.json");
$data = json_decode($json, true);
$plats = $data['plats'];

// Indexation des plats par ID pour l'affichage dans les blocs menus
$plats_par_id = [];
foreach ($plats as $plat) {
    $plats_par_id[$plat['id']] = $plat;
}
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

            <div class="search">
                <input type="text" id="recherche" placeholder="Rechercher un plat...">
            </div>

            <div class="filters">
                <button class="filter-btn active" onclick="filtrerCategorie('tous', this)">Tous</button>
                <button class="filter-btn" onclick="filtrerCategorie('entrees', this)">Entrées</button>
                <button class="filter-btn" onclick="filtrerCategorie('pizzas', this)">Pizzas</button>
                <button class="filter-btn" onclick="filtrerCategorie('pates', this)">Pâtes</button>
                <button class="filter-btn" onclick="filtrerCategorie('desserts', this)">Desserts</button>
                <button class="filter-btn" onclick="filtrerCategorie('boissons', this)">Boissons</button>
            </div>

            <div style="margin: 20px 0; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <div>
                    <label for="tri-prix">Trier par prix :</label>
                    <select id="tri-prix" class="filter-btn" onchange="appliquerFiltresEtTri()">
                        <option value="defaut">Par défaut</option>
                        <option value="croissant">Croissant</option>
                        <option value="decroissant">Décroissant</option>
                    </select>
                </div>
                <div>
                    <label for="filtre-allergene">Masquer les allergènes :</label>
                    <select id="filtre-allergene" class="filter-btn" onchange="appliquerFiltresEtTri()">
                        <option value="aucun">Aucun (Tout afficher)</option>
                        <option value="gluten">Gluten</option>
                        <option value="lactose">Lactose</option>
                        <option value="oeufs">Œufs</option>
                        <option value="fruits_a_coques">Fruits à coques</option>
                    </select>
                </div>
            </div>

            <div class="cards" id="conteneur-plats">
                <?php foreach($plats as $plat): ?>
                <div class="card plat-item" data-categorie="<?php echo htmlspecialchars($plat['categorie']); ?>"
                    data-nom="<?php echo htmlspecialchars(strtolower($plat['nom'])); ?>"
                    data-description="<?php echo htmlspecialchars(strtolower($plat['description'])); ?>"
                    data-prix="<?php echo $plat['prix']; ?>"
                    data-allergenes="<?php echo htmlspecialchars(json_encode($plat['allergenes'] ?? [])); ?>">

                    <img src="images/<?php echo htmlspecialchars($plat['image']); ?>"
                        alt="<?php echo htmlspecialchars($plat['nom']); ?>">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($plat['nom']); ?></h3>
                        <p><?php echo htmlspecialchars($plat['description']); ?></p>

                        <?php if (!empty($plat['allergenes'])): ?>
                        <div class="allergenes">
                            <span class="allergenes-label">⚠️ Allergènes :</span>
                            <?php foreach($plat['allergenes'] as $allergene): ?>
                            <span class="allergene-tag"><?php echo ucfirst(htmlspecialchars($allergene)); ?></span>
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
            </div>

            <div class="send" style="margin-top: 30px; text-align: center;">
                <a class="btn" href="panier.php">Mon panier de commande</a>
            </div>
        </div>
    </section>

    <footer>
        Bella Ciao Ristorante © 2026
    </footer>

        <script>
    let categorieActuelle = 'tous';

    // Écouteur sur la barre de recherche
    document.getElementById('recherche').addEventListener('input', appliquerFiltresEtTri);

    function filtrerCategorie(categorie, bouton) {
        categorieActuelle = categorie;

        // Gestion visuelle du bouton filtre actif
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        bouton.classList.add('active');

        // On lance le fetch (requête async vers le serveur)
        chargerPlatsAsync();
    }

    function appliquerFiltresEtTri() {
        // Pour la recherche et le tri : on travaille sur les cartes déjà affichées (côté JS, comme avant)
        const rechercheValeur  = document.getElementById('recherche').value.toLowerCase().trim();
        const allergeneExclure = document.getElementById('filtre-allergene').value;
        const triOption        = document.getElementById('tri-prix').value;

        const conteneur  = document.getElementById('conteneur-plats');
        const platsCards = Array.from(conteneur.getElementsByClassName('plat-item'));

        platsCards.forEach(card => {
            const nom       = card.getAttribute('data-nom');
            const desc      = card.getAttribute('data-description');
            const allergenes = JSON.parse(card.getAttribute('data-allergenes'));

            const matchRecherche = (nom.includes(rechercheValeur) || desc.includes(rechercheValeur));
            const matchAllergene = (allergeneExclure === 'aucun' || !allergenes.includes(allergeneExclure));

            card.style.display = (matchRecherche && matchAllergene) ? '' : 'none';
        });

        // Tri par prix
        if (triOption === 'croissant') {
            platsCards.sort((a, b) => parseFloat(a.getAttribute('data-prix')) - parseFloat(b.getAttribute('data-prix')));
            platsCards.forEach(card => conteneur.appendChild(card));
        } else if (triOption === 'decroissant') {
            platsCards.sort((a, b) => parseFloat(b.getAttribute('data-prix')) - parseFloat(a.getAttribute('data-prix')));
            platsCards.forEach(card => conteneur.appendChild(card));
        }
    }

    // -------------------------------------------------------
    // NOUVEAU : requête async vers get_plats.php
    // Appelée quand on change de catégorie
    // -------------------------------------------------------
    function chargerPlatsAsync() {
        const conteneur = document.getElementById('conteneur-plats');

        // On affiche un message pendant le chargement
        conteneur.innerHTML = '<p style="text-align:center;color:#888;">⏳ Chargement...</p>';

        // fetch envoie la requête au serveur SANS recharger la page
        fetch('get_plats.php?categorie=' + categorieActuelle)
            .then(function(reponse) {
                return reponse.json(); // on convertit la réponse JSON en tableau JS
            })
            .then(function(plats) {
                conteneur.innerHTML = ''; // on vide le conteneur

                if (plats.length === 0) {
                    conteneur.innerHTML = '<p style="text-align:center;color:#aaa;">Aucun plat trouvé.</p>';
                    return;
                }

                // On recrée une carte HTML pour chaque plat reçu
                plats.forEach(function(plat) {
                    let allergeneHtml = '';
                    if (plat.allergenes && plat.allergenes.length > 0) {
                        let tags = plat.allergenes.map(a => '<span class="allergene-tag">' + a.charAt(0).toUpperCase() + a.slice(1) + '</span>').join('');
                        allergeneHtml = '<div class="allergenes"><span class="allergenes-label">⚠️ Allergènes :</span>' + tags + '</div>';
                    } else {
                        allergeneHtml = '<div class="allergenes"><span class="allergenes-label">✅ Sans allergènes</span></div>';
                    }

                    let carte = document.createElement('div');
                    carte.className = 'card plat-item';
                    // On garde les data-attributes pour que appliquerFiltresEtTri() continue à fonctionner
                    carte.setAttribute('data-categorie', plat.categorie);
                    carte.setAttribute('data-nom', plat.nom.toLowerCase());
                    carte.setAttribute('data-description', plat.description.toLowerCase());
                    carte.setAttribute('data-prix', plat.prix);
                    carte.setAttribute('data-allergenes', JSON.stringify(plat.allergenes || []));

                    carte.innerHTML =
                        '<img src="images/' + plat.image + '" alt="' + plat.nom + '">' +
                        '<div class="card-body">' +
                            '<h3 class="card-title">' + plat.nom + '</h3>' +
                            '<p>' + plat.description + '</p>' +
                            allergeneHtml +
                            '<div class="price">' + plat.prix + '€</div>' +
                            '<form method="POST" action="ajouter.php">' +
                                '<input type="hidden" name="id_produit" value="' + plat.id + '">' +
                                '<button type="submit" class="btn">Commander</button>' +
                            '</form>' +
                        '</div>';

                    conteneur.appendChild(carte);
                });

                // On réapplique la recherche et le tri sur les nouvelles cartes
                appliquerFiltresEtTri();
            })
            .catch(function(erreur) {
                console.error('Erreur fetch :', erreur);
                conteneur.innerHTML = '<p style="color:red;">Erreur de chargement.</p>';
            });
    }

    // Au chargement de la page, on charge tous les plats via async
    window.onload = function() {
        chargerPlatsAsync();
    };
    </script>

</body>

</html>
