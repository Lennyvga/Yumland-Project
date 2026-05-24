
const monForm = document.getElementById("form-connexion");

if (monForm) {
    const boiteEmail = document.getElementById("Email");
    const boiteMdp = document.getElementById("Mdp");

    const zoneErreurEmail = document.getElementById("erreur1");
    const zoneErreurMdp = document.getElementById("erreur2");

    const oeil = document.getElementById("bouton-oeil");
    if (oeil) {
        oeil.onclick = function() {
            if (boiteMdp.type === "password") {
                boiteMdp.type = "text";
                oeil.textContent = "🔒";
            } else {
                boiteMdp.type = "password";
                oeil.textContent = "👁️";
            }
        };
    }

   
    monForm.onsubmit = function(evt) {
        
        zoneErreurEmail.textContent = "";
        zoneErreurMdp.textContent = "";

        let ok = true;
        let texteMdp = boiteMdp.value;
        let texteEmail = boiteEmail.value;

        if (texteEmail.trim() === "") {
            evt.preventDefault(); 
            zoneErreurEmail.textContent = "⚠️ L'adresse email est obligatoire.";
            ok = false;
        } 
  
        else if (!texteEmail.includes("@") || !texteEmail.includes(".")) {
            evt.preventDefault();
            zoneErreurEmail.textContent = "⚠️ L'adresse email n'est pas valide (il manque @ ou un point).";
            ok = false;
        }

        if (texteMdp.trim() === "") {
            evt.preventDefault();
            zoneErreurMdp.textContent = "⚠️ Le mot de passe est obligatoire.";
            ok = false;
        } 
      
        else if (texteMdp.length < 8) {
            evt.preventDefault();
            zoneErreurMdp.textContent = "⚠️ Le mot de passe doit contenir au moins 8 caractères.";
            ok = false;
        } 
        
        else if (texteMdp === texteMdp.toLowerCase()) {
            evt.preventDefault();
            zoneErreurMdp.textContent = "⚠️ Le mot de passe doit contenir au moins une lettre majuscule.";
            ok = false;
        }


        if (ok === false) {
            return false;
        }
    };
}

const monFormIns = document.getElementById("form-inscription");


