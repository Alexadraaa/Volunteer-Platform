User
<?php
session_start();
include("connection.php");
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
//  echo "User ID: $userId";

}

 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rescuer</title>
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
height: 100%;
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

.TableButton{
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
.doneButton{
    width: 100%;
    margin-top: 10px;
    padding: 10px 0;
    background-color: #33FF6B;
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
        .takebutton {
        background-color: rgba(50, 212, 9, 0.938); 
        border: none;
        color: white;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: block;
        font-size: 14px;
        margin: 10px auto; 
        cursor: pointer;
        border-radius: 5px;
    }

    .dropbutton {
        background-color: rgb(212, 9, 9); 
        border: none;
        color: white;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: block;
        font-size: 14px;
        margin: 10px auto; 
        cursor: pointer;
        border-radius: 5px; 
    }


    #markers {
          display: flex;
          align-items: center;
          position: absolute;
          top: 10px; 
          right: 70px; 
          z-index: 5; 
          
        }

        #imageButton1 {
          position: absolute;
          top: 30px;
          right: 20px; 
          border: none;
          background: none;
          cursor: pointer;
         }

        #imageButton1 img {
           width: 40px;
           height: 40px; 
           object-fit: contain; 
       }

       #markerstoggle {
          display: none; 
          position: absolute;
          background-color: hsl(0, 20%, 98%);
          min-width: 160px;
          box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
          z-index: 5;
          top: 70px; 
          right: 0;
      }

       #markerstoggle button {
         color: #fff;
         padding: 12px 12px;
         text-decoration: none;
         display: block;
         transition: 0.3s;
         width: 100%;
         text-align: left;
         font-size: 8px;
       }

       #markerstoggle button:hover {
         background-color: #f1f1f1;
         color: #111;
       }

       #markerstoggle img {
        width: 20px; 
        height: 20px;
        margin-right: 8px;
        object-fit: contain;
      }

      .marker-button {
        background-color: white;
       color: black;
}

/* Add these styles to your existing styles */
#toggleMarkersBtn1,
#toggleMarkersBtn2,
#toggleMarkersBtn3,
#toggleMarkersBtn4,
#toggleMarkersBtn5,
#toggleMarkersBtn6,
#toggleMarkersBtn7 {
        background-color: rgb(12, 45, 109);
        color: #fff;
        border: none;
        padding: 10px;
        margin: 5px;
        cursor: pointer;
        border-radius: 5px;
}

#toggleMarkersBtn1:hover,
#toggleMarkersBtn2:hover,
#toggleMarkersBtn3:hover,
#toggleMarkersBtn4:hover,
#toggleMarkersBtn5:hover,
#toggleMarkersBtn6:hover,
#toggleMarkersBtn7:hover {
        background-color: #2980b9;
}
#button-container {
            position: fixed;
            bottom: 200px; /* Adjust the value to move the button higher */
            right: 20px;
            z-index: 1000;
            display: flex;
        }

#perform-action-button-unload,
#perform-action-button-load {
    margin-right: 10px; /* Adjust the margin between buttons */
}

#perform-action-button-unload {
            background-color: #F3111F;
            color: white; 
            border: none; 
            padding: 10px 20px; 
            text-align: center; 
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px; 
            transition : background-color 0.3s ease;
            z-index: 4;
        }

        #perform-action-button-unload:hover {
        background-color: #d9534f;

}

#perform-action-button-load {
            background-color: #06DE16;
            color: white; 
            border: none; 
            padding: 10px 20px; 
            text-align: center; 
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px; 
            transition : background-color 0.3s ease;
            z-index: 4;
        }

        #perform-action-button-unload:hover {
        background-color: #d9534f;

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

<div id="markers">
    <button id="imageButton1" onclick="toggleMarkers()">
        <img src="toggle.png" alt="Markers Pins">
    </button>
    <div id="markerstoggle" class="dropdown-content">
    <button id="toggleMarkersBtn1" class="marker-button" data-marker-group="markersGroupActiveTaskCar">
    <img class="icon-next-to-button" src="bluecar.png" alt="ActiveTaskCar Icon">
    Toggle ActiveTaskCar
</button>

<button id="toggleMarkersBtn2" class="marker-button" data-marker-group="markersGroupInactiveTaskCar">
    <img class="icon-next-to-button" src="yellowcar.png" alt="InactiveTaskCar Icon">
    Toggle InactiveTaskCar
</button>

<button id="toggleMarkersBtn3" class="marker-button" data-marker-group="markersGroupActiveDonation">
    <img class="icon-next-to-button" src="greendonate.png" alt="ActiveDonation Icon">
    Toggle ActiveDonation
