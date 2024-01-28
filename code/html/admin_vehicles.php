<?php
session_start();
include("connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

// Fetch vehicles with an inactiveTaskCar marker
$sqlInactiveVehicles = "SELECT v.* FROM vehicle v
                       JOIN markers m ON v.ve_id = m.ve_id
                       WHERE m.marker_type = 'inactiveTaskCar'";
$resultInactiveVehicles = $conn->query($sqlInactiveVehicles);

// Check for errors in the query
if (!$resultInactiveVehicles) {
    die("Error in SQL query: " . $conn->error);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Vehicles</title>

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
            background-color: rgb(12, 45, 109);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: rgb(29, 101, 247);
        }

        .back-button {
            display: inline-block;
            background-color: #808080;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }

        .back-button:hover {
            background-color: #606060;
        }


    </style>
</head>
<body>

    <!-- Menu Toggle Button -->
    <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

    <header>
        <h1>Admin vehicles</h1>
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
        <h2>Select Vehicle <img src="yellowcar.png" alt="task image"style="width: 20px; height: 20px;"></h2>
    </div>
    <form action="selected_vehicle.php" method="post">
        <table>
            <thead>
                <tr>
                    <th>Vehicle ID</th>
                    <th>Vehicle Username</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <?php
                 while ($row = $resultInactiveVehicles->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['ve_id'] . "</td>";
                    echo "<td>" . $row['ve_username'] . "</td>";
                    echo '<td><input type="radio" name="selected_vehicle" value="' . $row['ve_id'] . '"></td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <input type="submit" value="Select Vehicle">
        <a href="adminorders.php" class="back-button">Back</a>
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