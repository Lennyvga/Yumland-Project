<?php
session_start();
header('Content-Type: application/json');

// Sécurité : client connecté uniquement
if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] !== 'client') {
    echo json_encode(['success' => false, 'message' => 'Non autorisé.']);
    exit();
}

// Lecture du corps JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['id_commande'], $input['articles'], $input['nouveau_total'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
    exit();
}

$id_commande   = $input['id_commande'];
$articles      = $input['articles'];
$nouveau_total = (float) $input['nouveau_total'];

// Charger les commandes
$fichier = 'commandes.json';
$data    = json_decode(file_get_contents($fichier), true);

$commande_trouvee = false;
$total_initial    = 0;

foreach ($data['commandes'] as &$cmd) {
    if ($cmd['id'] === $id_commande && $cmd['id_client'] == $_SESSION['auth']['id']) {

        // Vérif : on ne peut modifier que les commandes "accepted" (payées, pas encore en cuisine)
        if ($cmd['statut'] !== 'accepted') {
            echo json_encode(['success' => false, 'message' => 'Cette commande est déjà en préparation.']);
            exit();
        }

        $total_initial    = (float) $cmd['total'];
        $commande_trouvee = true;

        // Mise à jour des articles et du total
        $cmd['articles'] = $articles;
        $cmd['total']    = $nouveau_total;

        break;
    }
}
unset($cmd);

if (!$commande_trouvee) {
    echo json_encode(['success' => false, 'message' => 'Commande introuvable.']);
    exit();
}

// Calcul de la différence
$diff = round($nouveau_total - $total_initial, 2);

$reponse = ['success' => true];

if ($diff > 0) {

    // Commande plus chère → paiement supplémentaire via CYBank
    // En prod : appel à l'API CYBank ici
    $id_paiement = 'PAY-SUPPL-' . strtoupper(substr(md5(uniqid()), 0, 8));

    // On ajoute le paiement dans la commande pour traçabilité
    foreach ($data['commandes'] as &$cmd2) {
        if ($cmd2['id'] === $id_commande) {
            if (!isset($cmd2['paiements'])) {
                $cmd2['paiements'] = [];
            }
            $cmd2['paiements'][] = [
                'id'      => $id_paiement,
                'montant' => $diff,
                'date'    => date('Y-m-d H:i:s'),
                'type'    => 'supplement'
            ];
            break;
        }
    }
    unset($cmd2);

    $reponse['paiement_supplementaire'] = true;
    $reponse['montant_supplement']      = $diff;
    $reponse['id_paiement']             = $id_paiement;

} elseif ($diff < 0) {

    // Commande moins chère → ticket de réduction
    $code_ticket = 'REDUC-' . strtoupper(substr(md5(uniqid()), 0, 6));

    // On enregistre le ticket dans la commande
    foreach ($data['commandes'] as &$cmd3) {
        if ($cmd3['id'] === $id_commande) {
            $cmd3['ticket_reduction'] = [
                'code'    => $code_ticket,
                'montant' => abs($diff),
                'utilise' => false
            ];
            break;
        }
    }
    unset($cmd3);

    $reponse['ticket_reduction']  = true;
    $reponse['montant_reduction'] = $diff;
    $reponse['code_ticket']       = $code_ticket;

}

// Sauvegarde
file_put_contents($fichier, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode($reponse);
?>