</button>

<button id="toggleMarkersBtn4" class="marker-button" data-marker-group="markersGroupInactiveDonation">
    <img class="icon-next-to-button" src="orangedonate.png" alt="InactiveDonation Icon">
    Toggle InactiveDonation
</button>

<button id="toggleMarkersBtn5" class="marker-button" data-marker-group="markersGroupActiveRequest">
    <img class="icon-next-to-button" src="greenrequest.png" alt="ActiveRequest Icon">
    Toggle ActiveRequest
</button>

<button id="toggleMarkersBtn6" class="marker-button" data-marker-group="markersGroupInactiveRequest">
    <img class="icon-next-to-button" src="orangerequest.png" alt="InactiveRequest Icon">
    Toggle InactiveRequest
</button>

    </div>
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
        data: { userId: <?php echo json_encode($userId); ?> },
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

    var cell = document.createElement("td");

    var doneButton = document.createElement("button");
    doneButton.className = "doneButton";
    doneButton.innerText = "Done";

    doneButton.onclick = function() {
        DoneOrderButton(order.or_id);
    };
    cell.appendChild(doneButton);
    row.appendChild(cell);
    
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


function toggleUserMenu() {
    var userMenu = document.getElementById('userMenu');
    userMenu.style.display = (userMenu.style.display === 'block') ? 'none' : 'block';
}

function userContainerContains(element) {
    var userContainer = document.getElementById('user-container');
    return userContainer.contains(element);
}

function toggleMarkers() {
    var markerstoggle = document.getElementById('markerstoggle');
    markerstoggle.style.display = (markerstoggle.style.display === 'block') ? 'none' : 'block';
 
}

function markerstoggleContains(element) {
    var markers = document.getElementById('markers');
    return markers.contains(element);
}

/*function toggleMap() {
     var map = document.getElementById('map');
     map.style.display = map.style.display === 'none' ? 'block' : 'none';}*/

function logout() {
    window.location.href = 'initialpage.php';}

     
var map = L.map('map').setView([38.2488, 21.7345], 14); 

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

function fetchMarkers() {
    $.ajax({
        url: 'get_markersRescuer.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Fetched markers:', data);
            addMarkers(data);
        },
        error: function(error) {
            console.error('Error fetching markers:', error);
        }
    });
}

// Declare vehicleMarker outside the loop to ensure its scope
//var vehicleMarker;

function addMarkers(markersData) {
   
   var vehicleMarker=[];

    console.log(markersData.marker_type);

    markersData.forEach(function(markerData) {
        var marker_id= markerData.marker_id;
        console.log(marker_id);
        var latitude = markerData.latitude;  
        console.log(latitude);
        var longitude = markerData.longitude;
        console.log(longitude);

        var draggable = markerData.marker_type === 'activeTaskCar'; 

        if (latitude !== null && longitude !== null) {
            console.log(markerData.marker_type);
            var customIcon = getIconWhenFetchingMarkers(markerData.marker_type);
           var marker = L.marker([latitude, longitude], { icon: customIcon, draggable: draggable }).addTo(map);
            marker.bindPopup(getPopupContent(markerData));

            if(markerData.marker_type!=='base'){
            addMarkerToGroup(marker, markerData.marker_type);}

            marker.addTo(map);

            if (draggable) {
                vehicleMarker.push(marker);
                marker.on('dragend', function (event) {
                    var newCoords = event.target.getLatLng();
                  console.log(markerData.marker_id);
                    updateMarkerCoordinates(markerData.marker_id, newCoords.lat, newCoords.lng);
                   checkDistanceAndDisplayButton(marker, markersData);
                });
            }
        } else {
            console.error('Latitude and/or Longitude missing for Order ID:', markerData.or_id);
        }

    });
}

// Function to update marker coordinates in the database
function updateMarkerCoordinates(markerId, newLatitude, newLongitude) {
        // Make an AJAX request to update the coordinates
        console.log("eeeeeeee");
        console.log(markerId);
        $.ajax({
            url: 'update_marker_cordinates.php',
            type: 'POST',
            data: {
                marker_id: markerId,
                latitude: newLatitude,
                longitude: newLongitude
            },
            success: function (response) {
                console.log("Coordinates Updated:", response);
            },
            error: function (xhr, status, error) {
                console.error("Error Updating Coordinates:", status, error);
            }
        });
    }


