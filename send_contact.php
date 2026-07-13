<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.html");
    exit;
}

$nom       = htmlspecialchars($_POST['nom']);
$telephone = htmlspecialchars($_POST['telephone']);
$email     = htmlspecialchars($_POST['email']);
$message   = htmlspecialchars($_POST['message']);

$mail = new PHPMailer(true);

try {
    // SMTP SendGrid
    $mail->isSMTP();
    $mail->Host       = 'smtp.sendgrid.net';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'apikey'; // obligatoire
    $mail->Password   = getenv("SENDGRID_API_KEY");
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Expéditeur
    $mail->setFrom('contact@yoservicepro.fr', 'YoService Pro');

    // Destinataire
    $mail->addAddress('contact.yoservicepro@gmail.com');

    // Email HTML
    $mail->isHTML(true);
    $mail->Subject = "Nouveau message depuis le formulaire de contact";

    $mail->Body = '
    <div style="font-family: Arial, sans-serif; background:#f7f7f7; padding:20px;">
        <div style="max-width:600px; margin:auto; background:white; padding:30px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">

            <h2 style="color:#007bff; margin-bottom:20px;">📩 Nouveau message reçu</h2>

            <p style="font-size:16px; color:#333;">
                Vous avez reçu un nouveau message depuis le site <strong>Yo\'Service Pro</strong>.
            </p>

            <div style="margin-top:25px;">
                <p style="font-size:15px;"><strong>Nom :</strong> '.$nom.'</p>
                <p style="font-size:15px;"><strong>Téléphone :</strong> '.$telephone.'</p>
                <p style="font-size:15px;"><strong>Email :</strong> '.$email.'</p>
            </div>

            <div style="margin-top:25px; padding:15px; background:#f0f4ff; border-left:4px solid #007bff; border-radius:6px;">
                <p style="font-size:15px; color:#333; white-space:pre-line;">
                    '.$message.'
                </p>
            </div>

            <p style="margin-top:30px; font-size:14px; color:#777;">
                Ce message a été envoyé automatiquement depuis le formulaire de contact Yo\'Service Pro.
            </p>

        </div>
    </div>
    ';

    $mail->send();
    echo "Votre message a été envoyé avec succès.";

} catch (Exception $e) {
    echo "Erreur : le message n'a pas pu être envoyé.";
}
?>
