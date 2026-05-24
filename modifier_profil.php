<?php
session_start();
header('Content-Type: application/json');

// si pas connecté blocage
if (!isset($_SESSION['auth'])) {
    echo json_encode(['success' => false, 'message' => 'Action non autorisée.']);
    exit();
}

$mon_id = $_SESSION['auth']['id'];

// Récupération des données envoyés 
$json_recu = file_get_contents('php://input');
$donnees = json_decode($json_recu, true);

if (!$donnees) {
    echo json_encode(['success' => false, 'message' => 'Données vides.']);
    exit();
}

$fichier = "utilisateurs.json";
$utilisateurs_data = json_decode(file_get_contents($fichier), true);

$statut_modification = false;

// On parcourt le tableau avec un symbole "&" pour modifier directement la valeur
foreach ($utilisateurs_data['utilisateurs'] as &$u) {
    if ($u['id'] === $mon_id) {
        $u['prenom']      = htmlspecialchars($donnees['prenom']);
        $u['nom']         = htmlspecialchars($donnees['nom']);
        $u['telephone']   = htmlspecialchars($donnees['telephone']);
        $u['code_postal'] = htmlspecialchars($donnees['code_postal']);
        $u['adresse']     = htmlspecialchars($donnees['adresse']);
        
        // On met à jour la session PHP pour que le prénom change sur l'acceuil
        $_SESSION['auth']['prenom'] = $u['prenom'];
        
        $statut_modification = true;
        break;
    }
}

if ($statut_modification) {
    // fichier json
    file_put_contents($fichier, json_encode($utilisateurs_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
