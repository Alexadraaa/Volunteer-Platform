const urlParams = new URLSearchParams(window.location.search);
const orderId = urlParams.get('id');

// fetch product details based on the order ID (replace this with actual logic)
const products = [
    { name: 'Product 1', amount: 5 },
    { name: 'Product 2', amount: 3 },
    // Add more products as needed
];

const tableBody = document.querySelector('#productTable tbody');

// function to populate the table with product details
function populateTable() {
    tableBody.innerHTML = ''; // Clear existing rows

    products.forEach(product => {
        const row = tableBody.insertRow();
        row.innerHTML = `
            <td>${product.name}</td>
            <td>${product.amount}</td>
        `;
    });
}

// initial population of the table
populateTable();