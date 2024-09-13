<?php
require_once 'database.php'; 

$unreadSql = 'SELECT COUNT(*) AS unread_count FROM reclamations WHERE lu = 0';
$unreadStmt = $pdo->prepare($unreadSql);
$unreadStmt->execute();
$unreadCount = $unreadStmt->fetchColumn();

echo $unreadCount;
?>
