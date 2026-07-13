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
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="box">
    <h2>Créer un compte client</h2>

    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>

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
