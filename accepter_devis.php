<?php
session_start();

// Vérification du token envoyé dans l’email
if (!isset($_GET['token']) || !isset($_SESSION['token'])) {
    echo "<h2>Erreur : lien invalide.</h2>";
    exit;
}

if ($_GET['token'] !== $_SESSION['token']) {
    echo "<h2>Erreur : token incorrect.</h2>";
    exit;
}

// Enregistrement admin (optionnel)
file_put_contents("admin_data/accepted.txt",
"Nom: ".$_SESSION['nom']." | Email: ".$_SESSION['email']." | Prestation: ".$_SESSION['prestation']." | Date: ".date("d/m/Y H:i")."\n",
FILE_APPEND);

// Redirection vers la page de paiement Stripe
header("Location: paiement.php");
exit;
?>



