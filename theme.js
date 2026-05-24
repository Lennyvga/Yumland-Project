const bouton = document.getElementById("bouton-theme");
const corps = document.body;

let themeEnregistre = localStorage.getItem("theme-sauvegarde");

// On applique le thème sombre si besoin, SANS toucher aux autres classes (comme page-inscription)
if (themeEnregistre === "sombre") {
    corps.classList.add("mode-sombre");
    if (bouton) bouton.textContent = "☀️"; // On vérifie si le bouton existe avant de changer le texte
} else {
    corps.classList.remove("mode-sombre");
    if (bouton) bouton.textContent = "🌙";
}

function changerDeTheme() {
    corps.classList.toggle("mode-sombre");
    
    if (corps.classList.contains("mode-sombre")) {
        if (bouton) bouton.textContent = "☀️";
        localStorage.setItem("theme-sauvegarde", "sombre");
    } else {
        if (bouton) bouton.textContent = "🌙";
        localStorage.setItem("theme-sauvegarde", "clair");
    }
}

if (bouton) {
    bouton.onclick = changerDeTheme;
}