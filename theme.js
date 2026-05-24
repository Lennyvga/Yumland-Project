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
if (!window.location.href.includes('connexion.php') && !window.location.href.includes('inscription.php')) {
    // On demande au serveur de vérifier si notre session est toujours valide
    fetch('verifier_statut_session.php')
        .then(response => response.json())
        .then(data => {
            if (data.bloque === true) {
                // Si le serveur dit qu'on est bloqué, on éjecte direct vers la connexion
                window.location.href = 'connexion.php';
            }
        })
        .catch(err => console.log("Vérification sécurité active."));
}
