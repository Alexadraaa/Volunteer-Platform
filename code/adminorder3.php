<?php
session_start();
include("connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

// Fetch active orders with associated entries in the markers table
$sqlActive = "SELECT o.*, u.address
              FROM orders o
              JOIN markers m ON o.or_id = m.or_id
              JOIN users u ON o.or_c_id = u.user_id
              WHERE o.order_state IN ('Προς Παράδοση')";
$resultActive = $conn->query($sqlActive);

// Fetch inactive orders with associated entries in the markers table
$sqlInactive = "SELECT o.*, u.address
                FROM orders o
                JOIN markers m ON o.or_id = m.or_id
                JOIN users u ON o.or_c_id = u.user_id
                WHERE o.order_state IN ('Σε επεξεργασία')";
$resultInactive = $conn->query($sqlInactive);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin orders</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
    <script src="https://unpkg.com/draggablejs@1.1.0/lib/draggable.bundle.legacy.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                

<style>
 table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="submit"] {
            margin-top: 10px;
            background-color: rgb(12, 45, 109);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: rgb(29, 101, 247);
        }

</style>

</head>
<body>

    <!-- Menu Toggle Button -->
    <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

    <header>
        <h1>Admin orders</h1>
    </header>

    <!-- Side Navigation Menu -->
    <div id="mySidenav">
        <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
        <a href="announcementscreate.php" onclick="toggleMenu()">Δημιουργία Ανακοινώσεων</a>
        <a href="acountcreate.php" onclick="toggleMenu()">Δημιουργία Λογιαριασμών</a>
        <a href="storage.php" onclick="toggleMenu()">Διαχείρηση Αποθήκης</a>
        <a href="statistics.php" onclick="toggleMenu()">Στατιστικά</a>
        <a href="#" onclick="openOrdersPopup()">Παραγγελίες</a>
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

<div>
        <h2>Active Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer ID</th>
                    <th>Order Type</th>
                    <th>Order State</th>
                    <th>Order Date</th>
                    <th>Customer Address</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $resultActive->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['or_id'] . "</td>";
                    echo "<td>" . $row['or_c_id'] . "</td>";
                    echo "<td>" . $row['or_type'] . "</td>";
                    echo "<td>" . $row['order_state'] . "</td>";
                    echo "<td>" . $row['or_date'] . "</td>";
                    echo "<td>" . $row['address'] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Display inactive orders in a table -->
    <div>
        <h2>Inactive Orders</h2>
        <form action="selected_orders.php" method="post"> <!--  form for submitting selected orders -->
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer ID</th>
                        <th>Order Type</th>
                        <th>Order State</th>
                        <th>Order Date</th>
                        <th>Customer Address</th>
                        <th>Επέλεξε</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $resultInactive->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['or_id'] . "</td>";
                        echo "<td>" . $row['or_c_id'] . "</td>";
                        echo "<td>" . $row['or_type'] . "</td>";
                        echo "<td>" . $row['order_state'] . "</td>";
                        echo "<td>" . $row['or_date'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo '<td><input type="checkbox" name="selected_orders[]" value="' . $row['or_id'] . '"></td>';
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <input type="submit" value="Select Orders">
        </form>
    </div>

 
<script>

function toggleUserMenu() {
    var userMenu = document.getElementById('userMenu');
    userMenu.style.display = (userMenu.style.display === 'block') ? 'none' : 'block';
}

function logout() {
    window.location.href = 'initialpage.php';}

function toggleMenu() {
            var sidenav = document.getElementById('mySidenav');
            var menuToggle = document.getElementById('menu-toggle');
            var headerContent = document.getElementById('header-content');
            var announcementsHeader = document.querySelector('header h1');

            if (sidenav.style.width === '0px' || sidenav.style.width === '') {
                sidenav.style.width = '250px';
                menuToggle.style.display = 'none';
                document.body.style.backgroundColor = "";
                headerContent.style.marginLeft = '250px';
                announcementsHeader.style.marginLeft = '250px';
            } else {
                sidenav.style.width = '0';
                menuToggle.style.display = 'block';
                document.body.style.backgroundColor = "";
                headerContent.style.marginLeft = '0';
                announcementsHeader.style.marginLeft = '0';
            }
}

    </script>
</body>
</html>