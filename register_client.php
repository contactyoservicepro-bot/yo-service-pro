<?php
session_start();

// Si le client est déjà connecté → dashboard
if (isset($_SESSION["client_email"])) {
    header("Location: dashboard_client.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom      = trim($_POST["nom"]);
    $email    = strtolower(trim($_POST["email"]));
    $password = trim($_POST["password"]);
    $confirm  = trim($_POST["confirm"]);

    // Vérification mot de passe
    if ($password !== $confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {

        // Création du dossier si nécessaire
        if (!is_dir("client_data/accounts")) {
            mkdir("client_data/accounts", 0777, true);
        }

        // Fichier du compte
        $file = "client_data/accounts/" . $email . ".json";

        // Vérifier si le compte existe déjà
        if (file_exists($file)) {
            $error = "Un compte existe déjà avec cet email.";
        } else {

            // Création du compte
            $data = [
                "nom"        => $nom,
                "email"      => $email,
                "password"   => password_hash($password, PASSWORD_DEFAULT),
                "created_at" => date("Y-m-d H:i:s")
            ];

            file_put_contents($file, json_encode($data));

            // Connexion automatique
            $_SESSION["client_email"] = $email;

            header("Location: dashboard_client.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Espace Client - Inscription</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.register-box {
    background: white;
    padding: 40px;
    width: 420px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    text-align: center;
}

.register-box h2 {
    margin-bottom: 25px;
    color: #333;
}

.register-box input {
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}

.register-box button {
    width: 100%;
    padding: 12px;
    margin-top: 20px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
}

.register-box button:hover {
    background: #1e7e34;
}

.register-box p {
    margin-top: 20px;
}

.register-box a {
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

<div class="register-box">
    <h2>Créer un compte client</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="nom" placeholder="Nom complet" required>
        <input type="email" name="email" placeholder="Votre email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="password" name="confirm" placeholder="Confirmer le mot de passe" required>

        <button type="submit">Créer mon compte</button>
    </form>

    <p>Déjà un compte ?
        <a href="login_client.php">Se connecter</a>
    </p>
</div>

</body>
</html>

