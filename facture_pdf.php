<?php
require("fpdf.php");

function creerFacturePDF($nom, $email, $prestation, $prix_total) {

    // Création du PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Titre
    $pdf->SetFont('Arial','B',18);
    $pdf->Cell(0,10,"Facture - Yo'Service Pro",0,1,'C');
    $pdf->Ln(10);

    // Infos client
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,"Nom du client : $nom",0,1);
    $pdf->Cell(0,8,"Email : $email",0,1);
    $pdf->Ln(10);

    // Détails de la facture
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,"Details de la prestation",0,1);

    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,"Prestation : $prestation",0,1);
    $pdf->Cell(0,8,"Montant total payé : $prix_total €",0,1);
    $pdf->Ln(10);

    // Informations légales
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,"Informations",0,1);

    $pdf->SetFont('Arial','',12);
    $pdf->MultiCell(0,8,
        "Cette facture confirme le paiement de la prestation via notre plateforme sécurisée Stripe.\n\n".
        "Date de paiement : ".date("d/m/Y")."\n\n".
        "Merci pour votre confiance."
    );

    $pdf->Ln(10);

    // Signature
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,"Yo'Service Pro",0,1);
    $pdf->Cell(0,8,"Service professionnel de nettoyage et manutention",0,1);

    // Nom du fichier
    $nom_pdf = "facture_".time().".pdf";

    // Dossier factures
    if (!is_dir("factures")) {
        mkdir("factures");
    }

    // Sauvegarde
    $pdf->Output("F", "factures/".$nom_pdf);

    return $nom_pdf;
}
?>
