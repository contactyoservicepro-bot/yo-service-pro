<?php
require("fpdf.php");

function creerDevisPDF(
    $nom,
    $email,
    $telephone,
    $adresse,
    $prestation,
    $date,
    $details_tarif,
    $prix_total,
    $message
) {

    // Création du PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // -----------------------------
    // TITRE
    // -----------------------------
    $pdf->SetFont('Arial','B',18);
    $pdf->Cell(0,10,"Devis - Yo'Service Pro",0,1,'C');
    $pdf->Ln(10);

    // -----------------------------
    // INFORMATIONS CLIENT
    // -----------------------------
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,"Informations du client",0,1);

    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,"Nom : $nom",0,1);
    $pdf->Cell(0,8,"Email : $email",0,1);
    $pdf->Cell(0,8,"Téléphone : $telephone",0,1);
    $pdf->Cell(0,8,"Adresse : $adresse",0,1);
    $pdf->Ln(8);

    // -----------------------------
    // DÉTAILS DE LA PRESTATION
    // -----------------------------
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,"Détails de la prestation",0,1);

    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,"Prestation : $prestation",0,1);
    $pdf->Cell(0,8,"Date souhaitée : $date",0,1);
    $pdf->Ln(8);

    // -----------------------------
    // TARIFICATION
    // -----------------------------
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,"Tarification",0,1);

    $pdf->SetFont('Arial','',12);
    $pdf->MultiCell(0,8,
        "$details_tarif\n\n".
        "Prix total : $prix_total €"
    );

    // -----------------------------
    // MESSAGE DU CLIENT (OPTIONNEL)
    // -----------------------------
    if (!empty($message)) {
        $pdf->Ln(10);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10,"Message du client",0,1);

        $pdf->SetFont('Arial','',12);
        $pdf->MultiCell(0,8,$message);
    }

    // -----------------------------
    // MENTION PAIEMENT / ACCEPTATION
    // -----------------------------
    $pdf->Ln(10);
    $pdf->SetFont('Arial','I',11);
    $pdf->MultiCell(0,8,
        "Pour confirmer votre prestation, veuillez accepter le devis.\n".
        "Vous serez automatiquement redirigé vers la page de paiement en ligne."
    );

    // -----------------------------
    // NOM DU FICHIER
    // -----------------------------
    $nom_pdf = "devis_".time().".pdf";

    // -----------------------------
    // SAUVEGARDE DANS /devis/
    // -----------------------------
    // Dossier /devis/ (ancien emplacement)
    if (!is_dir("devis")) {
        mkdir("devis");
    }

    // Dossier client
    $client_folder = "client_data/devis/" . strtolower($email);

    if (!is_dir($client_folder)) {
        mkdir($client_folder, 0777, true);
    }

    // Sauvegarde dans le dossier du client
    $pdf->Output("F", $client_folder . "/" . $nom_pdf);

    return $nom_pdf;

?>

