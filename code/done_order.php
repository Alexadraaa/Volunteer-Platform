<?php
session_start();
include("connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    echo "User ID: $userId";}

// Check if the order ID is provided in the request
if (isset($_POST['order_id'])) {
    // Sanitize the input to prevent SQL injection
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $order_type = mysqli_real_escape_string($conn, $_POST['or_type']);
    $update_order_query = "UPDATE orders SET order_state = 'Παραδόθηκε' WHERE or_id = $order_id";
    $delete_from_order_query = "UPDATE orders SET or_task_id = NULL WHERE or_id = $order_id";
    
    if (mysqli_query($conn,$update_order_query) && mysqli_query($conn, $delete_from_order_query)){
        echo "Order updated successfully";
    } else {
        echo "Error updating order " . mysqli_error($conn);
    }
} else {
    echo "Order ID not provided in the request";
}

// Close the database connection
mysqli_close($conn);
?>