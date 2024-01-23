<?php
include("connection.php");
session_start();

$sql = "SELECT markers.latitude, markers.longitude, markers.marker_type, markers.or_id, orders.or_c_id, users.address
        FROM markers
        JOIN orders ON markers.or_id = orders.or_id
        JOIN users ON orders.or_c_id = users.user_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $markersData = array();
    while ($row = $result->fetch_assoc()) {
        $markersData[] = array(
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'markerType' => $row['marker_type'],
            'orderId' => $row['or_id'],
            'customerId' => $row['or_c_id'],
            'address' => $row['address'],
        );
    }

    echo json_encode($markersData);
} else {
    echo json_encode(array());
}

$conn->close();
?>
