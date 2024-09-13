<?php
session_start();
if (!isset($_SESSION['utilisateur'])) {
    header('location: login.php');
    exit();
}
include 'database.php'; // Inclure votre fichier de connexion PDO

$utilisateur = $_SESSION['utilisateur'];

// Définir $selectedUserId si l'utilisateur est sélectionné
$selectedUserId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search) {
    // Modifier la requête SQL pour inclure la recherche
    $sql = 'SELECT id, nom, prenom FROM utilisateur WHERE nom LIKE :search OR prenom LIKE :search';
    $sqlstate = $pdo->prepare($sql);
    $sqlstate->bindValue(':search', '%' . $search . '%');
} else {
    // Requête SQL sans recherche
    $sql = 'SELECT id, nom, prenom FROM utilisateur';
    $sqlstate = $pdo->prepare($sql);
}

$sqlstate->execute();
$utilisateurs = $sqlstate->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'admin_nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="4.css">
    <title>Dossiers par Utilisateur</title>
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.js"></script>
</head>
<body>
    <style>
.container2 {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
}

h1 {
    color: #333;
    font-size: 2.5rem;
    text-align: center;
    margin-bottom: 20px;
}

h2 {
    color: black;
    font-size: 2rem;
    margin-bottom: 20px;
    text-align: center;
}

ul li {
    margin: 10px;
}

ul li a {
    text-decoration: none;
    color: #007bff;
    font-size: 1.2rem;
    border-bottom: 2px solid transparent;
    transition: border-color 0.3s ease;
}

ul li a:hover {
    border-bottom: 2px solid #007bff;
}

.card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin: 15px;
    padding: 20px;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
}

.card-header {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 10px;
}

.card-content {
    font-size: 1rem;
    color: #555;
    margin-bottom: 20px;
}

.card-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.button {
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.button-cloture {
    background-color: #007bff;
    color: #fff;
}

.button-cloture:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.button-cloture-success {
    background-color: #28a745;
    color: #fff;
}

.button-cloture-success:hover {
    background-color: #218838;
    transform: scale(1.05);
}

.sidebar-right {
    position: fixed;
    top: 0;
    right: 0;
    height: 100%;
    width: 250px;
    background: #007bff;
    color: #fff;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    z-index: 1000;
}

.sidebar-right.show {
    transform: translateX(0);
}

.sidebar-right .e-formulaire-text {
    font-size: 1.2rem;
    margin-bottom: 20px;
}

.search-container {
    display: flex;
    justify-content: center;
    margin: 20px auto;
    width: 60%; 
}

.search-container input[type="text"] {
    width: 100%; 
    padding: 10px 15px;
    border: 2px solid #004274;
    border-radius: 25px 0 0 25px;
    font-size: 18px;
    outline: none;
    color: #004274;
    background-color: #ffffff;
    box-shadow: 0px 0px 9px 0px rgb(8, 11, 57);
}

.search-container button {
    padding: 10px 20px;
    border: 2px solid #004274;
    border-radius: 0 25px 25px 0;
    background-color: #004274;
    color: #ffffff;
    font-size: 18px;
    cursor: pointer;
    outline: none;
    transition: background-color 0.3s ease;
}

.search-container button:hover {
    background-color: #57a6b7;
}

@media all and (max-width: 767px) {
    .search-container {
        width: 90%; 
    }

    .search-container input[type="text"] {
        font-size: 16px;
    }

    .search-container button {
        font-size: 16px;
    }
}

.no-dossiers {
    text-align: center;
    font-size: 1.2rem;
    color: #777;
    margin: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
}    </style>

    <div class="container2">
        <?php if ($selectedUserId > 0): ?>
            <?php
            $sqlUser = 'SELECT nom, prenom FROM utilisateur WHERE id = ?';
            $stmtUser = $pdo->prepare($sqlUser);
            $stmtUser->execute([$selectedUserId]);
            $selectedUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
            ?>
            <h2>Dossiers de <?php echo htmlspecialchars($selectedUser['nom'] . ' ' . $selectedUser['prenom']); ?></h2>
            <div class="card-container">
                <?php
                $sqlDossiers = 'SELECT * FROM dossier WHERE utilisateur_id = ? ORDER BY date_depot DESC';
                $stmtDossiers = $pdo->prepare($sqlDossiers);
                $stmtDossiers->execute([$selectedUserId]);
                $dossiers = $stmtDossiers->fetchAll(PDO::FETCH_ASSOC);

                if (empty($dossiers)) {
                    echo '<div class="no-dossiers">Aucun dossier pour le moment</div>';
                } else {
                    foreach ($dossiers as $row) {
                        $id = htmlspecialchars($row['id']);
                        $nom = htmlspecialchars($row['nom']);
                        $prenom = htmlspecialchars($row['prenom']);
                        $lien_parente = htmlspecialchars($row['lien_parente']);
                        $type_declaration = htmlspecialchars($row['type_declaration']);
                        $montant = htmlspecialchars($row['montant']);
                        $date_depot = htmlspecialchars($row['date_depot']);
                        $statut = htmlspecialchars($row['statut']);
                    
                        $row_class = ($statut === 'Remboursé') ? 'rembourse' : 'non-rembourse';
                        ?>
                        <div class='card <?php echo $row_class; ?>'>
                            <div class='card-header'><?php echo $nom . ' ' . $prenom; ?></div>
                            <div class='card-content'>
                                <p><strong>Lien de Parenté:</strong> <?php echo $lien_parente; ?></p>
                                <p><strong>Type de Déclaration:</strong> <?php echo $type_declaration; ?></p>
                                <p><strong>Montant:</strong> <?php echo $montant; ?></p>
                                <p><strong>Date de Dépôt:</strong> <?php echo $date_depot; ?></p>
                                <p><strong>Statut:</strong> <?php echo $statut; ?></p>
                            </div>
                            <div class='card-buttons'>
                                <?php if ($statut === 'Remboursé'): ?>
                                    <span class='button button-cloture-success'>Dossier Clôturé</span>
                                <?php else: ?>
                                    <form action='cloturer_dossier.php' method='post' style='display:inline;'>
                                        <input type='hidden' name='dossier_id' value='<?php echo $id; ?>'>
                                        <button type='submit' class='button button-cloture'>Clôturer le dossier</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        <?php else: ?>
            <h1>Liste des Utilisateurs</h1>
            <form method="GET" action="">
                <div class="search-container">
                    <input type="text" name="search" placeholder="Rechercher un utilisateur..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Rechercher</button>
                </div>
            </form>
            <ul>
                <?php foreach ($utilisateurs as $user): ?>
                    <li><a href="?user_id=<?php echo htmlspecialchars($user['id']); ?>"><?php echo htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
