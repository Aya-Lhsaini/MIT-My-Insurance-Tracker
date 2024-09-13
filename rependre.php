<?php
require_once 'database.php';

if (isset($_POST['reponse']) && isset($_POST['reclamation_id'])) {
    $reclamation_id = $_POST['reclamation_id'];
    $reponse = $_POST['reponse'];

    $sql = 'UPDATE reclamations SET reponse = :reponse, date_response = NOW() WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':reponse' => $reponse,
        ':id' => $reclamation_idzs
    ]);

    echo "Réponse envoyée avec succès.";
}
