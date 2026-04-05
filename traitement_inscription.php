<?php
session_start();


$nom = $_POST['Nom'];
$prenom = $_POST['Prenom'];
$email = $_POST['Email'];
$telephone = $_POST['telephone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$code_postal = $_POST['code_postal'];
$adresse = $_POST['adresse'];
$infos_sup = $_POST['infos_sup'];


$json = file_get_contents("utilisateurs.json");
$data = json_decode($json, true);


foreach ($data['utilisateurs'] as $utilisateur) {
    if ($utilisateur['email'] == $email) {
        header('Location: inscription.php?erreur=email_existe');
        exit();
    }
}


$nouvel_utilisateur = [
    "id" => uniqid(),
    "nom" => $nom,
    "prenom" => $prenom,
    "email" => $email,
    "password" => $password,
    "telephone" => $telephone,
    "adresse" => $adresse,
    "code_postal" => $code_postal,
    "infos_sup" => $infos_sup,
    "role" => "client",
    "statut" => "actif",
    "date_inscription" => date("Y-m-d")
];


$data['utilisateurs'][] = $nouvel_utilisateur;
file_put_contents("utilisateurs.json", json_encode($data, JSON_PRETTY_PRINT));

header('Location: connexion.php');
exit();
?>
