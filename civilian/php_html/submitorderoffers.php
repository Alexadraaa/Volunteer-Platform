<?php
// code to submit an order for the offers made by the user
session_start();
include("../../connection.php");

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $offersData = json_decode($_POST["offers"], true);

    // create a single order for all offers made by the user
    $sqlOrder = "INSERT INTO orders (or_c_id, or_date, or_type, order_state) VALUES ($userId, NOW(), 'Προσφορά', 'Σε επεξεργασία')";
    mysqli_query($conn, $sqlOrder);

    $orderId = mysqli_insert_id($conn);

    // insert each offer into the offers table with the corresponding order ID
    foreach ($offersData as $offer) {
        $quantity = $offer["o_number"];
        $productId = $offer["o_pr_id"];
        $announcementId = $offer["o_an_id"];

        $sqlOffer = "INSERT INTO offers (o_c_id, o_an_id, o_pr_id, o_number, o_or_id) VALUES ($userId, $announcementId, $productId, $quantity, $orderId)";
        mysqli_query($conn, $sqlOffer);
    }

    echo "Offer submitted successfully";
} else {
    http_response_code(405); 
    echo "Invalid request method";
}
?>
