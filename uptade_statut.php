<?php
session_start();
// Vérification simple : seul le restau ou le livreur peuvent changer un statut
if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] == 'client') {
    header('Location: index.php');
    exit();
}

if (isset($_GET['id']) && isset($_GET['nouveau_statut'])) {
    $id_cmd = $_GET['id'];
    $statut = $_GET['nouveau_statut'];
    $id_livreur = isset($_GET['livreur']) ? $_GET['livreur'] : null;

    $data = json_decode(file_get_contents("commandes.json"), true);

    foreach ($data['commandes'] as &$cmd) {
        if ($cmd['id'] == $id_cmd) {
            $cmd['statut'] = $statut;
            if ($id_livreur) {
                $cmd['id_livreur'] = $id_livreur;
            }
            break;
        }
    }

    file_put_contents("commandes.json", json_encode($data, JSON_PRETTY_PRINT));
}

// Retour à la page précédente
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
