<?php
session_start();

$json = file_get_contents("plats.json");
$data = json_decode($json, true);
$plats_du_json = $data['plats'];

$total_panier = 0; 
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mon Panier - Bella Ciao</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">Bella Ciao</div>
            <div class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="menu.php">Menu</a>
                <a href="panier.php">Panier</a>

                <button id="bouton-theme" class="btn-theme">🌙</button>
            </div>
        </div>
    </nav>

    <section class="container">
        <h2 class="section-title">Récapitulatif de votre commande</h2>

        <?php if (empty($_SESSION['panier'])){ ?>
        <p>Votre panier est vide. <br><a class="btn" href="menu.php">Retourner au menu</a>.</p>
        <?php } else { ?>
        <table class="panier-table">
            <thead>
                <tr>
                    <th>Plat</th>
                    <th>Prix Unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($_SESSION['panier'] as $id_session => $quantite) {
                    foreach ($plats_du_json as $plat) {
                        if ($plat['id'] == $id_session) {
                            $sous_total = $plat['prix'] * $quantite;
                            $total_panier += $sous_total;
                            ?>
                <tr>
                    <td><?php echo $plat['nom']; ?></td>
                    <td><?php echo $plat['prix']; ?>€</td>
                    <td>
                        <?php echo $quantite; ?>
                        <select name="quantite_modif">
                            <?php for($i=1; $i<=9; $i++) { ?>
                            <option value="<?php echo $i; ?>" <?php echo ($i == $quantite) ? 'selected' : ''; ?>>
                                <?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><?php echo $sous_total; ?>€</td>
                    <td>
                        <form method="POST" action="supprimer_plat.php" style="margin:0;">
                            <input type="hidden" name="id" value="<?php echo $id_session; ?>">
                            <button type="submit" class="btn">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php
                            break; 
                        }
                    }
                } 
                ?>
                <tr>
                    <td colspan="3" class="total-a-payer"><strong>TOTAL À PAYER :</strong></td>
                    <td colspan="2"><strong><?php echo $total_panier; ?>€</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="valider-commande">
            <a href="menu.php" class="btn">Continuer mes achats</a>
            <a href="vider-panier.php" class="btn">Vider le panier</a>

            <?php if (isset($_SESSION['auth'])){ ?>
            <a href="valider.php" class="btn">Confirmer ma commande</a>
            <?php } else { ?>
            <br><br><br>
            <p>Vous devez être connecté pour commander.</p><br>
            <a href="connexion.php" class="btn">Se connecter</a>
            <a href="inscription.php" class="btn">S'inscrire</a>
            <?php } ?>
        </div>
        <?php } ?>
    </section>

    <footer class="footer-black">
        Bella Ciao Ristorante © 2026
    </footer>

             <script type="text/javascript" src="theme.js"></script>
</body>

</html>
