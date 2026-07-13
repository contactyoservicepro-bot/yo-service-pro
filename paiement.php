<?php
session_start();
require 'stripe/init.php';

// Clé secrète Stripe
\Stripe\Stripe::setApiKey('sk_test_51TpD1tItGpuwsD9KsNUdqO9gXbmkC4MRlBXGwZuc1bD7vSpoXvtAOcDgLrw5Z0OABNStpErolZCvFPhGgl3fTg4p00dJkOZcr6');

// Vérification des données
if (!isset($_SESSION['prix_total']) || !isset($_SESSION['prestation']) || !isset($_SESSION['email'])) {
    echo "Erreur : données manquantes.";
    exit;
}

$prix_total = $_SESSION['prix_total'];
$prestation = $_SESSION['prestation'];
$email      = $_SESSION['email'];

// Création de la session Stripe Checkout
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'customer_email' => $email,
    'line_items' => [[
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => "Prestation : $prestation",
            ],
            'unit_amount' => $prix_total * 100, // Stripe = centimes
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'https://tonsite.com/paiement_success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url'  => 'https://tonsite.com/paiement_cancel.php',
]);

// Redirection vers Stripe Checkout
header("Location: " . $session->url);
exit;
?>
