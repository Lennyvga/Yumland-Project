<?php
session_start();
$_SESSION['auth']['id'] = 'u5'; 

$mon_id = $_SESSION['auth']['id'];
$json = file_get_contents("commandes.json");
$data = json_decode($json, true);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Bella Ciao - Livreur</title>
</head>
<body>
    <div class="container">
        <h1 class="logo">Bella Ciao Livraison</h1>
        
        <?php foreach ($data['commandes'] as $com) {
            if ($com['id_livreur'] == $mon_id && $com['statut'] == 'en_livraison') { ?>
                <div class="card" style="width: 100%; padding: 20px;">
                    <h2>Détails de la course :</h2>
                    <p>Adresse : <?php echo $com['adresse']; ?></p>
                    <p>Client : <?php echo $com['utilisateur']; ?></p>
                    
                    <a href="http://maps.google.com/?q=<?php echo $com['adresse']; ?>" class="btn">
                        Voir sur la carte
                    </a>
                    <br><br>
                    <a href="update_statut.php?id=<?php echo $com['id']; ?>&statut=livree" class="btn" style="background:red;">
                        J'ai livré la commande
                    </a>
                </div>
        <?php } } ?>
    </div>
</body>
</html>
