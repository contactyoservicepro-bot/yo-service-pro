<?php
session_start();

// Récupération des infos client
$nom        = $_SESSION['nom'] ?? "Client";
$email      = $_SESSION['email'] ?? "";
$prestation = $_SESSION['prestation'] ?? "";

// Ton email pro
$destinataire = "contact.yoservicepro@gmail.com";

// 1️⃣ MAIL ADMIN (optionnel mais recommandé)
mail(
    $destinataire,
    "Paiement annulé par $nom",
    "Le client $nom ($email) a annulé le paiement pour la prestation : $prestation.",
    "From: $destinataire"
);

// 2️⃣ ENREGISTREMENT ADMIN
file_put_contents("admin_data/payment_cancelled.txt",
"Nom: $nom | Email: $email | Prestation: $prestation | Date: ".date("d/m/Y H:i")."\n",
FILE_APPEND);
?>

<!-- 3️⃣ DESIGN HTML ICI (APRÈS LE PHP) -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement annulé - Yo'Service Pro</title>
    <link rel="stylesheet" href="confirm.css">
</head>

<body>

<div class="container">
    <div class="card warning">
        <h1>⚠ Paiement annulé</h1>
        <p>Bonjour <strong><?php echo $nom; ?></strong>, vous avez annulé le paiement.</p>
        <p>Vous pouvez reprendre votre réservation à tout moment.</p>
        <a href="index.html" class="btn">Retour à l'accueil</a>
    </div>
</div>

</body>
</html>

