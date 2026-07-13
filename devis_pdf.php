<?php
require("fpdf/fpdf.php");

function creerDevisPDF($nom, $email, $telephone, $adresse, $prestation, $date, $details_tarif, $prix_total, $message) {

    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFont('Arial','B',18);
    $pdf->Cell(0,10,"Devis - Yo'Service Pro",0,1,'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,"Client",0,1);

    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,"Nom : $nom",0,1);
    $pdf->Cell(0,8,"Email : $email",0,1);
    $pdf->Cell(0,8,"Téléphone : $telephone",0,1);
    $pdf->Cell(0,8,"Adresse : $adresse",0,1);

    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,"Prestation",0,1);

    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,"$prestation",0,1);
    $pdf->Cell(0,8,"Date souhaitée : $date",0,1);

    $pdf->Ln(10);
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,"Tarification",0,1);

    $pdf->SetFont('Arial','',12);
    $pdf->MultiCell(0,8,"$details_tarif\n\nPrix total : $prix_total €");

    if (!empty($message)) {
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10,"Message du client",0,1);

        $pdf->SetFont('Arial','',12);
        $pdf->MultiCell(0,8,$message);
    }

    $nom_pdf = "devis_" . time() . ".pdf";

    $client_folder = "client_data/devis/" . strtolower($email);
    if (!is_dir($client_folder)) mkdir($client_folder, 0777, true);

    $pdf->Output("F", "$client_folder/$nom_pdf");

    return $nom_pdf;
}
?>

