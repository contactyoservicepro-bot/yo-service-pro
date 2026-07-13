<?php
// Sécurisation basique
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.html");
    exit;
}

// Récupération des données
$nom       = htmlspecialchars($_POST['nom']);
$telephone = htmlspecialchars($_POST['telephone']);
$email     = htmlspecialchars($_POST['email']);
$message   = htmlspecialchars($_POST['message']);

// Adresse où tu veux recevoir les messages
$destinataire = "contact.yoservicepro@gmail.com";  // 🔥 Mets ton vrai email ici

// Sujet du mail
$sujet = "Nouveau message depuis le formulaire de contact";

// Contenu du mail
$contenu = "
Vous avez reçu un nouveau message depuis votre site Yo'Service Pro :

Nom : $nom
Téléphone : $telephone
Email : $email

Message :
$message
";

// En-têtes du mail
$headers = "From: $email\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Envoi du mail
if (mail($destinataire, $sujet, $contenu, $headers)) {
    echo "Votre message a été envoyé avec succès.";
} else {
    echo "Erreur : le message n'a pas pu être envoyé.";
}
?>
