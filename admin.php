<?php
session_start();

// Sécurité : admin connecté ?
if (!isset($_SESSION['admin_logged'])) {
    header("Location: login_admin.php");
    exit;
}

// Dossiers principaux
$accounts_folder   = "client_data/accounts/";
$reservations_root = "client_data/reservations/";
$devis_root        = "client_data/devis/";
$paiements_root    = "client_data/paiements/";

// ===============================
// 1️⃣ LISTE DES CLIENTS
// ===============================
$clients = [];
if (is_dir($accounts_folder)) {
    foreach (glob($accounts_folder . "*.json") as $file) {
        $clients[] = json_decode(file_get_contents($file), true);
    }
}

// ===============================
// 2️⃣ RÉSERVATIONS
// ===============================
$reservations = [];

foreach (glob($reservations_root . "*", GLOB_ONLYDIR) as $client_dir) {
    $email = basename($client_dir);

    foreach (glob("$client_dir/*.txt") as $file) {
        $reservations[] = [
            "email" => $email,
            "file" => basename($file),
            "content" => nl2br(file_get_contents($file)),
            "status" => json_decode(file_get_contents("$client_dir/status.json"), true)[basename($file)] ?? "pending"
        ];
    }
}

// ===============================
// 3️⃣ DEVIS
// ===============================
$devis_list = [];

foreach (glob($devis_root . "*", GLOB_ONLYDIR) as $client_dir) {
    $email = basename($client_dir);

    $status_file = "$client_dir/status.json";
    $status = file_exists($status_file) ? json_decode(file_get_contents($status_file), true) : [];

    foreach (glob("$client_dir/*.pdf") as $file) {
        $name = basename($file);
        $devis_list[] = [
            "email" => $email,
            "file" => $name,
            "status" => $status[$name] ?? "pending",
            "path" => $file
        ];
    }
}

// ===============================
// 4️⃣ PAIEMENTS
// ===============================
$paiements = [];

foreach (glob($paiements_root . "*", GLOB_ONLYDIR) as $client_dir) {
    $email = basename($client_dir);

    $file = "$client_dir/paiements.json";
    if (file_exists($file)) {
        foreach (json_decode(file_get_contents($file), true) as $p) {
            $paiements[] = [
                "email" => $email,
                "montant" => $p["montant"],
                "prestation" => $p["prestation"],
                "date" => $p["date"],
                "status" => $p["status"],
                "facture" => $p["facture"]
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin - Yo'Service Pro</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #eef1f5;
    margin: 0;
    padding: 0;
}

.header {
    background: #343a40;
    padding: 20px;
    text-align: center;
    color: white;
    font-size: 22px;
    font-weight: bold;
}

.container {
    max-width: 1200px;
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

.section-title {
    font-size: 22px;
    margin-bottom: 15px;
    color: #007bff;
}
</style>

</head>
<body>

<div class="header">
    Dashboard Administrateur — Yo'Service Pro
</div>

<div class="container">

    <!-- CLIENTS -->
    <div class="card">
        <h3 class="section-title">👥 Liste des clients</h3>

        <?php if (empty($clients)) { ?>
            <p>Aucun client enregistré.</p>
        <?php } else { ?>
            <?php foreach ($clients as $c) { ?>
                <p><strong><?php echo $c["nom"]; ?></strong> — <?php echo $c["email"]; ?></p>
                <hr>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- RÉSERVATIONS -->
    <div class="card">
        <h3 class="section-title">📌 Réservations</h3>

        <?php if (empty($reservations)) { ?>
            <p>Aucune réservation.</p>
        <?php } else { ?>
            <?php foreach ($reservations as $r) { ?>
                <p><strong><?php echo $r["email"]; ?></strong> — <?php echo $r["file"]; ?></p>
                <p><?php echo $r["content"]; ?></p>
                <span class="status <?php echo $r["status"]; ?>"><?php echo $r["status"]; ?></span>
                <hr>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- DEVIS -->
    <div class="card">
        <h3 class="section-title">📄 Devis</h3>

        <?php if (empty($devis_list)) { ?>
            <p>Aucun devis.</p>
        <?php } else { ?>
            <?php foreach ($devis_list as $d) { ?>
                <p><strong><?php echo $d["email"]; ?></strong> — <?php echo $d["file"]; ?></p>
                <span class="status <?php echo $d["status"]; ?>"><?php echo $d["status"]; ?></span>

                <a class="pdf-link" href="<?php echo $d["path"]; ?>" target="_blank">
                    📄 Télécharger
                </a>
                <hr>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- PAIEMENTS -->
    <div class="card">
        <h3 class="section-title">💳 Paiements</h3>

        <?php if (empty($paiements)) { ?>
            <p>Aucun paiement.</p>
        <?php } else { ?>
            <?php foreach ($paiements as $p) { ?>
                <p><strong><?php echo $p["email"]; ?></strong></p>
                <p>Montant : <?php echo $p["montant"]; ?> €</p>
                <p>Prestation : <?php echo $p["prestation"]; ?></p>
                <p>Date : <?php echo $p["date"]; ?></p>
                <p>Status : <?php echo $p["status"]; ?></p>

                <a class="pdf-link" href="client_data/factures/<?php echo $p["email"]; ?>/<?php echo $p["facture"]; ?>" target="_blank">
                    📄 Facture
                </a>
                <hr>
            <?php } ?>
        <?php } ?>
    </div>

    <a href="logout.php" class="logout-btn">Déconnexion</a>

</div>

</body>
</html>



