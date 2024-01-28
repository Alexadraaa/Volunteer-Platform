<?php
session_start();
include("connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selected_orders']) && is_array($_POST['selected_orders'])) {
        $selectedOrders = implode(",", $_POST['selected_orders']);

        // Redirect to the Admin Vehicles page with the selected orders as a parameter
        header("Location: admin_vehicles.php?selected_orders=$selectedOrders");
        exit();
    } else {
        // No orders selected, show a pop-up message
        echo "<script>alert('Please select orders');</script>";
        // Redirect back to the previous page or another default page
        echo "<script>window.location.href = 'adminorders.php';</script>";
        exit();
    }
}

// If the form was not submitted, redirect to adminorder3.php
header("Location: adminorders.php");
exit();
?>