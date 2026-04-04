<!DOCTYPE html>
<php lang="fr">


<head>
<meta charset="UTF-8">
<title>Bella Ciao Ristorante</title>
<link rel="stylesheet" href="style.css">
</head>

<body>
        <nav class="navbar">
    <div class="container nav-content">
        <div class="logo">Bella Ciao</div>
        <div class="nav-links">
            <a href="index.php">Accueil</a>
            <a href="menu.php">Menu</a>
            <a href="notation.php">Notation</a>
            <a href="connexion.php">Se connecter</a>
            <a href="inscription.php">S'inscrire</a>
           
            <div class="dropdown">
                <a href="" class="nav-links">Profil ⏷</a>
                <div class="dropdown-content">
                    <a href="informations.php">Mes informations</a>
                    <a href="commandes.php">Mes commandes</a>
                    <a href="compte+.php">Mon compte Bella Ciao +</a>
                </div>
             </div>

        </div>
    </div>
</nav>


<section class="hero">
<div class="container">
<div class="hero-overlay">

<h1>Authentique cuisine italienne</h1>

<p>Des plats faits maison avec passion</p>

</div>
</div>
</section>



<div class="container">

<div class="search">

<input type="text" placeholder="Rechercher un plat...">

<button class="btn">Rechercher</button>

</div>

</div>


<div class="container">

<h2 class="section-title">Nos spécialités</h2>

<div class="cards">

<div class="card">

<img src="images/pizza-margherita.jpg">

<div class="card-body">

<div class="card-title">Pizza Margherita</div>

<div class="price">12€</div>

<a href="#" class="btn">Commander</a>

</div>

</div>

<div class="card">

<img src="images/pasta.jpg">

<div class="card-body">

<div class="card-title">Pasta Carbonara</div>

<div class="price">14€</div>

<a href="menu.php" class="btn">Commander</a>

</div>

</div>

<div class="card">

<img src="images/tiramisu.jpg">

<div class="card-body">

<div class="card-title">Tiramisu</div>

<div class="price">7€</div>

<a href="#" class="btn">Commander</a>

</div>

</div>

</div>

</div>

<footer class="footer-black">

Bella Ciao Ristorante © 2026

</footer>

</body>
</html>
