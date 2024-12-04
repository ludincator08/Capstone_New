<?php 

    require('admin/inc/db_config.php');
    require('admin/inc/essentials.php');

    date_default_timezone_set('Asia/Manila');

    session_start();
    if(!(isset($_SESSION['login'])) && $_SESSION['login'] == true){
    redirect('index.php');
    }

    // session_start();
    require_once('vendor/autoload.php');  // Ensure Guzzle is loaded

    // PayMongo Test API Key (Basic Auth: sk_test_...)
    $api_key = 'sk_test_gFNmRYPeb4T1UZW166N3W9hT';

    // Check if the reservation details are available in the session
    if (!isset($_SESSION['facilities']['available']) || $_SESSION['facilities']['available'] !== true) {
        // Redirect to the reservation page if the facilities is not available
        header("Location: confirm_reservation.php");
        exit();
    }

    // Payment details
    $amount = $_SESSION['facilities']['payment'] * 100;  // Convert amount to cents
    $currency = 'PHP';  // Currency used in the transaction
    $description = "Reservation for " . $_SESSION['facilities']['name'];
    $remarks = "Booking for facilities: " . $_SESSION['facilities']['name'];
    $checkout_url = '';

    // Create payment link with Guzzle
    $client = new \GuzzleHttp\Client();
    try {
        $response = $client->request('POST', 'https://api.paymongo.com/v1/links', [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($api_key . ':'),  // Basic Authentication
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'data' => [
                    'attributes' => [
                        'amount' => $amount,  // Amount in cents (e.g., 10000 cents = 100 PHP)
                        'currency' => $currency,
                        'description' => $description,
                        'remarks' => $remarks,
                        'livemode' => false,  // Set to true if you want to go live
                    ],
                ],
            ]
        ]);

        // Decode the response from PayMongo
        $data = json_decode($response->getBody(), true);

        // Check if the response is valid and get the checkout URL
        if (isset($data['data']['attributes']['checkout_url'])) {
            $checkout_url = $data['data']['attributes']['checkout_url'];
        } else {
            echo "Error creating payment link. Please try again.";
            exit();
        }

        // Redirect user to the PayMongo checkout page
        header("Location: " . $checkout_url);
        exit();

    } catch (\GuzzleHttp\Exception\RequestException $e) {
        // Handle error properly
        echo "Error: " . $e->getMessage();
    }

?>
