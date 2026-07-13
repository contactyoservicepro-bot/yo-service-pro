<?php
session_start();
require('fpdf/fpdf.php'); // Chemin vers ta librairie FPDF

// Vérification basique
if (!isset($_SESSION['nom'])) {
    echo "Erreur : aucune donnée trouvée.";
    exit;
}

// Récupération des données
$nom        = $_SESSION['nom'];
$telephone  = $_SESSION['telephone'];
$email      = $_SESSION['email'];
$adresse    = $_SESSION['adresse'];
$prestation = $_SESSION['prestation'];
$date       = $_SESSION['date'];
$message    = $_SESSION['message'];

$prix_total    = $_SESSION['prix_total'];
$details_tarif = $_SESSION['details_tarif'];

// Nom du fichier PDF
$nom_pdf = "devis_".time().".pdf";

// ------------------------------------------------------------
// 🟦 GÉNÉRATION DU DEVIS PDF
// ------------------------------------------------------------

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Devis - Yo'Service Pro",0,1,'C');

$pdf->Ln(10);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,"Client : $nom",0,1);
$pdf->Cell(0,10,"Email : $email",0,1);
$pdf->Cell(0,10,"Téléphone : $telephone",0,1);
$pdf->Cell(0,10,"Adresse : $adresse",0,1);

$pdf->Ln(10);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,"Détails de la prestation",0,1);

$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0,8,"Prestation : $prestation\nDate souhaitée : $date\n\nTarification :\n$details_tarif\n\nPrix total : $prix_total €");

$pdf->Ln(10);
$pdf->SetFont('Arial','I',11);
$pdf->MultiCell(0,8,
"Ce devis doit être accepté avant la réalisation de la prestation.\n".
"Une fois accepté, vous serez redirigé vers la page de paiement en ligne."
);

$pdf->Output("F", "devis/".$nom_pdf);

// ------------------------------------------------------------
// 🟧 ENVOI DU DEVIS PAR EMAIL AU CLIENT
// ------------------------------------------------------------

$destinataire = $email;
$sujet = "Votre devis - Yo'Service Pro";

$token = $_SESSION['token'];

$lien_acceptation = "https://tonsite.com/accepter_devis.php?token=$token";
$lien_refus = "https://tonsite.com/refuser_devis.php?token=$token";

$contenu = "
<html>
<body>

<p>Bonjour $nom,</p>

<p>Merci pour votre demande. Votre devis est prêt.</p>

<p><strong>Montant total : $prix_total €</strong></p>

<p>Veuillez choisir une option :</p>

<!-- Bouton accepter -->
<a href='$lien_acceptation'
style='display:inline-block;padding:12px 20px;background:#1e90ff;color:white;
text-decoration:none;border-radius:8px;font-weight:bold;margin-right:10px;'>
Accepter le devis
</a>

<!-- Bouton refuser -->
<a href='$lien_refus'
style='display:inline-block;padding:12px 20px;background:#ff4d4d;color:white;
text-decoration:none;border-radius:8px;font-weight:bold;'>
Refuser le devis
</a>

<p>Une fois accepté, vous serez automatiquement redirigé vers la page de paiement en ligne.</p>

<p>Cordialement,<br>Yo'Service Pro</p>

</body>
</html>
";

$headers  = "From: contact.yoservicepro@gmail.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

mail($destinataire, $sujet, $contenu, $headers);

</body>
</html>
