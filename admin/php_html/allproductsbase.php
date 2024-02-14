<?php
// This file is used to display all products in the base table
include("../../connection.php");

$sql = "SELECT product, num FROM base";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productName = $row['product'];
        $num = $row['num'];
      
        echo '<tr>';
        echo '<td>' . $productName . '</td>';
        echo '<td>' . $num . '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="2">No products found</td></tr>';
}

$conn->close();
?>
