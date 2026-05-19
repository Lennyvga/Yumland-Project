<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] !== 'client') {
    header('Location: connexion.php');
    exit();
}

$id_cmd = $_GET['id'] ?? null;
if (!$id_cmd) { header('Location: commandes.php'); exit(); }

$data_cmd   = json_decode(file_get_contents("commandes.json"), true);
$data_plats = json_decode(file_get_contents("plats.json"), true);
$plats      = $data_plats['plats'];

$commande = null;
foreach ($data_cmd['commandes'] as $c) {
    if ($c['id'] === $id_cmd && $c['id_client'] == $_SESSION['auth']['id']) {
        $commande = $c;
        break;
    }
}

if (!$commande || $commande['statut'] !== 'accepted') {
    header('Location: commandes.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier ma commande - Bella Ciao</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="container nav-content">
        <div class="logo">Bella Ciao</div>
        <div class="nav-links">
            <a href="index.php">Accueil</a>
            <a href="menu.php">Menu</a>
            <div class="dropdown">
                <a href="#" class="nav-links">Profil ⏷</a>
                <div class="dropdown-content">
                    <a href="informations.php">Mes informations</a>
                    <a href="commandes.php">Mes commandes</a>
                    <a href="compte+.php">Mon compte Bella Ciao +</a>
                </div>
            </div>
            <a href="deconnexion.php">Se déconnecter</a>
        </div>
    </div>
</nav>

<div class="container page-restaurateur">
    <h2 class="section-title">Modifier la commande <?php echo htmlspecialchars($id_cmd); ?></h2>
    <p>Vous pouvez ajouter ou retirer des plats tant que la commande n'est pas en préparation.</p>

    <script>
        const ARTICLES_INITIAUX = <?php echo json_encode($commande['articles']); ?>;
        const TOTAL_INITIAL     = <?php echo $commande['total']; ?>;
        const CMD_ID            = "<?php echo $commande['id']; ?>";
        const TOUS_PLATS        = <?php echo json_encode($plats); ?>;
    </script>

    <div class="modifier-grid">

        <div class="modifier-col">
            <h3>🧾 Articles dans la commande</h3>
            <div id="liste-commande"></div>
        </div>

        <div class="modifier-col">
            <h3>➕ Ajouter un plat</h3>
            <div class="search">
                <input type="text" id="recherche-ajout" placeholder="Rechercher un plat...">
            </div>
            <div id="liste-ajout"></div>
        </div>

    </div>

    <div class="total-bar">
        <div>
            <div>Total initial : <strong><?php echo $commande['total']; ?>€</strong></div>
            <div id="diff-msg"></div>
        </div>
        <div class="total-montant">Nouveau total : <span id="total-valeur">--</span>€</div>
        <button class="btn" id="btn-valider" onclick="validerModification()">Valider les modifications</button>
    </div>

    <div class="msg-flash" id="msg-flash"></div>
</div>

<footer>Bella Ciao Ristorante © 2026</footer>

<script>

    let articlesCmd = {};

    ARTICLES_INITIAUX.forEach(function(a) {
        articlesCmd[String(a.id)] = { nom: a.nom, prix: a.prix, quantite: a.quantite };
    });


    function renderCommande() {
        const conteneur = document.getElementById('liste-commande');
        conteneur.innerHTML = '';
        const ids = Object.keys(articlesCmd);

        if (ids.length === 0) {
            conteneur.innerHTML = '<p class="empty-col">Aucun article dans la commande.</p>';
        } else {
            ids.forEach(function(id) {
                const a   = articlesCmd[id];
                const row = document.createElement('div');
                row.className = 'article-row';
                row.innerHTML =
                    '<div>' +
                        '<div class="article-nom">' + a.nom + '</div>' +
                        '<div class="article-prix">' + (a.prix * a.quantite).toFixed(2) + '€ (' + a.prix + '€ × ' + a.quantite + ')</div>' +
                    '</div>' +
                    '<div class="qte-ctrl">' +
                        '<button onclick="changerQte(\'' + id + '\', -1)">−</button>' +
                        '<span>' + a.quantite + '</span>' +
                        '<button onclick="changerQte(\'' + id + '\', +1)">+</button>' +
                    '</div>';
                conteneur.appendChild(row);
            });
        }

        majTotal();
    }


    function renderCatalogue(filtre) {
        if (filtre === undefined) {
            filtre = '';
        }
        filtre = filtre.toLowerCase();

        const conteneur = document.getElementById('liste-ajout');
        conteneur.innerHTML = '';

        TOUS_PLATS.forEach(function(plat) {
            if (filtre !== '' && !plat.nom.toLowerCase().includes(filtre)) {
                return;
            }

            const div = document.createElement('div');
            div.className = 'plat-ajout';
            div.innerHTML =
                '<div><strong>' + plat.nom + '</strong><br><small>' + plat.prix + '€</small></div>' +
                '<button class="btn" onclick="ajouterPlat(\'' + plat.id + '\', \'' + plat.nom.replace(/'/g, "\\'") + '\', ' + plat.prix + ')">Ajouter</button>';
            conteneur.appendChild(div);
        });
    }


    function changerQte(id, delta) {
        if (!articlesCmd[id]) {
            return;
        }

        articlesCmd[id].quantite += delta;

        if (articlesCmd[id].quantite <= 0) {
            delete articlesCmd[id];
        }

        renderCommande();
    }


    function ajouterPlat(id, nom, prix) {
        id = String(id);

        if (articlesCmd[id]) {
            articlesCmd[id].quantite++;
        } else {
            articlesCmd[id] = { nom: nom, prix: prix, quantite: 1 };
        }

        renderCommande();
    }


    function majTotal() {
        let total = 0;

        Object.values(articlesCmd).forEach(function(a) {
            total += a.prix * a.quantite;
        });

        total = Math.round(total * 100) / 100;
        document.getElementById('total-valeur').textContent = total.toFixed(2);

        const diff    = Math.round((total - TOTAL_INITIAL) * 100) / 100;
        const diffMsg = document.getElementById('diff-msg');

        if (diff > 0) {
            diffMsg.className   = 'diff-plus';
            diffMsg.textContent = '▲ Supplément à payer : +' + diff.toFixed(2) + '€';
        } else if (diff < 0) {
            diffMsg.className   = 'diff-moins';
            diffMsg.textContent = '▼ Économie : ticket de réduction de ' + Math.abs(diff).toFixed(2) + '€';
        } else {
            diffMsg.className   = '';
            diffMsg.textContent = 'Aucun changement de prix.';
        }
    }


    function validerModification() {
        const btn = document.getElementById('btn-valider');
        btn.disabled    = true;
        btn.textContent = '⏳ Envoi...';

        const articlesEnvoi = Object.entries(articlesCmd).map(function([id, a]) {
            return { id: id, nom: a.nom, prix: a.prix, quantite: a.quantite };
        });

        const total = parseFloat(document.getElementById('total-valeur').textContent);

        fetch('api_modifier_commande.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ id_commande: CMD_ID, articles: articlesEnvoi, nouveau_total: total })
        })
        .then(function(r) {
            return r.json();
        })
        .then(function(data) {
            const flash = document.getElementById('msg-flash');
            flash.style.display = 'block';

            if (data.success) {
                flash.className = 'msg-flash msg-ok';

                if (data.paiement_supplementaire) {
                    flash.textContent = '✅ Commande mise à jour ! Supplément de ' + data.montant_supplement.toFixed(2) + '€ débité (paiement n°' + data.id_paiement + ').';
                } else if (data.ticket_reduction) {
                    flash.textContent = '✅ Commande mise à jour ! Ticket de réduction de ' + Math.abs(data.montant_reduction).toFixed(2) + '€ généré.';
                } else {
                    flash.textContent = '✅ Commande mise à jour avec succès !';
                }
            } else {
                flash.className   = 'msg-flash msg-err';
                flash.textContent = '❌ ' + (data.message || 'Une erreur est survenue.');
            }

            btn.disabled    = false;
            btn.textContent = 'Valider les modifications';
        })
        .catch(function() {
            const flash = document.getElementById('msg-flash');
            flash.style.display = 'block';
            flash.className     = 'msg-flash msg-err';
            flash.textContent   = '❌ Erreur réseau. Veuillez réessayer.';
            btn.disabled        = false;
            btn.textContent     = 'Valider les modifications';
        });
    }


    document.getElementById('recherche-ajout').addEventListener('input', function() {
        renderCatalogue(this.value);
    });


    renderCommande();
    renderCatalogue();

</script>

</body>
</html>
