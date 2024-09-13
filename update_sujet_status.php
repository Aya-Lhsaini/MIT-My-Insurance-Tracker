<?php
require_once 'database.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id']);

$sql = 'UPDATE reclamations SET lu = 1 WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);

echo json_encode(['status' => 'success']);
