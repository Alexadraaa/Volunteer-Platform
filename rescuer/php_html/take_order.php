<?php
session_start();
include("../../connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
  //  echo "User ID: $userId";
}

// check if the order ID is provided in the request
if (isset($_POST['order_id'])) {

    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);

// update the order state to "Προς Παράδοση" and the marker type to "activeRequest" or "activeDonation"
    $update_order_query = "UPDATE orders SET order_state = 'Προς Παράδοση' WHERE or_id = $order_id";
    $update_marker_query = "UPDATE markers 
                            SET 
                            marker_type = 
                                CASE 
                                    WHEN marker_type = 'inactiveRequest' THEN 'activeRequest'
                                    WHEN marker_type = 'inactiveDonation' THEN 'activeDonation'
                                    ELSE marker_type
                                    END 
                                WHERE or_id = $order_id";

                            
    $update_task_query = "UPDATE orders SET or_task_id = 
    (SELECT t_id FROM tasks t
     join vehicle v on t.t_vehicle = v.ve_id 
     join rescuer r on v.ve_id = r.resc_ve_id
    WHERE resc_id = $userId LIMIT 1) WHERE or_id = $order_id";

    $update_vehicle = "UPDATE markers SET ve_id=(SELECT v.ve_id from vehicle v
    join rescuer r on v.ve_id=r.resc_ve_id
    where r.resc_id = $userId) WHERE or_id= $order_id" ;

    if (mysqli_query($conn, $update_order_query) && mysqli_query($conn, $update_marker_query)&& mysqli_query($conn, $update_task_query)&& mysqli_query($conn, $update_vehicle)) {
        echo "Order and marker updated successfully";
    } else {
        echo "Error updating order or marker: " . mysqli_error($conn);
    }
} else {
    echo "Order ID not provided in the request";
}

mysqli_close($conn);
?>
