<?php
session_start();
include("../../connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

/// fetch active orders with associated entries in the markers table
$sqlActive = "SELECT o.*, t.t_id, CONCAT(u.name, ' ', u.lastname) AS fullname, u.address
FROM orders o
JOIN markers m ON o.or_id = m.or_id
JOIN users u ON o.or_c_id = u.user_id
JOIN tasks t ON o.or_task_id = t.t_id
WHERE o.order_state IN ('Προς Παράδοση')";
$resultActive = $conn->query($sqlActive);

// fetch inactive orders with associated entries in the markers table
$sqlInactive = "SELECT o.*, CONCAT(u.name, ' ', u.lastname) AS fullname, u.address
  FROM orders o
  JOIN markers m ON o.or_id = m.or_id
  JOIN users u ON o.or_c_id = u.user_id
  WHERE o.order_state IN ('Σε επεξεργασία')";
$resultInactive = $conn->query($sqlInactive);

$sqlTasks = "SELECT * FROM tasks";
$resultTasks = $conn->query($sqlTasks);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['selected_orders']) && is_array($_POST['selected_orders'])) {
    
        $selectedOrderIds = $_POST['selected_orders'];
        //echo "Selected Order IDs: " . implode(', ', $selectedOrderIds);
    } else {
        echo "No orders selected.";
    }
}

$sqlInactiveVehicles = "
    SELECT v.ve_id, v.ve_username, u.username AS rescuer_username 
    FROM vehicle v
    LEFT JOIN tasks t ON v.ve_id = t.t_vehicle
    LEFT JOIN rescuer r ON v.ve_id = r.resc_ve_id
    LEFT JOIN users u ON r.resc_id = u.user_id
    GROUP BY v.ve_id, v.ve_username, rescuer_username
    HAVING COUNT(t.t_id) <= 4  AND COUNT(r.resc_id) > 0 ";
            
$resultInactiveVehicles = $conn->query($sqlInactiveVehicles);


