<?php
session_start();
if (!isset($_SESSION['admin_logged']) || $_SESSION['admin_logged'] !== true) {
    header("Location: login_admin.php");
    exit;
}

function lireFichier($fichier) {
    if (!file_exists($fichier)) return [];
    return file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

$reservations = lireFichier("admin_data/reservations.txt");
$accepted     = lireFichier("admin_data/accepted.txt");
$refused      = lireFichier("admin_data/refused.txt");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin - Yo'Service Pro</title>
<style>
    body {
        margin: 0;
        font-family: Arial;
        background: #f0f2f5;
        display: flex;
    }

    /* Sidebar */
    .sidebar {
        width: 250px;
        background: #1e1e2f;
        color: white;
        height: 100vh;
        padding: 20px;
        position: fixed;
    }
    .sidebar h2 {
        margin-bottom: 30px;
        font-size: 22px;
        text-align: center;
    }
    .sidebar a {
        display: block;
        padding: 12px;
        margin: 10px 0;
        background: #2b2b40;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        transition: 0.2s;
    }
    .sidebar a:hover {
        background: #3a3a55;
    }

    /* Main content */
    .main {
        margin-left: 270px;
        padding: 30px;
        width: 100%;
    }

    /* Cards */
    .cards {
        display: flex;
        gap: 20px;
    }
    .card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        flex: 1;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        text-align: center;
    }
    .card h3 {
        margin: 0;
        font-size: 18px;
    }
    .card p {
        font-size: 28px;
        margin-top: 10px;
        font-weight: bold;
    }

    /* Sections */
    .section {
        margin-top: 40px;
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .section h2 {
        margin-bottom: 20px;
    }
    .section ul {
        list-style: none;
        padding: 0;
    }
    .section li {
        padding: 12px;
        border-bottom: 1px solid #ddd;
    }
</style>
</head>

<body>

<div class="sidebar">
    <h2>Yo'Service Pro</h2>
    <a href="admin.php">Dashboard</a>
    <a href="logout.php">Déconnexion</a>
</div>

<div class="main">
    <h1>Dashboard Admin</h1>

    <div class="cards">
        <div class="card">
            <h3>Réservations</h3>
            <p><?php echo count($reservations); ?></p>
        </div>
        <div class="card">
            <h3>Devis Acceptés</h3>
            <p><?php echo count($accepted); ?></p>
        </div>
        <div class="card">
            <h3>Devis Refusés</h3>
            <p><?php echo count($refused); ?></p>
        </div>
    </div>

    <div class="section">
        <h2>📌 Réservations reçues</h2>
        <ul>
            <?php foreach ($reservations as $r) echo "<li>$r</li>"; ?>
        </ul>
    </div>

    <div class="section">
        <h2>✔️ Devis acceptés</h2>
        <ul>
            <?php foreach ($accepted as $a) echo "<li>$a</li>"; ?>
        </ul>
    </div>

    <div class="section">
        <h2>❌ Devis refusés</h2>
        <ul>
            <?php foreach ($refused as $f) echo "<li>$f</li>"; ?>
        </ul>
    </div>

</div>

</body>
</html>


