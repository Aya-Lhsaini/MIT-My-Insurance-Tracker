<?php
session_start();
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $matricule = $_POST['matricule'];
    $departement = $_POST['departement'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Assurez-vous de hasher ce mot de passe avant de le stocker

    if (!empty($nom) && !empty($prenom) && !empty($matricule) && !empty($departement) && !empty($email) && !empty($password)) {
        $sql = 'INSERT INTO utilisateur (nom, prenom, matricule, departement, email, password) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $matricule, $departement, $email, $password]);
        header('Location: utilisateurs.php');
        exit();
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ajouter Utilisateur</title>
</head>
<body>
<style>

body {
    font-family: 'Lato', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(to right, #004274, #57a6b7);

}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.form-container {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #004274;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="password"] {
    width: calc(100% - 22px); 
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
}

button {
    background-color: #004274;
    color: #fff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 16px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #003a6c;
}

a {
    color: #004274;
    text-decoration: none;
    font-size: 16px;
    margin-top: 20px;
    display: inline-block;
}

a:hover {
    text-decoration: underline;
}

.error {
    color: red;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .container {
        padding: 10px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
    }
}

</style>
<div class="container">
    <div class="form-container">
        <h2>Ajouter Utilisateur</h2>
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required>
            <br>
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required>
            <br>
            <label for="matricule">Matricule :</label>
            <input type="text" id="matricule" name="matricule" required>
            <br>
            <label for="departement">Département :</label>
            <input type="text" id="departement" name="departement" required>
            <br>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" >
            <br>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <br>
       
            <br>
            <button type="submit">Ajouter</button>
        </form>
        <a href="utilisateurs.php">Retour à la liste</a>
    </div>
</div>
</div>
</body>
</html>

