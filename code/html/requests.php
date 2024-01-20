<?php
session_start();
include("connection.php");

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
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_nXA2oQ_YYbhvUp2MComLx7GwZLWVAxw&callback=initMap"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="..\js\umf.js" ></script>
  <!--  <script src="..\js\requests.js" ></script>-->
</head>
<body>

  <!-- Menu Toggle Button -->
  <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

  <header>
    <h1> Υποβολή Αιτημάτων</h1>
  </header>

  <!-- Side Navigation Menu -->
  <div id="mySidenav">
    <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
    <a href="mainpagecitizen.php" onclick="toggleMenu()">Αρχική</a>
    <a href="announcementscitizen.php" onclick="toggleMenu()">Ανακοινώσεις</a>
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

  <div id="menu-container">
    <div class="autocomplete-box">
      <div class="autocomplete-container" style="text-align: center;">
        <div style="display: flex; align-items: center; justify-content: center;">
          <input type="text" id="itemInput" placeholder="Αναζήτηση...">
           <button id="searchButton" onclick="showTab('search')">Search</button>
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
      <!-- The product rows will be dynamically added here -->
    </tbody>
  </table>
  </div>
  <div id="menu2" class="content tab-content hidden">
    <div id="category-container">
        <table class="category-table">
            <tbody id="category-table-body">
                <!-- Categories will be dynamically added here -->
            </tbody>
        </table>
        </div>
    <div id="products-container">
        <table class="products-table">
            <tbody id="products-table-body">
                <!-- Products will be dynamically added here -->
            </tbody>
        </table>
      </div>
</div>
  </div>

  <!--<button id="mybutton" onclick="myOrder()">Επόμενο</button>-->

<!-- <div id="order-summary-container" class="content tab-content hidden order-summary">
    <h2>Your Order Summary</h2>
    <table id="order-summary-table">
      <tbody id="order-summary-list" class="product-list"></tbody>
    </table>
    <button onclick="loadProducts() " style="background-color:  rgb(12, 45, 109); /* Green */ color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer;">Επιστροφή στο Μενού</button>
    <button onclick="myOrder()" style="background-color:  rgb(12, 45, 109); /* Blue */ color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer;">Υποβολή Παραγγελίας</button>
</div>
  <div id="user-info-container" class="content tab-content hidden user-info">
    <h2>Your User Information</h2>
    <table id="user-info-table">
      <tbody id="user-info-list"></tbody>
    </table>
</div>-->

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

 <!-- <div id="user-info-container" class="content tab-content hidden user-info">
    <h2>Your User Information</h2>
    <table id="user-info-table">
      <tbody id="user-info-list"></tbody>
    </table>
  </div>
 <div id="combined-container">

</div>
-->



  <footer>
    <div class="footer-section">
      <div></div>
      <p>Με την βοήθειά σου ,στηρίζεις τον καθημερινό διαμερισμό προιόντων σε ανθρώπους που το έχουν ανάγκη.Δώρισε σήμερα!</p>
      <a href="requests.html" class="button">
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
          <li><a href="mainpagecitizen.html">Η Ομάδα Μας</a></li>
          <li><a href="requests.html">Υπηρεσίες</a></li>
          <li><a href="contact.html">Επικοινωνία</a></li>
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

  var checkedProducts = [];
  var checkedProductStates = {};
  var checkedProductsInfo = {};
  var selectedCategory;

