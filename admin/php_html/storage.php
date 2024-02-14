<?php

session_start();
include("../../connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productName'];
    $category = $_POST['category'];
    $num = $_POST['num'];

    if (empty($productName) || empty($category) || empty($num)) {
        echo "Συμπλήρωσε όλα τα πεδία.";
    } else {

        $sql = "INSERT INTO base (category, product, num) VALUES ('$category', '$productName', $num)";
        $result = $conn->query($sql);

        if ($result) {
            echo "Προστέθηκαν τα προιόντα.";
        } else {
            echo "Error adding product: " . $conn->error;
        }
        $conn->close();
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  
  <link rel="stylesheet" type="text/css" href="..\css\umf.css">
  <link rel="stylesheet" type="text/css" href="..\css\storage.css">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_nXA2oQ_YYbhvUp2MComLx7GwZLWVAxw&callback=initMap"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="..\js\umf.js" ></script>
  <!--  <script src="..\js\requests.js" ></script>-->

</head>
<body>

  <!-- Menu Toggle Button -->
  <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>


<header>
    <h1>Διαχείρηση Αποθήκης</h1>
</header>


<div id="mySidenav">
    <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
    <a href="admin.php" onclick="toggleMenu()">Αρχική</a>
    <a href="announcementscreate.php" onclick="toggleMenu()">Δημιουργία Ανακοινώσεων</a>
    <a href="fetchVehiclebyRescuers.php" onclick="toggleMenu()">Δημιουργία Λογιαριασμών</a>
</div>


<div id="user-container">
<button id="imageButton" onclick="toggleUserMenu()">
  <img src="../../img/alesis.jpg" alt="Button Image">
  <div id="userMenu" class="dropdown-content">
      <a href="adminorders.php">Λίστα Αιτημάτων/Προσφορών</a>
      <a href="../../initialpage.php">Αποσύνδεση</a>
  </div>
</button>
</div>

<div id="menu-container">
    <div class="autocomplete-box">
        <div class="autocomplete-container" style="text-align: center;">
            <div style="display: flex; align-items: center; justify-content: center;">
                <input type="text" id="itemInput" placeholder="Αναζήτηση...">
                <button id="searchButton" >Αναζήτηση</button>
            </div>
        </div>
        <div class="tab-container">
            <div class="tab" onclick="switchTab('menu1')">Όλα τα προϊόντα</div>
            <div class="tab" onclick="switchTab('menu2')">Ανά κατηγορία</div>
            <div class="tab" onclick="switchTab('menu3')">Πρόσθεσε Προιόν</div> 
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
    <div id="menu3" class="content tab-content hidden">
    <form id="productForm" style="max-width: 200px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <label for="productName" style="display: block; margin-bottom: 8px;">Όνομα Προιόντος:</label>
        <input type="text" id="productName" name="productName" required style="width: 100%; padding: 8px; margin-bottom: 16px; box-sizing: border-box;">

        <label for="category" style="display: block; margin-bottom: 8px;">Κατηγορία:</label>
        <input type="text" id="category" name="category" required style="width: 100%; padding: 8px; margin-bottom: 16px; box-sizing: border-box;">

        <label for="num" style="display: block; margin-bottom: 8px;">Ποσότητα:</label>
        <input type="number" id="num" name="num" required style="width: 100%; padding: 8px; margin-bottom: 16px; box-sizing: border-box;">

        <button type="button" onclick="submitProductForm()" style="background-color: rgb(12, 45, 109); color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer;">Υπέβαλλε</button>
    </form>
</div>

</div>


</body>
<footer>
    <p>&copy; 2024 Volunteer-Platfmorm. All rights reserved.</p>
</footer>
<script>


  var checkedProducts = [];
  var checkedProductStates = {};
  var checkedProductsInfo = {};
  var selectedCategory;
  var lastActiveTabId = null;

  window.addEventListener('load', function () {
    console.log('Window loaded. Initializing with default data.');
    loadProducts();
    lastActiveTabId = 'menu1';
});


function switchTab(tabName) {
  var tabs = document.getElementsByClassName("tab-content");
  for (var i = 0; i < tabs.length; i++) {
    tabs[i].style.display = "none";
  }
  document.getElementById(tabName).style.display = "block";
  lastActiveTabId = tabName;

  if (tabName === "menu1") {
    console.log('Switching to All Products tab...');
    loadProducts();
  } else if (tabName === "menu2") {
    console.log('Switching to Categories tab...');
    loadCategories();
  }else if (tabName === "menu3"){
    console.log('Product Form tab...'); 
    document.getElementById(tabName).style.display = "block";
  }
}
function createPreviousButton(container) {
  var customButton = document.createElement("button");
  customButton.id = "custom-button1";
  customButton.textContent = "Πίσω";
  customButton.classList.add("custom-button"); 
  customButton.addEventListener("click", function () {
        loadProducts(null);
    });
  container.appendChild(customButton);
}


function loadProducts(searchTerm) {
    var productsContainer = document.getElementById("menu1");
    productsContainer.innerHTML = '';

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Products loaded successfully.');
            loadData(xhr.responseText, productsContainer);
        }
    };

    var url = searchTerm ? "getproductsbycategorybase.php?category=" + encodeURIComponent(searchTerm) + "&productSearch=1" : "allproductsbase.php";

    xhr.open("GET", url, true);
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
  console.log(  " EIMAI MESA STHN LOAD DATA ")
  table.classList.add('product-table');
  table.innerHTML = '<thead><tr><th>Προϊόν</th><th>Ποσότητα</th></tr></thead><tbody id="product-table-body">' + data + '</tbody>';
  tableContainer.appendChild(table);

  container.appendChild(tableContainer);

}

