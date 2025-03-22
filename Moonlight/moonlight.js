function showSignup(role) {
    document.getElementById("signup-role").value = role; 
    document.getElementById("signup-container").classList.remove("hidden");
    document.getElementById("login-container").classList.add("hidden");
    document.getElementById("signup-title").textContent = role === "enseignant" ? "Inscription Professeur" : "Inscription Étudiant";
}


function showLogin(role) {
    document.getElementById("login-role").value = role; 
    document.getElementById("login-container").classList.remove("hidden");
    document.getElementById("signup-container").classList.add("hidden");
    document.getElementById("login-title").textContent = role === "enseignant" ? "Connexion Professeur" : "Connexion Étudiant";
}


// Ensure the role is set before form submission
document.getElementById("signup-form").addEventListener("submit", function (e) {
    let roleField = document.getElementById("signup-role");
    if (!roleField.value) {
        e.preventDefault();
        alert("Veuillez sélectionner un rôle avant de vous inscrire.");
    }
});


document.getElementById("login-form").addEventListener("submit", function (e) {
    let role = document.getElementById("login-role").value;
    if (!role) {
        e.preventDefault();
        alert("Veuillez sélectionner un rôle avant de vous connecter.");
    }
});
