<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_POST['orderId'];

//  deletion in the database
$query = "DELETE FROM orders WHERE or_id = $order_id AND or_c_id = $user_id";
$result = mysqli_query($conn, $query);

if ($result) {
  //  header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);
} else {
 //   header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Failed to delete order']);
}

mysqli_close($conn);
