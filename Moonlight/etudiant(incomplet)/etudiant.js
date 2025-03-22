function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("active");

    // Ajouter une animation
    if (sidebar.classList.contains("active")) {
        sidebar.style.transition = "width 0.3s ease";
    } else {
        sidebar.style.transition = "width 0.3s ease";
    }
}
function toggleSubmenu(id) {
    const submenu = document.getElementById(id);
    const menuItem = submenu.parentElement;
    
    // Fermer tous les autres sous-menus
    document.querySelectorAll('.submenu').forEach((menu) => {
        if (menu !== submenu) menu.style.display = 'none';
    });
    document.querySelectorAll('.menu-item').forEach((item) => {
        if (item !== menuItem) item.classList.remove('active');
    });

    // Ouvrir/fermer le sous-menu sélectionné
    if (submenu.style.display === 'block') {
        submenu.style.display = 'none';
        menuItem.classList.remove('active');
    } else {
        submenu.style.display = 'block';
        menuItem.classList.add('active');
    }
}
function logout() {
    if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
        alert('Déconnexion réussie !');
        window.location.href = 'chatbot+moonlight.html'; // Redirige vers la page de connexion
    }
}

function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("active");

    // Ajuster la position du bouton de déconnexion
    const logoutBtn = document.querySelector(".logout-btn");
    if (sidebar.classList.contains("active")) {
        logoutBtn.style.left = "20px"; // Aligné avec la sidebar ouverte
    } else {
        logoutBtn.style.left = "-100%"; // Caché lorsque la sidebar est fermée
    }
}