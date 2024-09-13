<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'database.php';

    $dossier_id = $_POST['id'];
    $statut = $_POST['statut'];

    $sql = 'UPDATE dossier SET statut = ? WHERE id = ?';
    $sqlstate = $pdo->prepare($sql);
    $sqlstate->execute([$statut, $dossier_id]);

    $sql = 'UPDATE reclamations SET statut = ? WHERE dossier_id = ?';
    $sqlstate = $pdo->prepare($sql);
    $sqlstate->execute([$statut, $dossier_id]);

    echo 'Statut mis à jour avec succès';
}
?>

