var selectedProductsMenu1 = [];
var selectedProductsMenu2 = [];


function switchTab(tabName) {
  var tabs = document.getElementsByClassName("tab-content");
  for (var i = 0; i < tabs.length; i++) {
    tabs[i].style.display = "none";
  }
  document.getElementById(tabName).style.display = "block";

  // Ensure that menu2 content is displayed when switching to the "Ανά κατηγορία" tab
  if (tabName === 'menu2') {
    // Display initial category table
    showCategories();
  }
}
function submitForm() {
var checkboxesMenu1 = document.querySelectorAll('#menu1 .product-checkbox:checked');
var checkboxesMenu2 = document.querySelectorAll('#menu2 .product-checkbox:checked');

selectedProductsMenu1 = Array.from(checkboxesMenu1).map(checkbox => {
    return {
        product: checkbox.value,
        quantity: document.getElementById('quantityInput_' + checkbox.value.replace(/\s/g, '')).value
    };
});

selectedProductsMenu2 = Array.from(checkboxesMenu2).map(checkbox => {
    return {
        product: checkbox.value,
        quantity: document.getElementById('quantityInput_' + checkbox.value.replace(/\s/g, '')).value
    };
});

// Update the order summary
updateOrderSummary();

// Hide the menu container
var menuContainer = document.getElementById('menu-container');
menuContainer.style.display = 'none';

// Hide the submit button
var submitButton = document.getElementById('submit-button');
submitButton.style.display = 'none';

// Display the order menu
var orderMenu = document.getElementById('order-summary-container');
orderMenu.style.display = 'block';
}


function updateOrderSummary() {
var orderSummaryTable = document.getElementById("order-summary-table");

// Clear previous content
orderSummaryTable.getElementsByTagName('tbody')[0].innerHTML = '';

// Combine selected products from both menus
var allSelectedProducts = selectedProductsMenu1.concat(selectedProductsMenu2);

// Display the selected products in the order summary table
allSelectedProducts.forEach(product => {
    var row = orderSummaryTable.insertRow(-1);

    var cellProduct = row.insertCell(0);
    var cellQuantity = row.insertCell(1);

    cellProduct.textContent = product.product;
    cellQuantity.textContent = product.quantity;
});
}

function showCategories() {
  var categoryContainer = document.getElementById('menu2');
  categoryContainer.innerHTML = ''; // Clear previous content

  var categoryTable = document.createElement('table');
  categoryTable.classList.add('category-table');

  var categoryTableHead = document.createElement('thead');
  var categoryTableBody = document.createElement('tbody');

  var headerRow = document.createElement('tr');
  headerRow.innerHTML = '<th>Κατηγορία</th>';
  categoryTableHead.appendChild(headerRow);

  var categories = ['Ep', 'Legumes']; 

  categories.forEach(function (category) {
    var row = document.createElement('tr');
    row.innerHTML = '<td onclick="showCategoryProducts(\'' + category.toLowerCase() + '\')">' + category + '</td>';
    categoryTableBody.appendChild(row);
  });

  categoryTable.appendChild(categoryTableHead);
  categoryTable.appendChild(categoryTableBody);
  categoryContainer.appendChild(categoryTable);
}

