<?php
// send_response.php
require 'vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['response'])) {
    $id = $data['id'];
    $response = $data['response'];

    $sql = "UPDATE reclamations SET reponse = :response, date_response = NOW() WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([':response' => $response, ':id' => $id])) {

        try {
   
            $sql = "SELECT utilisateur_id FROM reclamations WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $reclamation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($reclamation && !empty($reclamation['utilisateur_id'])) {
                $utilisateur_id = $reclamation['utilisateur_id'];

                $sql = "SELECT nom, prenom, email FROM utilisateur WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $utilisateur_id]);
                $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($utilisateur && !empty($utilisateur['email'])) {
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'testtestaya43@gmail.com';//adresse email MIT
                        $mail->Password   = 'ojtq pust szzh oaev'; // **mot de passe d'application**
                        $mail->SMTPSecure = 'tls';
                        $mail->Port       = 587;
                        $mail->CharSet    = 'UTF-8';

                        // Destinataires
                        $mail->setFrom('testtestaya43@gmail.com', 'My Insurance Tracker');
                        $mail->addAddress($utilisateur['email'], "{$utilisateur['prenom']} {$utilisateur['nom']}");

                        // Contenu de l'email
                        $mail->isHTML(true);
                        $mail->Subject = 'Réponse à votre réclamation';
                        $mail->Body    = "
                            <p>Bonjour {$utilisateur['prenom']} {$utilisateur['nom']},</p>
                            <p>Nous avons bien reçu votre réclamation concernant le sujet suivant :</p>
    <p><strong>Sujet :</strong> $sujet</p>
                            <p>Nous tenons à vous informer que votre demande a reçu une réponse :</p>
                            <p><strong>Réponse :</strong> {$response}</p>
                            <p>Merci d'utiliser notre service.</p>
                                <p>Cordialement,</p>
    <p><strong>My Insurance Tracker</strong></p>

                        ";

                        $mail->send();
                    } catch (Exception $e) {
                        error_log('Erreur lors de l\'envoi de l\'email : ' . $mail->ErrorInfo);
                       
                    }
                }
            }
        } catch (Exception $e) {
           
            error_log('Erreur lors de la récupération des informations de l\'utilisateur : ' . $e->getMessage());
            
        }


        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update response.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
}
?>
