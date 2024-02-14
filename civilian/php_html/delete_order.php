<?php
//code allowing the user to delete an order from the database.
session_start();
include("../../connection.php");


if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.php");
    exit();
}

// retrieving user ID from session
$user_id = $_SESSION['user_id'];

// retrieving order ID from POST data
$order_id = $_POST['order_id'];

// checking if order ID is set in POST data
if (isset($_POST['order_id'])) {

    $query = "DELETE FROM orders WHERE or_id = '$order_id' AND or_c_id = '$user_id'"; 
    $result = mysqli_query($conn, $query);

    // checking if the query was successful
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete order']);
    }
} else {
    // if order ID is not set in POST data, returning error message as JSON
    echo json_encode(['success' => false, 'message' => 'Invalid request. orderId is not set.']);
}


mysqli_close($conn);
?>
