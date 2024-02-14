<?php
// code to submit an order for the products requested by the user

session_start();
include("../../connection.php");

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productsData = json_decode($_POST["requests"], true);

    // create a single order for all requests made by the user
    $sqlOrder = "INSERT INTO orders (or_c_id, or_date, or_type, order_state) VALUES ($userId, NOW(), 'Αίτημα', 'Σε επεξεργασία')";
    mysqli_query($conn, $sqlOrder);

    $orderId = mysqli_insert_id($conn);

    foreach ($productsData as $product) {
        $quantity = $product["re_number"];
        $productId = $product["re_pr_id"];

        $sqlRequest = "INSERT INTO requests (re_c_id, re_number, re_pr_id, re_or_id) VALUES ($userId, $quantity, $productId, $orderId)";
        mysqli_query($conn, $sqlRequest);
    }

   
    echo "Order submitted successfully";
} else {
    http_response_code(405); 
    echo "Invalid request method";
}
?>


