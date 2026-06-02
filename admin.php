<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

$json = file_get_contents(__DIR__ . "/utilisateurs.json");
$data = json_decode($json, true);
$utilisateurs = $data['utilisateurs'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Administration - Bella Ciao</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">Bella Ciao</div>
            <div class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="admin.php">Administration</a>
                <a href="deconnexion.php">Se déconnecter</a>
                <button id=\"bouton-theme\" class=\"btn-theme\">🌙</button>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="section-title">Liste des utilisateurs</h2>

        <div class="msg-flash" id="msg-admin" style="display: none;"></div>

        <table class="panier-table">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Rang Client</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>

            <?php
            foreach ($utilisateurs as $utilisateur) {
                if ($utilisateur['id'] === $_SESSION['auth']['id']) continue;
                
                echo '<tr>';
                echo '<td>' . htmlspecialchars($utilisateur['nom']) . '</td>';
                echo '<td>' . htmlspecialchars($utilisateur['prenom']) . '</td>';
                echo '<td>' . htmlspecialchars($utilisateur['email'] ?? '') . '</td>';
                echo '<td>' . htmlspecialchars($utilisateur['role']) . '</td>';
                
                //  Menu déroulant pour modifier le rang en asynchrone
                echo '<td>';
                echo '<select class="select-rang" data-id="' . $utilisateur['id'] . '">';
                echo '<option value="standard" ' . ((!isset($utilisateur['rang']) || $utilisateur['rang'] == 'standard') ? 'selected' : '') . '>Standard</option>';
                echo '<option value="premium" ' . ((isset($utilisateur['rang']) && $utilisateur['rang'] == 'premium') ? 'selected' : '') . '>Premium</option>';
                echo '<option value="vip" ' . ((isset($utilisateur['rang']) && $utilisateur['rang'] == 'vip') ? 'selected' : '') . '>VIP</option>';
                echo '</select>';
                echo '</td>';

                // Cellule pour afficher le statut en temps réel
                echo '<td id="status-text-' . $utilisateur['id'] . '">' . htmlspecialchars($utilisateur['statut'] ?? 'actif') . '</td>';
                
                echo '<td>';
                //  Bouton de blocage asynchrone 
                $texte_bouton = (isset($utilisateur['statut']) && $utilisateur['statut'] === 'bloque') ? 'Débloquer' : 'Bloquer';
                echo '<button class="btn btn-action-blocage" data-id="' . $utilisateur['id'] . '" data-statut="' . ($utilisateur['statut'] ?? 'actif') . '">' . $texte_bouton . '</button>';
                echo '</td>';
                
                echo '</tr>';
            }
            ?>
        </table>
    </div>

    <footer class="footer-black">
        <p>© 2026 Bella Ciao - Tous droits réservés</p>
    </footer>

    <script type="text/javascript" src="theme.js"></script>

    <script>
    // Gestion asynchrone du bouton Bloquer/Débloquer
    const boutons = document.querySelectorAll('.btn-action-blocage');
    boutons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            const statutActuel = this.getAttribute('data-statut');
            const actionEnvoyee = (statutActuel === 'bloque') ? 'debloquer' : 'bloquer';
            
            const params = new URLSearchParams();
            params.append('id', userId);
            params.append('action', actionEnvoyee);

            fetch('bloquer_utilisateur.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params
            })
            .then(function() {
                const msg = document.getElementById('msg-admin');
                msg.style.display = 'block';
                msg.className = 'msg-flash msg-ok';
                msg.textContent = 'Le statut de l utilisateur a ete modifie.';

                if (actionEnvoyee === 'bloquer') {
                    btn.setAttribute('data-statut', 'bloque');
                    btn.textContent = 'Débloquer';
                    document.getElementById('status-text-' + userId).textContent = 'bloque';
                } else {
                    btn.setAttribute('data-statut', 'actif');
                    btn.textContent = 'Bloquer';
                    document.getElementById('status-text-' + userId).textContent = 'actif';
                }
            });
        });
    });

    //  Gestion asynchrone du changement de Rang
    const selects = document.querySelectorAll('.select-rang');
    selects.forEach(function(select) {
        select.addEventListener('change', function() {
            const userId = this.getAttribute('data-id');
            const nouveauRang = this.value;

            const params = new URLSearchParams();
            params.append('id', userId);
            params.append('rang', nouveauRang);

            fetch('modifier_rang_utilisateur.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params
            })
            .then(function() {
                const msg = document.getElementById('msg-admin');
                msg.style.display = 'block';
                msg.className = 'msg-flash msg-ok';
                msg.textContent = 'Le rang de l utilisateur a ete mis a jour.';
            });
        });
    });
    </script>
</body>

</html>
