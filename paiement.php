<?php
session_start();
require 'stripe/init.php';

\Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

$email = strtolower($_GET['email']);
$devis_pdf = $_GET['devis'];

// Charger les infos du devis
$devis_file = "client_data/devis/$email/$devis_pdf";
$prix_total = 0;

// Tu peux ajouter un système pour relire le prix depuis le devis si tu veux

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'customer_email' => $email,
    'line_items' => [[
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => "Paiement du devis",
            ],
            'unit_amount' => $prix_total * 100,
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'https://yo-service-pro.onrender.com/paiement_success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url'  => 'https://yo-service-pro.onrender.com/paiement_cancel.php',
    'metadata' => [
        'email' => $email,
        'devis_pdf' => $devis_pdf
    ]
]);

header("Location: " . $session->url);
exit;
?>
