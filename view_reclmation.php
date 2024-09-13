<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit();
}

require_once 'database.php';

if (isset($_GET['id'])) {
    $reclamation_id = $_GET['id'];

    $sql = 'SELECT * FROM reclamations WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $reclamation_id]);
    $reclamation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reclamation) {
        $dossier_id = $reclamation['dossier_id'];
        $sujet = $reclamation['sujet'];
        $date_reclamation = $reclamation['date_reclamation'];
        $piece_jointe = $reclamation['piece_jointe'];
    } else {
        echo "Réclamation introuvable.";
        exit();
    }
} else {
    echo "Aucune réclamation spécifiée.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir la Réclamation</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>

    <h1>Réclamation #<?php echo $reclamation_id; ?></h1>
    <p><strong>Dossier ID:</strong> <?php echo $dossier_id; ?></p>
    <p><strong>Sujet de la réclamation:</strong> <?php echo $sujet; ?></p>
    <p><strong>Date de réclamation:</strong> <?php echo $date_reclamation; ?></p>
    
    <?php if (!empty($piece_jointe)) : ?>
        <p><strong>Pièce jointe:</strong> <a href="uploads/<?php echo $piece_jointe; ?>" target="_blank">Voir la pièce jointe</a></p>
    <?php else : ?>
        <p><strong>Pièce jointe:</strong> Aucune</p>
    <?php endif; ?>

    <a href="reclamations.php">Retour aux réclamations</a>

</body>
</html>
