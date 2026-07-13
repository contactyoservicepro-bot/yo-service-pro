<?php
session_start();
require("config_admin.php");

// Si le formulaire est envoyé
if (isset($_POST['password'])) {

    if ($_POST['password'] === $ADMIN_PASSWORD) {
        $_SESSION['admin_logged'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <style>
        body { font-family: Arial; background:#f5f5f5; padding:40px; }
        .box { max-width:400px; margin:auto; background:white; padding:30px; border-radius:10px; }
        input { width:100%; padding:10px; margin-top:10px; }
        button { width:100%; padding:10px; margin-top:20px; background:#007bff; color:white; border:none; border-radius:5px; }
        p { color:red; }
    </style>
</head>
<body>

<div class="box">
    <h2>Connexion Admin</h2>

    <?php if(isset($error)) echo "<p>$error</p>"; ?>

    <form method="POST">
        <input type="password" name="password" placeholder="Mot de passe admin">
        <button type="submit">Se connecter</button>
    </form>
</div>

</body>
</html>
