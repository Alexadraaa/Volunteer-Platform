User
<?php
session_start();
include("connection.php");
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
  echo "User ID: $userId";

    // Fetch latitude and longitude for the logged-in user's vehicle from the markers table
    $markerquery = "SELECT m.latitude, m.longitude, m.marker_type, o.or_id, o.or_date, o.or_type, o.order_state, t.t_id
    FROM markers m
    JOIN vehicle v ON m.ve_id = v.ve_id
    JOIN rescuer r ON v.ve_id = r.resc_ve_id
    JOIN users u ON r.resc_id = u.user_id
    LEFT JOIN tasks t ON m.ve_id = t.t_vehicle
    LEFT JOIN orders o ON t.t_id = o.or_task_id
    WHERE r.resc_id = $userId";
$markerresult = mysqli_query($conn, $markerquery);
 } 
 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rescuer_test</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <script src="https://unpkg.com/draggablejs@1.1.0/lib/draggable.bundle.legacy.min.js"></script> 
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnMBeBA0mgvQvW2SIliuCDZ0gfFusdVGE&libraries=places" defer></script>
         <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
      <!--  <link rel="stylesheet" type="text/css" href="..\css\umf.css">-->
              <script src="..\js\umf.js" ></script>

<style>
            body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: white;
}

header {
    background-color: rgb(12, 45, 109);
    color: white;
    padding: 10px;
    text-align: center;
    z-index: 2; /* Ensure the header is above the map */
}
#task {
    cursor: pointer;
    color: #fff;
    position: absolute;
    top: 20px;
    left: 20px;
    z-index: 3;
    font-size: 24px;
    font-weight: bold;
    font-size: 40px;
}

#Tasks {
    position: absolute;
    top: 30px; 
    left: 20px; 
    border: none;
    background: none;
    cursor: pointer;
    z-index: 3;
}

.top-left-button {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    z-index: 2;
}


#Tasks {
    position: absolute;
    top: 5px; 
    left: 20px; 
    cursor: pointer;
}

#Tasks img {
    width: 40px; 
    height: 40px; 
    object-fit: contain; 
}


#map {width:100%;
height: 600px;
margin-bottom: 20px;
z-index: 0 ; }

.top-right-button {
    position: fixed;
    top: 30px;
    right: 40px; /* Adjust the distance from the right to make space for the menu */
    width: 50px;
    height: 40px;
    cursor: pointer;
    z-index: 2;
}

#user-container {
    display: flex;
    align-items: center;
    position: absolute;
    top: 10px; 
    right: 20px; 
    z-index: 2; /* Set a higher z-index to make sure it appears above other elements */
}

#imageButton {
    position: absolute;
    top: 30px; 
    right: 20px; 
    border: none;
    background: none;
    cursor: pointer;
}

#imageButton img {
    width: 30px; 
    height: 30px; 
    object-fit: contain; 
}


.dropdown-content {
    display: none;
    position: absolute;
    background-color: hsl(0, 20%, 98%);
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    top: 100%;
    right: 0;
}

#imageButton:hover .dropdown-content {
    display: block;
}

.dropdown-content a {
    color: #333;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    transition: 0.3s; 
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
    color: #111; 
}

.icon-next-to-button {
    width: 20px;
    height: 20px;
    margin-right: 5px;
}

.popuptable{
    background: #fff;
    border-radius: 6px;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    text-align: center;
    padding: 0 30px 30px;
    color: #333;
    z-index: 3;
 visibility: hidden;
 display: none;

}

.open-popuptable{
visibility: visible;
display: block;
}

.popuptable table{
border-spacing: 30px;
text-align: center;
width:400px;
height:relative;
z-index:4;

}

.popuptable th{
background: rgb(12, 45, 109);
color: #fff;
}

.popuptable table, tr,  td, th{
  border-collapse: collapse;
}

