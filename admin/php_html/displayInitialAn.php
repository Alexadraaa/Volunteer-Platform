<?php
// 
session_start();
include("../../connection.php");

// fetch uploaded announcements with is_uploaded = 1
$stmt = $conn->prepare("SELECT DISTINCT announcement_content,announcement_date FROM announcements WHERE is_uploaded = 1");
$stmt->execute();
$result = $stmt->get_result();

$uploadedAnnouncements = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($uploadedAnnouncements);

$stmt->close();
$conn->close();
?>
