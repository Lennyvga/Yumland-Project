<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] !== 'livreur') {
    header('Location: connexion.php');
    exit();
}

$mon_id = $_SESSION['auth']['id'];
$data   = json_decode(file_get_contents("commandes.json"), true);

$ma_commande = null;
foreach ($data['commandes'] as $com) {
    if (isset($com['id_livreur']) && $com['id_livreur'] == $mon_id && $com['statut'] === 'en_livraison') {
        $ma_commande = $com;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Bella Ciao - Livraison</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<nav class="navbar">
    <div class="container nav-content">
        <div class="logo">Bella Ciao</div>
        <div class="nav-links">
            <a href="index.php">Accueil</a>
            <a href="deconnexion.php">Se déconnecter</a>

            <button id="bouton-theme" class="btn-theme">🌙</button>
        </div>
    </div>
</nav>

<div class="container">

    <?php if ($ma_commande): ?>

    <div class="card delivery-card" id="carte-livraison">
        <div class="card-body delivery-detail">
            <h2 class="card-title">🛵 Ma livraison en cours</h2>

            <p><span class="detail-label">Commande :</span> <?php echo htmlspecialchars($ma_commande['id']); ?></p>
            <p><span class="detail-label">Client :</span> <?php echo htmlspecialchars($ma_commande['utilisateur']); ?></p>
            <p><span class="detail-label">Adresse :</span> <?php echo htmlspecialchars($ma_commande['adresse']); ?></p>

            <?php if (!empty($ma_commande['interphone'])): ?>
            <p><span class="detail-label">Interphone :</span> <?php echo htmlspecialchars($ma_commande['interphone']); ?></p>
            <?php endif; ?>

            <?php if (!empty($ma_commande['etage'])): ?>
            <p><span class="detail-label">Étage :</span> <?php echo htmlspecialchars($ma_commande['etage']); ?></p>
            <?php endif; ?>

            <?php if (!empty($ma_commande['commentaire'])): ?>
            <p><span class="detail-label">Commentaire :</span> <?php echo htmlspecialchars($ma_commande['commentaire']); ?></p>
            <?php endif; ?>

            <div class="delivery-actions">
                <a class="btn full-width"
                   href="https://maps.google.com/?q=<?php echo urlencode($ma_commande['adresse']); ?>"
                   target="_blank">
                    🗺️ Ouvrir dans Google Maps
                </a>
                <button class="btn full-width" id="btn-livree"
                        onclick="confirmerLivraison('<?php echo $ma_commande['id']; ?>')">
                    ✅ J'ai livré la commande
                </button>
            </div>

            <div class="msg-flash" id="msg-livraison"></div>
        </div>
    </div>

    <?php else: ?>

    <div class="card delivery-card">
        <div class="card-body">
            <h2 class="card-title">🛵 Pas de livraison en cours</h2>
            <p>Aucune commande ne vous est actuellement assignée.</p>
        </div>
    </div>

    <?php endif; ?>

</div>

<footer class="footer-black">Bella Ciao Ristorante © 2026</footer>

<script>

    function confirmerLivraison(idCmd) {
        const btn = document.getElementById('btn-livree');
        btn.disabled    = true;
        btn.textContent = '⏳ Confirmation...';

        fetch('api_statut_commande.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ id_commande: idCmd, nouveau_statut: 'livree' })
        })
        .then(function(r) {
            return r.json();
        })
        .then(function(data) {
            const msg = document.getElementById('msg-livraison');
            msg.style.display = 'block';

            if (data.success) {
                msg.className     = 'msg-flash msg-ok';
                msg.textContent   = '✅ Livraison confirmée ! Bien joué 🎉';
                btn.style.display = 'none';
            } else {
                msg.className   = 'msg-flash msg-err';
                msg.textContent = '❌ ' + (data.message || 'Erreur lors de la confirmation.');
                btn.disabled    = false;
                btn.textContent = '✅ J\'ai livré la commande';
            }
        })
        .catch(function() {
            const msg = document.getElementById('msg-livraison');
            msg.style.display = 'block';
            msg.className     = 'msg-flash msg-err';
            msg.textContent   = '❌ Erreur réseau. Réessayez.';
            btn.disabled      = false;
            btn.textContent   = '✅ J\'ai livré la commande';
        });
    }

</script>

    <script type="text/javascript" src="theme.js"></script>
</body>
</html>
