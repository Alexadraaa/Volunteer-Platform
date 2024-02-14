<?php
//code allowing the user to delete an order from the database.
// Starting the session
session_start();
// Including the file containing the database connection
include("../../connection.php");

// Redirecting to the initial page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.php");
    exit();
}

// Retrieving user ID from session
$user_id = $_SESSION['user_id'];

// Retrieving order ID from POST data
$order_id = $_POST['order_id'];

// Checking if order ID is set in POST data
if (isset($_POST['order_id'])) {
    // Constructing the SQL query to delete the order
    $query = "DELETE FROM orders WHERE or_id = '$order_id' AND or_c_id = '$user_id'"; 

    // Executing the query
    $result = mysqli_query($conn, $query);

    // Checking if the query was successful
    if ($result) {
        // If successful, returning success message as JSON
        echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);
    } else {
        // If failed, returning error message as JSON
        echo json_encode(['success' => false, 'message' => 'Failed to delete order']);
    }
} else {
    // If order ID is not set in POST data, returning error message as JSON
    echo json_encode(['success' => false, 'message' => 'Invalid request. orderId is not set.']);
}

// Closing the database connection
mysqli_close($conn);
?>
