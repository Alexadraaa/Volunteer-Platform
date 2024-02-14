<?php
// retrieve all the categories from the database and display them in a table
// Including the file containing the database connection
include("../../connection.php");

// Check if searchTerm is set
if(isset($_GET['search']) && !empty($_GET['search'])) {
    // If searchTerm is set, construct SQL query to retrieve categories containing the searchTerm
    $searchTerm = $_GET['search'];
    $sql = "SELECT DISTINCT category FROM base WHERE category LIKE '%$searchTerm%'";
} else {
    // If searchTerm is not set, construct SQL query to retrieve all distinct categories
    $sql = "SELECT DISTINCT category FROM base";
}

// Execute the SQL query
$result = $conn->query($sql);

// Check if there are any categories found
if ($result->num_rows > 0) { 
    // Start table body
    echo '<tbody>';
    // Loop through each row of the result set
    while ($row = $result->fetch_assoc()) {
        // Extract category name from the current row
        $categoryName = $row['category'];
        // Display category name in a table row
        echo '<tr>';
        echo '<td class="category-cell" data-category="' . $categoryName . '" onclick="handleCategoryClick(\'' . $categoryName . '\')">' . $categoryName . '</td>';
        echo '</tr>';
    }
    // End table body
    echo '</tbody>';
} else {
    // If no categories found, display a message in a table row
    echo '<tr><td colspan="1">No categories found</td></tr>';
}

// Close the database connection
$conn->close();
?>
