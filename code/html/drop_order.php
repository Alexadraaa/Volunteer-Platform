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

    // Your update queries
    $update_order_query = "UPDATE orders SET order_state = 'Σε επεξέργασια' WHERE or_id = $order_id";
    $update_marker_query = "UPDATE markers 
                            SET 
                            marker_type = 
                                CASE 
                                    WHEN marker_type = 'activeRequest' THEN 'inactiveRequest'
                                    WHEN marker_type = 'activeDonation' THEN 'inactiveDonation'
                                    ELSE marker_type
                                    END 
                                WHERE or_id = $order_id";
    $delete_ve_from_marker_query = "UPDATE markers SET ve_id = NULL WHERE or_id = $order_id";
    $delete_from_order_query = "UPDATE orders SET or_task_id = NULL WHERE or_id = $order_id";
    // Perform the update operations
    if (mysqli_query($conn, $update_order_query) && mysqli_query($conn, $update_marker_query ) && 
    mysqli_query($conn, $delete_ve_from_marker_query) && mysqli_query($conn, $delete_from_order_query) ){
        echo "Order and marker updated successfully";
    } else {
        echo "Error updating order or marker: " . mysqli_error($conn);
    }
} else {
    echo "Order ID not provided in the request";
}

// Close the database connection
mysqli_close($conn);
?>
