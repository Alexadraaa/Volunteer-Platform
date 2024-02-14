<?php
// This file is used to insert a new announcement into the database and map it to the selected products in the base table 
// Fetch markers data for rescuer
session_start();
include("../../connection.php");
$userId = $_SESSION['user_id'];

$selectedProducts = $_POST['selectedProducts'];
$announcementContent = $_POST['content'];
$currentDate = date("Y-m-d"); 
$isUploaded = true;


$stmt = $conn->prepare("INSERT INTO announcements (an_ad_id, an_product_id, announcement_content, announcement_date, is_uploaded) VALUES (?, ?, ?, ?, ?)");

if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

$stmt->bind_param("isssd", $userId, $productId, $announcementContent, $currentDate, $isUploaded);

foreach ($selectedProducts as $productId) {
    if (!$stmt->execute()) {
        echo "Error inserting announcement: " . $stmt->error;
        $stmt->close();
        $conn->close();
        exit();
    }
}
$announcementId = $stmt->insert_id;  

$stmt->close();


$stmt = $conn->prepare("INSERT INTO announcement_product_mapping (an_id, an_product_id) VALUES (?, ?)");

if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}

$stmt->bind_param("ii", $announcementId, $productId);

foreach ($selectedProducts as $productId) {
    if (!$stmt->execute()) {
        echo "Error inserting into mapping table: " . $stmt->error;
        $stmt->close();
        $conn->close();
        exit();
    }
   // $storedProductIds[] = $productId;
}

$stmt->close();
$conn->close();

echo "Announcement and mapping successfully inserted.";

?>
