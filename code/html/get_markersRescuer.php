<?php
session_start();
include("connection.php");

function fetchMarkersData($conn, $userId) {
    $markersData = [];

    // Fetch vehicle markers
    $queryVehicle = "SELECT m.marker_id,m.latitude, m.longitude, m.marker_type, v.ve_id, v.ve_username, t.t_id
                    FROM markers m
                    JOIN vehicle v ON m.ve_id = v.ve_id
                    JOIN tasks t ON m.ve_id = t.t_vehicle
                    JOIN rescuer r ON v.ve_id = r.resc_ve_id
                    join users u on r.resc_id = u.user_id
                    WHERE u.user_id= $userId
                    AND (m.marker_type ='activeTaskCar'or m.marker_type = 'inactiveTaskCar') LIMIT 1";
    $resultVehicle = mysqli_query($conn, $queryVehicle);

    if ($resultVehicle) {
        while ($rowVehicle = mysqli_fetch_assoc($resultVehicle)) {
            $markersData[] = $rowVehicle;
        }
    }

    // Fetch active markers
    $queryActive = "SELECT m.marker_id,u.name, u.lastname, u.phone, m.latitude, m.longitude, m.marker_type, v.ve_id, v.ve_username, o.or_type, o.or_date, o.or_id, o.order_state, t.t_id
                    FROM markers m
                    JOIN orders o ON m.or_id = o.or_id
                    JOIN tasks t ON o.or_task_id = t.t_id
                    JOIN vehicle v ON t.t_vehicle = v.ve_id
                    JOIN rescuer r ON r.resc_ve_id = v.ve_id
                    JOIN users u ON r.resc_id = u.user_id
                    WHERE u.user_id = $userId
                    AND (m.marker_type = 'activeRequest' OR m.marker_type = 'activeDonation')";
    $resultActive = mysqli_query($conn, $queryActive);

    if ($resultActive) {
        while ($rowActive = mysqli_fetch_assoc($resultActive)) {
            $markersData[] = $rowActive;
        }
    }

    // Fetch inactive markers
    $queryInactive = "SELECT m.marker_id, m.latitude, m.longitude, m.marker_type, o.or_type, o.or_date, o.or_id, o.order_state, u.name, u.lastname, u.phone
    FROM markers m
    JOIN orders o ON m.or_id = o.or_id
    JOIN users u ON o.or_c_id = u.user_id
    WHERE m.marker_type = 'inactiveRequest' OR m.marker_type = 'inactiveDonation'";
    $resultInactive = mysqli_query($conn, $queryInactive);

    if ($resultInactive) {
        while ($rowInactive = mysqli_fetch_assoc($resultInactive)) {
            $markersData[] = $rowInactive;
        }
    }



//Fetch base 
$queryBase = "SELECT m.marker_id,m.latitude,m.longitude,m.marker_type
            from markers m where m.marker_type='base'";

$resultBase =  mysqli_query($conn, $queryBase);

if ($resultBase) {
    while ($rowBase = mysqli_fetch_assoc($resultBase)) {
        $markersData[] = $rowBase;
    }
}


return $markersData;
}


if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $rows = fetchMarkersData($conn, $userId);
    echo json_encode($rows);
} else {
    echo json_encode(['error' => 'User not logged in']);
}
?>


