<?php
// This file is used to insert a new marker into the database
session_start();
include("../../connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $markerType = $_POST['markerType'];
    $orId = $_POST['orId'];

    $sql = "INSERT INTO markers (latitude, longitude, marker_type, or_id) VALUES ('$latitude', '$longitude', '$markerType', '$orId')";
    $result = $conn->query($sql);

    if ($result) {
        echo 'Marker inserted successfully.';
    } else {
        echo 'Error inserting marker.';
    }
} else {
    echo 'Invalid request method.';
}

?>