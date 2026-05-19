<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] !== 'client') {
    echo json_encode(['success' => false, 'message' => 'Non autorisé.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['id_commande'], $input['note'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
    exit();
}

$id_commande = $input['id_commande'];
$note        = (int) $input['note'];
$commentaire = '';
if (isset($input['commentaire'])) {
    $commentaire = trim($input['commentaire']);
}

// Validation de la note
if ($note < 1 || $note > 5) {
    echo json_encode(['success' => false, 'message' => 'Note invalide (1 à 5 uniquement).']);
    exit();
}

$fichier = 'commandes.json';
$data    = json_decode(file_get_contents($fichier), true);

$trouve = false;
foreach ($data['commandes'] as &$cmd) {
    if ($cmd['id'] !== $id_commande) {
        continue;
    }

    // Vérif : appartient au client connecté
    if ($cmd['id_client'] != $_SESSION['auth']['id']) {
        echo json_encode(['success' => false, 'message' => 'Cette commande ne vous appartient pas.']);
        exit();
    }

    // Vérif : commande bien livrée
    if ($cmd['statut'] !== 'livree') {
        echo json_encode(['success' => false, 'message' => 'Seules les commandes livrées peuvent être notées.']);
        exit();
    }

    // Vérif : pas déjà notée
    if (!empty($cmd['note'])) {
        echo json_encode(['success' => false, 'message' => 'Vous avez déjà noté cette commande.']);
        exit();
    }

    // Enregistrement de la note
    $cmd['note']        = $note;
    $cmd['commentaire'] = $commentaire;
    $cmd['date_note']   = date('Y-m-d H:i:s');

    $trouve = true;
    break;
}
unset($cmd);

if (!$trouve) {
    echo json_encode(['success' => false, 'message' => 'Commande introuvable.']);
    exit();
}

file_put_contents($fichier, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode(['success' => true]);
?>
