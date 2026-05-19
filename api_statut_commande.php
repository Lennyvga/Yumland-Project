<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['auth'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé.']);
    exit();
}

$role = $_SESSION['auth']['role'];

// Seuls le restaurateur et le livreur peuvent changer un statut
if (!in_array($role, ['restaurateur', 'livreur'])) {
    echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['id_commande'], $input['nouveau_statut'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
    exit();
}

$id_commande    = $input['id_commande'];
$nouveau_statut = $input['nouveau_statut'];
$id_livreur     = $input['id_livreur'] ?? null;

// Transitions autorisées par rôle
$transitions_autorisees = [
    'restaurateur' => [
        'accepted'       => 'en_preparation',
        'en_preparation' => 'en_livraison',
    ],
    'livreur' => [
        'en_livraison' => 'livree',
    ]
];

$fichier = 'commandes.json';
$data    = json_decode(file_get_contents($fichier), true);

$trouve = false;

foreach ($data['commandes'] as &$cmd) {
    if ($cmd['id'] !== $id_commande) {
        continue;
    }

    $statut_actuel = $cmd['statut'];

    // Vérifier que la transition est autorisée pour ce rôle
    if (!isset($transitions_autorisees[$role][$statut_actuel])
        || $transitions_autorisees[$role][$statut_actuel] !== $nouveau_statut) {
        echo json_encode(['success' => false, 'message' => 'Transition de statut non autorisée.']);
        exit();
    }

    // Pour le livreur : vérifier que la commande lui est bien assignée
    if ($role === 'livreur' && $cmd['id_livreur'] != $_SESSION['auth']['id']) {
        echo json_encode(['success' => false, 'message' => 'Cette commande ne vous est pas assignée.']);
        exit();
    }

    $cmd['statut'] = $nouveau_statut;

    if ($id_livreur) {
        $cmd['id_livreur'] = $id_livreur;
    }

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
