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

$destinataire = "contact.yoservicepro@gmail.com"; // email pro


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
// 3️⃣ CRÉATION AUTOMATIQUE DES DOSSIERS CLIENT
// ===============================
$client_res_folder   = "client_data/reservations/" . $email;
$client_devis_folder = "client_data/devis/" . $email;

if (!is_dir($client_res_folder)) mkdir($client_res_folder, 0777, true);
if (!is_dir($client_devis_folder)) mkdir($client_devis_folder, 0777, true);

// Fichier statut réservation
$status_file = $client_res_folder . "/status.json";
if (!file_exists($status_file)) file_put_contents($status_file, json_encode([]));


// ===============================
// 4️⃣ ENREGISTREMENT DE LA RÉSERVATION
// ===============================
$res_file = $client_res_folder . "/" . time() . ".txt";

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

// Ajouter statut "pending"
$res_status = json_decode(file_get_contents($status_file), true);
$res_status[basename($res_file)] = "pending";
file_put_contents($status_file, json_encode($res_status));


// ===============================
// 5️⃣ MAIL AUTOMATIQUE DE CONFIRMATION AU CLIENT
// ===============================
$sujet_confirmation = "Votre demande a été reçue - Yo'Service Pro";

$contenu_confirmation = "
Bonjour $nom,

Votre demande de prestation a bien été reçue.

Nous allons analyser votre demande et vous envoyer :
- un devis détaillé
- une facture une fois le devis accepté.

Prestation : $prestation
Date souhaitée : $date

Merci pour votre confiance.
Yo'Service Pro
";

mail($email, $sujet_confirmation, $contenu_confirmation, "From: $destinataire");


// ===============================
// 6️⃣ GÉNÉRATION DU DEVIS PDF
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

// Déplacer le devis dans le dossier du client
rename("devis/" . $nom_pdf_devis, $client_devis_folder . "/" . $nom_pdf_devis);


// ===============================
// 7️⃣ ENVOI DU MAIL AVEC LE DEVIS + LIENS ACCEPTER / REFUSER
// ===============================
$sujet_devis = "Votre devis - Yo'Service Pro";

$boundary = md5(time());
$headers  = "From: $destinataire\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";

$body  = "--$boundary\r\n";
$body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";

$body .= "
Bonjour $nom,

Vous trouverez ci-joint votre devis pour la prestation suivante :

Prestation : $prestation
Date souhaitée : $date

Veuillez choisir une option :

Accepter le devis :
https://votresite.com/accepter_devis.php?email=" . urlencode($email) . "&devis=" . urlencode($nom_pdf_devis) . "

Refuser le devis :
https://votresite.com/refuser_devis.php?email=" . urlencode($email) . "&devis=" . urlencode($nom_pdf_devis) . "

Merci pour votre confiance.
Yo'Service Pro
";

$body .= "\r\n\r\n--$boundary\r\n";
$body .= "Content-Type: application/pdf; name=\"$nom_pdf_devis\"\r\n";
$body .= "Content-Disposition: attachment; filename=\"$nom_pdf_devis\"\r\n";
$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
$body .= chunk_split(base64_encode(file_get_contents($client_devis_folder . "/" . $nom_pdf_devis)));
$body .= "--$boundary--";

mail($email, $sujet_devis, $body, $headers);


// ===============================
// 8️⃣ MAIL POUR TOI (ADMIN)
// ===============================
$sujet_admin = "Nouvelle demande de prestation - Yo'Service Pro";

$contenu_admin = "
Nouvelle demande reçue :

Nom : $nom
Téléphone : $telephone
Email : $email
Adresse : $adresse
Prestation : $prestation
Date souhaitée : $date

Message :
$message

Tarif :
$details_tarif
Prix total : $prix_total €
";

mail($destinataire, $sujet_admin, $contenu_admin, "From: $email");


// ===============================
// 9️⃣ ENREGISTREMENT DANS admin_data/
// ===============================
if (!is_dir("admin_data")) mkdir("admin_data");

file_put_contents("admin_data/reservations.txt",
"Nom: $nom | Email: $email | Tel: $telephone | Prestation: $prestation | Date: $date | Prix: $prix_total | Message: $message\n",
FILE_APPEND);


// ===============================
// 🔟 MESSAGE DE CONFIRMATION SUR LE SITE
// ===============================
echo "<h2>Merci $nom, votre demande a bien été envoyée.</h2>";
echo "<p>Un email de confirmation et un devis vous ont été envoyés.</p>";
echo "<a href='index.php'>Retour à l'accueil</a>";

?>