.popuptable button{
    width: 100%;
    margin-top: 10px;
    padding: 10px 0;
    background: #87CEFA;
    color: #fff;
    border: 0;
    outline: none;
    font-size: 18px;
    border-radius: 5px;
    cursor: pointer;
}

#close-btn {
            position: absolute;
            top: 1px;
            right: 10px;
            font-size: 20px;
            color: #000;
            cursor: pointer;
        }

</style>
</head>
<body>
    <header>
        <h1>Διασώστης</h1>
    </header>


    <div id="user-container">
        <button id="imageButton" onclick="toggleUserMenu()">
            <img src="ssmvtnogc7ue0jufjd03h6mj89.png" alt="Button Image">
            <div id="userMenu" class="dropdown-content">
                <a href="profil.html">Προφιλ</a>
                <a href="initialpage.php">Αποσύνδεση</a>
            </div>
        </button>
    </div>

    <div id="task">
        <button class="top-left-button" id="Tasks" onclick="triggerTasksTable()">
            <img src="task.png" alt="taskimg">
        </button>

        <div id="popuptable1" class="popuptable" onmousedown="dragElement(this)">
            <a id="close-btn" class="closebtn" onclick="closePopup(popuptable1)">&times;</a>
            <table id="tasksTable">
                <thead>
                   <!-- <tr>
                        <th>Task ID</th>
                        <th>State</th>
                        <th>Orders</th>
                    </tr>-->
                </thead>
                <tbody id="tasksTableBody">
                    <!-- Table rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
    </div>

    <div id="popuptable2" class="popuptable" onmousedown="dragElement(this)">
    <a id="close-btn-2" class="closebtn" onclick="closePopup(popuptable2)">&times;</a>
    <table id="ordersTable" >
        <thead><!--
            <tr>
                <th>Order ID</th>
                <th>Customer ID</th>
                <th>Date</th>
                <th>Type</th>
                <th>State</th>
            </tr>-->
        </thead>
        <tbody id="ordersTableBody">
            <!-- Table rows will be dynamically added here -->
        </tbody>
    </table>
</div>
     
          
<div id="map"></div> 


<script>  
var popuptable1 = document.getElementById("popuptable1");
var popuptable2 = document.getElementById("popuptable2");

