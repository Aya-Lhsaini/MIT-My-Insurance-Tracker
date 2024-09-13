<?php
session_start();
require_once 'database.php';

if (isset($_POST['dossier_id'])) {
    $dossierId = intval($_POST['dossier_id']);
    
    $checkStatus = $pdo->prepare("SELECT statut FROM dossier WHERE id = ?");
    $checkStatus->execute([$dossierId]);
    $dossier = $checkStatus->fetch(PDO::FETCH_ASSOC);
    
    if ($dossier && $dossier['statut'] !== 'Remboursé') {
        $stmt = $pdo->prepare("UPDATE dossier SET statut = 'Remboursé' WHERE id = ?");
        $stmt->execute([$dossierId]);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>
