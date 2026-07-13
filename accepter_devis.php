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

// Mettre le devis en "accepted"
$status[$devis_pdf] = "accepted";

// Sauvegarder
file_put_contents($status_file, json_encode($status));

// Enregistrement admin
if (!is_dir("admin_data")) mkdir("admin_data");

file_put_contents("admin_data/accepted.txt",
"Email: $email | Devis: $devis_pdf | Date: ".date("d/m/Y H:i")."\n",
FILE_APPEND);

// Redirection vers la page de paiement Stripe
header("Location: paiement.php?email=" . urlencode($email) . "&devis=" . urlencode($devis_pdf));
exit;
?>




