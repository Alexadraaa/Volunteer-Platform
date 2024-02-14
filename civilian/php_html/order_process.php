<?php
// page for the user to submit an order for the selected products, view their user information and order summary
session_start();
include("../../connection.php");

if (isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id'];
} else {
 // echo "User not logged in.";
}

$checkedProductsInfo = isset($_GET['products']) ? json_decode($_GET['products'], true) : array();

// get user information
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

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    $getOrderDetailsQuery = "SELECT b.product , r.re_number
                             FROM orders o
                             JOIN requests r ON o.or_id = r.re_or_id
                             JOIN base b ON r.re_pr_id = b.product_id
                             WHERE o.or_id = ?";
    $getOrderDetailsStmt = $conn->prepare($getOrderDetailsQuery);
    $getOrderDetailsStmt->bind_param("i", $orderId);
    $getOrderDetailsStmt->execute();
    $orderDetailsResult = $getOrderDetailsStmt->get_result();

    $orderProducts = array();

    while ($row = $orderDetailsResult->fetch_assoc()) {
        $orderProducts[] = array(
            'productName' => $row['product'],
            'quantity' => $row['re_number']
        );
    }

} else {
    $orderProducts = array();
    foreach ($checkedProductsInfo as $productId => $productInfo) {
        $orderProducts[] = array(
            'productName' => $productInfo['productName'],
            'quantity' => $productInfo['quantity']
        );
    }
}

$userInfoJSON = json_encode($userInfo);
$orderProductsJSON = json_encode($orderProducts);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  
  <link rel="stylesheet" type="text/css" href="..\css\umf.css">
  <link rel="stylesheet" type="text/css" href="..\css\order_process.css">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_nXA2oQ_YYbhvUp2MComLx7GwZLWVAxw&callback=initMap"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="..\js\umf.js" ></script>
  <!--  <script src="..\js\requests.js" ></script>-->
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

.tables-container {
    display: flex;
    justify-content: space-around; /* or other flex properties as needed */
    align-items: center; /* align items vertically in the center */
    margin-top: 20px;
  
}

#button-container {
    display: flex;
    flex-direction: row;
    text-align: center; 
    margin-right: 30px;
}
    </style>
</head>
<body>

  <!-- menu toggle button -->
  <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

  <header>
    <h1> Υποβολή Αιτημάτων</h1>
  </header>

  <!-- side navigation nenu -->
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
        <a href="profilsection.php">Προφιλ</a>
        <a href="../../initialpage.php">Αποσύνδεση</a>
      </div>
    </button>
  </div>
  
  <!-- two tables with appropriate data -->
  <div id="order-summary-container" class="content tab-content hidden order-summary">
    <h2>Σύνοψη Παραγγελίας</h2>
    <table id="order-summary-table">
      <tbody id="order-summary-list" class="product-list"></tbody>
    </table>
  </div>
  <div id="user-info-container" class="content tab-content hidden user-info">
    <h2>Πληροφορίες Χρήστη</h2>
    <div id="combined-container">
        <!-- buttons will be appended here -->
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
<script>


var checkedProductsInfo = <?php echo json_encode($checkedProductsInfo); ?>;
console.log('Checked Products Info:', checkedProductsInfo);
var userInfo = <?php echo json_encode($userInfo); ?>;
var orderId = <?php echo isset($orderId) ? $orderId : 'null'; ?>;
var orderProductsData = <?php echo $orderProductsJSON; ?>;
//console.log("User Surname: " + userInfo.lastname);


function myOrder() {
console.log('myOrder function called');

var userInfo = <?php echo json_encode($userInfo); ?>;
console.log("User Surname: " + userInfo.lastname);

var orderSummaryTable = createOrderSummaryTable();
var userInfoTable = createUserInfoTable(userInfo);

// create a common container for both tables
var tablesContainer = document.createElement("div");
tablesContainer.className = "tables-container";

// append the tables to the common container
tablesContainer.appendChild(orderSummaryTable);
tablesContainer.appendChild(userInfoTable);

// get containers by ID
var orderSummaryContainer = document.getElementById("order-summary-container");
var userInfoContainer = document.getElementById("user-info-container");

orderSummaryContainer.innerHTML = "";
userInfoContainer.innerHTML = "";

// append the common container to respective containers
orderSummaryContainer.appendChild(tablesContainer);
userInfoContainer.appendChild(tablesContainer);

// create buttons
createButtons();

// hide the menu container and display order summary and user info containers
document.getElementById("menu-container").style.display = "none";
orderSummaryContainer.style.display = "inline-block";
userInfoContainer.style.display = "inline-block";
}

