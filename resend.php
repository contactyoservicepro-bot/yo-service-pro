<?php

function sendResendEmail($to, $subject, $html, $attachments = []) {

    $apiKey = getenv("RESEND_API_KEY");

    $data = [
        "from" => "YoService Pro <contact@yoservicepro.fr>",
        "to" => [$to],
        "subject" => $subject,
        "html" => $html,
    ];

    if (!empty($attachments)) {
        $data["attachments"] = $attachments;
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.resend.com/emails",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}
