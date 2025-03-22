<?php
// ... (Connexion à la base de données et vérification de session) ...

// Récupérer les rapports de plagiat d'un sujet (exemple)
$sujet_id = $_GET['sujet_id'] ?? 1;
$sql_plagiat = "SELECT rapports_plagiat.*, utilisateurs.nom FROM rapports_plagiat INNER JOIN copies_etudiants ON rapports_plagiat.copie_etudiant_id = copies_etudiants.id INNER JOIN utilisateurs ON copies_etudiants.etudiant_id = utilisateurs.id WHERE copies_etudiants.sujet_examen_id = " . $sujet_id;
$result_plagiat = $conn->query($sql_plagiat);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Détection de plagiat</title>
    <style>/* ... (Styles CSS) ... */</style>
</head>
<body>
    <h1>Détection de plagiat</h1>
    <table>
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Pourcentage de similarité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($rapport = $result_plagiat->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $rapport['nom']; ?></td>
                    <td><?php echo $rapport['pourcentage_similarite']; ?></td>
                    <td><a href="rapport_plagiat.php?id=<?php echo $rapport['id']; ?>">Voir</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>