function createOrderSummaryTable() {
  var orderSummaryContainer = document.createElement("div");
  orderSummaryContainer.className = "order-summary-container";
  orderSummaryContainer.style.maxHeight = "300px";
  orderSummaryContainer.style.overflowY = "auto";

  var orderSummaryTable = document.createElement("table");
  orderSummaryTable.className = "order-summary-table";

  // Create header row
  var headerRow = document.createElement("tr");
  var orderSummaryHeader = document.createElement("th");
  orderSummaryHeader.colSpan = 2; 
  orderSummaryHeader.textContent = "Η παραγγελία μου";
  headerRow.appendChild(orderSummaryHeader);

  orderSummaryTable.appendChild(headerRow);

  var productHeaderRow = document.createElement("tr");
  var productNameHeader = document.createElement("th");
  var quantityHeader = document.createElement("th");

  productNameHeader.textContent = "Προϊόν";
  quantityHeader.textContent = "Ποσότητα";

  productHeaderRow.appendChild(productNameHeader);
  productHeaderRow.appendChild(quantityHeader);

  orderSummaryTable.appendChild(productHeaderRow);

  if (orderId !== null) {
    console.log('Order ID:', orderId);
    console.log('Order Products:', orderProductsData);
    orderProductsData.forEach(function (productInfo) {
      var row = document.createElement("tr");
      var productNameCell = document.createElement("td");
      var quantityCell = document.createElement("td");

      productNameCell.textContent = productInfo.productName;
      quantityCell.textContent = productInfo.quantity;
      console.log('Product Name:', productInfo.productName);
      console.log('Product Quantity:', productInfo.quantity);

      row.appendChild(productNameCell);
      row.appendChild(quantityCell);

      orderSummaryTable.appendChild(row);
    });
  } else {
   
    for (var productId in checkedProductsInfo) {
    var productInfo = checkedProductsInfo[productId];

    var row = document.createElement("tr");
    var productNameCell = document.createElement("td");
    var quantityCell = document.createElement("td");

    productNameCell.textContent = productInfo.productName;
    quantityCell.textContent = productInfo.quantity;

    row.appendChild(productNameCell);
    row.appendChild(quantityCell);

    orderSummaryTable.appendChild(row);

    console.log('Product ID:', productInfo.productId);
    console.log('Product Quantity:', productInfo.quantity);
}
  }

  orderSummaryContainer.appendChild(orderSummaryTable);
  return orderSummaryTable;
}




function createUserInfoTable(userInfo) {

    var userInfoTable = document.createElement("table");
    userInfoTable.className = "user-info-table";

    // Create header row for "Πληροφορίες Χρήστη"
    var headerRow = document.createElement("tr");
    var userInfoHeader = document.createElement("th");
    userInfoHeader.colSpan = 4; 
    userInfoHeader.textContent = "Πληροφορίες Χρήστη";
    headerRow.appendChild(userInfoHeader);

    userInfoTable.appendChild(headerRow);

    // Create header row for data
    var dataHeaderRow = document.createElement("tr");
    var nameHeader = document.createElement("th");
    var surnameHeader = document.createElement("th");
    var phoneHeader = document.createElement("th");
    var addressHeader = document.createElement("th");

    nameHeader.textContent = "Όνομα";
    surnameHeader.textContent = "Επίθετο";
    phoneHeader.textContent = "Τηλέφωνο";
    addressHeader.textContent = "Διεύθυνση";

    dataHeaderRow.appendChild(nameHeader);
    dataHeaderRow.appendChild(surnameHeader);
    dataHeaderRow.appendChild(phoneHeader);
    dataHeaderRow.appendChild(addressHeader);

    userInfoTable.appendChild(dataHeaderRow);

    // Create row for user data
    var dataRow = document.createElement("tr");
    var nameCell = document.createElement("td");
    var surnameCell = document.createElement("td");
    var phoneCell = document.createElement("td");
    var addressCell = document.createElement("td");

    nameCell.textContent = userInfo.name;
    surnameCell.textContent = userInfo.lastname;
    phoneCell.textContent = userInfo.phone;
    addressCell.textContent = userInfo.address;

    dataRow.appendChild(nameCell);
    dataRow.appendChild(surnameCell);
    dataRow.appendChild(phoneCell);
    dataRow.appendChild(addressCell);

    userInfoTable.appendChild(dataRow);

    return userInfoTable;
}

