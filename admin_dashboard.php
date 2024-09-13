<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('location: login.php');
    exit();
}

include 'database.php'; // Inclure votre fichier de connexion PDO

$utilisateur = $_SESSION['utilisateur'];

// Gestion de la mise à jour des informations utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $prenom = $_POST['prenom'];
    $matricule = $_POST['matricule'];
    $departement = $_POST['departement'];
    $password = $_POST['password'];
    $confirm_mdp = $_POST['confirm_mdp'];

    $userId = $utilisateur['id']; // On suppose que l'ID de l'utilisateur est stocké dans la session

    if ($password !== '' || $confirm_mdp !== '') {
        if ($password === $confirm_mdp) {
     
            $sql = "UPDATE utilisateur SET nom=?, prenom=?, matricule=?, departement=?, password=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $prenom, $matricule, $departement,$password, $userId]);
        } else {
            $message = 'Les mots de passe ne correspondent pas.';
        }
    } else {
        $sql = "UPDATE utilisateur SET nom=?, prenom=?, matricule=?, departement=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $prenom, $matricule, $departement, $userId]);
    }

    // Mettre à jour les informations de session
    $_SESSION['utilisateur']['nom'] = $name;
    $_SESSION['utilisateur']['prenom'] = $prenom;
    $_SESSION['utilisateur']['matricule'] = $matricule;
    $_SESSION['utilisateur']['departement'] = $departement;
    
    header('location: accueil.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MES INFORMATIONS</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
</head>
<body>
<style>
        #changeInfoModal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: none; 
            z-index: 1000; 
            width: 90%;
            max-width: 600px;
            box-sizing: border-box;
        }

        #changeInfoModal h2 {
            margin: 0 0 20px;
            font-size: 1.6em;
            color: #333;
        }

        #changeInfoModal form {
            display: flex;
            flex-direction: column;
        }

        #changeInfoModal label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        #changeInfoModal input[type="text"],
        #changeInfoModal input[type="password"],
        #changeInfoModal select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 1em;
        }

        #changeInfoModal button {
            padding: 10px 20px;
            background-color: #004274;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        #changeInfoModal button:hover {
            background-color: #003660;
        }

        .error-message {
            color: #ff0000;
            margin-bottom: 15px;
        }

        #changeInfoBtn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #004274;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        #changeInfoBtn:hover {
            background-color: #003660;
        }
        #modalCloseBtn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        #modalCloseBtn:hover {
            color: #007bff; 
            transform: scale(1.1); 
        }
</style>

<?php include 'admin_nav.php'; ?>

<div class="container">
    <div class="container1">
       <center>
           <h1>Bonjour, <?php echo htmlspecialchars($utilisateur['nom']) . ' ' . htmlspecialchars($utilisateur['prenom']); ?></h1>
       </center>
    </div>

    <div class="container2">
        <ul class="responsive-table">
            <h3>Mes informations:</h3>
            <li class="table-header">
                <div class="col col-1">NOM</div>
                <div class="col col-2">PRENOM</div>
                <div class="col col-3">MATRICULE</div>
                <div class="col col-4">DEPARTEMENT</div>
            </li>
            <li class="table-row">
                <div class="col col-1" data-label="NOM"><?php echo htmlspecialchars($utilisateur['nom']); ?></div>
                <div class="col col-2" data-label="PRENOM"><?php echo htmlspecialchars($utilisateur['prenom']); ?></div>
                <div class="col col-3" data-label="MATRICULE"><?php echo htmlspecialchars($utilisateur['matricule']); ?></div>
                <div class="col col-4" data-label="DEPARTEMENT"><?php echo htmlspecialchars($utilisateur['departement']); ?></div>
            </li>
        </ul>
    </div>
    
    <button id="changeInfoBtn">Modifier mes informations</button>

    <div id="changeInfoModal">
        <span id="modalCloseBtn">&times;</span>
        <h2>Modifier vos informations</h2>
        <?php if (isset($message)): ?>
            <div class="error-message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" required><br>

            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>" required><br>

            <label for="matricule">Matricule:</label>
            <input type="text" id="matricule" name="matricule" value="<?php echo htmlspecialchars($utilisateur['matricule']); ?>" required><br>

            <label for="departement">Département:</label>
            <div class="sec-2">
                <select name="departement" id="departement" class="password" required>
                    <option value="maintenance" <?php if ($utilisateur['departement'] === 'maintenance') echo 'selected'; ?>>Maintenance</option>
                    <option value="qualite" <?php if ($utilisateur['departement'] === 'qualite') echo 'selected'; ?>>Qualité</option>
                    <option value="production" <?php if ($utilisateur['departement'] === 'production') echo 'selected'; ?>>Production</option>
                    <option value="process" <?php if ($utilisateur['departement'] === 'process') echo 'selected'; ?>>Process</option>
                    <option value="RH" <?php if ($utilisateur['departement'] === 'RH') echo 'selected'; ?>>RH</option>
                    <option value="finance" <?php if ($utilisateur['departement'] === 'finance') echo 'selected'; ?>>Finance</option>
                    <option value="achats" <?php if ($utilisateur['departement'] === 'achats') echo 'selected'; ?>>Achats</option>
                    <option value="IT" <?php if ($utilisateur['departement'] === 'IT') echo 'selected'; ?>>IT</option>
                </select>
            </div><br>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password"><br>

            <label for="confirm_mdp">Confirmer mot de passe:</label>
            <input type="password" id="confirm_mdp" name="confirm_mdp"><br>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
</div>

<script>
    const changeInfoBtn = document.getElementById("changeInfoBtn");
    const changeInfoModal = document.getElementById("changeInfoModal");
    const closeModalBtn = document.getElementById("modalCloseBtn");

    changeInfoBtn.addEventListener("click", function () {
        changeInfoModal.style.display = "block";
    });

    closeModalBtn.addEventListener("click", function () {
        changeInfoModal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === changeInfoModal) {
            changeInfoModal.style.display = "none";
        }
    });
</script>
</body>
</html>
