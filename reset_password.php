<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du Mot de Passe</title>
    <link rel="stylesheet" href="reset_password.css">
</head>
<body>
    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $matricule = $_POST['matricule'];
            $email = $_POST['email'];

            if (!empty($nom) && !empty($matricule) && !empty($email)) {
                require_once 'database.php';

                $sql = 'SELECT * FROM utilisateur WHERE nom = ? AND matricule = ? AND email = ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $matricule, $email]);

                if ($stmt->rowCount() > 0) {
                    $utilisateur = $stmt->fetch();
                    $password = $utilisateur['password'];
                    echo "<p>Le mot de passe pour l'utilisateur <strong>" . htmlspecialchars($nom) . "</strong> est : <strong>" . htmlspecialchars($password) . "</strong></p>";
                } else {
                    echo "<p>Aucun utilisateur trouvé avec ces informations.</p>";
                }
            } else {
                echo "<p>Veuillez remplir tous les champs.</p>";
            }
        } else {
            echo "<p>Requête invalide.</p>";
        }
        ?>
        <div class="button-container">
            <a href="login.php"><button type="button">Retour à la Connexion</button></a>
        </div>
    </div>
</body>
</html>