function createButtons() {
    // create a div element to hold buttons
    var buttonContainer = document.createElement("div");
    buttonContainer.id = "button-container";

    // create the first button
    var returnToMenuButton = document.createElement("button");
    returnToMenuButton.textContent = "Επιστροφή στο Μενού";
    returnToMenuButton.style.backgroundColor = "rgb(12, 45, 109)";
    returnToMenuButton.style.color = "white";
    returnToMenuButton.style.padding = "10px 20px";
    returnToMenuButton.style.textAlign = "center";
    returnToMenuButton.style.textDecoration = "none";
    returnToMenuButton.style.display = "inline-block";
    returnToMenuButton.style.fontSize = "16px";
    returnToMenuButton.style.margin = "4px 2px";
    returnToMenuButton.style.cursor = "pointer";
    returnToMenuButton.addEventListener("click", function (event) {
    console.log("Return to Menu button clicked");
    window.location.href = "requests.php"; 
});
    // create the second button
    var submitOrderButton = document.createElement("button");
    submitOrderButton.textContent = "Υποβολή Παραγγελίας";
    submitOrderButton.style.backgroundColor = "rgb(12, 45, 109)";
    submitOrderButton.style.color = "white";
    submitOrderButton.style.padding = "10px 20px";
    submitOrderButton.style.textAlign = "center";
    submitOrderButton.style.textDecoration = "none";
    submitOrderButton.style.display = "inline-block";
    submitOrderButton.style.fontSize = "16px";
    submitOrderButton.style.margin = "4px 2px";
    submitOrderButton.style.cursor = "pointer";
    submitOrderButton.addEventListener("click", submitOrder);

    // create the third button for navigating to "profilsection.php"
    var changeProfileButton = document.createElement("button");
    changeProfileButton.textContent = "Αλλαγή Στοιχείων";
    changeProfileButton.style.backgroundColor = "rgb(12, 45, 109)";
    changeProfileButton.style.color = "white";
    changeProfileButton.style.padding = "10px 20px";
    changeProfileButton.style.textAlign = "center";
    changeProfileButton.style.textDecoration = "none";
    changeProfileButton.style.display = "inline-block";
    changeProfileButton.style.fontSize = "16px";
    changeProfileButton.style.margin = "4px 2px";
    changeProfileButton.style.cursor = "pointer";
    changeProfileButton.addEventListener("click", function (event) {
        console.log("Change Profile button clicked");
        window.location.href = "profilsection.php";
    });


    buttonContainer.appendChild(returnToMenuButton);
    buttonContainer.appendChild(submitOrderButton);
    buttonContainer.appendChild(changeProfileButton);


    var orderContainer = document.getElementById("order-summary-container");
    orderContainer.appendChild(buttonContainer);

    var userInfoContainer = document.getElementById("user-info-container");
    userInfoContainer.appendChild(buttonContainer);
}

function submitOrder() {
  console.log('submitOrder function called');
var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function () {
    if (xhr.readyState == 4) {
        if (xhr.status == 200) {
          console.log(xhr.responseText);
            console.log('Order submitted successfully.');
            alert("Η παραγγελία σας υποβλήθηκε επιτυχώς!");
            window.location.href = "mainpagecitizen.php";
        } else {
            console.error('Error submitting order:', xhr.status, xhr.statusText);
            // Display an error message to the user
            alert("Σφάλμα κατά την υποβολή της παραγγελίας. Παρακαλούμε δοκιμάστε ξανά.");

            // If there is a response from the server, display it
            if (xhr.responseText) {
                console.error('Server response:', xhr.responseText);
                // Display the server response as an additional alert
                alert("Σφάλμα από τον διακομιστή: " + xhr.responseText);
            }
        }
    }
};

xhr.open("POST", "submitorder.php", true);
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

// Prepare an array to store the product data
var productsData = [];


var userId = <?php echo json_encode($userId); ?>;
for (var productId in checkedProductsInfo) {
    var productInfo = checkedProductsInfo[productId];
    var requestData = {
        re_c_id: userId, 
        re_number: productInfo.quantity,
        re_pr_id: productInfo.productId
    };
    productsData.push(requestData);
}
console.log('Products Data:', productsData);
// Convert the array to a JSON string and send it as a parameter
xhr.send("requests=" + encodeURIComponent(JSON.stringify(productsData)));
}

document.addEventListener("DOMContentLoaded", function () {
    myOrder();
  });


</script>