<?php

include("../../connection.php");

$response = [];
$results = [];

if (isset($_GET['search'])) {
    $searchTerm = '%' . $_GET['search'] . '%';

    if (!empty($_GET['search'])) {
        $productResult = $conn->prepare("SELECT product, num FROM base WHERE product LIKE ?");
        $productResult->bind_param('s', $searchTerm);
        $productResult->execute();
        $productResult->bind_result($productName, $num);

        while ($productResult->fetch()) {
            $results[] = ['product' => $productName, 'num' => $num];
        }

        $productResult->close();

        if (!empty($results)) {
            $response = ['type' => 1, 'results' => $results];
        } else {
            $categoryResult = $conn->prepare("SELECT DISTINCT category FROM base WHERE category LIKE ?");
            $categoryResult->bind_param('s', $searchTerm);
            $categoryResult->execute();
            $categoryResult->bind_result($categoryName);

            while ($categoryResult->fetch()) {
                $categoryData[] = ['category' => $categoryName];
            }

            $categoryResult->close();

            if (!empty($categoryData)) {
                $response = ['type' => 2, 'results' => $categoryData];
            } else {
                $response = ['type' => 0, 'error' => 'Το προϊόν ή η κατηγορία δεν βρέθηκε.'];
            }
        }
    } else {
        // Handle the case when the user clicks the button without entering any search term
        $response = ['type' => 0, 'error' => 'Παρακαλώ εισάγετε κείμενο για αναζήτηση.'];
    }
} else {
    // Handle the case when $_GET['search'] is not set
    $response = ['type' => 0, 'error' => 'Παρακαλώ εισάγετε κείμενο για αναζήτηση.'];
}

header('Content-Type: application/json');
echo json_encode($response);


$conn->close();

?>
