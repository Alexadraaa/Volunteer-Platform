document.addEventListener("DOMContentLoaded", function() {
    const table = document.getElementById('orderTable');
    const tableBody = table.querySelector('tbody'); 
    console.log('Orders page loaded');
    populateTable(tableBody); 
});


function populateTable(tableBody) {
    tableBody.innerHTML = ''; // clear existing rows
    console.log('Fetching orders...');
    console.log(orders);
    orders.forEach(order => {
        const row = tableBody.insertRow();
        row.innerHTML = `
            <td><a href="eachorder.php?id=${order.or_id}">${order.or_id}</a></td>
            <td>${order.order_state}</td>
            <td>${order.fullName}</td>
            <td>${order.or_date}</td>
            <td>
                <button class="icon-button" onclick="redoOrder(${order.or_id})" title="Ξανά κάνε την παραγγελία">
                    <img src="../../img/edit.png" alt="Edit">
                </button>
                <button class="icon-button" onclick="confirmDeleteOrder(${order.or_id})" title="Διαγραφή">
                    <img src="../../img/delete.png" alt="Delete">
                </button>
            </td>
            <td>${order.or_type}</td>
        `;
    });
}
function confirmDeleteOrder(orderId) {
   // console.log('Deleting order with ID:', orderId);
    
    if (orderId) {
        const confirmation = confirm("Είσαι σίγουρος ότι θέλεις να διαγράψεις την παραγγελία;");
        if (confirmation) {
            deleteOrder(orderId);
        }
    } else {
        console.error('Invalid orderId:', orderId);
    }
}
function deleteOrder(orderId) {
    var formData = new FormData();
    formData.append('order_id', orderId);

    fetch('delete_order.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response:', data);

        if (data.success) {
            alert(`Παραγγελία με ID ${orderId} διαγράφτηκε με επιτυχία!`);
            window.location.href = 'mainpagecitizen.php';
        } else {
            alert(`'Ακύρωση της διαγραφής της παραγγελίας ${orderId}`);
        }
    })
    .catch(error => {
        console.error('Error:', error.message);
    });
}

function redoOrder(orderId) {
    alert(`Ξανα κάνε την παραγγελία ${orderId}`);
    window.location.href = `order_process.php?id=${orderId}`;
}
