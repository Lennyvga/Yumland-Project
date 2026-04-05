<!DOCTYPE html>
<html>

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

            </div>
        </div>
    </nav>



    <div class="form-wrapper">

        <div class="nomrestaurant" id="entete-blanc">

            Bella Ciao
        </div><br><br>

        <div class="ligne">
            <div class="sousligne">
                <form action="traitement_inscription.php" method="post">
                    <label for="Nom">Nom</label><br>
                    <input type="text" id="Nom" name="Nom" required><br><br>
            </div>

            <div class="sousligne">
                <label for="Prenom">Prenom</label><br>
                <input type="text" id="Prenom" name="Prenom" required><br><br>
            </div>

        </div>

        <div class="ligne">
            <div class="sousligne">
                <label for="Email">Email</label><br>
                <input type="email" id="Email" name="Email" required><br><br>
            </div>

            <div class="sousligne">
                <label for="Tel">Téléphone</label><br>
                <input type="tel" id="Tel" name="Téléphone" pattern="[0-9]{10}" placeholder="0612345678"
                    required><br><br>
            </div>
        </div>
        <div class="ligne">
            <div class="sousligne">
                <label for="Mdp">Mot de passe</label><br>
                <input type="password" id="Mdp" name="password" minlength="8" required
                    placeholder="8 caractères minimum avec au moins 1 majuscule"><br><br>
            </div>
        </div>
        <div class="ligne">
            <div>
                <label for="Numpost">Code postal</label><br>
                <input type="text" name="Code postal" maxlength="5" required><br><br>
            </div>

            <div class="sousligne">
                <label for="Livraison">Adresse de livraison</label><br>
                <input type="text" name="adresse" required><br><br>
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
            <span>Si vous avez déjà un compte, veuillez cliquer juste ici ➤ </span>
            <a href="connexion.php">Se connecter</a>
        </div>

        </form>
    </div>




    <footer>
        <p>© 2026 Bella Ciao - Tous droits réservés</p>
    </footer>



</body>

</html>
