<?php
// this is the php file that is called when the user clicks the "drop order" button in the rescuer's profile page in order to drop an order from his tasks
session_start();
include("../../connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    echo "User ID: $userId";}


if (isset($_POST['order_id'])) {

    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
// update the order state to "Σε επεξέργασια" and the marker type to "inactiveRequest" or "inactiveDonation"
// means that this order isnt anymore in the rescuer's tasks
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
    
    $delete_from_order_query = "UPDATE orders SET or_task_id = NULL WHERE or_id = $order_id";
    $update_marker_vehicle = "UPDATE markers SET ve_id = NULL  WHERE or_id = $order_id";

    if (mysqli_query($conn, $update_order_query) && mysqli_query($conn, $update_marker_query )&& mysqli_query($conn, $delete_from_order_query)
    && mysqli_query($conn,  $update_marker_vehicle) ){
        echo "Order and marker updated successfully";
    } else {
        echo "Error updating order or marker: " . mysqli_error($conn);
    }
} else {
    echo "Order ID not provided in the request";
}


mysqli_close($conn);
?>
