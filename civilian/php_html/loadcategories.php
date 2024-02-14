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

$result = $conn->query($sql);

// check if there are any categories found
if ($result->num_rows > 0) { 
    echo '<tbody>';
    // loop through each row of the result set
    while ($row = $result->fetch_assoc()) {
        // extract category name from the current row
        $categoryName = $row['category'];
        // display category name in a table row
        echo '<tr>';
        echo '<td class="category-cell" data-category="' . $categoryName . '" onclick="handleCategoryClick(\'' . $categoryName . '\')">' . $categoryName . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
} else {
    echo '<tr><td colspan="1">No categories found</td></tr>';
}

$conn->close();
?>
