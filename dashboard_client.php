<?php
session_start();

if (!isset($_SESSION['client_email'])) {
    header("Location: login_client.php");
    exit;
}

$email = strtolower($_SESSION['client_email']);

// Dossiers client
$res_folder   = "client_data/reservations/$email";
$devis_folder = "client_data/devis/$email";
$paiement_folder = "client_data/paiements/$email";

// ===============================
// 1️⃣ RÉSERVATIONS
// ===============================
$reservations = [];

if (is_dir($res_folder)) {
    foreach (glob("$res_folder/*.txt") as $file) {
        $reservations[] = [
            "file" => basename($file),
            "content" => nl2br(file_get_contents($file))
        ];
    }

    $res_status_file = "$res_folder/status.json";
    $res_status = file_exists($res_status_file)
        ? json_decode(file_get_contents($res_status_file), true)
        : [];
}

// ===============================
// 2️⃣ DEVIS
// ===============================
$devis_list = [];
$devis_status = [];

if (is_dir($devis_folder)) {

    foreach (glob("$devis_folder/*.pdf") as $file) {
        $devis_list[] = basename($file);
    }

    $status_file = "$devis_folder/status.json";
    $devis_status = file_exists($status_file)
        ? json_decode(file_get_contents($status_file), true)
        : [];
}

// ===============================
// 3️⃣ PAIEMENTS
// ===============================
$paiements = [];

$paiement_file = "$paiement_folder/paiements.json";
if (file_exists($paiement_file)) {
    $paiements = json_decode(file_get_contents($paiement_file), true);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Espace Client - Yo'Service Pro</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #eef1f5;
    margin: 0;
    padding: 0;
}

.header {
    background: #007bff;
    padding: 20px;
    text-align: center;
    color: white;
    font-size: 22px;
    font-weight: bold;
}

.container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.card h3 {
    margin-bottom: 15px;
    font-size: 20px;
    color: #333;
}

.status {
    font-weight: bold;
    padding: 6px 12px;
    border-radius: 6px;
    display: inline-block;
    margin-top: 8px;
}

.status.pending { background: #ffc107; color: #000; }
.status.accepted { background: #28a745; color: white; }
.status.refused { background: #dc3545; color: white; }

.pdf-link {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 14px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

.pdf-link:hover {
    background: #0056d2;
}

.logout-btn {
    display: block;
    width: 200px;
    margin: 40px auto;
    padding: 12px;
    background: #dc3545;
    color: white;
    text-align: center;
    border-radius: 6px;
    text-decoration: none;
    font-size: 16px;
}

.logout-btn:hover {
    background: #b52a3a;
}
</style>

</head>
<body>

<div class="header">
    Espace Client — Yo'Service Pro
</div>

<div class="container">

    <div class="card">
        <h3>👋 Bonjour, <?php echo $email; ?></h3>
        <p>Voici votre tableau de bord personnel.</p>
    </div>

    <!-- RÉSERVATIONS -->
    <div class="card">
        <h3>📌 Vos réservations</h3>

        <?php if (empty($reservations)) { ?>
            <p>Aucune réservation pour le moment.</p>
        <?php } else { ?>
            <?php foreach ($reservations as $r) { ?>
                <div>
                    <p><strong>Réservation :</strong> <?php echo $r["file"]; ?></p>
                    <p><?php echo $r["content"]; ?></p>

                    <?php
                        $st = $res_status[$r["file"]] ?? "pending";
                        echo "<span class='status $st'>$st</span>";
                    ?>

                    <hr>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- DEVIS -->
    <div class="card">
        <h3>📄 Vos devis</h3>

        <?php if (empty($devis_list)) { ?>
            <p>Aucun devis disponible.</p>
        <?php } else { ?>
            <?php foreach ($devis_list as $d) { ?>
                <div>
                    <p><strong><?php echo $d; ?></strong></p>

                    <?php
                        $st = $devis_status[$d] ?? "pending";
                        echo "<span class='status $st'>$st</span>";
                    ?>

                    <a class="pdf-link" href="client_data/devis/<?php echo $email; ?>/<?php echo $d; ?>" target="_blank">
                        📄 Télécharger le devis
                    </a>

                    <hr>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- PAIEMENTS -->
    <div class="card">
        <h3>💳 Vos paiements</h3>

        <?php if (empty($paiements)) { ?>
            <p>Aucun paiement enregistré.</p>
        <?php } else { ?>
            <?php foreach ($paiements as $p) { ?>
                <div>
                    <p><strong>Montant :</strong> <?php echo $p["montant"]; ?> €</p>
                    <p><strong>Prestation :</strong> <?php echo $p["prestation"]; ?></p>
                    <p><strong>Date :</strong> <?php echo $p["date"]; ?></p>
                    <p><strong>Statut :</strong> <?php echo $p["status"]; ?></p>

                    <a class="pdf-link" href="client_data/factures/<?php echo $email; ?>/<?php echo $p["facture"]; ?>" target="_blank">
                        📄 Télécharger la facture
                    </a>

                    <hr>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <a href="logout_client.php" class="logout-btn">Se déconnecter</a>

</div>

</body>
</html>
