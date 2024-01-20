<?php
/*
session_start();
include("connection.php");

$userId = $_SESSION['user_id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productsData = json_decode($_POST["products"], true);

    foreach ($productsData as $product) {
        $userId = $product["re_c_id"];
        $quantity = $product["re_number"];
        $productId = $product["re_pr_id"];

        // Perform database insertion (replace with your actual database query)
        $sql = "INSERT INTO requests (re_c_id, re_number, re_pr_id) VALUES ($userId, $quantity, $productId)";
        // Execute the query (assuming you have a database connection, $conn)
         mysqli_query($conn, $sql);
    }

    // Send a response back to the client
    echo "Order submitted successfully";
} else {
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method";
}
*/

session_start();
include("connection.php");

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productsData = json_decode($_POST["products"], true);

    // create a single order for all requests made by the user
    $sqlOrder = "INSERT INTO orders (or_c_id, or_date, or_type, order_state) VALUES ($userId, NOW(), 'Αίτημα', 'Σε επεξεργασία')";
    mysqli_query($conn, $sqlOrder);

    // Retrieve the generated order ID
    $orderId = mysqli_insert_id($conn);

    // insert each request into the requests table with the corresponding order ID
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
