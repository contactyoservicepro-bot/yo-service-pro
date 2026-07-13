<?php
session_start();
require 'stripe/init.php';
require 'facture_pdf.php';

// Vérification du paramètre Stripe
if (!isset($_GET['session_id'])) {
    $erreur = "Erreur : session Stripe manquante.";
} else {
    $session_id = $_GET['session_id'];

    // Clé secrète Stripe
    \Stripe\Stripe::setApiKey('sk_test_51TpD1tItGpuwsD9KsNUdqO9gXbmkC4MRlBXGwZuc1bD7vSpoXvtAOcDgLrw5Z0OABNStpErolZCvFPhGgl3fTg4p00dJkOZcr6');

    // Récupération de la session Stripe
    try {
        $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);
    } catch (Exception $e) {
        $erreur = "Erreur Stripe : ".$e->getMessage();
    }

    // Vérification du paiement
    if (!isset($erreur) && $checkout_session->payment_status !== "paid") {
        $erreur = "Paiement non confirmé.";
    }
}

if (!isset($erreur)) {

    // Récupération des infos client
    $nom        = $_SESSION['nom'];
    $email      = strtolower($_SESSION['email']);
    $prestation = $_SESSION['prestation'];
    $prix_total = $_SESSION['prix_total'];
    $devis_pdf  = $_SESSION['devis_pdf']; // nom du devis PDF

    // ===============================
    // 1️⃣ CRÉATION DOSSIERS CLIENT
    // ===============================
    $facture_folder  = "client_data/factures/" . $email;
    $paiement_folder = "client_data/paiements/" . $email;
    $devis_status_file = "client_data/devis/" . $email . "/status.json";

    if (!is_dir($facture_folder)) mkdir($facture_folder, 0777, true);
    if (!is_dir($paiement_folder)) mkdir($paiement_folder, 0777, true);

    if (!file_exists($devis_status_file)) file_put_contents($devis_status_file, json_encode([]));

    // ===============================
    // 2️⃣ GÉNÉRATION DE LA FACTURE PDF
    // ===============================
    $nom_facture_pdf = creerFacturePDF($nom, $email, $prestation, $prix_total);

    // Déplacer la facture dans le dossier client
    rename("factures/" . $nom_facture_pdf, $facture_folder . "/" . $nom_facture_pdf);

    // ===============================
    // 3️⃣ ENREGISTREMENT DU PAIEMENT (CLIENT)
    // ===============================
    $paiement_file = $paiement_folder . "/paiements.json";

    $paiements = file_exists($paiement_file)
        ? json_decode(file_get_contents($paiement_file), true)
        : [];

    $paiements[] = [
        "montant" => $prix_total,
        "prestation" => $prestation,
        "date" => date("d/m/Y H:i"),
        "status" => "paid",
        "facture" => $nom_facture_pdf
    ];

    file_put_contents($paiement_file, json_encode($paiements));

    // ===============================
    // 4️⃣ MISE À JOUR DU STATUT DU DEVIS
    // ===============================
    $devis_status = json_decode(file_get_contents($devis_status_file), true);

    $devis_status[$devis_pdf] = "accepted";

    file_put_contents($devis_status_file, json_encode($devis_status));

    // ===============================
    // 5️⃣ ENVOI DE LA FACTURE AU CLIENT
    // ===============================
    $destinataire = "contact.yoservicepro@gmail.com";

    $sujet = "Votre facture - Yo'Service Pro";

    $boundary = md5(time());
    $headers  = "From: $destinataire\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";

    $body  = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";

    $body .= "
Bonjour $nom,

Merci pour votre paiement.

Vous trouverez ci-joint votre facture pour la prestation :
$prestation

Nous restons disponibles pour toute question.

Yo'Service Pro
";

    $body .= "\r\n\r\n--$boundary\r\n";
    $body .= "Content-Type: application/pdf; name=\"$nom_facture_pdf\"\r\n";
    $body .= "Content-Disposition: attachment; filename=\"$nom_facture_pdf\"\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $body .= chunk_split(base64_encode(file_get_contents($facture_folder . "/" . $nom_facture_pdf)));
    $body .= "--$boundary--";

    mail($email, $sujet, $body, $headers);

    // ===============================
    // 6️⃣ MAIL ADMIN
    // ===============================
    mail(
        $destinataire,
        "Paiement confirmé par $nom",
        "Le client $nom ($email) a payé la prestation : $prestation.\nMontant : $prix_total €",
        "From: $destinataire"
    );

    // ===============================
    // 7️⃣ ENREGISTREMENT ADMIN
    // ===============================
    file_put_contents("admin_data/paid.txt",
    "Nom: $nom | Email: $email | Prestation: $prestation | Montant: $prix_total | Date: ".date("d/m/Y H:i")."\n",
    FILE_APPEND);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement confirmé - Yo'Service Pro</title>
    <link rel="stylesheet" href="confirm.css">
</head>

<body>

<div class="container">
    <div class="card <?php echo isset($erreur) ? 'error' : 'success'; ?>">
        <?php if (isset($erreur)) { ?>
            <h1>✖ Erreur</h1>
            <p><?php echo $erreur; ?></p>
        <?php } else { ?>
            <h1>✔ Paiement confirmé</h1>
            <p>Merci <strong><?php echo $nom; ?></strong>, votre facture vous a été envoyée par email.</p>
        <?php } ?>

        <a href="index.html" class="btn">Retour à l'accueil</a>
    </div>
</div>

</body>
</html>


