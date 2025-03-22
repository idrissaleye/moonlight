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
        window.location.href = 'moonlight.html'; // Redirige vers la page de connexion
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
// Liste des professeurs (exemple)
const professeurs = [
    { nom: "Professeur A", fichier: "cours_prof_A.pdf" },
    { nom: "Professeur B", fichier: "cours_prof_B.pdf" },
    { nom: "Professeur C", fichier: "" } // Pas de fichier disponible
];

// Fonction pour afficher la table des professeurs
function showProfTable() {
    const display = document.getElementById('content-display');
    
    let tableHTML = `
        <h2>Liste des professeurs connectés</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom du professeur</th>
                    <th>Télécharger</th>
                    <th>Téléverser</th>
                </tr>
            </thead>
            <tbody>
    `;

    professeurs.forEach(prof => {
        tableHTML += `
            <tr>
                <td>${prof.nom}</td>
                <td>
                    ${prof.fichier 
                        ? `<a href="${prof.fichier}" download>Télécharger</a>` 
                        : `<span style="color: grey;">Aucun fichier</span>`}
                </td>
                <td>
                    <input type="file" id="upload-${prof.nom.replace(/\s+/g, '-').toLowerCase()}" hidden />
                    <button onclick="uploadFile('${prof.nom.replace(/\s+/g, '-').toLowerCase()}')">Téléverser</button>
                </td>
            </tr>
        `;
    });

    tableHTML += `
            </tbody>
        </table>
    `;

    display.innerHTML = tableHTML;
}

// Stockage de l'état des fichiers téléversés
const uploadedFiles = {};

function uploadFile(profId) {
    // Vérifie si un fichier a déjà été téléversé
    if (uploadedFiles[profId]) {
        alert(`Un fichier a déjà été téléversé pour le professeur ${profId.replace('-', ' ')}`);
        return;
    }

    const fileInput = document.getElementById(`upload-${profId}`);
    fileInput.click();

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            uploadedFiles[profId] = fileInput.files[0].name; // Enregistre le fichier
            alert(`Fichier "${fileInput.files[0].name}" téléversé pour le professeur ${profId.replace('-', ' ')}`);

            // Désactiver le bouton après téléversement
            const button = document.querySelector(`[onclick="uploadFile('${profId}')"]`);
            button.disabled = true;
            button.style.backgroundColor = '#45475a';
            button.textContent = 'Fichier déjà téléversé';

            // Ici, tu peux ajouter une requête AJAX pour envoyer le fichier au serveur
        }
    });
}

// Ajouter un événement au clic sur "Téléchargement"
document.querySelector('[href="#telechargement"]').addEventListener('click', (e) => {
    e.preventDefault(); // Empêche le comportement par défaut du lien
    showProfTable();
});

function afficherNotes() {
    const display = document.getElementById('content-display');

    // Exemple de données de notes (à remplacer par une récupération via une base de données)
    const notes = [
        { professeur: "Professeur A", matiere: "Mathématiques", note: 16 },
        { professeur: "Professeur B", matiere: "Physique", note: 14 },
        { professeur: "Professeur C", matiere: "Chimie", note: 18 }
    ];

    let tableHTML = `
        <h2>Vos notes</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom du professeur</th>
                    <th>Matière</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
    `;

    notes.forEach(note => {
        tableHTML += `
            <tr>
                <td>${note.professeur}</td>
                <td>${note.matiere}</td>
                <td>${note.note}/20</td>
            </tr>
        `;
    });

    tableHTML += `
            </tbody>
        </table>
    `;

    display.innerHTML = tableHTML;
}