function triggerTasksTable() {
    showPopup(popuptable1);

    $.ajax({
        type: "GET",
        url: "fetch_tasks.php",
        data: { userId: <?php echo $userId; ?> },
        dataType: "json", 
        success: function(response) {
            if (response.success) {
                var tasks = response.tasks;
                populateTasksTable(tasks);
            } else {
                console.error("Error fetching tasks:", response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching tasks:", error);
        }
    });
}
function populateTasksTable(tasks) {
var tableBody = document.getElementById("tasksTableBody");
tableBody.innerHTML = "";


var tableHeader = document.createElement("tr");
tableHeader.innerHTML = "<th>Task ID</th><th>State</th><th>Date</th><th>Vehicle</th>";
tableBody.appendChild(tableHeader);

tasks.forEach(function(task) {
    var row = document.createElement("tr");
    row.innerHTML = "<td>" + task.t_id + "</td>" +
                    "<td>" + task.t_state + "</td>" +
                    "<td>" + task.t_date + "</td>" +
                    "<td>" + task.t_vehicle + "</td>";

    var ordersButton = document.createElement("button");
    ordersButton.className = "Tablebutton";
    ordersButton.innerText = "Orders";
    ordersButton.onclick = function() {
        displayOrdersTable(task.t_id);
    };

    var cell = document.createElement("td");
    cell.appendChild(ordersButton);
    row.appendChild(cell);

    tableBody.appendChild(row);
    tasks.forEach(function (task) {
        console.log("Task ID: " + task.t_id);
        console.log("Task State: " + task.t_state);
        // ... access other properties as needed
    });
});
}

function displayOrdersTable(taskId) {
    showPopup(popuptable2); 
    $.ajax({
        type: "GET",
        url: "fetch_orders.php",
        data: { taskId: taskId },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                createOrdersTable(response.orders);
            } else {
                console.error("Error fetching orders:", response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching orders:", error);
        }
    });
}


function createOrdersTable(orders) {/*
    var ordersTable = document.createElement("table");
    var tableBody = document.createElement("tbody");*/
    var ordersTableBody = document.getElementById("ordersTableBody");
    ordersTableBody.innerHTML = "";
  
    var tableHeader = document.createElement("tr");
    tableHeader.innerHTML = "<th>Order ID</th><th>Type</th><th>State</th>";
    ordersTableBody.appendChild(tableHeader);

    orders.forEach(function(order) {
        var row = document.createElement("tr");
        row.innerHTML = "<td>" + order.or_id + "</td>" +
                        "<td>" + order.or_type + "</td>" +
                        "<td>" + order.order_state + "</td>";

        ordersTableBody.appendChild(row);
    });
    ordersTable.appendChild(tableBody);

}
/*
function triggerTasksTable() {
    console.log("Triggering TasksTable");
    showPopup(popuptable1);  // Show the pop-up for tasks
}
*/

function showPopup(popupElement) {
    popupElement.classList.add("open-popuptable");
}

function closePopup(x) {
    x.classList.remove("open-popuptable");
}

function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    
    // calculate the initial position to center the element
    var rect = elmnt.getBoundingClientRect();
    pos3 = rect.left + rect.width / 2;
    pos4 = rect.top + rect.height / 2;

    if (document.getElementById(elmnt.id + "-header")) {
        document.getElementById(elmnt.id + "-header").onmousedown = dragMouseDown;
    } else {
        elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
   
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;

        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
      
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
      
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
      
        document.onmouseup = null;
        document.onmousemove = null;
    }
}




function initMap() {
            var map = L.map('map').setView([38.2488, 21.7345], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var markers = [];

            // Iterate through the fetched data and add markers to an array
            <?php
            while ($markerRow = mysqli_fetch_assoc($markerresult)) {
                $latitude = $markerRow['latitude'];
                $longitude = $markerRow['longitude'];
                $markerType = $markerRow['marker_type'];
                $orderId = $markerRow['or_id'];
                $orderDate = $markerRow['or_date'];
                $orderType = $markerRow['or_type'];
                $orderState = $markerRow['order_state'];
                $taskId = $markerRow['t_id'];
            ?>
                // Define custom icons based on marker type
                var iconUrl = '';
                switch ('<?php echo $markerType; ?>') {
                    case 'activeTaskCar':
                        iconUrl = 'bluecar.png';
                        break;
                    case 'inactiveTaskCar':
                        iconUrl = 'yellowcar.png';
                        break;
                    case 'activeRequest':
                        iconUrl = 'greenrequest.png';
                        break;
                    case 'inactiveRequest':
                        iconUrl = 'orangerequest.png';
                        break;
                    case 'activeDonation':
                        iconUrl = 'greendonate.png';
                        break;  
                    case 'inactiveDonation':
                        iconUrl = 'orangedonate.png';
                        break;      
                    // Add more cases as needed for other marker types
                    default:
                        // Default icon
                        iconUrl = 'bluecar.png';
                }

                var customIcon = L.icon({
                    iconUrl: iconUrl,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32],
                    popupAnchor: [0, -32]
                });

                var marker = L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>], { icon: customIcon }).addTo(map);

            
            <?php } ?>
            var base = L.icon({
   iconUrl: 'home.png',

   iconSize:     [30, 30], // size of the icon
   iconAnchor:   [20, 15], // point of the icon which will correspond to marker's location
   popupAnchor:  [-5, -10] // point from which the popup should open relative to the iconAnchor
});

L.marker([38.245823, 21.735651], { icon: base }).addTo(map);
L.marker([38.246644, 21.734562], { icon: base }).addTo(map);
            

        }

        initMap();


</script>

</body>
</html>
