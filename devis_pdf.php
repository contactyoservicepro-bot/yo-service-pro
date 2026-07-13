<?php
require("fpdf/fpdf.php");

function creerDevisPDF($nom, $email, $telephone, $adresse, $prestation, $date, $details_tarif, $prix_total, $message) {

    // Nom du fichier PDF
    $nom_pdf = "devis_" . time() . ".pdf";

    // Création du PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Couleur principale Yo'Service Pro
    $bleu = [0, 123, 255];

    // ===============================
    // HEADER AVEC LOGO
    // ===============================
    if (file_exists("logo.png")) {
        $pdf->Image("logo.png", 10, 10, 40); // logo Yo'Service Pro
    }

    $pdf->SetFont("Helvetica", "B", 22);
    $pdf->SetTextColor($bleu[0], $bleu[1], $bleu[2]);
    $pdf->Cell(0, 15, "Devis Yo'Service Pro", 0, 1, "R");
    $pdf->Ln(10);

    // ===============================
    // INFORMATIONS CLIENT
    // ===============================
    $pdf->SetFont("Helvetica", "", 12);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->Cell(0, 8, "Client :", 0, 1);
    $pdf->SetFont("Helvetica", "B", 12);
    $pdf->Cell(0, 8, $nom, 0, 1);
    $pdf->SetFont("Helvetica", "", 12);
    $pdf->Cell(0, 6, "Email : $email", 0, 1);
    $pdf->Cell(0, 6, "Téléphone : $telephone", 0, 1);
    $pdf->Cell(0, 6, "Adresse : $adresse", 0, 1);
    $pdf->Ln(8);

    // ===============================
    // DÉTAILS DE LA PRESTATION
    // ===============================
    $pdf->SetFont("Helvetica", "B", 14);
    $pdf->SetTextColor($bleu[0], $bleu[1], $bleu[2]);
    $pdf->Cell(0, 10, "Détails de la prestation", 0, 1);

    $pdf->SetFont("Helvetica", "", 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 7, "Prestation : " . ucfirst(str_replace("_", " ", $prestation)));
    $pdf->MultiCell(0, 7, "Date souhaitée : " . $date);
    $pdf->Ln(5);

    // ===============================
    // TARIFS
    // ===============================
    $pdf->SetFont("Helvetica", "B", 14);
    $pdf->SetTextColor($bleu[0], $bleu[1], $bleu[2]);
    $pdf->Cell(0, 10, "Tarification", 0, 1);

    $pdf->SetFont("Helvetica", "", 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 7, $details_tarif);
    $pdf->Ln(5);

    // Encadré prix total
    $pdf->SetFont("Helvetica", "B", 16);
    $pdf->SetFillColor(240, 240, 255);
    $pdf->SetTextColor($bleu[0], $bleu[1], $bleu[2]);
    $pdf->Cell(0, 12, "Prix total : " . number_format($prix_total, 2) . " €", 0, 1, "C", true);
    $pdf->Ln(10);

    // ===============================
    // MESSAGE CLIENT
    // ===============================
    if (!empty($message)) {
        $pdf->SetFont("Helvetica", "B", 14);
        $pdf->SetTextColor($bleu[0], $bleu[1], $bleu[2]);
        $pdf->Cell(0, 10, "Message du client", 0, 1);

        $pdf->SetFont("Helvetica", "", 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell(0, 7, $message);
        $pdf->Ln(10);
    }

    // ===============================
    // FOOTER
    // ===============================
    $pdf->SetY(-40);
    $pdf->SetFont("Helvetica", "", 10);
    $pdf->SetTextColor(120, 120, 120);
    $pdf->Cell(0, 6, "Yo'Service Pro - Services à domicile", 0, 1, "C");
    $pdf->Cell(0, 6, "Email : contact.yoservicepro@gmail.com", 0, 1, "C");
    $pdf->Cell(0, 6, "Site : yoservicepro.fr", 0, 1, "C");

    // Sauvegarde du PDF
    $pdf->Output("F", "client_data/devis/$email/$nom_pdf");

    return $nom_pdf;
}
?>

