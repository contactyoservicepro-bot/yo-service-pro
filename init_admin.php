<?php
session_start();

// Vérification admin
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: login_admin.php");
    exit;
}

// Dossier admin_data
$folder = "admin_data";

// Liste des fichiers nécessaires
$files = [
    "reservations.txt",
    "accepted.txt",
    "refused.txt",
    "paid.txt",
    "payment_cancelled.txt"
];

// Création du dossier si nécessaire
if (!is_dir($folder)) {
    mkdir($folder);
    $created_folder = true;
}

// Création des fichiers manquants
$created_files = [];

foreach ($files as $file) {
    $path = $folder . "/" . $file;
    if (!file_exists($path)) {
        file_put_contents($path, ""); // fichier vide
        $created_files[] = $file;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Initialisation Admin</title>
    <style>
        body { font-family: Arial; background:#f5f5f5; padding:40px; }
        .box { max-width:600px; margin:auto; background:white; padding:30px; border-radius:10px; }
        h2 { margin-bottom:20px; }
        ul { padding-left:20px; }
        a { display:inline-block; margin-top:20px; padding:10px 20px; background:#007bff; color:white; text-decoration:none; border-radius:6px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Initialisation du système admin</h2>

    <?php if (isset($created_folder)) { ?>
        <p>📁 Dossier <strong>admin_data</strong> créé.</p>
    <?php } ?>

    <?php if (!empty($created_files)) { ?>
        <p>📄 Les fichiers suivants ont été créés :</p>
        <ul>
            <?php foreach ($created_files as $f) echo "<li>$f</li>"; ?>
        </ul>
    <?php } else { ?>
        <p>✔ Tous les fichiers admin existaient déjà. Rien à créer.</p>
    <?php } ?>

    <a href="admin.php">Retour au Dashboard</a>
</div>

</body>
</html>
