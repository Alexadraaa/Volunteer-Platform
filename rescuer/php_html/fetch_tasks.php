<?php
// php file to fetch the tasks of the rescuer
session_start();
include("../../connection.php");

$response = array();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // fetch tasks for the specific rescuer
    $tQuery = "SELECT t_id, t_state, t_date, t_vehicle
                FROM tasks
                LEFT JOIN vehicle ON t_vehicle = ve_id
                LEFT JOIN rescuer ON ve_id = resc_ve_id
                LEFT JOIN users ON resc_id = user_id
                WHERE resc_id = $userId AND t_state = 'inprocess'";

    $tresult = $conn->query($tQuery);

    if ($tresult->num_rows > 0) {
        $tasks = array();

        while ($trow = $tresult->fetch_assoc()) {
            $taskData = array(
                't_id' => $trow['t_id'],
                't_state' => $trow['t_state'],
                't_date' => $trow['t_date'],
                't_vehicle' => $trow['t_vehicle'],
            );

            $tasks[] = $taskData;
        }

        $response['success'] = true;
        $response['tasks'] = $tasks;
    } else {
        $response['success'] = false;
        $response['message'] = "No tasks found for the rescuer.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "User not logged in.";
}

echo json_encode($response);
?>
