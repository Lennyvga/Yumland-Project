<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="page-inscription">
    <nav class="navbar">
        <div class="container nav-content">
            <div class="logo">Bella Ciao</div>
            <div class="nav-links">
                <a href="index.php">Accueil</a>
                <a href="menu.php">Menu</a>

                <?php if (isset($_SESSION['auth'])) { ?>

                <?php if ($_SESSION['auth']['role'] === 'admin') { ?>
                <a href="admin.php">Administration</a>
                <?php } ?>

                <?php if ($_SESSION['auth']['role'] === 'restaurateur') { ?>
                <a href="restaurateur.php">Commandes</a>
                <?php } ?>

                <?php if ($_SESSION['auth']['role'] === 'livreur') { ?>
                <a href="livreur.php">Ma livraison</a>
                <?php } ?>

                <?php if ($_SESSION['auth']['role'] === 'client') { ?>
                <a href="notation.php">Notation</a>
                <div class="dropdown">
                    <a href="#" class="nav-links">Profil ⏷</a>
                    <div class="dropdown-content">
                        <a href="informations.php">Mes informations</a>
                        <a href="commandes.php">Mes commandes</a>
                        <a href="compte+.php">Mon compte Bella Ciao +</a>
                    </div>
                </div>
                <?php } ?>

                <a href="deconnexion.php">Se déconnecter</a>

                <?php } else { ?>
                <a href="connexion.php">Se connecter</a>
                <a href="inscription.php">S'inscrire</a>
                <?php } ?>

                <button id="bouton-theme" class="btn-theme">🌙</button>

            </div>
        </div>
    </nav>

    <div class="form-wrapper">

        <div class="nomrestaurant" id="entete-blanc">
            Bella Ciao
        </div><br><br>

        <form action="traitement_inscription.php" method="post" id="form-inscription">
        <div class="ligne">
            <div class="sousligne">
                <label for="Nom">Nom</label><br>
                <input type="text" id="Nom" name="Nom"><br>
                <span id="erreur-nom" class="erreur-message"></span><br>
            </div>

            <div class="sousligne">
                <label for="Prenom">Prenom</label><br>
                <input type="text" id="Prenom" name="Prenom"><br>
                <span id="erreur-prenom" class="erreur-message"></span><br>
            </div>
        </div>

        <div class="ligne">
            <div class="sousligne">
                <label for="Email">Email</label><br>
                <input type="text" id="Email" name="Email"><br>
                <span id="erreur-email" class="erreur-message"></span><br>
            </div>

            <div class="sousligne">
                <label for="Tel">Téléphone <span id="compteur-tel" class="style-compteur">(0/10)</span></label>
                <input type="text" id="Tel" name="Téléphone" placeholder="0612345678"><br>
                <span id="erreur-tel" class="erreur-message"></span><br>
            </div>
        </div>

        <div class="ligne">
            <div class="sousligne">
                <label for="Mdp">Mot de passe <span id="compteur-mdp" class="style-compteur">(0 min)</span></label>
                <div class="conteneur-mdp">
                    <input type="password" id="Mdp" name="password" placeholder="8 caractères minimum avec au moins 1 majuscule">
                    <button type="button" id="bouton-oeil-ins" class="btn-oeil">👁️</button>
                </div>
                <span id="erreur-mdp" class="erreur-message"></span><br>
            </div>
        </div>

        <div class="ligne">
             <div>
                <label for="Numpost">Code postal <span id="compteur-postal" class="style-compteur">(0/5)</span></label><br>
                <input type="text" id="Numpost" name="Code postal" maxlength="5"><br>
                <span id="erreur-numpost" class="erreur-message"></span><br>
            </div>

            <div class="sousligne">
                <label for="Livraison">Adresse de livraison</label><br>
                <input type="text" id="Livraison" name="adresse"><br>
                <span id="erreur-livraison" class="erreur-message"></span><br>
            </div>
        </div>

        <div class="sousligne">
            <label for="Supp">Eléments suplémentaires que vous souhaitez rajouter ?</label>
            <textarea></textarea>
        </div>

        <div class="send">
            <button type="submit" class="btn">Envoyer</button>
        </div>

        <br>

        <div class="nav-links">
            <span class="text-changement">Si vous avez déjà un compte, veuillez cliquer juste ici ➤ </span>
            <a href="connexion.php" class="a-changement">Se connecter</a>
        </div>

        </form>
    </div>

    <footer class="footer-black">
        <p>© 2026 Bella Ciao - Tous droits réservés</p>
    </footer>

    <script type="text/javascript" src="theme.js"></script>
    <script type="text/javascript" src="validation.js"></script>
</body>

</html>