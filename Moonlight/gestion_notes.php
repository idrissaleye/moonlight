<?php
// ... (Connexion à la base de données et vérification de session) ...

// Récupérer les notes d'un sujet (exemple)
$sujet_id = $_GET['sujet_id'] ?? 1;
$sql_notes = "SELECT notes.*, utilisateurs.nom FROM notes INNER JOIN copies_etudiants ON notes.copie_etudiant_id = copies_etudiants.id INNER JOIN utilisateurs ON copies_etudiants.etudiant_id = utilisateurs.id WHERE copies_etudiants.sujet_examen_id = " . $sujet_id;
$result_notes = $conn->query($sql_notes);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Gestion des notes</title>
    <style>/* ... (Styles CSS) ... */</style>
</head>
<body>
    <h1>Gestion des notes</h1>
    <table>
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($note = $result_notes->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $note['nom']; ?></td>
                    <td><?php echo $note['note']; ?></td>
                    <td><?php echo $note['commentaire']; ?></td>
                    <td><a href="modifier_note.php?id=<?php echo $note['id']; ?>">Modifier</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>