// Custom Icon for Markers
var baseIcon = L.icon({
    iconUrl: '../html/home.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var activeTaskCarIcon = L.icon({
    iconUrl: '../html/bluecar.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var inactiveTaskCarIcon = L.icon({
    iconUrl: 'yellowcar.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var activeDonationIcon = L.icon({
    iconUrl: 'greendonate.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var inactiveDonationIcon = L.icon({
    iconUrl: 'orangedonate.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var activeRequestIcon = L.icon({
    iconUrl: 'greenrequest.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var inactiveRequestIcon = L.icon({
    iconUrl: 'orangerequest.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

var markersGroupActiveTaskCar = L.layerGroup();
var markersGroupInactiveTaskCar = L.layerGroup();
var markersGroupActiveDonation = L.layerGroup();
var markersGroupInactiveDonation = L.layerGroup();
var markersGroupActiveRequest = L.layerGroup();
var markersGroupInactiveRequest = L.layerGroup();



function getIconWhenFetchingMarkers(markerType) {
    switch (markerType) {
        case 'base':
            return baseIcon;
        case 'activeTaskCar':
            return activeTaskCarIcon;
        case 'inactiveTaskCar':
            return inactiveTaskCarIcon;
        case 'activeDonation':
            return activeDonationIcon;
        case 'inactiveDonation':
            return inactiveDonationIcon;
        case 'activeRequest':
            return activeRequestIcon;
        case 'inactiveRequest':
            return inactiveRequestIcon;
        default:
            return baseIcon; 
    }
}

function getPopupContent(data) {
    switch (data.marker_type) {
        case 'activeTaskCar':
            return "Resquer Vehicle name: " + data.ve_username +
                   "<br>Vehicle ID: " + data.ve_id +
                   "<br>Task ID: " + data.t_id;
        case 'inactiveTaskCar':
            return "Resquer Vehicle name: " + data.ve_username +
                   "<br>Date: " + data.ve_id ;
        case 'activeRequest':
            return "Active Request: Order ID: " + data.or_id +
                   "<br>Name: " + data.name +
                   "<br>LastName: " + data.lastname +
                   "<br>Phone: " + data.phone +
                   "<br>Asigned Vehicle: " + data.ve_username +
                   "<br>Date: " + data.or_date +
                   "<br>Type: " + data.or_type +
                   "<br>State: " + data.order_state +
                   "<br>Task ID: " + data.t_id+
                   "<br><button class='dropbutton' onclick='DropButton(" + data.or_id + ")'>Drop</button>";
        case 'inactiveRequest':
            return "Inactive Request: Order ID: " + data.or_id +
                   "<br>Name: " + data.name +
                   "<br>LastName: " + data.lastname +
                   "<br>Phone: " + data.phone +
                   "<br>Date: " + data.or_date +
                   "<br>Type: " + data.or_type +
                   "<br>State: " + data.order_state +
                   "<br><button class='takebutton' onclick='TakeButton(" + data.or_id + ")'>Take</button>";
        case 'activeDonation':
            return "Active Donation: Order ID: " + data.or_id +
                   "<br>Name: " + data.name +
                   "<br>LastName: " + data.lastname +
                   "<br>Phone: " + data.phone +
                   "<br>Asigned Vehicle: " + data.ve_username +
                   "<br>Date: " + data.or_date +
                   "<br>Type: " + data.or_type +
                   "<br>State: " + data.order_state +
                   "<br>Task ID: " + data.t_id+
                   "<br><button class='dropbutton' onclick='DropButton(" + data.or_id + ")'>Drop</button>";
        case 'inactiveDonation':
            return "Inactive Donation: Order ID: " + data.or_id +
                   "<br>Name: " + data.name +
                   "<br>LastName: " + data.lastname +
                   "<br>Phone: " + data.phone +
                   "<br>Date: " + data.or_date +
                   "<br>Type: " + data.or_type +
                   "<br>State: " + data.order_state +
                   "<br><button class='takebutton' onclick='TakeButton(" + data.or_id + ")'>Take</button>";
        default:
            return 'Default Popup: Order ID ' + data.or_id;
    }
}

function TakeButton(orderId) {
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Specify the request method, URL, and set it to be asynchronous
    xhr.open("POST", "take_order.php", true);

    // Set the request header
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Define the callback function to handle the response from the server
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Handle the response from the server
            console.log(xhr.responseText);
        }
    };

    // Send the request to the server with the order ID as data
    xhr.send("order_id=" + orderId);
}

