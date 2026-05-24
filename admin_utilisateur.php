<?php
session_start();

// verification si c'est bien l'admin
if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] !== 'admin') {
    header('Location: connexion.php');
    exit();
}

$fichier = "utilisateurs.json";
$utilisateurs_data = json_decode(file_get_contents($fichier), true);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Bella Ciao - Administration Utilisateurs</title>
</head>
<body>

    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">Bella Ciao Admin</div>
            <div class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="deconnexion.php">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="section-title">Gestion des Utilisateurs</h1>
        
        <div class="msg-flash" id="msg-admin" style="display: none;"></div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom / Prénom</th>
                    <th>Rôle</th>
                    <th>Rang Client</th>
                    <th>Statut actuel</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilisateurs_data['utilisateurs'] as $u) { 
                    // securité auto blocage 
                    if ($u['id'] === $_SESSION['auth']['id']) {
                        continue; 
                    }
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['id']); ?></td>
                        <td><?php echo htmlspecialchars($u['nom'] . " " . $u['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($u['role']); ?></td>
                        
                        <td>
                            <select class="select-rang" data-id="<?php echo $u['id']; ?>">
                                <option value="standard" <?php if(!isset($u['rang']) || $u['rang'] == 'standard') echo 'selected'; ?>>Standard</option>
                                <option value="premium" <?php if(isset($u['rang']) && $u['rang'] == 'premium') echo 'selected'; ?>>Premium</option>
                                <option value="vip" <?php if(isset($u['rang']) && $u['rang'] == 'vip') echo 'selected'; ?>>VIP</option>
                            </select>
                        </td>

                        <td id="status-text-<?php echo $u['id']; ?>">
                            <?php echo htmlspecialchars($u['statut'] ?? 'actif'); ?>
                        </td>

                        <td>
                            <button class="btn btn-action-blocage" 
                                    data-id="<?php echo $u['id']; ?>" 
                                    data-statut="<?php echo $u['statut'] ?? 'actif'; ?>">
                                <?php echo (isset($u['statut']) && $u['statut'] === 'bloque') ? 'Débloquer' : 'Bloquer'; ?>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
    // gestion blocage/deblocage
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

                // Mise a jour de l'interface graphique  recharger
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

    // changement de rang
    const selects = document.querySelectorAll('.select-rang');
    selects.forEach(function(select) {
        select.addEventListener('change', function() {
            const userId = this.getAttribute('data-id');
            const nouveauRang = this.value;

            const params = new URLSearchParams();
            params.append('id', userId);
            params.append('rang', nouveauRang);

            // Fetch vers notre script de traitement de rang
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
