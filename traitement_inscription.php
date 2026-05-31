<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: inscription.php');
    exit();
}


$nom        = htmlspecialchars(trim($_POST['Nom'] ?? ''), ENT_QUOTES, 'UTF-8');
$prenom     = htmlspecialchars(trim($_POST['Prenom'] ?? ''), ENT_QUOTES, 'UTF-8');
$email      = filter_var(trim($_POST['Email'] ?? ''), FILTER_SANITIZE_EMAIL);
$telephone  = htmlspecialchars(trim($_POST['Téléphone'] ?? ''), ENT_QUOTES, 'UTF-8');
$code_postal= htmlspecialchars(trim($_POST['code_postal'] ?? ''), ENT_QUOTES, 'UTF-8');
$adresse    = htmlspecialchars(trim($_POST['adresse'] ?? ''), ENT_QUOTES, 'UTF-8');
$infos_sup  = htmlspecialchars(trim($_POST['infos_sup'] ?? ''), ENT_QUOTES, 'UTF-8');


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: inscription.php?erreur=email_invalide');
    exit();
}


if (empty($nom) || empty($prenom) || empty($email) || empty($_POST['password'] ?? '')) {
    header('Location: inscription.php?erreur=champs_manquants');
    exit();
}


$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$json = file_get_contents("utilisateurs.json");
$data = json_decode($json, true);


foreach ($data['utilisateurs'] as $utilisateur) {
    if ($utilisateur['email'] == $email) {
        header('Location: inscription.php?erreur=email_existe');
        exit();
    }
}

$nouvel_utilisateur = [
    "id"               => uniqid(),
    "nom"              => $nom,
    "prenom"           => $prenom,
    "email"            => $email,
    "password"         => $password,
    "telephone"        => $telephone,
    "adresse"          => $adresse,
    "code_postal"      => $code_postal,
    "infos_sup"        => $infos_sup,
    "role"             => "client",
    "statut"           => "actif",
    "date_inscription" => date("Y-m-d")
];

$data['utilisateurs'][] = $nouvel_utilisateur;
file_put_contents("utilisateurs.json", json_encode($data, JSON_PRETTY_PRINT));

header('Location: connexion.php');
exit();
?>
