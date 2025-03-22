<?php
session_start();

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['utilisateur_id']) || $_SESSION['utilisateur_role'] !== 'enseignant') {
    header("Location: moonlight.html");
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "plateforme_examens";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Déterminer la page à inclure
$page = $_GET['page'] ?? 'tableau_de_bord'; // Par défaut, afficher le tableau de bord

// Tableau de correspondance entre les pages et les fichiers
$pages = [
    'tableau_de_bord' => 'tableau_de_bord.php',
    'gestion_sujets' => 'gestion_sujets.php',
    'consultation_copies' => 'consultation_copies.php',
    'gestion_notes' => 'gestion_notes.php',
    'statistiques' => 'statistiques.php',
    'detection_plagiat' => 'detection_plagiat.php',
    'deconnexion' => 'deconnexion.php'
];

// Vérifier si la page demandée existe
if (!array_key_exists($page, $pages)) {
    $page = 'tableau_de_bord'; // Page par défaut si la page demandée n'existe pas
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Professeur</title>
    <style>
        /* style.css */
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        main {
            padding: 20px;
        }

        section {
            background-color: #fff;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
        }

        form {
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Styles spécifiques pour le tableau de bord */
        #tableau-de-bord-contenu {
            /* Ajoutez vos styles pour le tableau de bord ici */
        }

        /* Styles spécifiques pour les statistiques */
        #statistiques-contenu {
            /* Ajoutez vos styles pour les statistiques ici */
        }
    </style>
</head>
<body>
    <header>
        <h1>Espace Professeur</h1>
        <nav>
            <ul>
                <li><a href="professeur.php?page=tableau_de_bord">Tableau de bord</a></li>
                <li><a href="professeur.php?page=gestion_sujets">Gestion des sujets</a></li>
                <li><a href="professeur.php?page=consultation_copies">Consultation des copies</a></li>
                <li><a href="professeur.php?page=gestion_notes">Gestion des notes</a></li>
                <li><a href="professeur.php?page=statistiques">Statistiques</a></li>
                <li><a href="professeur.php?page=detection_plagiat">Détection de plagiat</a></li>
                <li><a href="professeur.php?page=deconnexion">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php include($pages[$page]); ?>
    </main>
        <!-- Chatbot intégré -->
        <div class="chat-icon" onclick="toggleChat()">💬</div>
    <div class="chat-container" id="chat-container">
        <div class="chat-box" id="chat-box"></div>
        <div class="user-input">
            <input type="text" id="user-message" placeholder="Écrivez un message...">
            <button class="chat-button" onclick="sendMessage()">Envoyer</button>
        </div>
    </div>
<script>
    async function sendMessage() {
        let userInput = document.getElementById("user-message").value;
        if (userInput.trim() === "") return;

        let chatBox = document.getElementById("chat-box");
        chatBox.innerHTML += `<div><strong>Vous :</strong> ${userInput}</div>`;
        document.getElementById("user-message").value = "";

        try {
            // Envoi de la requête vers Flask
            const response = await fetch('http://127.0.0.1:5000/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ prompt: userInput })
            });

            const data = await response.json();

            if (data.response) {
                chatBox.innerHTML += `<div><strong>Bot :</strong> ${data.response}</div>`;
            } else if (data.error) {
                chatBox.innerHTML += `<div><strong>Bot :</strong> Erreur : ${data.error}</div>`;
            }
        } catch (error) {
            console.error('Erreur :', error);
            chatBox.innerHTML += `<div><strong>Bot :</strong> Impossible de contacter le serveur.</div>`;
        }

        // Faire défiler automatiquement le chat vers le bas
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function toggleChat() {
        let chatContainer = document.getElementById("chat-container");
        chatContainer.style.display = (chatContainer.style.display === "none" || chatContainer.style.display === "") ? "block" : "none";
    }
</script>
<style> 
    /* Styles du chatbot */
.chat-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 60px;
    height: 60px;
    background: #007bff;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    font-size: 30px;
    z-index: 1000;
}
.chat-container {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 400px;
    background: white;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    display: none;
    z-index: 1000;
}
.chat-box {
    height: 300px;
    overflow-y: auto;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}
.user-input {
    display: flex;
    margin-top: 10px;
}
#user-message {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}
.chat-button {
    padding: 10px;
    border: none;
    background: #007bff;
    color: white;
    cursor: pointer;
    border-radius: 5px;
    margin-left: 5px;
}

</style>

    <script>
        // script.js
        function modifierSujet(id) {
            document.getElementById('sujet-id').value = id;
            // ... (Code pour récupérer les données du sujet et les afficher dans le formulaire) ...
            document.getElementById('btn-annuler-sujet').style.display = 'inline';
        }

        function supprimerSujet(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce sujet ?")) {
                // ... (Code pour supprimer le sujet via une requête AJAX) ...
            }
        }

        document.getElementById('btn-annuler-sujet').addEventListener('click', function() {
            document.getElementById('form-sujet').reset();
            document.getElementById('sujet-id').value = '';
            this.style.display = 'none';
        });
    </script>
    <!-- fin chatbot -->
</body>
</html>

<?php
// Fermeture de la connexion à la fin du script
$conn->close();
?>