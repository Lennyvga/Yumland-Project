<?php
session_start();


$json = file_get_contents("plats.json");
$data = json_decode($json, true);
$plats_du_json = $data['plats'];

$total_general = 0; 
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
            </div>
        </div>
    </nav>

    <section class="container">
        <h2 class="section-title">Récapitulatif de votre commande</h2>

        <?php if (empty($_SESSION['panier'])){ ?>
        <p>Votre panier est vide. <a href="menu.php">Retourner au menu</a>.</p>
        <?php 
    }
        else { ?>
        <table class="panier-table">
            <tr>
                <th>Plat</th>
                <th>Prix Unitaire</th>
                <th>Quantité</th>
                <th>Sous-total</th>
            </tr>
            </th>
            <td>
                <?php 
               
                foreach ($_SESSION['panier'] as $id_session => $quantite) {
                    
                    foreach ($plats_du_json as $plat) {
                        if ($plat['id'] == $id_session) {
                            $sous_total = $plat['prix'] * $quantite;
                            $total_general += $sous_total;
                            ?>
                <tr>
                    <td><?php echo $plat['nom']; ?></td>
                    <td><?php echo $plat['prix']; ?>€</td>
                    <td><?php echo $quantite; ?>
                        <select name="menu" action="ajouter.php">
                            <!-- Pas fonctionnel dans la confirmation des quantités-->
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="3">3</option>
                            <option value="3">4</option>
                            <option value="3">5</option>
                            <option value="3">6</option>
                            <option value="3">7</option>
                            <option value="3">8</option>
                            <option value="3">9</option>
                        </select>
                    </td>
                    <td><?php echo $sous_total; ?>€</td>
                    <td>
                        <form method="POST" action="supprimer_plat.php">
                            <input type="hidden" name="id" value="<?php echo $id_session; ?>">
                            <button type="submit" class="btn">Supprimer</button>
                        </form>
                    </td>
                <tr>


                    <td>
                        <form action="modifier_panier.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $id_session; ?>">


                </tr>
                </tr>

                <?php
                 break; 
                        }
                    }
                } 
                ?>
            </td>

            <tr>
                <td colspan="3" class="total-a-payer"><strong>TOTAL À PAYER :</strong></td>
                <td><strong><?php echo $total_general; ?>€</strong></td>
            </tr>
        </table>

        <div class="valider-commande">
            <a href="menu.php" class="btn">Continuer mes achats</a>

            <?php if (isset($_SESSION['auth'])){ ?>
            <a href="valider.php" class="btn-commande">Confirmer ma commande</a>

            <?php   } else { ?>
            <br><br><br>
            <p>Vous devez être connecté pour commander.</p><br>
            <a href="connexion.php" class="btn">Se connecter / S'inscrire</a>

            <?php } ?>

        </div>
        <?php } ?>



    </section>

    <footer class="footer-black">
        Bella Ciao Ristorante © 2026
    </footer>

</body>

</html>
