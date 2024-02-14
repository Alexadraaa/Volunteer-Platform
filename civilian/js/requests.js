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
function loadProducts(searchTerm=null) {
  console.log('Loading products with search term:', searchTerm);
  var productsContainer = document.getElementById("menu1");
  productsContainer.innerHTML = '';

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
          console.log('Products loaded successfully.');
          loadData(xhr.responseText, productsContainer);
      }
  };

  var url = searchTerm ? "getproductsbycategory.php?category=" + encodeURIComponent(searchTerm)  : "allproducts.php";

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
// console.log(  " EIMAI MESA STHN LOAD DATA ")
table.classList.add('product-table');
table.innerHTML = '<thead><tr><th>Προϊόν</th><th>Ποσότητα</th><th>Επιλογή</th></tr></thead><tbody id="product-table-body">' + data + '</tbody>';
tableContainer.appendChild(table);

container.appendChild(tableContainer);


attachCheckboxEventListeners();
attachQuantityChangeEventListeners();
restoreCheckedState();


var existingCustomButton = document.getElementById("custom-button");
if (!existingCustomButton) {
    console.log('Creating next button...');
    console.log()
    createNextButton(container);
    }
printCheckedProductsInfo();

}

function createNextButton(container) {
  var customButton = document.createElement("button");
  customButton.id = "custom-button";
  customButton.textContent = "Επόμενο";
  customButton.classList.add("custom-button");
  customButton.addEventListener("click", function (event) {
      event.preventDefault();

      var selectedProducts = document.querySelectorAll('.product-checkbox:checked');
      if (selectedProducts.length === 0) {
          alert('Επιλέξτε τουλάχιστον ένα προϊόν για να προχωρήσετε.');
      } else {
          var selectedProductsArray = Array.from(selectedProducts).map(function (checkbox) {
              var productId = checkbox.getAttribute('data-product-id');
              var productInfo = checkedProductsInfo[productId];
              return {
                  productId: String(productId), 
                  productName: productInfo.productName,
                  quantity: productInfo.quantity
              };
          });

          var selectedProductsJSON = encodeURIComponent(JSON.stringify(selectedProductsArray));

          window.location.href = 'order_process.php?products=' + selectedProductsJSON ;
      }
  });
  container.appendChild(customButton);
  console.log(selectedProductsJSON);
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
//  console.log('Loading products for category:', category);
  selectedCategory = category;

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
      if (xhr.readyState == 4 && xhr.status == 200) {
          console.log('Products for category loaded successfully.');
      //    console.log('Raw JSON Data:', xhr.responseText);
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
              switchTab('menu2');
          });
          productContainer.insertBefore(backButton, productContainer.firstChild);
          console.log("EIMAI MESA STHN HANDLE CATEGORY CLICK");
      //    var existingCustomButton = document.getElementById("custom-button");
      //     if (!existingCustomButton) {
          console.log('Creating next button...');
            createNextButton(productContainer);  }

    //  }
  };
  xhr.open("GET", "getproductsbycategorybase.php?category=" + encodeURIComponent(selectedCategory) + "&view=input", true);

 // xhr.open("GET", "getproductsbycategory.php?category=" + encodeURIComponent(selectedCategory), true);
  xhr.send();
}

function loadProductBySearch(searchTerm) {
var productsContainer = document.getElementById("menu1");
//  console.log(productsContainer);
// console.log('Loading products with search term:', searchTerm);
  productsContainer.innerHTML = '';

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
          if (xhr.status == 200) {
              console.log('Raw JSON Data:', xhr.responseText);
              var response = JSON.parse(xhr.responseText);
              console.log('Search type:', response.type);

              if (response.type === 1 || response.type === 2) {
                  console.log('Products/Categories with search term loaded successfully.');
                  console.log('Data:', response.results);
                  loadDataSearch(response.results, productsContainer, response.type);
              } else if (response.type === 0) {
                  alert('Συμπλήρωσε το πεδίο αναζήτησης ή το προϊόν δεν υπάρχει.');
                  loadProducts();
              }
          } else {
              console.error('Error loading products:', xhr.statusText);
          }
      }
  };
  var url = "searchProductAdmin.php?search=" + encodeURIComponent(searchTerm) + "&productSearch=1";
  xhr.open("GET", url, true);
  xhr.send();
}

