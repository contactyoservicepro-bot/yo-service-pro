<?php
session_start();

// Si le client est déjà connecté → dashboard
if (isset($_SESSION["client_email"])) {
    header("Location: dashboard_client.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email    = strtolower(trim($_POST["email"]));
    $password = trim($_POST["password"]);

    // Chemin du fichier client
    $file = "client_data/accounts/" . $email . ".json";

    if (!file_exists($file)) {
        $error = "Aucun compte trouvé avec cet email.";
    } else {

        $data = json_decode(file_get_contents($file), true);

        if (password_verify($password, $data["password"])) {

            // Connexion OK
            $_SESSION["client_email"] = $email;
            header("Location: dashboard_client.php");
            exit;

        } else {
            $error = "Mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Espace Client - Connexion</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="box">
    <h2>Connexion à votre espace</h2>

    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Votre email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>

        <button type="submit">Se connecter</button>
    </form>

    <p>Pas encore de compte ?
        <a href="register_client.php">Créer un compte</a>
    </p>
</div>

</body>
</html>

