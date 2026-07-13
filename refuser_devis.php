<?php
session_start();

// Récupération des paramètres
$email = strtolower($_GET['email']);
$devis_pdf = $_GET['devis'];

// Fichier statut du devis
$status_file = "client_data/devis/$email/status.json";

// Charger le statut actuel
$status = file_exists($status_file)
    ? json_decode(file_get_contents($status_file), true)
    : [];

// Mettre le devis en "refused"
$status[$devis_pdf] = "refused";

// Sauvegarder
file_put_contents($status_file, json_encode($status));

// Enregistrement admin
if (!is_dir("admin_data")) mkdir("admin_data");

file_put_contents("admin_data/refused.txt",
"Email: $email | Devis: $devis_pdf | Date: ".date("d/m/Y H:i")."\n",
FILE_APPEND);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Devis refusé - Yo'Service Pro</title>
    <link rel="stylesheet" href="confirm.css">
</head>

<body>

<div class="container">
    <div class="card warning">

        <h1>✖ Devis refusé</h1>
        <p>Votre décision a été prise en compte.</p>
        <p>Nous restons disponibles si vous souhaitez modifier votre demande ou obtenir un nouveau devis.</p>

        <a href="index.html" class="btn">Retour à l'accueil</a>
    </div>
</div>

</body>
</html>