function switchTab(tabName) {
  var tabs = document.getElementsByClassName("tab-content");
  for (var i = 0; i < tabs.length; i++) {
    tabs[i].style.display = "none";
  }
  document.getElementById(tabName).style.display = "block";

  if (tabName === "menu1") {
    console.log('Switching to All Products tab...');
    loadProducts();
  } else if (tabName === "menu2") {
    console.log('Switching to Categories tab...');
    loadCategories();
  }
}
/*
function attachCheckboxEventListeners() {
    var checkboxes = document.querySelectorAll('.product-checkbox');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            updateCheckedState();
            printCheckedProductsInfo();

            var productName = this.value;

            if (this.checked) {
                checkedProducts.push(productName);
            } else {
                var index = checkedProducts.indexOf(productName);
                if (index !== -1) {
                    checkedProducts.splice(index, 1);
                }
            }

            checkedProductStates[productName] = this.checked;

            console.log('Product Name:', productName);
            console.log('Checked Products:', checkedProducts);
            console.log('Checked Product States:', checkedProductStates);
        });

        checkbox.checked = checkedProductStates[checkbox.value] || false;
    });
}*/
function attachCheckboxEventListeners() {
  var checkboxes = document.querySelectorAll('.product-checkbox');

  checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
      updateCheckedState();
      printCheckedProductsInfo();

      var productName = this.value;
      var productId = this.getAttribute('data-product-id'); // Get the product_id

      if (this.checked) {
        checkedProducts.push(productName);
      } else {
        var index = checkedProducts.indexOf(productName);
        if (index !== -1) {
          checkedProducts.splice(index, 1);
        }
      }

      checkedProductStates[productName] = this.checked;

      // Store product_id along with other information
      checkedProductsInfo[productId] = {
        productName: productName,
        quantity: 1, // Default quantity, you may adjust it based on your needs
        isChecked: this.checked
      };

      console.log('Product Name:', productName);
      console.log('Product ID:', productId);
      console.log('Checked Products:', checkedProducts);
      console.log('Checked Product States:', checkedProductStates);
      console.log('Checked Products Info:', checkedProductsInfo);
    });

    checkbox.checked = checkedProductStates[checkbox.value] || false;
  });
}

function attachQuantityChangeEventListeners() {
    var quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(function (input) {
      input.addEventListener('input', function () {
        updateQuantity(input.value, input.getAttribute('data-product-id'));
        printCheckedProductsInfo();
      });
    });
  }

function updateQuantity(quantity, productId) {
    console.log('Running updateQuantity for ' + productId + ', New Quantity: ' + quantity);
    checkedProductsInfo[productId] = {
      productName: checkedProductsInfo[productId].productName,
      quantity: parseInt(quantity, 10) || 1,
      isChecked: checkedProductsInfo[productId] ? checkedProductsInfo[productId].isChecked : false
    };
  }


function printCheckedProductsInfo() {
    console.log('Checked Products Info:', checkedProductsInfo);
}

function loadProducts() {
    var productsContainer = document.getElementById("menu1");
    console.log('Loading products...');
    console.log('loadProducts function called');

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        console.log('Products loaded successfully.');
        loadData(xhr.responseText, productsContainer);
      }
    };
    xhr.open("GET", "allproducts.php", true);
    xhr.send();
}

function loadData(data, container) {
  console.log('Loading data:', data);

  container.innerHTML = '';

  var tableContainer = document.createElement('div');
  tableContainer.classList.add('table-container');
  tableContainer.style.overflow = 'auto';
  tableContainer.style.maxHeight = '300px';
  tableContainer.style.margin = '0 auto';
  tableContainer.style.width = '70%';
  tableContainer.style.top = '30%';

  // Create and append the table
  var table = document.createElement('table');
  table.classList.add('product-table');
  table.innerHTML = '<thead><tr><th>Προϊόν</th><th>Άτομα</th><th>Επιλογή</th></tr></thead><tbody id="product-table-body">' + data + '</tbody>';
  tableContainer.appendChild(table);

  container.appendChild(tableContainer);

  attachCheckboxEventListeners();
  attachQuantityChangeEventListeners();
  restoreCheckedState();

  var existingCustomButton = document.getElementById("custom-button");
  if (!existingCustomButton) {
    createNextButton(container);
  }

  printCheckedProductsInfo();
}

function createNextButton(container) {
  var customButton = document.createElement("button");
  customButton.id = "custom-button";
  customButton.textContent = "Επόμενο";
  customButton.style.backgroundColor = "rgb(12, 45, 109)";
  customButton.style.color = "white";
  customButton.style.padding = "10px";
  customButton.style.border = "none";
  customButton.style.borderRadius = "5px";
  customButton.style.cursor = "pointer";
  customButton.addEventListener("click", myOrder);

  container.appendChild(customButton);
}


