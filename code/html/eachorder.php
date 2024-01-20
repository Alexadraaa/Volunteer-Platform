<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.html");
    exit();
}

$order_id = $_GET['id'];

// Fetch products for the specific order
$queryProducts = "SELECT b.product, r.re_number
                  FROM requests r
                  JOIN base b ON r.re_pr_id = b.product_id
                  WHERE r.re_or_id = $order_id";

$resultProducts = mysqli_query($conn, $queryProducts);

if (!$resultProducts) {
    die("Error retrieving order products: " . mysqli_error($conn));
}

// fetched products will be stored in the $products array
$products = [];
while ($rowProduct = mysqli_fetch_assoc($resultProducts)) {
    $products[] = $rowProduct;
}

mysqli_free_result($resultProducts);
mysqli_close($conn);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_nXA2oQ_YYbhvUp2MComLx7GwZLWVAxw&callback=initMap"></script>
    <link rel="stylesheet" type="text/css" href="..\css\umf.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-3MXwVuYi4I3nbLckmfrSrQ86AOk+2Fc2sc9p8h7Q8Q4jpn3TIWWV6A/5aqL8z5SIN6UBVVVGO1hU1c3V3P36RQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="..\js\umf.js" ></script>
    <script src="..\js\eachorder.js" ></script>
    <title>User Orders</title>
    <style>
        #main-content {
            text-align: center;
        }
        
        #productTable {
            width: 50%;
            margin: 0 auto; 
        }
        
        #productTable th, #productTable td {
            padding: 8px; /
        }
  
        #productTablee th {
            background-color: #f2f2f2;
        }
        
        #productTable tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        #productTable tbody tr:hover {
            background-color: #e0e0e0;
        }
                </style>
</head>
<body>

  <!-- Menu Toggle Button -->
  <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>
    
  <header>
      <h1> Λεπτομέρειες Παραγγελίας</h1>
  </header>

    <!-- Side Navigation Menu -->
    <div id="mySidenav">
        <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
        <a href="mainpagecitizen.php" onclick="toggleMenu()">Αρχική</a>
        <a href="announcementscitizen.php" onclick="toggleMenu()">Ανακοινώσεις</a>
        <a href="requests.php" onclick="toggleMenu()">Υπηρεσίες</a>
        <a href="contact.php" onclick="toggleMenu()">Επικοινωνία</a>
    </div>

    <div id="user-container">
        <button id="imageButton" onclick="toggleUserMenu()">
            <img src="ssmvtnogc7ue0jufjd03h6mj89.png" alt="Button Image">
            <div id="userMenu" class="dropdown-content">
                <a href="orders.php">Λίστα Αιτημάτων/Προσφορών</a>
                <a href="profilsection.php">Προφιλ</a>
                <a href="initialpage.php">Αποσύνδεση</a>
            </div>
        </button>
    </div>
    <table id="productTable">
        <thead>
            <tr>
                <th>Προιόν</th>
                <th>Ποσότητα</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($products as $product) {
                echo "<tr>";
                echo "<td>{$product['product']}</td>";
                echo "<td>{$product['re_number']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <footer>
        <div class="footer-section">
          <div></div>  
          <p>Με την βοήθειά σου ,στηρίζεις τον καθημερινό διαμερισμό προιόντων σε ανθρώπους που το έχουν ανάγκη.Δώρισε σήμερα!</p>
          <a href="requests.php" class="button">
            <img src="donate.png" alt="Donate Now">
        </a>
        </div>
            <hr class="divider">
            <div class="section2">
                    <div class="column">
                        <h3>Επικοινωνία</h3>
                        <ul>
                          <li>Τηλέφωνο(χωρίς χρέωση):+306946930521</li>
                          <li>Κινητό:+306946930521</li>
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
                        <p>Πλατεία Γεωργίου,Πάτρα</p>
                       <div id="map"></div>  
                 </div>
                </div>
    </footer>
</body>
</html>
