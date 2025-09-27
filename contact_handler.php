<?php
// Script de traitement du formulaire de contact
session_start();

// Configuration de l'email de destination
$destinataire = "cabreltiomene21@gmail.com";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupérer et nettoyer les données du formulaire
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $telephone = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Variables pour les messages de retour
    $success = false;
    $error_message = '';
    
    // Validation des champs obligatoires
    if (empty($nom)) {
        $error_message = "Le nom est obligatoire.";
    } elseif (empty($prenom)) {
        $error_message = "Le prénom est obligatoire.";
    } elseif (empty($email)) {
        $error_message = "L'email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "L'email n'est pas valide.";
    } elseif (empty($message)) {
        $error_message = "Le message est obligatoire.";
    } else {
        // Préparer l'email
        $sujet = "Nouveau message de contact - Lékol";
        $corps_message = "
        <html>
        <head>
            <title>Nouveau message de contact</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #2B80F6; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #2B80F6; }
                .value { margin-top: 5px; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Nouveau message de contact - Lékol</h2>
                </div>
                <div class='content'>
                    <div class='field'>
                        <div class='label'>Nom :</div>
                        <div class='value'>" . htmlspecialchars($nom) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Prénom :</div>
                        <div class='value'>" . htmlspecialchars($prenom) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Email :</div>
                        <div class='value'>" . htmlspecialchars($email) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Téléphone :</div>
                        <div class='value'>" . htmlspecialchars($telephone) . "</div>
                    </div>
                    <div class='field'>
                        <div class='label'>Message :</div>
                        <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                    </div>
                </div>
                <div class='footer'>
                    <p>Message envoyé depuis le site Lékol le " . date('d/m/Y à H:i') . "</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // En-têtes de l'email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@lekol.com" . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Tentative d'envoi de l'email
        if (mail($destinataire, $sujet, $corps_message, $headers)) {
            $success = true;
            $error_message = "Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.";
        } else {
            $error_message = "Erreur lors de l'envoi du message. Veuillez réessayer plus tard.";
        }
    }
    
    // Redirection avec les messages
    $redirect_url = isset($_POST['from_page']) ? $_POST['from_page'] : 'Contact.php';
    $redirect_url .= '?success=' . ($success ? '1' : '0') . '&message=' . urlencode($error_message);
    
    header("Location: " . $redirect_url);
    exit;
} else {
    // Redirection si accès direct
    header("Location: Contact.php");
    exit;
}
?>
