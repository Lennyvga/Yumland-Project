<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['Email'];
    $password = $_POST['Mdp'];

    $json = file_get_contents("utilisateurs.json");
    $data = json_decode($json, true);
//on cherche si l'utilisateur existe
    foreach ($data['utilisateurs'] as $utilisateur) {
        if ($utilisateur['email'] == $email && password_verify($password, $utilisateur['password'])) {
          //connexion reussi  
        $_SESSION['auth'] = $utilisateur;
          if ($utilisateur['role'] == 'admin') {
    header('Location: admin.php');
} else if ($utilisateur['role'] == 'restaurateur') {
    header('Location: restaurateur.php');
} else if ($utilisateur['role'] == 'livreur') {
    header('Location: livreur.php');
} else {
    header('Location: index.php');
}
exit();
    }

    $erreur = "Email ou mot de passe incorrect";
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Connexion</title>
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
        <?php
        if (isset($erreur)) {
            echo '<p style="color:red;">' . $erreur . '</p>';
        }
        ?>
        <form action="connexion.php" method="post">
            <div class="ligne">
                <div class="sousligne">
                    <label for="Email">Email</label><br>
                    <input type="text" id="Email" name="Email" required><br><br>
                </div>

                <div class="sousligne">
                    <label for="Mdp">Mot de passe </label><br>
                    <input type="password" id="Mdp" name="Mdp" pattern="(?=.*[A-Z]).{8,}" required><br><br>
                </div>

            </div>

            <div class="send">
                <button type="submit" class="btn">Envoyer</button>
            </div>

            <div class="nav-links">
                <br><br> <span>Si vous n'avez pas de compte, veuillez cliquer juste ici ➤ </span>
                <a href="inscription.php">S'inscrire</a>
            </div>



        </form>
    </div>

    <footer>
        <p>© 2026 Bella Ciao - Tous droits réservés</p>
    </footer>

</body>

</html>
