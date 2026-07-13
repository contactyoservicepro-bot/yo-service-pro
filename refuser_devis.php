<?php
session_start();

// Vérification du token envoyé dans l’email
if (!isset($_GET['token']) || !isset($_SESSION['token'])) {
    $erreur = "Erreur : lien invalide.";
} elseif ($_GET['token'] !== $_SESSION['token']) {
    $erreur = "Erreur : token incorrect.";
}

if (!isset($erreur)) {

    // Récupération des infos client
    $nom        = $_SESSION['nom'];
    $email      = $_SESSION['email'];
    $prestation = $_SESSION['prestation'];

    // Ton email pro
    $destinataire = "contact.yoservicepro@gmail.com";

    // 1️⃣ MAIL ADMIN
    $sujet_admin = "Devis refusé par $nom";
    $contenu_admin = "
Le client $nom ($email) a refusé le devis pour la prestation :
$prestation

Date : ".date("d/m/Y H:i")."
";

    mail($destinataire, $sujet_admin, $contenu_admin, "From: $destinataire");

    // 2️⃣ ENREGISTREMENT ADMIN
    file_put_contents("admin_data/refused.txt",
    "Nom: $nom | Email: $email | Prestation: $prestation | Date: ".date("d/m/Y H:i")."\n",
    FILE_APPEND);
}
?>

<!-- 3️⃣ DESIGN HTML ICI -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Devis refusé - Yo'Service Pro</title>
    <link rel="stylesheet" href="confirm.css">
</head>

<body>

<div class="container">
    <div class="card <?php echo isset($erreur) ? 'error' : 'warning'; ?>">

        <?php if (isset($erreur)) { ?>
            <h1>✖ Erreur</h1>
            <p><?php echo $erreur; ?></p>

        <?php } else { ?>
            <h1>✖ Devis refusé</h1>
            <p>Bonjour <strong><?php echo $nom; ?></strong>, votre refus a été pris en compte.</p>
            <p>Nous restons disponibles si vous souhaitez modifier votre demande ou obtenir un nouveau devis.</p>
        <?php } ?>

        <a href="index.html" class="btn">Retour à l'accueil</a>
    </div>
</div>

</body>
</html>


