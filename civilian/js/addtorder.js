function addToOrder(product) {
    // Get the order frame
    var orderFrame = window.parent.document.getElementById('selectedProducts');

    // If the order frame does not exist, create it
    if (!orderFrame) {
        orderFrame = createOrderFrame();
    }

    // Make sure the order frame is visible
    orderFrame.style.display = 'block';

    // Log the added product to the console
    console.log('Adding to order:', product);

    // You can add further logic here to dynamically update the order display
}

// Function to create the order frame if it doesn't exist
function createOrderFrame() {
    var orderFrame = window.parent.document.createElement('iframe');
    orderFrame.id = 'selectedProducts';
    orderFrame.src = 'order.html';
    orderFrame.frameborder = '0';
    orderFrame.style.width = '300px';
    orderFrame.style.height = '400px';
    orderFrame.style.border = '1px solid #ccc';
    orderFrame.style.borderRadius = '0 0 5px 5px';

    // Append the order frame to the parent document body
    window.parent.document.body.appendChild(orderFrame);

    return orderFrame;
}