<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // extract data from the POST request
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];

    // ppdate user information in the database
    $sql = "UPDATE users SET name=?, lastname=?, address=?, phone=?, username=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $lastname, $address, $phone, $username, $user_id);

    if ($stmt->execute()) {
        // Handle success, e.g., send a success response
        echo json_encode(['success' => true]);
    } else {
        
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }

    $stmt->close();
}

$conn->close();
?>
