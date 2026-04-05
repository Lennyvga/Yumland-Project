<?php
session_start();
// On récupère les données
$json_commandes = file_get_contents("commandes.json");
$donnees_commandes = json_decode($json_commandes, true);

$json_users = file_get_contents("utilisateurs.json");
$donnees_users = json_decode($json_users, true);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Bella Ciao - Restaurant</title>
</head>
<body>
    <div class="container">
        <h1 class="logo">Bella Ciao</h1>
        <h2 class="section-title">Commandes à gérer</h2>

        <div style="display: flex;">
            <div style="width: 50%; padding: 10px;">
                <h3>🥘 À CUISINER</h3>
                <?php foreach ($donnees_commandes['commandes'] as $c) { 
                    if ($c['statut'] == 'accepted') { ?>
                        <div class="card" style="width: 100%; margin-bottom: 20px; padding: 10px;">
                            <p>Commande : <?php echo $c['id']; ?></p>
                            <p>Client : <?php echo $c['utilisateur']; ?></p>
                            
                            <form action="update_statut.php" method="GET">
                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                <input type="hidden" name="statut" value="en_livraison">
                                
                                <select name="livreur" required>
                                    <option value="">Choisir un livreur</option>
                                    <?php foreach ($donnees_users['utilisateurs'] as $u) {
                                        if ($u['role'] == 'livreur') { ?>
                                            <option value="<?php echo $u['id']; ?>">
                                                <?php echo $u['prenom']; ?>
                                            </option>
                                    <?php } } ?>
                                </select>
                                <br><br>
                                <button type="submit" class="btn">Envoyer en cuisine</button>
                            </form>
                        </div>
                <?php } } ?>
            </div>

            <div style="width: 50%; padding: 10px;">
                <h3>🚚 EN LIVRAISON</h3>
                <?php foreach ($donnees_commandes['commandes'] as $c) {
                    if ($c['statut'] == 'en_livraison') { ?>
                        <div class="card" style="width: 100%; padding: 10px;">
                            <p>La commande <?php echo $c['id']; ?> est partie.</p>
                        </div>
                <?php } } ?>
            </div>
        </div>
    </div>
</body>
</html>