if (!$resultInactiveVehicles) {
    die("Error in SQL query: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Παραγγελίες</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
    <link rel="stylesheet" type="text/css" href="../css/umf.css">
    <link rel="stylesheet" type="text/css" href="../css/adminorders.css">
    <script src="https://unpkg.com/draggablejs@1.1.0/lib/draggable.bundle.legacy.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
     

</head>
<body>

    <!-- Menu Toggle Button -->
    <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

    <header>
        <h1>Παραγγελίες Διαχειριστή</h1>
    </header>

    <!-- Side Navigation Menu -->
    <div id="mySidenav">
        <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
        <a href="admin.php" onclick="toggleMenu()">Αρχική</a>
        <a href="announcementscreate.php" onclick="toggleMenu()">Δημιουργία Ανακοινώσεων</a>
        <a href="fetchVehiclebyRescuers.php" onclick="toggleMenu()">Δημιουργία Λογιαριασμών</a>
        <a href="storage.php" onclick="toggleMenu()">Διαχείρηση Αποθήκης</a>
       
    </div>

    
<div id="user-container">
  <button id="imageButton" onclick="toggleUserMenu()">
      <img src="../../img/alesis.jpg" alt="Button Image">
      <div id="userMenu" class="dropdown-content">
          <a href="../../initialpage.php">Αποσύνδεση</a>
      </div>
  </button>
</div>


<div class="tab">
        <button class="tablinks" onclick="openTab(event, 'ActiveOrders')">Active Παραγγελίες</button>
        <button class="tablinks" onclick="openTab(event, 'InactiveOrders')">Inactive Παραγγελίες</button>
        <button class="tablinks" onclick="openTab(event, 'Tasks')">Tasks</button>
    </div>


    <div id="ActiveOrders" class="tabcontent">
        <h2>Active Παραγγελίες <img src="../../img/task.png" alt="task image"style="width: 20px; height: 20px;"></h2>
            <table>
                <thead>
                    <tr>
                        <th>Παραγγελία ID</th>
                        <th>Πελάτης ID</th>
                        <th>Τύπος Παραγγελίας </th>
                        <th>Κατάσταση</th>
                        <th>Ημερομηνία Παραγγελίας</th>
                        <th>Ονοματεπώνυμο</th>
                        <th>Διεύθυνση</th>
                        <th>Task ID</th>
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
                     echo "<td>" . $row['fullname'] . "</td>"; 
                     echo "<td>" . $row['address'] . "</td>";
                     echo "<td>" . $row['t_id'] . "</td>";
                     echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
        </tbody>
            <a href="admin.php" class="back-button">Πίσω</a>
    </div>

    <div id="Tasks" class="tabcontent">
        <h2>Tasks <img src="../../img/delivery.png" alt="task image"style="width: 20px; height: 20px;"></h2>
            <table>
                <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Κατάσταση</th>
                        <th>Ημερομηνία Ανάθεσης</th>
                        <th>Σχετιζόμενο Όχημα</th>
                    </tr>
                </thead>
             <tbody>
                <?php
                while ($row = $resultTasks->fetch_assoc()) {
                     echo "<tr>";
                     echo "<td>" . $row['t_id'] . "</td>";
                     echo "<td>" . $row['t_state'] . "</td>";
                     echo "<td>" . $row['t_date'] . "</td>";
                     echo "<td>" . $row['t_vehicle'] . "</td>";
                     echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
        </tbody>
            <a href="adminorders.php" class="back-button">Πίσω</a>
    </div>

    <div id="ordersAndVehicles">
    <div id="InactiveOrders" class="tabcontent" style="display: block;">
        <h2>Inactive Παραγγελίες <img src="../../img/task.png" alt="task image" style="width: 20px; height: 20px;" ></h2>            
        <form id="orderForm" method="post"> <!--  form for submitting selected orders -->
            <table>
                <thead>
                    <tr>
                        <th>Παραγγελία ID</th>
                        <th>Πελάτης ID</th>
                        <th>Τύπος Παραγγελίας </th>
                        <th>Κατάσταση</th>
                        <th>Ημερομηνία Παραγγελίας</th>
                        <th>Ονοματεπώνυμο</th>
                        <th>Διεύθυνση</th>
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
                        echo "<td>" . $row['fullname'] . "</td>"; 
                        echo "<td>" . $row['address'] . "</td>";
                        echo '<td><input type="checkbox" name="selected_orders[]" value="' . $row['or_id'] . '"></td>';
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <input type="submit" value="Επέλεξε Orders">
        </form>
    </div>

    <div id="vehicleSelection" style="display: none;">
        <h2>Επέλεξε Όχημα <img src="../../img/yellowcar.png" alt="task image" style="width: 20px; height: 20px;"></h2>
        <form id="vehicleForm" method="post">
            <table>
                <thead>
                    <tr>
                        <th>Όχημα ID</th>
                        <th>Username Οχήματος</th>
                        <th>Username Διασώστη</th>
                        <th>Επέλεξε</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $resultInactiveVehicles->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['ve_id'] . "</td>";
                        echo "<td>" . $row['ve_username'] . "</td>";
                        echo "<td>" . $row['rescuer_username'] . "</td>";
                        echo '<td><input type="radio" name="selected_vehicle" value="' . $row['ve_id'] . '"></td>';
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <input type="submit" value="Επιλογή">
            <a href="adminorders.php" class="back-button">Πίσω</a>
        </form>
    </div>
</div>


 
<script>

    var selectedOrderIds = [];

    document.getElementById("orderForm").addEventListener("submit", function(event) {
        event.preventDefault();

        selectedOrderIds = []; 

        var checkboxes = document.querySelectorAll('input[name="selected_orders[]"]:checked');
        checkboxes.forEach(function(checkbox) {
            selectedOrderIds.push(checkbox.value);
        });

        if (selectedOrderIds.length > 0) {
            console.log("Selected Order IDs: " + selectedOrderIds.join(', '));
            document.getElementById("InactiveOrders").style.display = "none";
            document.getElementById("vehicleSelection").style.display = "block";
        } else {
            console.log("No orders selected.");
        }
    });

    document.getElementById("vehicleForm").addEventListener("submit", function(event) {
    event.preventDefault();

    var selectedVehicleId = document.querySelector('input[name="selected_vehicle"]:checked');
    if (selectedVehicleId) {
        console.log("Selected Order IDs: " + selectedOrderIds.join(', '));
        console.log("Selected Vehicle ID: " + selectedVehicleId.value);

        // Make the AJAX request here
        $.ajax({
            type: "POST",
            url: "selected_vehicle.php",
            data: {
                selectedOrderIds: selectedOrderIds,
                selectedVehicleId: selectedVehicleId.value
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                window.location.href = 'admin.php';
                console.error(xhr.responseText);
            }
        });
    } else {
        console.log("No vehicle selected.");
    }
});

function toggleUserMenu() {
    var userMenu = document.getElementById('userMenu');
    userMenu.style.display = (userMenu.style.display === 'block') ? 'none' : 'block';
}

function logout() {
    window.location.href = '../../initialpage.php';}

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

function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

    </script>
</body>

</html>