<?php
include("connection.php");


$category = $_GET['category'];


$sql = "SELECT product FROM base WHERE category = '$category'";
$result = $conn->query($sql);

// Display product rows dynamically
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productName = $row['product'];
/*
        echo '<tr>';
        echo '<td>' . $productName . '</td>';
        echo '<td><input type="number" class="quantity-input" value="1" id="quantityInput_' . $productName . '"></td>';
        echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '"></td>';
        echo '</tr>';*/
        echo '<tr>';
        echo '<td>' . $productName . '</td>';
        echo '<td><input type="number" class="quantity-input" value="1" id="quantityInput_' . $productName . '" data-product-id="' . $productName . '"></td>';
        echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '"></td>';
        echo '</tr>';
        
    }
} else {
    echo '<tr><td colspan="3">No products found for this category</td></tr>';
}

$conn->close();
?>
