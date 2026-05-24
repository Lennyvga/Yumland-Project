<?php
session_start();

// si client  pas connecté, retour à la page de connexion
if (!isset($_SESSION['auth'])) {
    header('Location: connexion.php');
    exit();
}

$mon_id = $_SESSION['auth']['id'];
$utilisateurs_data = json_decode(file_get_contents("utilisateurs.json"), true);

// Recherche utilisateur connecté dans le fichier JSON
$moi = null;
foreach ($utilisateurs_data['utilisateurs'] as $u) {
    if ($u['id'] === $mon_id) {
        $moi = $u;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Bella Ciao - Mon Profil</title>
</head>
<body class="page-inscription">

    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">Bella Ciao</div>
            <div class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="deconnexion.php">Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="form-wrapper">
            <div class="nomrestaurant">Mon Profil</div>
            
            <div class="msg-flash" id="msg-profil" style="display: none;"></div>

            <form id="form-profil">
                <div class="ligne">
                    <div>
                        <label>Prénom</label>
                        <input type="text" id="prenom" value="<?php echo htmlspecialchars($moi['prenom'] ?? ''); ?>" required>
                    </div>
                    <div>
                        <label>Nom</label>
                        <input type="text" id="nom" value="<?php echo htmlspecialchars($moi['nom'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="ligne">
                    <div>
                        <label>Téléphone</label>
                        <input type="text" id="telephone" value="<?php echo htmlspecialchars($moi['telephone'] ?? ''); ?>">
                    </div>
                    <div>
                        <label>Code Postal</label>
                        <input type="text" id="code_postal" value="<?php echo htmlspecialchars($moi['code_postal'] ?? ''); ?>">
                    </div>
                </div>

                <div class="ligne">
                    <div>
                        <label>Adresse de livraison</label>
                        <input type="text" id="adresse" value="<?php echo htmlspecialchars($moi['adresse'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="send">
                    <button type="submit" class="btn" id="btn-enregistrer">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.getElementById('form-profil').addEventListener('submit', function(e) {
        e.preventDefault(); // Empêche le rechargement de la page 

        const btn = document.getElementById('btn-enregistrer');
        const msg = document.getElementById('msg-profil');
        
        btn.disabled = true;
        btn.textContent = 'Enregistrement...';

        // collecte donnée
        const donnees = {
            prenom: document.getElementById('prenom').value,
            nom: document.getElementById('nom').value,
            telephone: document.getElementById('telephone').value,
            code_postal: document.getElementById('code_postal').value,
            adresse: document.getElementById('adresse').value
        };

        // Envoi 
        fetch('api_modifier_profil.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(donnees)
        })
        .then(function(reponse) {
            return reponse.json();
        })
        .then(function(data) {
            msg.style.display = 'block';
            
            if (data.success) {
                msg.className = 'msg-flash msg-ok';
                msg.textContent = 'Le profil a ete mis a jour avec succes.';
            } else {
                msg.className = 'msg-flash msg-err';
                msg.textContent = 'Erreur : ' + data.message;
            }
            
            btn.disabled = false;
            btn.textContent = 'Enregistrer';
        })
        .catch(function() {
            msg.style.display = 'block';
            msg.className = 'msg-flash msg-err';
            msg.textContent = 'Erreur de connexion au serveur.';
            btn.disabled = false;
            btn.textContent = 'Enregistrer';
        });
    });
    </script>
</body>
</html>
