<?php
session_start();
if (!isset($_SESSION['utilisateur'])  ) {
    if ($_SESSION['utilisateur']['admin'] !== 1){
    header('Location: login.php');
    exit();
}}
$admin = $_SESSION['utilisateur'];
?>
<?php

$utilisateur_id = $_SESSION['utilisateur']['id'];
$sql = 'SELECT * FROM notifications WHERE utilisateur_id = :utilisateur_id AND lu = 0 ORDER BY date_notif DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute(['utilisateur_id' => $utilisateur_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_update = 'UPDATE notifications SET lu = 1 WHERE utilisateur_id = :utilisateur_id AND lu = 0';
$pdo->prepare($sql_update)->execute(['utilisateur_id' => $utilisateur_id]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>
    <?php include 'admin_nav.php'; ?>

    <div class="container mt-4">
        <h1>Notifications</h1>
        <?php if (count($notifications) > 0): ?>
            <ul class="list-group">
                <?php foreach ($notifications as $notification): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($notification['sujet']); ?></strong><br>
                        <?php echo htmlspecialchars($notification['message']); ?><br>
                        <small><?php echo date('d-m-Y H:i:s', strtotime($notification['date_notif'])); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune notification.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
