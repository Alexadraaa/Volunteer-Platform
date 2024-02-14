<?php
// this is the php file that is called when the user logs in and the map is loaded in order to fetch the markers data
session_start();
include("../../connection.php");

function fetchMarkersData($conn, $userId) {
    $markersData = [];

    // fetch vehicle markers data 
    $queryVehicle = " SELECT DISTINCT m.marker_id, m.latitude, m.longitude, m.marker_type, v.ve_id, v.ve_username, t.t_id, u.username, v.ve_state
                        FROM markers m
                        JOIN vehicle v ON m.ve_id = v.ve_id
                        LEFT JOIN tasks t ON m.ve_id = t.t_vehicle
                        JOIN rescuer r ON r.resc_ve_id = v.ve_id 
                        JOIN users u ON r.resc_id = u.user_id
                        WHERE u.user_id = ?
                        AND (m.marker_type ='activeTaskCar' OR m.marker_type = 'inactiveTaskCar')
                        LIMIT 1";

    $stmtVehicle = mysqli_prepare($conn, $queryVehicle);
    mysqli_stmt_bind_param($stmtVehicle, "i", $userId);
    mysqli_stmt_execute($stmtVehicle);
    $resultVehicle = mysqli_stmt_get_result($stmtVehicle);

    if ($resultVehicle) {
        while ($rowVehicle = mysqli_fetch_assoc($resultVehicle)) {
            $markersData[] = $rowVehicle;
        }
    }

    // fetch active markers from each task. If a rescuer have 3 tasks then first would be displayed the markers of the current tasks ,when the task will be done  then the markers of the next task
   /* $queryActive = "SELECT m.marker_id, u2.name, u2.lastname, u2.phone, m.latitude, m.longitude, m.marker_type, v.ve_id, v.ve_username, o.or_type, o.or_date, o.or_id, o.order_state, t.t_id
                    FROM markers m
                    JOIN orders o ON m.or_id = o.or_id
                    JOIN tasks t ON o.or_task_id = t.t_id
                    JOIN vehicle v ON t.t_vehicle = v.ve_id
                    JOIN rescuer r ON r.resc_ve_id = v.ve_id
                    JOIN users u ON r.resc_id = u.user_id
                    JOIN users u2 ON o.or_c_id = u2.user_id
                    WHERE u.user_id = ?
                    AND (m.marker_type = 'activeRequest' OR m.marker_type = 'activeDonation')
                    ORDER BY t.t_id ASC
                    LIMIT 1";*/
                    $queryActive = "SELECT 
                    m.marker_id, ou.name, ou.lastname, ou.phone, 
                    m.latitude, m.longitude, m.marker_type, 
                    v.ve_id, v.ve_username, 
                    o.or_type, o.or_date, o.or_id, o.order_state, 
                    t.t_id,t.t_state
                FROM 
                    markers m
                JOIN 
                    orders o ON m.or_id = o.or_id
                JOIN 
                    tasks t ON o.or_task_id = t.t_id
                JOIN 
                    vehicle v ON t.t_vehicle = v.ve_id
                JOIN 
                    rescuer r ON r.resc_ve_id = v.ve_id
                JOIN 
                    users u ON r.resc_id = u.user_id
                JOIN 
                    users ou ON o.or_c_id = ou.user_id
                WHERE 
                    u.user_id = $userId
                    AND (m.marker_type = 'activeRequest' OR m.marker_type = 'activeDonation')
                    AND t.t_id = (
                        SELECT 
                            MIN(t2.t_id)
                        FROM 
                            tasks t2
                        JOIN 
                            orders o2 ON t2.t_id = o2.or_task_id
                        JOIN 
                            markers m2 ON o2.or_id = m2.or_id
                        WHERE 
                            m2.marker_type IN ('activeRequest', 'activeDonation')
                    )
                   
                ORDER BY 
                    t.t_id ASC";
   $resultActive = mysqli_query($conn, $queryActive);
    if ($resultActive) {
        while ($rowActive = mysqli_fetch_assoc($resultActive)) {
            $markersData[] = $rowActive;
        }
    }

    // fetch inactive markers
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

    // fetch base 
    $queryAllBase = "SELECT m.marker_id, m.latitude, m.longitude, m.marker_type, t.t_id
                    FROM markers m
                    LEFT JOIN vehicle v ON m.ve_id = v.ve_id
                    LEFT JOIN tasks t ON v.ve_id = t.t_vehicle
                    WHERE m.marker_type = 'base'
                    LIMIT 1";
    $resultAllBase = mysqli_query($conn, $queryAllBase);

    if ($resultAllBase) {
        while ($rowBase = mysqli_fetch_assoc($resultAllBase)) {
            $markersData[] = $rowBase;
        }
    }

    return $markersData;
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // check if there are any tasks in progress for the current user, select the minimum task id and check if there are any orders associated with the task.
    // if there are no tasks in progress, then update the marker type to inactiveTaskCar and the vehicle state to onhold
    // if there are tasks in progress, then update the marker type to activeTaskCar and the vehicle state to ontheroad
    // if there are no orders associated with the task, then update the task state to done
    $checkTasksQuery = "SELECT MIN(t_id) AS min_task_id FROM tasks WHERE t_vehicle IN (SELECT resc_ve_id FROM rescuer WHERE resc_id = ?) AND t_state= 'inprocess'";
    $stmtTasks = mysqli_prepare($conn, $checkTasksQuery);
    mysqli_stmt_bind_param($stmtTasks, "i", $userId);
    mysqli_stmt_execute($stmtTasks);
    $resultTasks = mysqli_stmt_get_result($stmtTasks);
    $rowTasks = mysqli_fetch_assoc($resultTasks);
    $minTaskId = $rowTasks['min_task_id'];
    
    $checkOrdersQuery = "SELECT COUNT(*) AS order_count FROM orders WHERE or_task_id = ?";
    $stmtOrders = mysqli_prepare($conn, $checkOrdersQuery);
    mysqli_stmt_bind_param($stmtOrders, "i", $minTaskId);
    mysqli_stmt_execute($stmtOrders);
    $resultOrders = mysqli_stmt_get_result($stmtOrders);
    $rowOrders = mysqli_fetch_assoc($resultOrders);
    $orderCount = $rowOrders['order_count'];

    if ($orderCount == 0) {
        $updateTaskStateQuery = "UPDATE tasks SET t_state = 'done' WHERE t_id = ?";
        $stmtUpdateTaskState = mysqli_prepare($conn, $updateTaskStateQuery);
        mysqli_stmt_bind_param($stmtUpdateTaskState, "i", $minTaskId);
        $updateTaskStateResult = mysqli_stmt_execute($stmtUpdateTaskState);
    }

    $checkMarkerTaskQuery = "SELECT COUNT(*) AS task_count FROM tasks WHERE t_vehicle IN (SELECT resc_ve_id FROM rescuer WHERE resc_id = ? AND t_state = 'inprocess')";
    $stmtMarkerTask = mysqli_prepare($conn, $checkMarkerTaskQuery);
    mysqli_stmt_bind_param($stmtMarkerTask, "i", $userId);
    mysqli_stmt_execute($stmtMarkerTask);
    $resultMarkerTask = mysqli_stmt_get_result($stmtMarkerTask);
    $rowMarkerTask = mysqli_fetch_assoc($resultMarkerTask);
    $taskCount = $rowMarkerTask['task_count'];

    if ($taskCount == 0) {
        $updateMarkerTypeQuery = "UPDATE markers SET marker_type = 'inactiveTaskCar' WHERE ( ve_id IN (SELECT resc_ve_id FROM rescuer WHERE resc_id = ?)  AND or_id IS NULL)";
        $stmtUpdateMarkerType = mysqli_prepare($conn, $updateMarkerTypeQuery);
        mysqli_stmt_bind_param($stmtUpdateMarkerType, "i", $userId);
        $updateMarkerTypeResult = mysqli_stmt_execute($stmtUpdateMarkerType);
    
        if ($updateMarkerTypeResult) {
            $updateVehicleStateQuery = "UPDATE vehicle SET ve_state = 'onhold' WHERE ve_id IN (SELECT resc_ve_id FROM rescuer WHERE resc_id = ?)";
            $stmtUpdateVehicleState = mysqli_prepare($conn, $updateVehicleStateQuery);
            mysqli_stmt_bind_param($stmtUpdateVehicleState, "i", $userId);
            $updateVehicleStateResult = mysqli_stmt_execute($stmtUpdateVehicleState);}
    }else{
        $updateMarkerTypeQuery = "UPDATE markers SET marker_type = 'activeTaskCar' WHERE ( ve_id IN (SELECT resc_ve_id FROM rescuer WHERE resc_id = ?)  AND or_id IS NULL)";
        $stmtUpdateMarkerType = mysqli_prepare($conn, $updateMarkerTypeQuery);
        mysqli_stmt_bind_param($stmtUpdateMarkerType, "i", $userId);
        $updateMarkerTypeResult = mysqli_stmt_execute($stmtUpdateMarkerType);
    
        if ($updateMarkerTypeResult) {
            $updateVehicleStateQuery = "UPDATE vehicle SET ve_state = 'ontheroad' WHERE ve_id IN (SELECT resc_ve_id FROM rescuer WHERE resc_id = ?)";
            $stmtUpdateVehicleState = mysqli_prepare($conn, $updateVehicleStateQuery);
            mysqli_stmt_bind_param($stmtUpdateVehicleState, "i", $userId);
            $updateVehicleStateResult = mysqli_stmt_execute($stmtUpdateVehicleState);}
    
    }
    $rows = fetchMarkersData($conn, $userId);
    echo json_encode($rows);
    }
  


 else {
echo json_encode(['error' => 'User not logged in']);
}
?>