function DropButton(orderId) {
    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Specify the request method, URL, and set it to be asynchronous
    xhr.open("POST", "drop_order.php", true);

    // Set the request header
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Define the callback function to handle the response from the server
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Handle the response from the server
            console.log(xhr.responseText);
        }
    };

    // Send the request to the server with the order ID as data
    xhr.send("order_id=" + orderId);
}

function DoneOrderButton(orderId){
        // Create a new XMLHttpRequest object
        var xhr = new XMLHttpRequest();

// Specify the request method, URL, and set it to be asynchronous
xhr.open("POST", "done_order.php", true);

// Set the request header
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

// Define the callback function to handle the response from the server
xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        // Handle the response from the server
        console.log(xhr.responseText);
    }
};

// Send the request to the server with the order ID as data
xhr.send("order_id=" + orderId);
}

function toggleMarkersGroup(group) {
    if (map.hasLayer(group)) {
        map.removeLayer(group);
    } else {
        map.addLayer(group);
    }
}

function addMarkerToGroup(marker, markerType) {
    console.log('Adding marker to group:', markerType);
    console.log('Marker:', marker);
    switch (markerType) {
        case 'activeTaskCar':
            markersGroupActiveTaskCar.addLayer(marker);
            break;
        case 'inactiveTaskCar':
            markersGroupInactiveTaskCar.addLayer(marker);
            break;
        case 'activeDonation':
            markersGroupActiveDonation.addLayer(marker);
            break;
        case 'inactiveDonation':
            markersGroupInactiveDonation.addLayer(marker);
            break;
        case 'activeRequest':
            markersGroupActiveRequest.addLayer(marker);
            break;
        case 'inactiveRequest':
            markersGroupInactiveRequest.addLayer(marker);
            break;
        default:
            console.error('Invalid marker type:', markerType);
    }
}


var toggleMarkersButton1 = document.getElementById('toggleMarkersBtn1');
toggleMarkersButton1.addEventListener('click', function () {
    console.log('Markers Group Active Task Car:', markersGroupActiveTaskCar);
    toggleMarkersGroup(markersGroupActiveTaskCar);
});

var toggleMarkersButton2 = document.getElementById('toggleMarkersBtn2');
toggleMarkersButton2.addEventListener('click', function () {
    toggleMarkersGroup(markersGroupInactiveTaskCar);
});

var toggleMarkersButton3 = document.getElementById('toggleMarkersBtn3');
toggleMarkersButton3.addEventListener('click', function () {
    toggleMarkersGroup(markersGroupActiveDonation);
});

var toggleMarkersButton4 = document.getElementById('toggleMarkersBtn4');
toggleMarkersButton4.addEventListener('click', function () {
    toggleMarkersGroup(markersGroupInactiveDonation);
});

var toggleMarkersButton5 = document.getElementById('toggleMarkersBtn5');
toggleMarkersButton5.addEventListener('click', function () {
    toggleMarkersGroup(markersGroupActiveRequest);
});

var toggleMarkersButton6 = document.getElementById('toggleMarkersBtn6');
toggleMarkersButton6.addEventListener('click', function () {
    console.log('Markers Group Inactive Request:', markersGroupInactiveRequest);
    toggleMarkersGroup(markersGroupInactiveRequest);
});


var buttonContainer = document.getElementById('button-container');

