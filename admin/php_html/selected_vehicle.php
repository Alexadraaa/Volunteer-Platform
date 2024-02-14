<?php
// php file to associate selected orders with a selected vehicle and create a new task
session_start();
include("../../connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selectedVehicleId']) && isset($_POST['selectedOrderIds'])) {
        $selectedVehicleId = $_POST['selectedVehicleId'];
        $selectedOrders = $_POST['selectedOrderIds'];

        echo "Received data in selected_vehicle.php:\n";
        echo "Selected Vehicle ID: " . $selectedVehicleId . "\n";
        echo "Selected Order IDs: " . implode(', ', $selectedOrders);

        // check if the selected vehicle has associated tasks
        $checkTasksSql = "SELECT COUNT(*) FROM tasks WHERE t_vehicle = ?";
        $stmt = $conn->prepare($checkTasksSql);
        $stmt->bind_param("s", $selectedVehicleId);
        $stmt->execute();
        $stmt->bind_result($numTasks);
        $stmt->fetch();
        $stmt->close();

        // if the vehicle has no associated tasks, update the marker
        /*
        if ($numTasks == 0) {
            $updateMarkerSql = "UPDATE markers SET marker_type = 'activeTaskCar' WHERE ve_id = ?";
            $stmt = $conn->prepare($updateMarkerSql);
            $stmt->bind_param("s", $selectedVehicleId);
            if ($stmt->execute()) {
                echo "Marker updated successfully. Selected Vehicle ID: $selectedVehicleId\n";
            } else {
                echo "Error updating marker.\n";
                exit();
            }
            $stmt->close();
        }
*/
if ($numTasks == 0) {
    // update marker_type from 'inactiveTaskCar' to 'activeTaskCar' and ve_state from 'onhold' to 'ontheroad'
    $updateMarkerAndVeStateSql = "
        UPDATE markers 
        SET marker_type = 'activeTaskCar', ve_id = ?
        WHERE ve_id = ? AND marker_type = 'inactiveTaskCar'
    ";
    $stmt = $conn->prepare($updateMarkerAndVeStateSql);
    $stmt->bind_param("ss", $selectedVehicleId, $selectedVehicleId);
    
    if ($stmt->execute()) {
        echo "Marker and ve_state updated successfully. Selected Vehicle ID: $selectedVehicleId\n";
    } else {
        echo "Error updating marker and ve_state.\n";
        exit();
    }
    
    $stmt->close();

    // update ve_state from 'onhold' to 'ontheroad' in the vehicle table
    $updateVeStateSql = "UPDATE vehicle SET ve_state = 'ontheroad' WHERE ve_id = ? AND ve_state = 'onhold'";
    $stmt = $conn->prepare($updateVeStateSql);
    $stmt->bind_param("s", $selectedVehicleId);
    
    if ($stmt->execute()) {
        echo "Vehicle state updated successfully. Selected Vehicle ID: $selectedVehicleId\n";
    } else {
        echo "Error updating vehicle state.\n";
        exit();
    }

    $stmt->close();
}
        // insert new task with default t_state 'inprocess'
        $insertTaskSql = "INSERT INTO tasks (t_state, t_date, t_vehicle) VALUES ('inprocess', CURDATE(), ?)";
        $stmt = $conn->prepare($insertTaskSql);
        $stmt->bind_param("s", $selectedVehicleId);
        $stmt->execute();

        $lastInsertedTaskId = $stmt->insert_id;

        foreach ($selectedOrders as $orderID) {
            $updateOrderSql = "UPDATE orders SET or_task_id = ? WHERE or_id = ?";
            $stmt = $conn->prepare($updateOrderSql);
            $stmt->bind_param("ss", $lastInsertedTaskId, $orderID);
            if ($stmt->execute()) {
                echo "Order updated successfully. Order ID: $orderID, Task ID: $lastInsertedTaskId\n";
            } else {
                echo "Error updating order. Order ID: $orderID\n";
            }
            $stmt->close();

            $updateOrderStateSql = "UPDATE orders SET order_state = 'Προς Παράδοση' WHERE or_id = ?";
            $stmt = $conn->prepare($updateOrderStateSql);
            $stmt->bind_param("s", $orderID);

            if ($stmt->execute()) {
                echo "Order state updated successfully. Order ID: $orderID\n";
            } else {
                echo "Error updating order state. Order ID: $orderID\n";
            }

            $stmt->close();
# new
            $updateMarkerVeIdSql = "UPDATE markers SET ve_id = ? WHERE or_id = ?";
            $stmt = $conn->prepare($updateMarkerVeIdSql);
            $stmt->bind_param("ss", $selectedVehicleId, $orderID);
        
            if ($stmt->execute()) {
                echo "Ve_id updated successfully in markers table. Order ID: $orderID\n";
            } else {
                echo "Error updating ve_id in markers table. Order ID: $orderID\n";
            }
        
            $stmt->close();
# stop new
            $updateMarkerSql = "
                UPDATE markers 
                SET marker_type = 
                    CASE 
                        WHEN (SELECT or_type FROM orders WHERE or_id = ?) = 'Αίτημα' THEN 'activeRequest'
                        WHEN (SELECT or_type FROM orders WHERE or_id = ?) = 'Προσφορά' THEN 'activeDonation'
                        ELSE marker_type
                    END
                WHERE or_id = ? AND marker_type IN ('inactiveRequest', 'inactiveDonation')
            ";

            $stmt = $conn->prepare($updateMarkerSql);
            $stmt->bind_param("sss", $orderID, $orderID, $orderID);

            if ($stmt->execute()) {
                echo "Marker updated successfully. Order ID: $orderID\n";
            } else {
                echo "Error updating marker. Order ID: $orderID\n";
            }
            $stmt->close();
        }

        echo json_encode(['success' => true]);
        exit();
    } else {
        exit();
    }
}


exit();


?>
