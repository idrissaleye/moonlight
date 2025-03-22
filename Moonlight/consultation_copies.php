<?php
// ... (Connexion à la base de données et vérification de session) ...

// Récupérer les copies d'un sujet (exemple)
$sujet_id = $_GET['sujet_id'] ?? 1; // Récupérer l'ID du sujet depuis l'URL
$sql_copies = "SELECT copies_etudiants.*, utilisateurs.nom FROM copies_etudiants INNER JOIN utilisateurs ON copies_etudiants.etudiant_id = utilisateurs.id WHERE copies_etudiants.sujet_examen_id = " . $sujet_id;
$result_copies = $conn->query($sql_copies);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Consultation des copies</title>
    <style>/* ... (Styles CSS) ... */</style>
</head>
<body>
    <h1>Consultation des copies</h1>
    <table>
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Date de soumission</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($copie = $result_copies->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $copie['nom']; ?></td>
                    <td><?php echo $copie['date_soumission']; ?></td>
                    <td><a href="copie.php?id=<?php echo $copie['id']; ?>">Voir</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>