function loadDataSearch(data, container, type) {
    console.log('Loading data:', data);

    container.innerHTML = '';
    var tableContainer = document.createElement('div');

    if (type === 1 || type === 2) {
        tableContainer.classList.add('table-container');
        tableContainer.style.overflow = 'auto';
        tableContainer.style.maxHeight = '300px';
        tableContainer.style.margin = '0 auto';
        tableContainer.style.width = '70%';

        var table = document.createElement('table');
        tableContainer.appendChild(table);

        var thead = document.createElement('thead');
        table.appendChild(thead);

        if (type === 1) {
            thead.innerHTML = '<tr><th>Προϊόν</th><th>Ποσότητα</th></tr>';
            console.log("KATW");
        } else if (type === 2) {
            thead.innerHTML = '<tr><th>Κατηγορία</th></tr>';
        }

        for (var i = 0; i < data.length; i++) {
            var row = table.insertRow(i + 1); 
            var cell = row.insertCell(0);
            if (type === 1) {
                cell.innerHTML = data[i].product;
                console.log(cell.innerHTML);
                var cell2 = row.insertCell(1);
                cell2.innerHTML = data[i].num;
                var existingCustomButton1 = document.getElementById("custom-button1");

             if (!existingCustomButton1) {
            console.log('Creating next button...');
            createPreviousButton(container);
             }
            } else if (type === 2) {
                cell.innerHTML = data[i].category;
                loadCategories(cell.innerHTML);
            }
        }

        container.appendChild(tableContainer);
    }
}

function loadCategories(searchTerm = null) {
    console.log('Loading categories with search term:', searchTerm);
    var categoriesContainer = document.getElementById("menu2");
    categoriesContainer.innerHTML = '';

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
          //  console.log('Response from server:', xhr.responseText);
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
         //   console.log(categoriesContainer);
            console.log("EIMAI MESA STHN LOAD CATEGORIES");
           // attachCategoryClickListener(categoriesContainer);
        }
    };

    var url = searchTerm ? "loadcategories.php?search=" + encodeURIComponent(searchTerm) : "loadcategories.php";
    xhr.open("GET", url, true);
    xhr.send();
}

