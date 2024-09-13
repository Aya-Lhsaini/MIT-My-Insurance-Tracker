<?php
session_start();

if (isset($_POST['login'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $matricule = $_POST['matricule'];
    $departement = $_POST['departement'];
    $password = $_POST['password'];
    $confirm_mdp = $_POST['confirm_mdp'];

    if (!empty($nom) && !empty($prenom) && !empty($matricule) && !empty($departement) && !empty($password)) {
        if ($password === $confirm_mdp) {
            require_once 'database.php';

            $sqlCheck = $pdo->prepare('SELECT * FROM utilisateur WHERE matricule = ?');
            $sqlCheck->execute([$matricule]);

            if ($sqlCheck->rowCount() > 0) {
                echo '<div>Un utilisateur avec ce matricule existe déjà.</div>';
            } else {
                $sqlState = $pdo->prepare('INSERT INTO utilisateur (nom, prenom, email, matricule, departement, password) VALUES (?, ?, ?, ?, ?, ?)');
                $sqlState->execute([$nom, $prenom, $email, $matricule, $departement, $password]);
                header('Location: login.php');
            }
        } else {
            echo '<div>Les mots de passe ne correspondent pas.</div>';
        }
    } else {
        echo '<div>Les champs sont obligatoires.</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Sign up</title>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.js"></script>
    <script src="script.js"></script>
</head>
<body>
<form method="post">
    <div class="logo-container">
        <div class="logo2"><img src="logo app.png" alt="logo" class="logo-img"></div>
    </div>  
    <div class="screen-1">    
        <?php if (isset($message)): ?>
            <div class="error-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <div class="email">
            <label for="nom"><b>Nom</b></label>
            <div class="sec-2">
                <input type="text" id="nom" name="nom" onkeyup="this.value = this.value.toUpperCase();" required>
            </div>
        </div>
        <div class="password">
            <label for="prenom"><b>Prénom</b></label>
            <div class="sec-2">
                <input type="text" id="prenom" name="prenom" onkeyup="this.value = this.value.toUpperCase();" required>
            </div>
        </div>
        <div class="password">
            <label for="email"><b>Adresse email</b></label>
            <div class="sec-2">
                <ion-icon name="mail-outline"></ion-icon>
                <input type="email" name="email" placeholder="email@opmobility.com"/>
            </div>
        </div>
        <div class="password">
            <label for="matricule"><b>Matricule</b></label>
            <div class="sec-2">
                <ion-icon name="id-card-outline"></ion-icon> 
                <input type="text" name="matricule" required/>
            </div>
        </div>
        <div class="password">
            <label for="departement"><b>Département</b></label>
            <div class="sec-2">
                <ion-icon name="people-outline"></ion-icon>
                <select name="departement" class="password" required>
                    <option value="maintenance">Maintenance</option>
                    <option value="qualite">Qualité</option>
                    <option value="production">Production</option>
                    <option value="process">Process</option>
                    <option value="RH">RH</option>
                    <option value="finance">Finance</option>
                    <option value="achats">Achats</option>
                    <option value="IT">IT</option>
                </select>
            </div>
        </div>
        <div class="password">
            <label for="password"><b>Mot de passe</b></label>
            <div class="sec-2">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input class="pas" type="password" name="password" id="password" required/>
                <ion-icon class="show-hide" id="togglePassword" name="eye-outline" onclick="toggleVisibility('password', 'togglePassword')"></ion-icon>
            </div>
        </div>
        <div class="password">
            <label for="confirm_mdp"><b>Confirmation du mot de passe</b></label>
            <div class="sec-2">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input class="pas" type="password" id="confirm_mdp" name="confirm_mdp" required/>
                <ion-icon class="show-hide" id="toggleConfirmPassword" name="eye-outline" onclick="toggleVisibility('confirm_mdp', 'toggleConfirmPassword')"></ion-icon>
            </div>
        </div>
        <button class="login" type="submit" name="login">S'ENREGISTRER</button>
        <div class="footer"><a href="login.php">SE CONNECTER</a></div>
    </div>
</form>
</body>
</html>
