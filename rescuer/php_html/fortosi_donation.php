<?php

session_start();
include("../../connection.php");

if (isset($_POST['or_id'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['or_id']);

    $update_order_query = "UPDATE orders SET order_state = 'Παραδόθηκε' WHERE or_id = $order_id";
    
    if (mysqli_query($conn, $update_order_query)) {
        echo "Order updated successfully";
    } else {
        echo "Error updating order: " . mysqli_error($conn);
    }
} else {
    echo "Order ID not provided in the request";
}
?>