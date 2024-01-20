<?php
include("connection.php");

$sql = "SELECT DISTINCT category FROM base";
$result = $conn->query($sql);

// Display category rows dynamically
if ($result->num_rows > 0) { 
    echo '<tbody>';
    while ($row = $result->fetch_assoc()) {
        $categoryName = $row['category'];

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