function loadDataSearch(data, container, type) {
  console.log('Loading data search:', data);
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
          thead.innerHTML = '<thead><tr><th>Προϊόν</th><th>Άτομα</th><th>Επιλογή</th></tr></thead><tbody id="product-table-body"></tbody>';
      } else if (type === 2) {
          thead.innerHTML = '<tr><th>Κατηγορία</th></tr>';
      }

      for (var i = 0; i < data.length; i++) {
          var row = table.insertRow(i + 1);
          var cell = row.insertCell(0);

          if (type === 1) {
           // console.log("edw na ta baleis!!!!!!1");
           cell.innerHTML = data[i].product;

          var cell2 = row.insertCell(1);
          var quantityInput = document.createElement('input');
          quantityInput.type = 'number';
          quantityInput.value = 1;
          quantityInput.classList.add('quantity-input');
          quantityInput.id = 'quantityInput_' + data[i].product;
          quantityInput.setAttribute('data-product-id', data[i].product);
          cell2.appendChild(quantityInput);
          
          var cell3 = row.insertCell(2);
          var checkbox = document.createElement('input');
          checkbox.type = 'checkbox';
          checkbox.value = data[i].product;
          checkbox.classList.add('product-checkbox');
          cell3.appendChild(checkbox);

     //     var existingCustomButton = document.getElementById("custom-button");
          var existingCustomButton1 = document.getElementById("custom-button1");
    //      if (!existingCustomButton) {
     //     console.log('Creating next button...');
    //      createNextButton(container);
    //       }
           if (!existingCustomButton1) {
          console.log('Creating next button...');
          createPreviousButton(container);
           }
   
          console.log("EIMAI MESA STHN LOAD DATA");
          } else if (type === 2) {
              console.log("gamw");
              cell.innerHTML = data[i].category;
              loadCategories(cell.innerHTML);
          }
      }

      container.appendChild(tableContainer);
      attachCheckboxEventListeners();
      attachQuantityChangeEventListeners();
      restoreCheckedState();

  }
}

document.addEventListener("DOMContentLoaded", function () {
  console.log('Document loaded. Initializing with default data.');
  loadProducts();
  setTimeout(function () {
     attachCategoryClickListener(document.getElementById("menu1"));
}, 1000);
//  });
 });
/*
document.getElementById("searchButton").addEventListener("click", function () {
  console.log("i am here");
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
});*/
document.addEventListener("DOMContentLoaded", function () {
  var searchButton = document.getElementById("searchButton");
  if (searchButton) { // Check if the button exists
      searchButton.addEventListener("click", function () {
          console.log("Search button clicked");
          var productName = document.getElementById('itemInput').value;

          // Use the last active tab ID
          if (lastActiveTabId === 'menu1') {
              console.log("Calling loadProductBySearch from menu1");
              loadProductBySearch(productName);
          } else if (lastActiveTabId === 'menu2') {
              console.log("Calling loadProductBySearch from menu2");
              switchTab('menu1');
              loadProductBySearch(productName);
          } else {
              alert('Συμπλήρωσε το πεδίο αναζήτησης');
          }
      });
  } else {
      console.error("Search button not found");
  }
});


function attachCheckboxEventListeners() {
var checkboxes = document.querySelectorAll('.product-checkbox');

checkboxes.forEach(function (checkbox) {
  console.log('Attaching checkbox event listeners...');
  checkbox.addEventListener('change', function () {
    updateCheckedState();
    printCheckedProductsInfo();

    var productName = this.value;
    var productId = this.getAttribute('data-product-id'); 

    if (this.checked) {
      checkedProducts.push(productName);
    } else {
      var index = checkedProducts.indexOf(productName);
      if (index !== -1) {
        checkedProducts.splice(index, 1);
      }
    }

    checkedProductStates[productName] = this.checked;

    // store product_id along with other information
    checkedProductsInfo[productId] = {
      productName: productName,
      quantity: 1, 
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
