<?php
session_start();

// seul l'admin modifie les rang
if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Verification que les donnees POST sont bien reçues
if (isset($_POST['id']) && isset($_POST['rang'])) {
    $id_user = $_POST['id'];
    $nouveau_rang = $_POST['rang'];

    $fichier = "utilisateurs.json";
    $utilisateurs_data = json_decode(file_get_contents($fichier), true);

    // On parcourt la liste pour trouver l'utilisateur et changer son rang
    foreach ($utilisateurs_data['utilisateurs'] as &$u) {
        if ($u['id'] === $id_user) {
            $u['rang'] = htmlspecialchars($nouveau_rang);
            break;
        }
    }

    // Sauvegarde du fichier JSON mis a jour
    file_put_contents($fichier, json_encode($utilisateurs_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

exit();
?>