function attachCategoryClickListener(container) {
    console.log(container);
    container.addEventListener("click", function (event) {
        console.log('Attaching category click listener...');
        console.log("Container clicked");
        var target = event.target;
        // console.log("Clicked element:", target);
        // console.log("Element content:", target.textContent.trim());
        if (target.textContent.trim() !== "") {
            // console.log(" AS DOUME");
            var category = target.textContent.trim();
            console.log('Clicked category:', category);
            var activeTab = document.querySelector('.tab-content:not(.hidden)');

            if (activeTab.id === 'menu1') {
                if (category === 'Πίσω') {
                  //  console.log('Επόμενο clicked. Performing next action...');
                    loadProducts(); }
               else if(category === 'Επόμενο'){  loadProducts();}
               else{  loadProducts(category);}

            } else if (activeTab.id === 'menu2') {
                if (category === 'Πίσω') {
                  //  console.log('Επόμενο clicked. Performing next action...');
                    loadProducts(); }
                else if(category === 'Επόμενο'){  loadProducts();}
                else {handleCategoryClick(category);}
            }
        }
    });
}

function handleCategoryClick(category) {
    console.log('Category clicked:', category);
    var productContainer = document.getElementById("menu2");
    productContainer.innerHTML = '';
    console.log('Loading products for category:', category);
    selectedCategory = category;

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log('Products for category loaded successfully.');
            console.log('Raw JSON Data:', xhr.responseText);
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
            backButton.addEventListener("click", function () {
                // Go back to menu2 when the back button is clicked
                switchTab('menu2');
            });
            productContainer.insertBefore(backButton, productContainer.firstChild);
        }
    };
    xhr.open("GET", "getproductsbycategorybase.php?category=" + encodeURIComponent(selectedCategory) + "&view=normal", true);
    //xhr.open("GET", "getproductsbycategorybase.php?category=" + encodeURIComponent(selectedCategory), true);
    xhr.send();
}


function submitProductForm() {
    var productName = document.getElementById('productName').value;
    var category = document.getElementById('category').value;
    var num = document.getElementById('num').value;

    var formData = new FormData();
    formData.append('productName', productName);
    formData.append('category', category);
    formData.append('num', num);


    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                alert('Προστέθηκαν επιτυχώς.');
            } else {
                console.error('Error adding product:', xhr.statusText);
            }
        }
    };

    xhr.open('POST', 'storage.php', true);
    xhr.send(formData);
}


function loadProductBySearch(searchTerm) {
    var productsContainer = document.getElementById("menu1");
    console.log('Loading products with search term:', searchTerm);
    productsContainer.innerHTML = '';
 /*   if (searchTerm.trim() === "") {
        alert('Συμπλήρωσε το πεδίο αναζήτησης');
        loadProducts();
    }*/
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                console.log('Raw JSON Data:', xhr.responseText);
                console.log('Search type:', response.type);
                console.log(response.results);
                console.log(searchTerm);

                if (response.type === 1 || response.type === 2) {
                    console.log('Products/Categories with search term loaded successfully.');
                    console.log('Data:', response.results);
                    loadDataSearch(response.results, productsContainer, response.type);
                } else if (response.type === 0) {
                    console.log(searchTerm);
                    console.log('No products found with search term:', searchTerm);
                    alert('Συμπλήρωσε το πεδίο αναζήτησης ή το προϊόν δεν υπάρχει.');
                    loadProducts();
                }
            } else {
                console.error('Error loading products:', xhr.statusText);
            }
        }
    };

    // Modify the URL to search for both categories and products
    var url = "searchProductAdmin.php?search=" + encodeURIComponent(searchTerm) ;

    xhr.open("GET", url, true);
    xhr.send();
}
document.addEventListener("DOMContentLoaded", function () {
    console.log('Document loaded. Initializing with default data.');
    loadProducts(); // Load default categories in menu1
    setTimeout(function () {
        attachCategoryClickListener(document.getElementById("menu1"));
    }, 1000);
});


document.getElementById("searchButton").addEventListener("click", function () {
    var productName = document.getElementById('itemInput').value;
    
    // Use the last active tab ID
    if (lastActiveTabId === 'menu1') {
        console.log("Calling loadProductBySearch from menu1");
        loadProductBySearch(productName);
    } else if (lastActiveTabId === 'menu2') {
        console.log("Calling  from menu2");
       // loadCategories(productName);
       switchTab('menu1');
       loadProductBySearch(productName);
        
    } else {
        alert('Συμπλήρωσε το πεδίο αναζήτησης');
    }
});

</script>
</html>