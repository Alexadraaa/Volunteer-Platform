<?php
session_start();
include("../../connection.php");

function fetchAllMarkersData($conn) {
    $markersData = [];

    // fetch all vehicle markers
    $queryAllVehicles = "SELECT DISTINCT m.marker_id, m.latitude, m.longitude, m.marker_type, v.ve_id, v.ve_username, t.t_id, u.username, v.ve_state
                        FROM markers m
                        JOIN vehicle v ON m.ve_id = v.ve_id
                        LEFT JOIN tasks t ON m.ve_id = t.t_vehicle
                        JOIN rescuer r ON r.resc_ve_id = v.ve_id 
                        JOIN users u ON r.resc_id = u.user_id
                        WHERE m.marker_type IN ('activeTaskCar', 'inactiveTaskCar')";
    $resultAllVehicles = mysqli_query($conn, $queryAllVehicles);

    if ($resultAllVehicles) {
        while ($rowVehicle = mysqli_fetch_assoc($resultAllVehicles)) {
            $markersData[] = $rowVehicle;
        }
    }

    // fetch all active markers
    $queryAllActive = "SELECT m.marker_id,m.latitude, m.longitude, m.marker_type, v.ve_id, v.ve_username,
                       o.or_type, o.or_date, o.or_id, o.order_state, t.t_id,
                       ou.name , ou.lastname ,ou.phone
                      FROM markers m 
                      JOIN orders o ON m.or_id = o.or_id 
                      JOIN tasks t ON o.or_task_id = t.t_id 
                      JOIN vehicle v ON t.t_vehicle = v.ve_id 
                      JOIN rescuer r ON r.resc_ve_id = v.ve_id 
                      JOIN users u ON r.resc_id = u.user_id
                      JOIN users ou ON o.or_c_id = ou.user_id
                      WHERE m.marker_type IN ('activeRequest', 'activeDonation')";
    $resultAllActive = mysqli_query($conn, $queryAllActive);

    if ($resultAllActive) {
        while ($rowActive = mysqli_fetch_assoc($resultAllActive)) {
            $markersData[] = $rowActive;
        }
    }

    // fetch all inactive markers
    $queryAllInactive = "SELECT m.marker_id,m.latitude, m.longitude, m.marker_type, o.or_type, o.or_date, o.or_id, o.order_state,
                         u.name, u.lastname, u.phone
                         FROM markers m 
                         JOIN orders o ON m.or_id = o.or_id 
                         JOIN users u ON o.or_c_id = u.user_id 
                         WHERE m.marker_type IN ('inactiveRequest', 'inactiveDonation')";
    $resultAllInactive = mysqli_query($conn, $queryAllInactive);

    if ($resultAllInactive) {
        while ($rowInactive = mysqli_fetch_assoc($resultAllInactive)) {
            $markersData[] = $rowInactive;
        }
    }

    $queryAllBase = "SELECT marker_id, latitude, longitude, marker_type, NULL AS t_id
    FROM markers
    WHERE marker_type = 'base'
    LIMIT 1";
    $resultAllBase = mysqli_query($conn, $queryAllBase);

    if ($resultAllBase) {
        while ($rowBase = mysqli_fetch_assoc($resultAllBase)) {
          $markersData[] = $rowBase;
        }
    }

    return $markersData;
}

$rows = fetchAllMarkersData($conn);

if (!empty($rows)) {
    echo json_encode($rows);
} else {
    echo json_encode(['message' => 'No markers data found']);
}
?>