function loadCategories() {
  var categoriesContainer = document.getElementById("menu2");
  categoriesContainer.innerHTML = '';

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var tableContainer = document.createElement('div');
      tableContainer.style.overflow = 'auto';
      tableContainer.style.maxHeight = '300px';
      tableContainer.style.margin = '0 auto';
      tableContainer.style.width = '70%';
      var table = document.createElement('table');

      var thead = document.createElement('thead');
      thead.innerHTML = '<tr><th>Κατηγορία</th></tr>';
      table.appendChild(thead);

      var tbody = document.createElement('tbody');
      tbody.id = 'category-table-body';
      tbody.innerHTML = xhr.responseText;
      table.appendChild(tbody);

      tableContainer.appendChild(table);
      categoriesContainer.appendChild(tableContainer);

      var categoryCells = document.getElementsByClassName("category-cell");
      for (var i = 0; i < categoryCells.length; i++) {
        categoryCells[i].addEventListener("click", function () {
          var category = this.getAttribute("data-category");
          handleCategoryClick(category);
        });
      }

      var existingCustomButton = document.getElementById("custom-button");
      if (existingCustomButton) {
        existingCustomButton.remove();
      }
    }
  };

  xhr.open("GET", "loadcategories.php", true);
  xhr.send();
}

function handleCategoryClick(category) {
    var productContainer = document.getElementById("menu2");
    console.log('Loading products for category:', category);
    selectedCategory = category;
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
        console.log('Products for category loaded successfully.');
        loadData(xhr.responseText, productContainer);
        
        var backButton = document.createElement("button");
            backButton.id = "custom1-button";
            backButton.textContent = "Πίσω στις Κατηγορίες";
            backButton.style.backgroundColor = "rgb(12, 45, 109)";
            backButton.style.color = "white";
            backButton.style.padding = "10px";
            backButton.style.border = "none";
            backButton.style.borderRadius = "5px";
            backButton.style.cursor = "pointer";
            backButton.addEventListener("click", loadCategories);
            productContainer.insertBefore(backButton, productContainer.firstChild);
      }

    };

    xhr.open("GET", "getproductsbycategory.php?category=" + encodeURIComponent(selectedCategory), true);
    xhr.send();
  }

function restoreCheckedState() {
    var checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(function (checkbox) {
      checkbox.checked = checkedProductStates[checkbox.value] || false;

      if (checkedProductsInfo[checkbox.value]) {
        var quantityInput = document.getElementById('quantityInput_' + checkbox.value);
        quantityInput.value = checkedProductsInfo[checkbox.value].quantity || 1;
      }
    });
}
/*
function updateCheckedState() {
    var checkboxes = document.querySelectorAll('.product-checkbox');
    checkedProductsInfo = {};  

    checkboxes.forEach(function (checkbox) {
      if (checkbox.checked) {
       
        var quantityInput = document.getElementById('quantityInput_' + checkbox.value);
        checkedProductsInfo[checkbox.value] = {
          quantity: parseInt(quantityInput.value, 10) || 1,
          isChecked: checkbox.checked
        };
      }
    });
  }*/

  function updateCheckedState() {
  var checkboxes = document.querySelectorAll('.product-checkbox');
  checkedProductsInfo = {};

  checkboxes.forEach(function (checkbox) {
    if (checkbox.checked) {
      var productId = checkbox.getAttribute('data-product-id');
      var quantityInput = document.getElementById('quantityInput_' + checkbox.value);
      checkedProductsInfo[productId] = {
        productName: checkbox.value,
        quantity: parseInt(quantityInput.value, 10) || 1,
        isChecked: checkbox.checked
      };
    }
  });

}


document.addEventListener("DOMContentLoaded", function () {
    console.log('Document loaded. Initializing with default data.');
    loadProducts();
  });


