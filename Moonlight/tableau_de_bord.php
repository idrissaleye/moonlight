<?php
// ... (Connexion à la base de données et vérification de session) ...

// Exemple : Statistiques générales
$sql_total_etudiants = "SELECT COUNT(*) FROM utilisateurs WHERE role = 'etudiant'";
$result_total_etudiants = $conn->query($sql_total_etudiants);
$total_etudiants = $result_total_etudiants->fetch_row()[0];

$sql_total_sujets = "SELECT COUNT(*) FROM sujets_examen WHERE enseignant_id = " . $_SESSION['utilisateur_id'];
$result_total_sujets = $conn->query($sql_total_sujets);
$total_sujets = $result_total_sujets->fetch_row()[0];

// ... (Autres statistiques) ...
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Tableau de bord</title>
    <style>/* ... (Styles CSS) ... */</style>
</head>
<body>
    <h1>Tableau de bord</h1>
    <p>Nombre total d'étudiants : <?php echo $total_etudiants; ?></p>
    <p>Nombre total de sujets d'examen : <?php echo $total_sujets; ?></p>
    <?php // ... (Afficher d'autres statistiques) ... ?>
</body>
</html>