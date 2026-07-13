<?php
session_start();

// 1️⃣ RÉCUPÉRATION DES DONNÉES DU FORMULAIRE
$nom        = $_POST['nom'];
$telephone  = $_POST['telephone'];
$email      = strtolower($_POST['email']);
$adresse    = $_POST['adresse'];
$prestation = $_POST['prestation'];
$date       = $_POST['date'];
$message    = $_POST['message'] ?? "";

$destinataire = "contact.yoservicepro@gmail.com";

// ===============================
// 2️⃣ CALCUL AUTOMATIQUE SELON LA PRESTATION
// ===============================
$details_tarif = "";
$prix_total = 0;

// NETTOYAGE
if ($prestation === "nettoyage") {
    $materiel = $_POST['materiel'];
    $heures   = $_POST['heures_nettoyage'];

    if ($materiel === "yo") {
        $prix_total = 25 * $heures;
        $details_tarif = "Nettoyage avec matériel Yo'Service Pro : 25€/h\nHeures : $heures";
    } else {
        $prix_total = 40 * $heures;
        $details_tarif = "Nettoyage avec matériel du client : 40€/h\nHeures : $heures";
    }
}

// AIDE À DOMICILE
if ($prestation === "aide_domicile") {
    $type_aide = $_POST['type_aide'];

    if ($type_aide === "simple") {
        $heures = $_POST['heures_aide'];
        $prix_total = 20 * $heures;
        $details_tarif = "Aide simple : 20€/h\nHeures : $heures";
    }

    if ($type_aide === "renforcee") {
        $heures = $_POST['heures_aide'];
        $prix_total = 30 * $heures;
        $details_tarif = "Aide renforcée : 30€/h\nHeures : $heures";
    }

    if ($type_aide === "forfait3h") {
        $prix_total = 70;
        $details_tarif = "Forfait aide à domicile 3h : 70€";
    }
}

// AIDE DÉMÉNAGEMENT
if ($prestation === "aide_demenagement") {
    $forfait = $_POST['forfait_demenagement'];

    if ($forfait === "forfait3h") {
        $prix_total = 150;
        $details_tarif = "Forfait déménagement 3h : 150€";
    }

    if ($forfait === "forfait5h") {
        $prix_total = 230;
        $details_tarif = "Forfait déménagement 5h : 230€";
    }
}

// ===============================
// 3️⃣ CRÉATION DES DOSSIERS CLIENT
// ===============================
$client_res_folder   = "client_data/reservations/$email";
$client_devis_folder = "client_data/devis/$email";

if (!is_dir($client_res_folder)) mkdir($client_res_folder, 0777, true);
if (!is_dir($client_devis_folder)) mkdir($client_devis_folder, 0777, true);

$status_file = "$client_res_folder/status.json";
if (!file_exists($status_file)) file_put_contents($status_file, json_encode([]));

// ===============================
// 4️⃣ ENREGISTREMENT DE LA RÉSERVATION
// ===============================
$res_file = "$client_res_folder/" . time() . ".txt";

$res_content = "
Nom : $nom
Téléphone : $telephone
Email : $email
Adresse : $adresse
Prestation : $prestation
Date souhaitée : $date

Message :
$message
";

file_put_contents($res_file, $res_content);

$res_status = json_decode(file_get_contents($status_file), true);
$res_status[basename($res_file)] = "pending";
file_put_contents($status_file, json_encode($res_status));

// ===============================
// 5️⃣ GÉNÉRATION DU DEVIS PDF
// ===============================
require("devis_pdf.php");

$nom_pdf_devis = creerDevisPDF(
    $nom,
    $email,
    $telephone,
    $adresse,
    $prestation,
    $date,
    $details_tarif,
    $prix_total,
    $message
);

// ===============================
// 6️⃣ ENVOI VIA FORMSPREE (avec PDF)
// ===============================

$endpoint = "https://formspree.io/f/mvzeoqdk"; // ton endpoint Formspree

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $endpoint,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => [
        "nom"        => $nom,
        "email"      => $email,
        "telephone"  => $telephone,
        "adresse"    => $adresse,
        "prestation" => $prestation,
        "date"       => $date,
        "message"    => $message,
        "details_tarif" => $details_tarif,
        "prix_total" => $prix_total,

        // Pièce jointe PDF
        "file" => new CURLFile("client_data/devis/$email/$nom_pdf_devis", "application/pdf", $nom_pdf_devis)
    ]
]);

$response = curl_exec($curl);
curl_close($curl);

// ===============================
// 7️⃣ ADMIN
// ===============================
if (!is_dir("admin_data")) mkdir("admin_data");

file_put_contents("admin_data/reservations.txt",
"Nom: $nom | Email: $email | Tel: $telephone | Prestation: $prestation | Prix: $prix_total\n",
FILE_APPEND);

// ===============================
// 8️⃣ MESSAGE FINAL STYLÉ
// ===============================
echo '
<div style="max-width:600px;margin:40px auto;padding:30px;background:#fff;border-radius:12px;
box-shadow:0 4px 15px rgba(0,0,0,0.1);font-family:Arial,sans-serif;text-align:center;">

    <h2 style="color:#007bff;">Merci '.$nom.' 🎉</h2>
    <p style="font-size:16px;color:#333;">Votre demande a bien été envoyée.</p>
    <p style="font-size:16px;color:#333;">Un devis PDF vous a été envoyé par email.</p>

    <a href="index.php" 
       style="display:inline-block;margin-top:20px;padding:12px 20px;background:#007bff;
       color:white;border-radius:8px;text-decoration:none;">
       Retour à l\'accueil
    </a>
</div>
';
?>

