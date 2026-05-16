<?php
// Appelé par le JavaScript — renvoie les plats filtrés en JSON
header('Content-Type: application/json');

$contenu = file_get_contents('plats.json');
$donnees = json_decode($contenu, true);
$plats   = $donnees['plats'];

// Récupération des filtres envoyés par le JS
$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : 'tous';
$regime    = isset($_GET['regime'])    ? $_GET['regime']    : '';
$saveur    = isset($_GET['saveur'])    ? $_GET['saveur']    : '';
$tri       = isset($_GET['tri'])       ? $_GET['tri']       : '';
$recherche = isset($_GET['recherche']) ? strtolower(trim($_GET['recherche'])) : '';

// Filtre catégorie
if ($categorie !== 'tous') {
    $plats = array_filter($plats, fn($p) => $p['categorie'] === $categorie);
}

// Filtre régime
if ($regime !== '') {
    $plats = array_filter($plats, fn($p) => in_array($regime, $p['regime']));
}

// Filtre saveur
if ($saveur !== '') {
    $plats = array_filter($plats, fn($p) => $p['saveur'] === $saveur);
}

// Filtre recherche texte
if ($recherche !== '') {
    $plats = array_filter($plats, fn($p) =>
        strpos(strtolower($p['nom']), $recherche) !== false ||
        strpos(strtolower($p['description']), $recherche) !== false
    );
}

// Remettre les indices à 0
$plats = array_values($plats);

// Tri
if ($tri === 'prix_asc')   usort($plats, fn($a, $b) => $a['prix'] <=> $b['prix']);
if ($tri === 'prix_desc')  usort($plats, fn($a, $b) => $b['prix'] <=> $a['prix']);
if ($tri === 'populaire')  usort($plats, fn($a, $b) => $b['nb_commandes'] <=> $a['nb_commandes']);

echo json_encode($plats);
?>