if (monFormIns) {
    const boiteNom = document.getElementById("Nom");
    const boitePrenom = document.getElementById("Prenom");
    const boiteEmailIns = document.getElementById("Email");
    const boiteTel = document.getElementById("Tel");
    const boiteMdpIns = document.getElementById("Mdp");
    const boiteNumpost = document.getElementById("Numpost");
    const boiteLivraison = document.getElementById("Livraison");

    const zoneErreurNom = document.getElementById("erreur-nom");
    const zoneErreurPrenom = document.getElementById("erreur-prenom");
    const zoneErreurEmailIns = document.getElementById("erreur-email");
    const zoneErreurTel = document.getElementById("erreur-tel");
    const zoneErreurMdpIns = document.getElementById("erreur-mdp");
    const zoneErreurNumpost = document.getElementById("erreur-numpost");
    const zoneErreurLivraison = document.getElementById("erreur-livraison");

    const oeilIns = document.getElementById("bouton-oeil-ins");
    if (oeilIns) {
        oeilIns.onclick = function() {
            if (boiteMdpIns.type === "password") {
                boiteMdpIns.type = "text";
                oeilIns.textContent = "👁️‍🗨️"; 
            } else {
                boiteMdpIns.type = "password";
                oeilIns.textContent = "👁️"; 
            }
        };
    }

    // --- COMPTEURS DE CARACTÈRES EN TEMPS RÉEL ---
    const compteurTel = document.getElementById("compteur-tel");
    const compteurMdp = document.getElementById("compteur-mdp");
    const compteurPostal = document.getElementById("compteur-postal");

    // 1. Pour le Téléphone (0/10)
    if (boiteTel && compteurTel) {
        boiteTel.oninput = function() {
            compteurTel.textContent = "(" + boiteTel.value.length + "/10)";
        };
    }

    // 2. Pour le Mot de passe (X min)
    if (boiteMdpIns && compteurMdp) {
        boiteMdpIns.oninput = function() {
            let longueur = boiteMdpIns.value.length;
            if (longueur < 8) {
                compteurMdp.textContent = "(" + longueur + "/8 min)";
            } else {
                compteurMdp.textContent = "(OK)";
            }
        };
    }

    // 3. Pour le Code postal (0/5)
    if (boiteNumpost && compteurPostal) {
        boiteNumpost.oninput = function() {
            compteurPostal.textContent = "(" + boiteNumpost.value.length + "/5)";
        };
    }

    monFormIns.onsubmit = function(evt) {
    
        zoneErreurNom.textContent = "";
        zoneErreurPrenom.textContent = "";
        zoneErreurEmailIns.textContent = "";
        zoneErreurTel.textContent = "";
        zoneErreurMdpIns.textContent = "";
        zoneErreurNumpost.textContent = "";
        zoneErreurLivraison.textContent = "";

        let ok = true;

       
        if (boiteNom.value.trim() === "") {
            evt.preventDefault();
            zoneErreurNom.textContent = "⚠️ Le nom est obligatoire.";
            ok = false;
        }

      
        if (boitePrenom.value.trim() === "") {
            evt.preventDefault();
            zoneErreurPrenom.textContent = "⚠️ Le prénom est obligatoire.";
            ok = false;
        }

       
        if (boiteEmailIns.value.trim() === "") {
            evt.preventDefault();
            zoneErreurEmailIns.textContent = "⚠️ L'adresse email est obligatoire.";
            ok = false;
        } else if (!boiteEmailIns.value.includes("@") || !boiteEmailIns.value.includes(".")) {
            evt.preventDefault();
            zoneErreurEmailIns.textContent = "⚠️ L'adresse email n'est pas valide (il manque @ ou un point).";
            ok = false;
        }

       
        if (boiteTel.value.trim() === "") {
            evt.preventDefault();
            zoneErreurTel.textContent = "⚠️ Le numéro de téléphone est obligatoire.";
            ok = false;
        } else if (boiteTel.value.length !== 10 || isNaN(boiteTel.value)) {
            evt.preventDefault();
            zoneErreurTel.textContent = "⚠️ Le numéro de téléphone doit contenir exactement 10 chiffres.";
            ok = false;
        }

       
        if (boiteMdpIns.value.trim() === "") {
            evt.preventDefault();
            zoneErreurMdpIns.textContent = "⚠️ Le mot de passe est obligatoire.";
            ok = false;
        } else if (boiteMdpIns.value.length < 8) {
            evt.preventDefault();
            zoneErreurMdpIns.textContent = "⚠️ Le mot de passe doit contenir au moins 8 caractères.";
            ok = false;
        } else if (boiteMdpIns.value === boiteMdpIns.value.toLowerCase()) {
            evt.preventDefault();
            zoneErreurMdpIns.textContent = "⚠️ Le mot de passe doit contenir au moins une lettre majuscule.";
            ok = false;
        }

        
        if (boiteNumpost.value.trim() === "") {
            evt.preventDefault();
            zoneErreurNumpost.textContent = "⚠️ Le code postal est obligatoire.";
            ok = false;
        } else if (boiteNumpost.value.length !== 5 || isNaN(boiteNumpost.value)) {
            evt.preventDefault();
            zoneErreurNumpost.textContent = "⚠️ Le code postal doit contenir exactement 5 chiffres.";
            ok = false;
        }

        
        if (boiteLivraison.value.trim() === "") {
            evt.preventDefault();
            zoneErreurLivraison.textContent = "⚠️ L'adresse de livraison est obligatoire.";
            ok = false;
        }

        if (ok === false) {
            return false;
        }
    };
}