<?php
// code to retrieve all the products from the database and display them in a table
include("../../connection.php");

// retrieving the category from the GET parameter
$category = $_GET['category'];

// checking if the 'view' parameter is set in the GET request, otherwise defaulting it to an empty string
$view = isset($_GET['view']) ? $_GET['view'] : '';

$sql = "SELECT product, num FROM base WHERE category = '$category'";

$result = $conn->query($sql);

// checking if there are any rows returned by the query
if ($result->num_rows > 0) {
    // looping through each row of the result set
    while ($row = $result->fetch_assoc()) {
        // extracting product name and quantity from the current row
        $productName = $row['product'];
        $num = $row['num'];

        if ($view === 'input') {
            echo '<tr>';
            echo '<td>' . $productName . '</td>'; 
            echo '<td><input type="number" class="quantity-input" value="1" id="quantityInput_' . $productName . '" data-product-id="' . $productName . '"></td>'; // Input field for quantity
            echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '"></td>'; // checkbox for selection
            echo '</tr>';
        } else {
            echo '<tr>';
            echo '<td>' . $productName . '</td>';  // product name column
            echo '<td>' . $num . '</td>'; // quantity column
            echo '</tr>';
        }
    }
}

$conn->close();
?>