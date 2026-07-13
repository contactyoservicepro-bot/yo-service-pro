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

<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.login-box {
    background: white;
    padding: 40px;
    width: 380px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    text-align: center;
}

.login-box h2 {
    margin-bottom: 25px;
    color: #333;
}

.login-box input {
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}

.login-box button {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
}

.login-box button:hover {
    background: #0056d2;
}

.login-box p {
    margin-top: 20px;
}

.login-box a {
    color: #007bff;
    text-decoration: none;
}

.error {
    color: red;
    margin-bottom: 10px;
}
</style>

</head>
<body>

<div class="login-box">
    <h2>Connexion à votre espace</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

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


