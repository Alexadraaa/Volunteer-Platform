<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.html");
    exit();
}


$user_id = $_SESSION['user_id'];

// fetch user's full name
$queryUser = "SELECT name, lastname FROM users WHERE user_id = $user_id";
$resultUser = mysqli_query($conn, $queryUser);

if (!$resultUser) {
    die("Error retrieving user information: " . mysqli_error($conn));
}

$rowUser = mysqli_fetch_assoc($resultUser);
$fullName = $rowUser['name'] . ' ' . $rowUser['lastname'];

// fetch user orders with necessary information
$queryOrders = "SELECT orders.or_id, orders.order_state, orders.or_type, orders.or_date
                FROM orders
                WHERE orders.or_c_id = $user_id";

$resultOrders = mysqli_query($conn, $queryOrders);

if (!$resultOrders) {
    die("Error retrieving user orders: " . mysqli_error($conn));
}

// fetched orders will be stored in the $orders array
$orders = [];
while ($rowOrder = mysqli_fetch_assoc($resultOrders)) {
    $rowOrder['fullName'] = $fullName; // add full name to the order data
    $orders[] = $rowOrder;
}

mysqli_free_result($resultUser);
mysqli_free_result($resultOrders);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_nXA2oQ_YYbhvUp2MComLx7GwZLWVAxw&callback=initMap"></script>
    <link rel="stylesheet" type="text/css" href="..\css\orders.css">
    <link rel="stylesheet" type="text/css" href="..\css\umf.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-3MXwVuYi4I3nbLckmfrSrQ86AOk+2Fc2sc9p8h7Q8Q4jpn3TIWWV6A/5aqL8z5SIN6UBVVVGO1hU1c3V3P36RQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="..\js\umf.js" ></script>
   <script src="..\js\orders.js" ></script>
    <title>User Orders</title>
</head>
<body>

  <!-- Menu Toggle Button -->
  <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>
    
  <header>
      <h1> Συναλλαγές</h1>
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

<div id="main-content">
  <table id="orderTable">
    <thead>
        <tr>
            <th>ID Παραγγελίας</th>
            <th>Κατάσταση</th>
            <th>Ονοματεπώνυμο</th>
            <th>Ημ/νία</th>
        <!--    <th>Ημερομηνία Παράδοσης</th>-->
            <th>Ενέργειες</th>
            <th>Αίτηματα/Προσφορά</th>
        </tr>
    </thead>
    <tbody>
        <!-- Table rows will be added dynamically using JavaScript -->
    </tbody>
</table>
</div>

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
const orders = <?php echo json_encode($orders); ?>; // fetch orders from PHP
const tableBody = document.querySelector('#orderTable tbody');

// function to populate the table with orders
function populateTable() {
    tableBody.innerHTML = ''; // clear existing rows

    orders.forEach(order => {
        const row = tableBody.insertRow();
        row.innerHTML = `
            <td><a href="eachorder.php?id=${order.or_id}">${order.or_id}</a></td>
            <td>${order.order_state}</td>
            <td>${order.fullName}</td>
            <td>${order.or_date}</td>
            <td>
                <button class="icon-button" onclick="editOrder(${order.or_id})" title="Επεξεργασία">
                    <img src="edit.png" alt="Edit">
                </button>
                <button class="icon-button" onclick="confirmDeleteOrder(${order.or_id})" title="Διαγραφή">
                        <img src="delete.png" alt="Delete">
                    </button>
            </td>
            <td>${order.or_type}</td>
        `;
    });
}
function confirmDeleteOrder(orderId) {
        const confirmation = confirm("Είσαι σίγουρος ότι θέλεις να διαγράψεις την παραγγελία;");
        if (confirmation) {
            deleteOrder(orderId);
        }
        
}

function deleteOrder(orderId) {
        fetch('delete_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                orderId: orderId,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Order with ID ${orderId} deleted successfully`);
               
            } else {
                alert(`Failed to delete order with ID ${orderId}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }


function editOrder(orderId) {
    alert(`Edit order with ID ${orderId}`);
}

// Initial population of the table
populateTable();

</script>
</html>
