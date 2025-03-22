<?php
// ... (Connexion à la base de données et vérification de session) ...

// Traitement de l'ajout de sujets
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $titre = $_POST['titre'];

    // Gestion du téléchargement du fichier
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["fichier"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (file_exists($_FILES["fichier"]["tmp_name"])) {
        $uploadOk = 1;
    } else {
        echo "Le fichier n'est pas un fichier valide.";
        $uploadOk = 0;
    }

    if ($_FILES["fichier"]["size"] > 10000000) {
        echo "Le fichier est trop volumineux.";
        $uploadOk = 0;
    }

    $allowed_file_types = array("pdf", "doc", "docx");
    if (!in_array($fileType, $allowed_file_types)) {
        echo "Seuls les fichiers PDF, DOC et DOCX sont autorisés.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Votre fichier n'a pas été téléchargé.";
    } else {
        if (move_uploaded_file($_FILES["fichier"]["tmp_name"], $target_file)) {
            echo "Le fichier " . htmlspecialchars(basename($_FILES["fichier"]["name"])) . " a été téléchargé.";

            // Ajout de la gestion du contenu
            if (empty($_POST['contenu'])) {
                $contenu = NULL;
            } else {
                $contenu = $_POST['contenu'];
            }

            // Requête corrigée : la colonne 'id' n'est pas spécifiée
            $sql_ajout = "INSERT INTO sujets_examen (titre, contenu, fichier_chemin, enseignant_id) VALUES (?, ?, ?, ?)";
            $stmt_ajout = $conn->prepare($sql_ajout);
            $stmt_ajout->bind_param("sssi", $titre, $contenu, $target_file, $_SESSION['utilisateur_id']);

            echo $sql_ajout; // Affiche la requête pour débogage

            if ($stmt_ajout->execute()) {
                echo "Sujet ajouté avec succès.";
            } else {
                echo "Erreur lors de l'ajout du sujet : " . $stmt_ajout->error;
            }
        } else {
            echo "Une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    }
}

$sql_sujets = "SELECT * FROM sujets_examen WHERE enseignant_id = " . $_SESSION['utilisateur_id'];
$result_sujets = $conn->query($sql_sujets);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Gestion des sujets</title>
    <style>/* ... (Styles CSS) ... */</style>
</head>
<body>
    <h1>Gestion des sujets</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="titre" placeholder="Titre">
        <textarea name="contenu" placeholder="Contenu du sujet"></textarea>
        <input type="file" name="fichier">
        <button type="submit" name="ajouter">Ajouter</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Fichier</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sujet = $result_sujets->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $sujet['titre']; ?></td>
                    <td><?php echo $sujet['contenu']; ?></td>
                    <td><a href="<?php echo $sujet['fichier_chemin']; ?>" target="_blank">Télécharger</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>