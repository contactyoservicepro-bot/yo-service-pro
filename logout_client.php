<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Déconnexion - Yo'Service Pro</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.logout-box {
    background: white;
    padding: 40px;
    width: 420px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    text-align: center;
}

.logout-box h2 {
    margin-bottom: 20px;
    color: #333;
}

.logout-box p {
    margin-bottom: 25px;
    color: #555;
}

.logout-box a {
    display: inline-block;
    padding: 12px 20px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 16px;
}

.logout-box a:hover {
    background: #0056d2;
}
</style>

</head>
<body>

<div class="logout-box">
    <h2>Déconnexion réussie</h2>
    <p>Vous avez été déconnecté de votre espace client.</p>

    <a href="login_client.php">Se reconnecter</a>
</div>

</body>
</html>
