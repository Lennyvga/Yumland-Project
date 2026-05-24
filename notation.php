<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] !== 'client') {
    header('Location: connexion.php');
    exit();
}

$id_client = $_SESSION['auth']['id'];
$data_cmd  = json_decode(file_get_contents("commandes.json"), true);

$commandes_a_noter = [];
foreach ($data_cmd['commandes'] as $c) {
    if ($c['id_client'] == $id_client && $c['statut'] === 'livree' && empty($c['note'])) {
        $commandes_a_noter[] = $c;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Notation - Bella Ciao</title>
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

            <button id="bouton-theme" class="btn-theme">🌙</button>
        </div>
    </div>
</nav>

<div class="notation-wrapper">

    <h2 class="section-title">⭐ Notez vos commandes</h2>
    <p class="notation-subtitle">Dites-nous ce que vous avez pensé de votre expérience chez Bella Ciao.</p>

    <?php if (empty($commandes_a_noter)): ?>

    <p>Aucune commande à noter pour l'instant.</p>
    <p>Les commandes livrées apparaîtront ici pour que vous puissiez les noter.</p>
    <div class="send">
        <a href="menu.php" class="btn">Retourner au menu</a>
    </div>

    <?php else: ?>

    <div class="notation-section">
        <?php foreach ($commandes_a_noter as $cmd): ?>
        <div class="commande-a-noter" id="bloc-<?php echo $cmd['id']; ?>">
            <h3>Commande <?php echo htmlspecialchars($cmd['id']); ?></h3>
            <p><small>Passée le : <?php echo htmlspecialchars($cmd['datetime']); ?></small></p>
            <ul>
                <?php foreach ($cmd['articles'] as $a): ?>
                <li><?php echo $a['quantite']; ?>× <?php echo htmlspecialchars($a['nom']); ?></li>
                <?php endforeach; ?>
            </ul>

            <div class="rating" id="stars-<?php echo $cmd['id']; ?>">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio"
                       id="star<?php echo $i . '-' . $cmd['id']; ?>"
                       name="rating-<?php echo $cmd['id']; ?>"
                       value="<?php echo $i; ?>">
                <label for="star<?php echo $i . '-' . $cmd['id']; ?>">★</label>
                <?php endfor; ?>
            </div>

            <div class="notation-form">
                <textarea id="comment-<?php echo $cmd['id']; ?>"
                          placeholder="Votre commentaire (optionnel)..."></textarea>
                <div class="send">
                    <button class="btn" id="btn-<?php echo $cmd['id']; ?>"
                            onclick="envoyerNote('<?php echo $cmd['id']; ?>')">
                        Envoyer ma note
                    </button>
                </div>
            </div>

            <div class="msg-flash" id="msg-<?php echo $cmd['id']; ?>"></div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php endif; ?>

</div>

<footer class="footer-black">Bella Ciao Ristorante © 2026</footer>

<script>

    function envoyerNote(idCmd) {
        const radios = document.querySelectorAll('input[name="rating-' + idCmd + '"]');
        let note = null;

        radios.forEach(function(r) {
            if (r.checked) {
                note = parseInt(r.value);
            }
        });

        if (!note) {
            afficherMsg(idCmd, '⚠️ Veuillez choisir une note (1 à 5 étoiles).', 'err');
            return;
        }

        const commentaire = document.getElementById('comment-' + idCmd).value.trim();
        const btn         = document.getElementById('btn-' + idCmd);
        btn.disabled      = true;
        btn.textContent   = '⏳ Envoi...';

        fetch('api_noter_commande.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({ id_commande: idCmd, note: note, commentaire: commentaire })
        })
        .then(function(r) {
            return r.json();
        })
        .then(function(data) {
            if (data.success) {
                afficherMsg(idCmd, '✅ Note envoyée ! Merci pour votre avis.', 'ok');
                btn.style.display = 'none';
                radios.forEach(function(r) {
                    r.disabled = true;
                });
                document.getElementById('comment-' + idCmd).disabled = true;
            } else {
                afficherMsg(idCmd, '❌ ' + (data.message || 'Erreur lors de l\'envoi.'), 'err');
                btn.disabled    = false;
                btn.textContent = 'Envoyer ma note';
            }
        })
        .catch(function() {
            afficherMsg(idCmd, '❌ Erreur réseau. Réessayez.', 'err');
            btn.disabled    = false;
            btn.textContent = 'Envoyer ma note';
        });
    }


    function afficherMsg(idCmd, texte, type) {
        const msg         = document.getElementById('msg-' + idCmd);
        msg.style.display = 'block';
        msg.className     = 'msg-flash msg-' + type;
        msg.textContent   = texte;
    }

</script>

        <script type="text/javascript" src="theme.js"></script>
</body>
</html>
