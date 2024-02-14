<?php
session_start();
include("../../connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    //echo "User ID: $userId";
}

if (isset($_POST['or_id'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['or_id']);

    $update_order_query = "UPDATE orders SET order_state = 'Παραδόθηκε', or_task_id = NULL WHERE or_id = $order_id";

    if (mysqli_query($conn, $update_order_query)) {
        echo "Order updated successfully. ";
    } else {
        echo "Error updating order: " . mysqli_error($conn);
        exit; 
    }

    $delete_marker_query = "DELETE FROM markers WHERE or_id = $order_id";

    if (mysqli_query($conn, $delete_marker_query)) {
        echo "Marker deleted successfully";
    } else {
        echo "Error deleting marker: " . mysqli_error($conn);
    }
} else {
    echo "Order ID not provided in the request";
}

mysqli_close($conn);
?>