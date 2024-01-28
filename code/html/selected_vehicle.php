<?php
session_start();
include("connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selected_vehicle'])) {
        $selectedVehicleId = $_POST['selected_vehicle'];

        // Insert new task with selected orders and vehicle ID
        $insertTaskSql = "INSERT INTO tasks (selected_orders, selected_vehicle_id) VALUES ('$selectedOrders', '$selectedVehicleId')";
        $conn->query($insertTaskSql);

        // Update markers table to change orders and vehicles from active to inactive
        $updateMarkersSql = "UPDATE markers SET marker_type = 'inactiveTaskCar' WHERE or_id IN ($selectedOrders) OR ve_id = '$selectedVehicleId'";
        $conn->query($updateMarkersSql);

        // Redirect to a success page or another page as needed
        header("Location: admin.php");
        exit();
    } else {
        // No vehicle selected, show a pop-up message or handle accordingly
        echo "<script>alert('Please select a vehicle');</script>";
        echo"<script>window.location.href = 'admin_vehicles.php';</script>";
        exit();
    }
}

// If the form was not submitted, redirect to a default page
header("Location: admin_vehicles.php");
exit();
?>