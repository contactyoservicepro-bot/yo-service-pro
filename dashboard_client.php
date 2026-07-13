<?php
session_start();
if (!isset($_SESSION['client_email'])) {
    header("Location: login_client.php");
    exit;
}

$email = $_SESSION['client_email'];

// Chemins des fichiers
$reservation_file = "client_data/reservations/$email.txt";
$devis_file       = "client_data/devis/$email.txt";
$paiement_file    = "client_data/paiements/$email.txt";

// Lecture des données
$reservation = file_exists($reservation_file) ? file_get_contents($reservation_file) : "Aucune réservation.";
$devis       = file_exists($devis_file) ? file_get_contents($devis_file) : "Aucun devis.";
$paiement    = file_exists($paiement_file) ? file_get_contents($paiement_file) : "Aucun paiement.";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Espace Client</title>
<style>
body { font-family: Arial; background:#f0f2f5; padding:40px; }
.box { max-width:700px; margin:auto; background:white; padding:30px; border-radius:10px; }
h2 { margin-bottom:20px; }
.section { margin-top:20px; padding:20px; background:#fafafa; border-radius:8px; }
a { display:inline-block; margin-top:20px; padding:10px 20px; background:#007bff; color:white; text-decoration:none; border-radius:6px; }
</style>
</head>
<body>

<div class="box">
    <h2>Bienvenue, <?php echo $email; ?></h2>

    <div class="section">
        <h3>📌 Votre réservation</h3>
        <p><?php echo nl2br($reservation); ?></p>
    </div>

    <div class="section">
        <h3>📄 Votre devis</h3>
        <p><?php echo nl2br($devis); ?></p>
    </div>

    <div class="section">
        <h3>💳 Paiement</h3>
        <p><?php echo nl2br($paiement); ?></p>
    </div>

    <a href="logout_client.php">Déconnexion</a>
</div>

</body>
</html>