// Function to check if the dragged activeTaskCar marker is within 100m of activeRequest or activeDonnation markers
function checkDistanceAndDisplayButton(draggedMarker, allMarkers) {
    var vehicleCoords = draggedMarker.getLatLng();
    //var buttonContainer = document.getElementById('button-container');
    var buttonVisible = false; // Flag to track button visibility
    var activeMarkerType; // Variable to store the type of the active marker

    allMarkers.forEach(function (markerData) {
        if (markerData.marker_type !== 'activeTaskCar') {
            var markerCoords = L.latLng(markerData.latitude, markerData.longitude);
            var distance = vehicleCoords.distanceTo(markerCoords);

            if (distance <= 100) {
                // If the distance is within 100m, set the flag to true and store the marker type
                buttonVisible = true;
                activeMarkerType = markerData.marker_type;
            }
        }
    });

       // Toggle button visibility and display the button with the correct marker type
       if (buttonVisible) {
        displayButton(vehicleCoords, activeMarkerType);
    } else {
        buttonContainer.innerHTML = ''; // Clear the button container
    }
}
    // Function to display a button based on marker type
    function displayButton(vehicleCoords, markerType) {
        //var buttonContainer = document.getElementById('button-container');

        
        // Customize button text and action based on marker type
        switch (markerType) {
            case 'activeDonation':
                     // Create the container if it doesn't exist
                     if (!buttonContainer) {
                    buttonContainer = document.createElement('div');
                    buttonContainer.id = 'button-container';
                    document.body.appendChild(buttonContainer);
                }

                var button = document.createElement('button');
                button.id = 'perform-action-button-load'; // Add an ID for styling

                button.innerHTML = 'Φόρτωση';
                button.addEventListener('click', function () {
                    // Add your action for both activeDonnation and activeRequest
                    console.log('Button clicked for ' + markerType + ' Marker');
                    
                    // Create a popup
                    var popup = L.popup()
                        .setLatLng([vehicleCoords.lat, vehicleCoords.lng])
                        .setContent('Unloaded order ' + markerType)
                        .openOn(map); // Assuming 'map' is your Leaflet map object
                });

                buttonContainer.innerHTML = ''; // Clear previous buttons
                buttonContainer.appendChild(button);
                break;
            case 'activeRequest':
                // Create the container if it doesn't exist
                if (!buttonContainer) {
                    buttonContainer = document.createElement('div');
                    buttonContainer.id = 'button-container';
                    document.body.appendChild(buttonContainer);
                }

                var button = document.createElement('button');
                button.id = 'perform-action-button-unload'; // Add an ID for styling

                button.innerHTML = 'Εκφόρτωση';
                button.addEventListener('click', function () {
                    // Add your action for both activeDonnation and activeRequest
                    console.log('Button clicked for ' + markerType + ' Marker');
                    
                    // Create a popup
                    var popup = L.popup()
                        .setLatLng([vehicleCoords.lat, vehicleCoords.lng])
                        .setContent('Unloaded order ' + markerType)
                        .openOn(map); // Assuming 'map' is your Leaflet map object
                });

                buttonContainer.innerHTML = ''; // Clear previous buttons
                buttonContainer.appendChild(button);
                break;
            case'base':
                if (!buttonContainer) {
                    buttonContainer = document.createElement('div');
                    buttonContainer.id = 'button-container';
                    document.body.appendChild(buttonContainer);
                    console.log('Container created');
                }

                var button1 = document.createElement('button');
                button1.id = 'perform-action-button-load'; // Add an ID for styling

                button1.innerHTML = 'Φόρτωση';
                button1.addEventListener('click', function () {
                    // Add your action for both activeDonnation and activeRequest
                    console.log('Button clicked for ' + markerType + ' Marker');
                    
                    // Create a popup
                    var popup1 = L.popup()
                        .setLatLng([vehicleCoords.lat, vehicleCoords.lng])
                        .setContent('Loaded order ' + markerType)
                        .openOn(map); // Assuming 'map' is your Leaflet map object
                });

                var button2 = document.createElement('button');
                button2.id = 'perform-action-button-unload'; // Add an ID for styling

                button2.innerHTML = 'Εκφόρτωση';
                button2.addEventListener('click', function () {
                    // Add your action for both activeDonnation and activeRequest
                    console.log('Button clicked for ' + markerType + ' Marker');
                    
                    // Create a popup
                    var popup2 = L.popup()
                        .setLatLng([vehicleCoords.lat, vehicleCoords.lng])
                        .setContent('Uloaded order ' + markerType)
                        .openOn(map); // Assuming 'map' is your Leaflet map object
                });

                button1.addEventListener('mouseover', function () {
                    button1.style.backgroundColor = '#2e8b57' ;
                });
                button1.addEventListener('mouseout', function () {
                    button1.style.backgroundColor = 'MediumSeaGreen' ;
                });

                button2.addEventListener('mouseover', function () {
                    button2.style.backgroundColor = '#2e8b57' ;
                });
                button2.addEventListener('mouseout', function () {
                    button2.style.backgroundColor = 'MediumSeaGreen' ;
                });

                buttonContainer.innerHTML = '';

                buttonContainer.appendChild(button1);
                buttonContainer.appendChild(button2);


            break;

            default:
                // Handle other cases or do nothing
                break;
        
        }

       
    }

function fortosi_ekfortosi_order(orderId){

// Create a new XMLHttpRequest object
var xhr = new XMLHttpRequest();

// Specify the request method, URL, and set it to be asynchronous
xhr.open("POST", "fortosi_ekfortosi_order.php", true);

// Set the request header
xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

// Define the callback function to handle the response from the server
xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        // Handle the response from the server
        console.log(xhr.responseText);
    }
};

// Send the request to the server with the order ID as data
xhr.send("order_id=" + orderId);
}

fetchMarkers();

</script>

</body>
</html>
