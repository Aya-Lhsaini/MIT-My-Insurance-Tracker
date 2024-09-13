<?php
session_start(); 

if (!isset($_SESSION['utilisateur'])) {
    header('Location: login.php');
    exit();
}

$utilisateur = $_SESSION['utilisateur'];
$pdo = new PDO('mysql:host=localhost;dbname=mit', 'root', '');

$sql = 'SELECT * FROM dossier WHERE utilisateur_id = :utilisateur_id';
$sqlstate = $pdo->prepare($sql);
$sqlstate->execute(['utilisateur_id' => $utilisateur['id']]);
$dossiers = $sqlstate->fetchAll(PDO::FETCH_ASSOC);

$sql = 'SELECT * FROM reclamations WHERE utilisateur_id = :utilisateur_id';
$sqlstate = $pdo->prepare($sql);
$sqlstate->execute(['utilisateur_id' => $utilisateur['id']]);
$reclamations = $sqlstate->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes reclamations</title>
    <link rel="stylesheet" href="2.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 100px;
        }
        .reclamation-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .reclamation-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: calc(50% - 20px);
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }
        .reclamation-card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #004274;
            color: #fff;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        .card-body {
            padding: 15px;
        }
        .card-body .date {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .card-body .response {
            font-size: 14px;
            color: #333;
            margin-top: 10px;
        }
        .container2 {
            top: 100px;
        }
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        #overlay.show {
            display: flex;
        }
        #form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            position: relative;
            width: 80%;
            max-width: 500px;
        }
        #close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: transparent;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
        .rclm {
            background-color: #004274; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 4px; 
            font-size: 16px; 
            cursor: pointer; 
            transition: background-color 0.3s ease; 
        }

        .rclm:hover {
            background-color: #00355a; 
        }

        .rclm:focus {
            outline: none; 
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <button class="button-24" id="btn">+ Ajouter une réclamation</button>
    <div id="overlay">
        <div id="form-container">
            <button id="close-btn">&times;</button>
            <form method="post" enctype="multipart/form-data">
                <label for="dossier">Choisir un dossier :</label>
                <select name="dossier" id="dossier" required>
                    <option value="">-- Sélectionnez un dossier --</option>
                    <?php foreach ($dossiers as $dossier) : ?>
                        <option value="<?php echo $dossier['id']; ?>">
                            Dossier <?php echo $dossier['id']; ?> - <?php echo $dossier['type_declaration']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for="sujet">Sujet de la réclamation :</label>
                <textarea id="sujet" name="sujet" rows="4" required></textarea>
                <label for="piece_jointe">Ajouter une nouvelle pièce jointe :</label>
                <input type="file" id="piece_jointe" name="piece_jointe">
                <button type="submit" name="submit_reclamation" class="rclm">Envoyer la réclamation</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('btn').addEventListener('click', function () {
        document.getElementById('overlay').classList.add('show');
    });
    document.getElementById('close-btn').addEventListener('click', function () {
        document.getElementById('overlay').classList.remove('show');
    });
</script>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (isset($_POST['submit_reclamation'])) {
    $dossier_id = $_POST['dossier'];
    $sujet = $_POST['sujet'];

    if (!empty($dossier_id) && !empty($sujet)) {
        $date_reclamation = new DateTime();
        $formatted_date_reclamation = $date_reclamation->format('Y-m-d ');

        $sql = 'INSERT INTO reclamations (utilisateur_id, dossier_id, sujet, date_reclamation) VALUES (?, ?, ?, ?)';
        $sqlstate = $pdo->prepare($sql);
        $sqlstate->execute([$utilisateur['id'], $dossier_id, $sujet, $formatted_date_reclamation]);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'testtestaya43@gmail.com';
            $mail->Password = 'ojtq pust szzh oaev';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('testtestaya43@gmail.com', 'My Insurance Tracker');
            $mail->addAddress('aya.lhs12@gmail.com', 'Admin'); //adresse email admin
            $mail->isHTML(true);
            $mail->Subject = 'Nouvelle réclamation reçue';
            $mail->Body = "
            <p>Bonjour Admin,</p>
            <p>Nous vous informons qu'une nouvelle réclamation a été soumise par <strong>{$utilisateur['nom']} {$utilisateur['prenom']}</strong>.</p>
            <p><strong>Détails de la réclamation :</strong></p>
            <ul>
                <li><strong>Sujet :</strong> $sujet</li>
                <li><strong>ID du Dossier :</strong> $dossier_id</li>
                <li><strong>Date de soumission :</strong> " . date('Y-m-d H:i:s') . "</li>
            </ul>
            <p>Nous vous recommandons de consulter cette réclamation dès que possible afin de prendre les mesures nécessaires.</p>
            <p>Merci de votre diligence.</p>
            <p>Cordialement,</p>
            <p><strong>My Insurance Tracker</strong></p>
        ";
            $mail->send();
            echo 'L\'email a été envoyé avec succès';
        } catch (Exception $e) {
            echo 'L\'email n\'a pas pu être envoyé. Erreur : ', $mail->ErrorInfo;
        }
        echo "<script>alert('Réclamation créée avec succès.'); window.location.href='';</script>";
    } else {
        echo "<script>alert('Veuillez remplir tous les champs.');</script>";
    }
}


?>

<div class="container2">
    <div class="reclamation-cards">
        <?php
        foreach ($reclamations as $reclamation) {
            $dossier_id = $reclamation['dossier_id'];
            $sujet = $reclamation['sujet'];
            $date_reclamation = $reclamation['date_reclamation'];
            $reponse = $reclamation['reponse'];
            $date_reponse = $reclamation['date_response'] ? $reclamation['date_response'] : 'Non encore répondu';
            echo "
            <div class='reclamation-card'>
                <div class='card-header'>Dossier ID: $dossier_id</div>
                <div class='card-body'>
                    <div class='date'>Date de réclamation: $date_reclamation</div>
                    <div class='sujet'>$sujet</div>
                    <div class='response'>Réponse: $reponse</div>
                    <div class='date'>Date de réponse: $date_reponse</div>
                </div>
            </div>";
        }
        ?>
    </div>
</div>
</body>
</html>
