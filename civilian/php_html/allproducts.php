<?php
// retrieve all the products from the database and display them in a table
include("../../connection.php");
$sql = "SELECT product,product_id FROM base";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productName = $row['product'];
        $productId = $row['product_id'];
      
echo '<tr>';
echo '<td>' . $productName . '</td>';
echo '<td><input type="number" class="quantity-input" value="1" id="quantityInput_' . $productName . '" data-product-id="' . $productId . '"></td>';
echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '" data-product-id="' . $productId . '"></td>';
echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3">No products found</td></tr>';
}

$conn->close();

?>

