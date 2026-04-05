<?php
session_start();

if (!isset($_SESSION['auth']) || $_SESSION['auth']['role'] != 'admin') {
    header('Location: index.php');
    exit();
}

$id = $_POST['id'];
$action = $_POST['action'];

$json = file_get_contents(__DIR__ . "/utilisateurs.json");
$data = json_decode($json, true);

foreach ($data['utilisateurs'] as &$utilisateur) {
    if ($utilisateur['id'] == $id) {
        if ($action == 'bloquer') {
            if ($utilisateur['statut'] == 'actif') {
                $utilisateur['statut'] = 'bloque';
            } else {
                $utilisateur['statut'] = 'actif';
            }
        } else if ($action == 'desactiver') {
            $utilisateur['statut'] = 'desactive';
        }
        break;
    }
}

file_put_contents(__DIR__ . "/utilisateurs.json", json_encode($data, JSON_PRETTY_PRINT));

header('Location: admin.php');
exit();
?>
