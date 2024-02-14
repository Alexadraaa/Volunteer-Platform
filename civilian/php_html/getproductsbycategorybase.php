<?php
// code to retrieve all the products from the database and display them in a table
// Including the file containing the database connection
include("../../connection.php");

// Retrieving the category from the GET parameter
$category = $_GET['category'];

// Checking if the 'view' parameter is set in the GET request, otherwise defaulting it to an empty string
$view = isset($_GET['view']) ? $_GET['view'] : '';

// Constructing the SQL query to retrieve products from the database based on the provided category
$sql = "SELECT product, num FROM base WHERE category = '$category'";

// Executing the SQL query
$result = $conn->query($sql);

// Checking if there are any rows returned by the query
if ($result->num_rows > 0) {
    // Looping through each row of the result set
    while ($row = $result->fetch_assoc()) {
        // Extracting product name and quantity from the current row
        $productName = $row['product'];
        $num = $row['num'];

        // Checking the value of the 'view' parameter
        if ($view === 'input') {
            // Displaying product details in input form (Case 2)
            echo '<tr>';
            echo '<td>' . $productName . '</td>'; // Product name column
            echo '<td><input type="number" class="quantity-input" value="1" id="quantityInput_' . $productName . '" data-product-id="' . $productName . '"></td>'; // Input field for quantity
            echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '"></td>'; // Checkbox for selection
            echo '</tr>';
        } else {
            // Displaying product details in table format (Case 1)
            echo '<tr>';
            echo '<td>' . $productName . '</td>'; // Product name column
            echo '<td>' . $num . '</td>'; // Quantity column
            echo '</tr>';
        }
    }
}

// Closing the database connection
$conn->close();
?>