function myOrder() {
  console.log('myOrder function called');
    if (checkedProducts.length === 0) {
        alert("Please select at least one product before submitting the order.");
        return;
    }
    var userInfo = <?php echo json_encode($userInfo); ?>;
    console.log("User Surname: " + userInfo.lastname);


   
// creation of tables one for user's information and one for my order
    var orderSummaryTable = createOrderSummaryTable();
  

    var userInfoTable = createUserInfoTable(userInfo);


    var orderSummaryContainer = document.getElementById("order-summary-container");
    orderSummaryContainer.innerHTML = "";
    orderSummaryContainer.appendChild(orderSummaryTable);
    

    var userInfoContainer = document.getElementById("user-info-container");
    userInfoContainer.innerHTML = "";
    userInfoContainer.appendChild(userInfoTable);

    createButtons();
    
 
    document.getElementById("menu-container").style.display = "none";
    orderSummaryContainer.style.display = "inline-block";
    userInfoContainer.style.display = "inline-block";
   
    
}
/*
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
    orderSummaryHeader.colSpan = 2; // Set the colspan to span two columns
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

    // Create rows for each selected product
    for (var i = 0; i < checkedProducts.length; i++) {
        var productName = checkedProducts[i];
        var productInfo = checkedProductsInfo[productName];

        var row = document.createElement("tr");
        var productNameCell = document.createElement("td");
        var quantityCell = document.createElement("td");

        productNameCell.textContent = productName;
        quantityCell.textContent = productInfo.quantity;

        row.appendChild(productNameCell);
        row.appendChild(quantityCell);

        orderSummaryTable.appendChild(row);

        console.log('Product Name:', productName);
        console.log('Product Quantity:', productInfo.quantity);
    }
    orderSummaryContainer.appendChild(orderSummaryTable);
    return orderSummaryTable;
}*/
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
  orderSummaryHeader.colSpan = 2; // Set the colspan to span two columns
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

  // Create rows for each selected product
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

    console.log('Product ID:', productId);
    console.log('Product Quantity:', productInfo.quantity);
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
    userInfoHeader.colSpan = 4; // Set the colspan to span four columns
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

/*
function createButtons() {
    // Create a div element to hold buttons
    var buttonContainer = document.createElement("div");
    buttonContainer.id = "button-container";

    // Create the first button
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
    console.log("Button clicked");
    loadProducts();
});


    // Create the second button
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

    // Append buttons to the container
    buttonContainer.appendChild(returnToMenuButton);
    buttonContainer.appendChild(submitOrderButton);

    // Append the container to the user-info-container
    var orderContainer = document.getElementById("order-summary-container");
    orderContainer.insertBefore(buttonContainer, orderContainer.firstChild);

    var userInfoContainer = document.getElementById("user-info-container");
    userInfoContainer.insertBefore(buttonContainer, userInfoContainer.firstChild);
}
*/


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
        loadProducts();
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
                console.log('Order submitted successfully.');
                alert("Η παραγγελία σας υποβλήθηκε επιτυχώς!");
                window.location.href = "mainpagecitizen.php";
            } else {
                console.error('Error submitting order:', xhr.status, xhr.statusText);
                alert("Σφάλμα κατά την υποβολή της παραγγελίας. Παρακαλούμε δοκιμάστε ξανά.");
            }
        }
    };

    xhr.open("POST", "submitorder.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Prepare an array to store the product data
    var productsData = [];

    // Iterate through checkedProductsInfo
    var userId = <?php echo json_encode($userId); ?>;
    for (var productId in checkedProductsInfo) {
        var productInfo = checkedProductsInfo[productId];
        var requestData = {
            re_c_id: userId, 
            re_number: productInfo.quantity,
            re_pr_id: productId
        };
        productsData.push(requestData);
    }

    // Convert the array to a JSON string and send it as a parameter
    xhr.send("products=" + encodeURIComponent(JSON.stringify(productsData)));
}


</script>
</html>