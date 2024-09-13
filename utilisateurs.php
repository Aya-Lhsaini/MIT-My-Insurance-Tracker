<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin - Liste des utilisateurs</title>
    <style>
        .edit-button {
    background-color: #f39c12;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
    margin-right: 5px;
}

.edit-button:hover {
    background-color: #e67e22;
}

        .add-user-button {
            margin-bottom: 20px;
        }

        .add-user-button button {
            background-color: #004274;
            width: 300px;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            justify-content: center;
        }

        .add-user-button button:hover {
            background-color: #57a6b7;
        }

        .delete-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<?php
session_start();

// Connexion à la base de données
require_once 'database.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $sql = 'DELETE FROM utilisateur WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: utilisateurs.php');
        exit;
    }
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = 'SELECT id, nom, prenom, matricule, departement FROM utilisateur';

if (!empty($search)) {
    $sql .= ' WHERE nom LIKE :search OR prenom LIKE :search OR matricule LIKE :search OR departement LIKE :search';
}

$sqlstate = $pdo->prepare($sql);

if (!empty($search)) {
    $sqlstate->bindValue(':search', '%' . $search . '%');
}

$sqlstate->execute();
$utilisateurs = $sqlstate->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'admin_nav.php'; ?>

<div class="container">
    <div class="container2">
        <h2>Liste des utilisateurs:</h2>
        
        <div class="add-user-button">
            <a href="add_user.php"><button type="button">Ajouter Utilisateur</button></a>
        </div>

        <form method="GET" action="">
            <div class="search-container">
                <input type="text" name="search" placeholder="Rechercher un utilisateur...">
                <button type="submit">Rechercher</button>
            </div>
        </form>

        <ul class="responsive-table-1">
            <li class="table-header">
                <div class="col col-1">NOM</div>
                <div class="col col-2">PRENOM</div>
                <div class="col col-3">MATRICULE</div>
                <div class="col col-4">DEPARTEMENT</div>
                <div class="col col-5">ACTIONS</div>
            </li>

            <?php foreach ($utilisateurs as $utilisateur) : ?>
            <li class="table-row">
                <div class="col col-1" data-label="NOM"><?php echo htmlspecialchars($utilisateur['nom']); ?></div>
                <div class="col col-2" data-label="PRENOM"><?php echo htmlspecialchars($utilisateur['prenom']); ?></div>
                <div class="col col-3" data-label="MATRICULE"><?php echo htmlspecialchars($utilisateur['matricule']); ?></div>
                <div class="col col-4" data-label="DEPARTEMENT"><?php echo htmlspecialchars($utilisateur['departement']); ?></div>
                <div class="col col-5">
    <a href="edit_user.php?id=<?php echo $utilisateur['id']; ?>">
        <button type="button" class="edit-button">Modifier</button>
    </a>
    <a href="utilisateurs.php?delete_id=<?php echo $utilisateur['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
        <button type="button" class="delete-button">Supprimer</button>
    </a>
</div>

            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
</body>
</html>
