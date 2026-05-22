/* Problème au niveau du changement du theme entre les différentes pages du site à plusiers niveaux : 
    - Stockage de l'information du changement de theme pour le transmettre aux autres pages
    - Rechargement de la page si on sauvegardait ce theme avec du php
    - Donc solution de secours pour respecter les conditions de l'énoncé du projet, utilisation de localStorage, meme si il n'est pas présent dans le cours
    */ 

/* Voici le code fonctionnel*/
const bouton = document.getElementById("bouton-theme");
const corps = document.body;

let themeEnregistre = localStorage.getItem("theme-sauvegarde");

if (themeEnregistre === "sombre") {
    corps.className = "mode-sombre";
    bouton.textContent = "☀️";
} else {
    corps.className = "";
    bouton.textContent = "🌙";
}

function changerDeTheme() {
    if (corps.className === "mode-sombre") {
        corps.className = "";
        bouton.textContent = "🌙";
     
        localStorage.setItem("theme-sauvegarde", "clair");
    } else {
        corps.className = "mode-sombre";
        bouton.textContent = "☀️";
 
        localStorage.setItem("theme-sauvegarde", "sombre");
    }
}

bouton.onclick = changerDeTheme;