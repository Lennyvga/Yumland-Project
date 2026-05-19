<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] !== 'restaurateur') {
    header('Location: connexion.php');
    exit();
}

$donnees_commandes = json_decode(file_get_contents("commandes.json"), true);
$donnees_users     = json_decode(file_get_contents("utilisateurs.json"), true);

$livreurs = array_filter($donnees_users['utilisateurs'], function($u) {
    return $u['role'] === 'livreur';
});
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Bella Ciao - Cuisine</title>
</head>
<body class="page-restaurateur">

<nav class="navbar">
    <div class="container nav-content">
        <div class="logo">Bella Ciao</div>
        <div class="nav-links">
            <a href="index.php">Accueil</a>
            <a href="deconnexion.php">Se déconnecter</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="section-title">Tableau de bord — Commandes</h2>

    <div class="kanban">

        <!-- COL 1 : Payées → lancer en préparation -->
        <div class="kanban-col">
            <h3>💳 Payées</h3>
            <?php $found = false;
            foreach ($donnees_commandes['commandes'] as $c):
                if ($c['statut'] !== 'accepted') continue; $found = true; ?>
            <div class="kanban-card" id="card-<?php echo $c['id']; ?>">
                <div class="cmd-ref"><?php echo $c['id']; ?></div>
                <p>👤 <?php echo htmlspecialchars($c['utilisateur']); ?></p>
                <ul>
                    <?php foreach ($c['articles'] as $a): ?>
                    <li><?php echo $a['quantite']; ?>× <?php echo htmlspecialchars($a['nom']); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><strong><?php echo $c['total']; ?>€</strong></p>
                <button class="btn" onclick="changerStatut('<?php echo $c['id']; ?>','en_preparation',null,this)">
                    👨‍🍳 Lancer en préparation
                </button>
            </div>
            <?php endforeach;
            if (!$found) { echo '<p class="empty-col">Aucune commande payée en attente.</p>'; } ?>
        </div>

        <!-- COL 2 : En préparation → assigner livreur -->
        <div class="kanban-col">
            <h3>🥘 En préparation</h3>
            <?php $found = false;
            foreach ($donnees_commandes['commandes'] as $c):
                if ($c['statut'] !== 'en_preparation') continue; $found = true; ?>
            <div class="kanban-card" id="card-<?php echo $c['id']; ?>">
                <div class="cmd-ref"><?php echo $c['id']; ?></div>
                <p>👤 <?php echo htmlspecialchars($c['utilisateur']); ?></p>
                <ul>
                    <?php foreach ($c['articles'] as $a): ?>
                    <li><?php echo $a['quantite']; ?>× <?php echo htmlspecialchars($a['nom']); ?></li>
                    <?php endforeach; ?>
                </ul>
                <select id="livreur-<?php echo $c['id']; ?>">
                    <option value="">— Choisir un livreur —</option>
                    <?php foreach ($livreurs as $l): ?>
                    <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['prenom']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="btn" onclick="lancerLivraison('<?php echo $c['id']; ?>',this)">
                    🚀 Assigner &amp; envoyer
                </button>
            </div>
            <?php endforeach;
            if (!$found) { echo '<p class="empty-col">Aucune commande en préparation.</p>'; } ?>
        </div>

        <!-- COL 3 : En livraison -->
        <div class="kanban-col">
            <h3>🚚 En livraison</h3>
            <?php $found = false;
            foreach ($donnees_commandes['commandes'] as $c):
                if ($c['statut'] !== 'en_livraison') continue; $found = true;
                $nom_livreur = 'Inconnu';
                foreach ($donnees_users['utilisateurs'] as $u) {
                    if ($u['id'] == $c['id_livreur']) {
                        $nom_livreur = $u['prenom'];
                        break;
                    }
                } ?>
            <div class="kanban-card">
                <div class="cmd-ref"><?php echo $c['id']; ?></div>
                <p>👤 <?php echo htmlspecialchars($c['utilisateur']); ?></p>
                <p>🛵 <?php echo htmlspecialchars($nom_livreur); ?></p>
                <p>📍 <?php echo htmlspecialchars($c['adresse']); ?></p>
            </div>
            <?php endforeach;
            if (!$found) { echo '<p class="empty-col">Aucune commande en livraison.</p>'; } ?>
        </div>

        <!-- COL 4 : Livrées -->
        <div class="kanban-col">
            <h3>✅ Livrées</h3>
            <?php $found = false;
            foreach ($donnees_commandes['commandes'] as $c):
                if ($c['statut'] !== 'livree') continue; $found = true; ?>
            <div class="kanban-card">
                <div class="cmd-ref"><?php echo $c['id']; ?></div>
                <p>👤 <?php echo htmlspecialchars($c['utilisateur']); ?></p>
            </div>
            <?php endforeach;
            if (!$found) { echo '<p class="empty-col">Aucune commande livrée.</p>'; } ?>
        </div>

    </div>
</div>

<div class="toast msg-ok" id="toast-ok"></div>
<div class="toast msg-err" id="toast-err"></div>

<footer>Bella Ciao Ristorante © 2026</footer>

<script>

    function showToast(msg, type) {
        const t = document.getElementById('toast-' + type);
        t.textContent   = msg;
        t.style.display = 'block';
        setTimeout(function() {
            t.style.display = 'none';
        }, 3500);
    }


    function changerStatut(idCmd, nouveauStatut, idLivreur, btn) {
        btn.disabled    = true;
        btn.textContent = '⏳ Envoi...';

        fetch('api_statut_commande.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ id_commande: idCmd, nouveau_statut: nouveauStatut, id_livreur: idLivreur })
        })
        .then(function(r) {
            return r.json();
        })
        .then(function(data) {
            if (data.success) {
                showToast('✅ Commande ' + idCmd + ' mise à jour !', 'ok');
                const card = document.getElementById('card-' + idCmd);
                if (card) {
                    card.remove();
                }
            } else {
                showToast('❌ ' + (data.message || 'Erreur'), 'err');
                btn.disabled    = false;
                btn.textContent = 'Réessayer';
            }
        })
        .catch(function() {
            showToast('❌ Erreur réseau', 'err');
            btn.disabled    = false;
            btn.textContent = 'Réessayer';
        });
    }


    function lancerLivraison(idCmd, btn) {
        const select    = document.getElementById('livreur-' + idCmd);
        const idLivreur = select ? select.value : null;

        if (!idLivreur) {
            showToast('⚠️ Veuillez choisir un livreur.', 'err');
            return;
        }

        changerStatut(idCmd, 'en_livraison', idLivreur, btn);
    }

</script>

</body>
</html>
