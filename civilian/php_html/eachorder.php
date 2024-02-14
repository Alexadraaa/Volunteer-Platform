<?php
//page that displays the details of a specific order
// Starting the session
session_start();
// Including the file containing the database connection
include("../../connection.php");

// Redirecting to the initial page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../initialpage.php");
    exit();
}

// Retrieving the order ID from the GET parameter
$order_id = $_GET['id'];

// Constructing SQL queries to fetch products for the specific order
$queryProducts = "SELECT b.product, r.re_number
FROM requests r
JOIN base b ON r.re_pr_id = b.product_id
WHERE r.re_or_id = $order_id
UNION ALL
SELECT b.product, o.o_number 
FROM offers o
JOIN base b ON o.o_pr_id = b.product_id
WHERE o.o_or_id = $order_id";

// Executing the query to fetch products for the specific order
$resultProducts = mysqli_query($conn, $queryProducts);

// Checking for errors in fetching order products
if (!$resultProducts) {
    die("Error retrieving order products: " . mysqli_error($conn));
}

// Fetching products for the specific order and storing them in the $products array
$products = [];
while ($rowProduct = mysqli_fetch_assoc($resultProducts)) {
    $products[] = $rowProduct;
}

// Freeing the result set and closing the database connection
mysqli_free_result($resultProducts);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/umf.css">
    <link rel="stylesheet" type="text/css" href="../css/eachorder.css">
    <script src="../js/umf.js"></script>
    <title>Παραγγελίες Πολίτη</title>
</head>
<body>

<div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

<header>
    <h1>Λεπτομέρειες Παραγγελίας</h1>
</header>

<div id="mySidenav">
    <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
    <a href="mainpagecitizen.php" onclick="toggleMenu()">Αρχική</a>
    <a href="announcementscitizen.php" onclick="toggleMenu()">Ανακοινώσεις</a>
    <a href="requests.php" onclick="toggleMenu()">Υπηρεσίες</a>
    <a href="contact.php" onclick="toggleMenu()">Επικοινωνία</a>
</div>

<div id="user-container">
    <button id="imageButton" onclick="toggleUserMenu()">
        <img src="../../img/ssmvtnogc7ue0jufjd03h6mj89.png" alt="Button Image">
        <div id="userMenu" class="dropdown-content">
            <a href="orders.php">Λίστα Αιτημάτων/Προσφορών</a>
            <a href="profilsection.php">Προφίλ</a>
            <a href="../../initialpage.php">Αποσύνδεση</a>
        </div>
    </button>
</div>

<table id="productTable">
    <thead>
    <tr>
        <th colspan="2">Λεπτομέρειες Παραγγελίας με ID: <?php echo $order_id; ?></th>
    </tr>
    <tr>
        <th>Προιόν</th>
        <th>Ποσότητα</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (empty($products)) {
        echo "<tr><td colspan='2'>No products found for this order.</td></tr>";
    } else {
        foreach ($products as $product) {
            echo "<tr>";
            echo "<td>{$product['product']}</td>";
            echo "<td>{$product['re_number']}</td>";
            echo "</tr>";
        }
    }
    ?>
    </tbody>
</table>

<button id="backButton" onclick="goBack()">Πίσω στις Παραγγελίες</button>

<footer>
    <div class="footer-section">
        <div></div>
        <p>Με τη βοήθειά σου, στηρίζεις τον καθημερινό διαμερισμό προϊόντων σε ανθρώπους που το έχουν ανάγκη. Δώρισε σήμερα!</p>
        <a href="announcementscitizen.php" class="button">
            <img src="../../img/donate.png" alt="Donate Now">
        </a>
    </div>
    <hr class="divider">
    <div class="section2">
        <div class="column">
            <h3>Επικοινωνία</h3>
            <ul>
                <li>Τηλέφωνο(χωρίς χρέωση): +306946930521</li>
                <li>Κινητό: +306946930521</li>
            </ul>
            <div id="social-media" class="left-align-icons" style="margin-top: 10px;">
                <a href="#" class="fa fa-facebook icon-small" target="_blank" rel="noopener noreferrer"></a>
                <a href="#" class="fa fa-twitter icon-medium" target="_blank" rel="noopener noreferrer"></a>
                <a href="mailto:thebestteam@outlook.com" class="fa fa-envelope icon-small"></a>
            </div>
        </div>
        <div class="column">
            <h3>Links</h3>
            <ul>
                <li><a href="mainpagecitizen.php">Η Ομάδα Μας</a></li>
                <li><a href="requests.php">Υπηρεσίες</a></li>
                <li><a href="contact.php">Επικοινωνία</a></li>
            </ul>
        </div>
        <div class="column">
            <h3>Τοποθεσία</h3>
            <p>Πλατεία Γεωργίου, Πάτρα</p>
            <div id="map"></div>
        </div>
    </div>
</footer>

<script>
    function goBack() {
        window.location.href = "orders.php";
    }
</script>

</body>
</html>
