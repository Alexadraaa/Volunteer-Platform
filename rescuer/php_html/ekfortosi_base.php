<?php
session_start();
include("../../connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

if (isset($_POST['t_id'])) {
    $taskId = mysqli_real_escape_string($conn, $_POST['t_id']);

    // fetch all orders associated with the given task ID
    $fetch_orders_query = "SELECT o.or_id, o.or_type
                           FROM orders o
                           WHERE o.or_task_id = $taskId AND o.or_type = 'Προσφορά'";
    $orders_result = mysqli_query($conn, $fetch_orders_query);

    if ($orders_result) {
        while ($order = mysqli_fetch_assoc($orders_result)) {
            $orderId = $order['or_id'];

            // fetch all requests associated with the current order
            $fetch_requests_query = "SELECT o.o_number, o.o_pr_id
                                     FROM offers o
                                     WHERE o.o_or_id = $orderId";
            $requests_result = mysqli_query($conn, $fetch_requests_query);

            if ($requests_result) {
                while ($request = mysqli_fetch_assoc($requests_result)) {
                    $productId = $request['o_pr_id'];
                    $quantity = $request['o_number'];

                    // update the base table for the current product
                    $update_base_query = "UPDATE base 
                                          SET num = num + $quantity 
                                          WHERE product_id = $productId";
                    $update_result = mysqli_query($conn, $update_base_query);

                    if (!$update_result) {
                        echo "Error updating base table: " . mysqli_error($conn);
                        exit;
                    }
                }
            } else {
                echo "Error fetching requests: " . mysqli_error($conn);
                exit;
            }

            // delete markers associated with the current order
            $delete_marker_query = "DELETE FROM markers WHERE or_id = $orderId";
            $delete_marker_result = mysqli_query($conn, $delete_marker_query);

            if (!$delete_marker_result) {
                echo "Error deleting markers: " . mysqli_error($conn);
                exit;
            }

            // update or_task_id to null for the current order
            $update_order_query = "UPDATE orders SET or_task_id = NULL 
                                   WHERE or_id = $orderId";
            $update_order_result = mysqli_query($conn, $update_order_query);

            if (!$update_order_result) {
                echo "Error updating order: " . mysqli_error($conn);
                exit;
            }
        }
        echo "Products loaded into the vehicle successfully, markers deleted, and order task IDs updated";
    } else {
        echo "Error fetching orders: " . mysqli_error($conn);
    }
} else {
    echo "Task ID not provided in the request";
}

mysqli_close($conn);

?>