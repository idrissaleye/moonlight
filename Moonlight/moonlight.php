<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "plateforme_examens";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer le rôle correctement
$role = $_POST['role'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['inscription'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $role = isset($_POST['role']) && ($_POST['role'] === 'enseignant' || $_POST['role'] === 'eleve') ? $_POST['role'] : 'eleve';


    // Check if email already exists
    $check_sql = "SELECT id FROM utilisateurs WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Email already exists, redirect with an error message
        header("Location: moonlight.html?error=email_exists");
        exit();
    }

    $check_stmt->close();

    // Insert the new user
    $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nom, $prenom, $email, $mot_de_passe, $role);

    if ($stmt->execute()) {
        $_SESSION['message_bienvenue'] = "Inscription réussi !";
        header("Location: moonlight.html?success=1");
        exit();
    } else {
        echo "Erreur lors de l'inscription : " . $stmt->error;
    }
    $stmt->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['connexion'])) {
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $sql = "SELECT id, nom, prenom, mot_de_passe, role FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($mot_de_passe, $row['mot_de_passe'])) {
            $_SESSION['utilisateur_id'] = $row['id'];
            $_SESSION['utilisateur_nom'] = $row['nom'];
            $_SESSION['utilisateur_prenom'] = $row['prenom'];
            $_SESSION['utilisateur_role'] = $row['role'];

            header("Location: " . ($row['role'] == 'enseignant' ? 'professeur.php' : 'etudiant.html'));
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Utilisateur non trouvé.";
    }
    $stmt->close();
}

$conn->close();
?>
