<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reclamations des employes</title>
    <link rel="stylesheet" href="4.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .hidden-sujet {
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }

        .read-sujet {
            color: gray;
            cursor: default;
            text-decoration: none;
        }

        h1 {
            text-align: center;
            color: #004274;
            margin: 20px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #004274;
            color: white;
            text-transform: uppercase;
        }

        td a {
            color: #004274;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #333;
        }

        .response-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .response-form button {
            background-color: #004274;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .response-form button:hover {
            background-color: #00355a;
        }
        .hidden-reponse {
    color: green;
    cursor: pointer;
    text-decoration: underline;
}

.read-reponse {
    color: gray;
    cursor: default;
    text-decoration: none;
}

        .response-message {
            margin-top: 10px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }

            .modal-content {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <?php include 'admin_nav.php'; ?>

    <?php
    require_once 'database.php';

    $statut = isset($_GET['statut']) ? $_GET['statut'] : '';

    $sql = 'SELECT r.*, d.nom AS dossier_nom, d.prenom AS dossier_prenom, d.type_declaration
    FROM reclamations r
    INNER JOIN dossier d ON r.dossier_id = d.id';


    if (!empty($statut)) {
        $sql .= ' WHERE r.statut = :statut';
    }

    $sql .= ' ORDER BY r.id DESC';

    $stmt = $pdo->prepare($sql);

    if (!empty($statut)) {
        $stmt->bindParam(':statut', $statut);
    }

    $stmt->execute();
    $reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    ?>

    <h1>Toutes les Réclamations</h1>

    <form method="GET" action="">
        <label for="statut">Filtrer par statut :</label>
        <select name="statut" id="statut" onchange="this.form.submit()">
            <option value="">Tous</option>
            <option value="remboursé" <?php echo (isset($_GET['statut']) && $_GET['statut'] === 'remboursé') ? 'selected' : ''; ?>>Remboursé</option>
            <option value="en cours" <?php echo (isset($_GET['statut']) && $_GET['statut'] === 'en cours') ? 'selected' : ''; ?>>En cours</option>
        </select>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>ID Dossier</th>
                <th>Sujet</th>
                <th>Pièce Jointe</th>
                <th>Date de Réclamation</th>
                <th>Statut</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Type de Déclaration</th>
                <th>Reponse</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reclamations as $reclamation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reclamation['dossier_id']); ?></td>
                    <td>
                        <span 
                            class="<?php echo $reclamation['lu'] ? 'read-sujet' : 'hidden-sujet'; ?>" 
                            onclick="showSujet(this, <?php echo htmlspecialchars($reclamation['id']); ?>)"
                            data-sujet="<?php echo addslashes(htmlspecialchars($reclamation['sujet'])); ?>"
                        >
                            Cliquez pour voir le sujet
                        </span>
                    </td>
                    <td>
                        <?php if (!empty($reclamation['piece_jointe'])): ?>
                            <a href="uploads/<?php echo htmlspecialchars($reclamation['piece_jointe']); ?>" target="_blank">Voir</a>
                        <?php else: ?>
                            Aucune
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($reclamation['date_reclamation']); ?></td>
                    <td><?php echo htmlspecialchars($reclamation['statut']); ?></td>
                    <td><?php echo htmlspecialchars($reclamation['dossier_nom']); ?></td>
                    <td><?php echo htmlspecialchars($reclamation['dossier_prenom']); ?></td>
                    <td><?php echo htmlspecialchars($reclamation['type_declaration']); ?></td>
                    <td>
    <?php if (!empty($reclamation['reponse'])): ?>
        <span 
            class="hidden-reponse" 
            onclick="showReponse(this, <?php echo htmlspecialchars($reclamation['id']); ?>)" 
            data-reponse="<?php echo addslashes(htmlspecialchars($reclamation['reponse'])); ?>"
        >
            Cliquez pour voir la réponse
        </span>
    <?php else: ?>
        Aucune réponse
    <?php endif; ?>
</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<div id="sujetModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p id="modalContent"></p>
        <form id="responseForm" class="response-form" method="POST">
            <input type="hidden" id="reclamationId" name="reclamationId" value="">
            <textarea id="responseText" name="responseText" placeholder="Votre réponse..." rows="4" required></textarea>
            <button type="submit" name="submit_response">Envoyer la réponse</button>
        </form>
        <div id="responseMessage" class="response-message"></div>
    </div>
</div>

<div id="reponseModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="RcloseModal()">&times;</span>
        <p id="reponsemodalContent"></p>
    </div>
</div>


    <script>
      function showSujet(element, sujetId) {
    const modal = document.getElementById('sujetModal');
    const modalContent = document.getElementById('modalContent');
    const reclamationId = document.getElementById('reclamationId');

    modalContent.textContent = element.dataset.sujet;
    reclamationId.value = sujetId;

    modal.style.display = 'block';

    element.classList.remove('hidden-sujet');
    element.classList.add('read-sujet');

    fetch('update_sujet_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: sujetId })
    });
}

function closeModal() {
    const modal = document.getElementById('sujetModal');
    modal.style.display = 'none';
}

function showReponse(element, reponseId) {
    const modal = document.getElementById('reponseModal');
    const modalContent = document.getElementById('reponsemodalContent');

    modalContent.textContent = element.dataset.reponse;

    modal.style.display = 'block';

    element.classList.remove('hidden-reponse');
    element.classList.add('read-reponse');

    fetch('update_sujet_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: reponseId })
    });
}

function RcloseModal() {
    const modal = document.getElementById('reponseModal');
    modal.style.display = 'none';
}

window.onclick = function(event) {
    if (event.target === document.getElementById('sujetModal')) {
        closeModal();
    } else if (event.target === document.getElementById('reponseModal')) {
        RcloseModal();
    }
};

document.getElementById('responseForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const reclamationId = document.getElementById('reclamationId').value;
    const responseText = document.getElementById('responseText').value;

    fetch('send_respons.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: reclamationId, response: responseText })
    }).then(response => response.json())
      .then(data => {
          const responseMessage = document.getElementById('responseMessage');
          if (data.status === 'success') {
              responseMessage.textContent = 'Réponse envoyée avec succès!';
              responseMessage.style.color = 'green';
              document.getElementById('responseForm').reset();
          } else {
              responseMessage.textContent = 'Erreur lors de l\'envoi de la réponse.';
              responseMessage.style.color = 'red';
          }
      });
});

    </script>
    
</body>
</html>