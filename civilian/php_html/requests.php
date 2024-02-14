<?php
// page for submitting requests for products
session_start();
include("../../connection.php");

$userId = $_SESSION['user_id'];

$getUserInfoQuery = "SELECT name, lastname, phone, address FROM users WHERE user_id = ?";
$getUserInfoStmt = $conn->prepare($getUserInfoQuery);
$getUserInfoStmt->bind_param("i", $userId);
$getUserInfoStmt->execute();
$userInfoResult = $getUserInfoStmt->get_result();

if ($userInfoResult->num_rows > 0) {
    $userInfo = $userInfoResult->fetch_assoc();
} else {
    $userInfo = array('name' => 'N/A', 'lastname' => 'N/A', 'phone' => 'N/A', 'address' => 'N/A');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  
  <link rel="stylesheet" type="text/css" href="..\css\umf.css">
  <link rel="stylesheet" type="text/css" href="..\css\requests.css">
    <title>Αιτήματα</title>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_nXA2oQ_YYbhvUp2MComLx7GwZLWVAxw&callback=initMap"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        var userInfo = <?php echo json_encode($userInfo); ?>;
        var userId = <?php echo json_encode($userId); ?>;
     </script> 
    <script src="..\js\umf.js" ></script>
    <script src="..\js\requests.js" ></script>
<style>
    #custom-button,
    #custom-button1 {
    background-color: rgb(12, 45, 109);
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: inline-block;
    margin-right: 10px; 
}
  </style>
</head>
<body>

  <!-- menu toggle button -->
  <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

  <header>
    <h1> Υποβολή Αιτημάτων</h1>
  </header>

  <!-- side navigation menu -->
  <div id="mySidenav">
    <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
    <a href="mainpagecitizen.php" onclick="toggleMenu()">Αρχική</a>
    <a href="announcementscitizen.php" onclick="toggleMenu()">Ανακοινώσεις</a>
    <a href="contact.php" onclick="toggleMenu()">Επικοινωνία</a>
  </div>

   <!-- user container -->
  <div id="user-container">
    <button id="imageButton" onclick="toggleUserMenu()">
      <img src="../../img/profil.png" alt="Button Image">
      <div id="userMenu" class="dropdown-content">
        <a href="orders.php">Λίστα Αιτημάτων/Προσφορών</a>
        <a href="profilsection.php">Προφίλ</a>
        <a href="../../initialpage.php">Αποσύνδεση</a>
      </div>
    </button>
  </div>

  <!-- menu container -->
  <div id="menu-container">
    <div class="autocomplete-box">
      <div class="autocomplete-container" style="text-align: center;">
        <div style="display: flex; align-items: center; justify-content: center;">
          <input type="text" id="itemInput" placeholder="Αναζήτηση...">
           <button id="searchButton">Αναζήτηση</button>
        </div>
     </div>
      <div class="tab-container">
        <div class="tab" onclick="switchTab('menu1')">Όλα τα προϊόντα</div>
        <div class="tab" onclick="switchTab('menu2')">Ανά κατηγορία</div>
      </div>
    </div>
<div id="menu1" class="content tab-content">
  <table class="product-table">
    <tbody id="product-table-body">
      <!-- the product rows will be dynamically added here -->
    </tbody>
  </table>
  </div>
  <div id="menu2" class="content tab-content hidden">
    <div id="category-container">
        <table class="category-table">
            <tbody id="category-table-body">
                <!-- categories will be dynamically added here -->
            </tbody>
        </table>
        </div>
    <div id="products-container">
        <table class="products-table">
            <tbody id="products-table-body">
                <!-- products will be dynamically added here -->
            </tbody>
        </table>
      </div>
</div>
  </div>

  <div id="order-summary-container" class="content tab-content hidden order-summary">
    <h2>Your Order Summary</h2>
    <table id="order-summary-table">
      <tbody id="order-summary-list" class="product-list"></tbody>
    </table>
  </div>
  <div id="user-info-container" class="content tab-content hidden user-info">
    <h2>Your User Information</h2>
    <div id="combined-container">
        <!-- Buttons will be appended here -->
    </div>
    <table id="user-info-table">
        <tbody id="user-info-list"></tbody>
    </table>
</div>


  <footer>
    <div class="footer-section">
      <div></div>
      <p>Με την βοήθειά σου ,στηρίζεις τον καθημερινό διαμερισμό προιόντων σε ανθρώπους που το έχουν ανάγκη.Δώρισε σήμερα!</p>
      <a href="announcementscitizen.php" class="button">
        <img src="../../img/donate.png" alt="Donate Now">
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