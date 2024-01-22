<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "alexistest";
// Establish a database connection (replace with your database credentials)


$conn = new mysqli($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch marker data from the database
$query = "SELECT * FROM markers";
$result = $conn->query($query);

$markers = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $markers[] = array(
            'lat' => $row['latitude'],
            'lng' => $row['longitude'],
            'type' => $row['type'],
            'activity' => $row['activity']
        );
    }
}

// Output JSON
header('Content-Type: application/json');
echo json_encode($markers);

// Close connection
$conn->close();

?>