function showCategoryProducts(category) {
  var categoryContainer = document.getElementById('menu2');
  categoryContainer.innerHTML = ''; // Clear previous content

  var backButton = document.createElement('button');
  backButton.innerHTML = 'Πίσω στις Κατηγορίες';
  backButton.onclick = function() {
    showCategories();
  };

  categoryContainer.appendChild(backButton);

  var categoryTable = document.createElement('table');
  categoryTable.classList.add('product-table');

  var categoryTableHead = document.createElement('thead');
  var categoryTableBody = document.createElement('tbody');

  var headerRow = document.createElement('tr');
  headerRow.innerHTML = '<th>Προϊόν</th><th>Άτομα</th><th>Επιλογή</th>';
  categoryTableHead.appendChild(headerRow);

  var productNames;

  if (category === 'eppp') {
    productNames = ['Lasagna', 'Spaghetti', 'Kritharakiki'];
  } else if (category === 'legumes') {
    productNames = ['Lentils', 'Beans'];
  } else {
    // Default category content
    productNames = ['Category X', 'Category Y', 'Category Z'];
  }

  productNames.forEach(function (productName) {
var row = document.createElement('tr');
var productIdentifier = productName.replace(/\s/g, ''); // Remove spaces for the identifier
row.innerHTML = '<td>' + productName + '</td>' +
                '<td><input type="number" class="quantity-input" value="1" id="quantityInput_' + productIdentifier + '"></td>' +
                '<td><input type="checkbox" class="product-checkbox" value="' + productName + '" onclick="addToOrder(\'' + productName + '\')"></td>';
categoryTableBody.appendChild(row);
});

  categoryTable.appendChild(categoryTableHead);
  categoryTable.appendChild(categoryTableBody);
  categoryContainer.appendChild(categoryTable);
}

function addToOrder(product) {
    var productIdentifier = product.replace(/\s/g, ''); // Remove spaces for the identifier
var quantityInput = document.getElementById('quantityInput_' + productIdentifier);

if (quantityInput) {
var quantity = quantityInput.value; // Use the input value or default to 1
// Add the selected product to Menu 2 along with the quantity
selectedProductsMenu2.push({ product: product, quantity: quantity });
}
}

function showMenu() {
// Hide the order menu
var orderMenu = document.getElementById('order-summary-container');
orderMenu.style.display = 'none';

// Display the menu container
var menuContainer = document.getElementById('menu-container');
menuContainer.style.display = 'block';

// Display the submit button (assuming the id is 'submit-button')
var submitButton = document.getElementById('submit-button');
submitButton.style.display = 'block';
}
























function attachCheckboxEventListeners() {
  var checkboxes = document.querySelectorAll('.product-checkbox');

  checkboxes.forEach(function (checkbox) {
      var productName = checkbox.value;  // Use checkbox variable directly

      var quantityInput = document.getElementById('quantityInput_' + productName);

      checkbox.addEventListener('change', function () {
          updateQuantityInfo(checkbox, quantityInput);
      });

      // Use the input event for quantityInput
      if (quantityInput) {
          quantityInput.addEventListener('input', function () {
              updateQuantityInfo(checkbox, quantityInput);
          });
      }

      checkbox.checked = checkedProductStates[productName] || false;
  });
}

function updateQuantityInfo(checkbox, quantityInput) {
  // Check if quantityInput is defined
  if (!quantityInput) {
      console.error("Quantity input is undefined");
      return;
  }

  var isChecked = checkbox.checked;
  var productName = checkbox.value;

  console.log('Checkbox:', checkbox);
  console.log('Is Checked:', isChecked);
  console.log('Product Name:', productName);

  var quantity = parseInt(quantityInput.value, 10) || 1;


  console.log('Current Quantity:', quantity);

  quantityInput.value = quantity;

  // If the product is checked, update the global variables
  if (isChecked) {
      if (!checkedProducts.includes(productName)) {
          checkedProducts.push(productName);
      }

      checkedProductsInfo[productName] = {
          quantity: quantity,
          isChecked: isChecked
      };
  } else {
      // If the product is not checked, remove it from the global variables
      var index = checkedProducts.indexOf(productName);
      if (index !== -1) {
          checkedProducts.splice(index, 1);
      }
      delete checkedProductsInfo[productName];
  }

  checkedProductStates[productName] = isChecked;

  // Log updated information for debugging
  console.log('Updated Quantity Input:', quantityInput.value);
  console.log('Checked Products:', checkedProducts);
  console.log('Checked Product States:', checkedProductStates);
  console.log('Checked Products Info:', checkedProductsInfo);
}
