<?php
session_start();
require_once("getapikey.php");
$montant = $_SESSION['total_panier']; 
$vendeur = "MI-3_D"; 
$transaction = strval(time()); 
$retour = "http://localhost:8080/merci.php"; 

$api_key = getAPIKey($vendeur);

$chaine = $api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#";
$control = md5($chaine);
?>

    <link rel="stylesheet" href="style.css">

<form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST" id="form_paiement">
    <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
    <input type="hidden" name="montant" value="<?php echo $montant; ?>">
    <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
    <input type="hidden" name="retour" value="<?php echo $retour; ?>">
    <input type="hidden" name="control" value="<?php echo $control; ?>">
    

    
    <p>Montant à payer : <?php echo $montant; ?> €</p>
    <button type="submit" class="btn">Paiement</button>
</form>
