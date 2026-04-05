<?php
session_start();

// Vérifier que l'utilisateur est connecté et est admin
if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

// Lire le fichier JSON
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
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="section-title">Liste des utilisateurs</h2>

        <table class="panier-table">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Inscription</th>
                <th>Actions</th>
            </tr>

            <?php
            foreach ($utilisateurs as $utilisateur) {
                echo '<tr>';
                echo '<td>' . $utilisateur['nom'] . '</td>';
                echo '<td>' . $utilisateur['prenom'] . '</td>';
                echo '<td>' . $utilisateur['email'] . '</td>';
                echo '<td>' . $utilisateur['role'] . '</td>';
                echo '<td>' . $utilisateur['statut'] . '</td>';
                echo '<td>' . $utilisateur['date_inscription'] . '</td>';
                echo '<td>';
                echo '<form method="POST" action="bloquer_utilisateur.php">';
                echo '<input type="hidden" name="id" value="' . $utilisateur['id'] . '">';
                echo '<button type="submit" class="btn">Bloquer</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>

    <footer>
        <p>© 2026 Bella Ciao - Tous droits réservés</p>
    </footer>
</body>
</html>
