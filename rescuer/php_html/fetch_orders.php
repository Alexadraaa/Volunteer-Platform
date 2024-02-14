<?php
include("../../connection.php");

if (isset($_GET['taskId'])) {
    $taskId = $_GET['taskId'];

    $query = "SELECT * FROM orders WHERE or_task_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = array();

    while ($row = $result->fetch_assoc()) {
        $orderData = array(
            'or_id' => $row['or_id'],
            'or_type' => $row['or_type'],
            'order_state' => $row['order_state'],
        );

        $orders[] = $orderData;
    }

    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode(array('success' => true, 'orders' => $orders));
} else {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'Task ID not provided'));
}

$conn->close();
?>
