<?php
session_start();
// update the markers vehicle coordinates when the vehicle moves to a new location
include("../../connection.php");


$markerId = $_POST['id'];
$newLatitude = $_POST['latitude'];
$newLongitude = $_POST['longitude'];

$sql = "UPDATE markers SET latitude = ?, longitude = ? WHERE marker_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ddi", $newLatitude, $newLongitude, $markerId);

if ($stmt->execute()) {
    echo "Coordinates updated successfully";
} else {
    echo "Error updating coordinates: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
