User<?php
// main page of the rescuer that can deliver orders from the base to the civilians(requests) and orders from the civilians to the base(offers). .
session_start();
include("../../connection.php");
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Διασώστης</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
        <script src="https://unpkg.com/draggablejs@1.1.0/lib/draggable.bundle.legacy.min.js"></script> 
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnMBeBA0mgvQvW2SIliuCDZ0gfFusdVGE&libraries=places" defer></script>
         <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <link rel="stylesheet" type="text/css" href="..\css\umf.css">
        <link rel="stylesheet" type="text/css" href="..\css\rescuer.css">
        <script src="..\js\umf.js" ></script>

</head>
<body>
    <header>
        <h1>Διασώστης</h1>
    </header>

    <!-- user container -->
    <div id="user-container">
        <button id="imageButton" onclick="toggleUserMenu()">
            <img src="../../img/profil.png" alt="Button Image">
            <div id="userMenu" class="dropdown-content">
                <a href="profilsection.php">Προφίλ</a>
                <a href="../../initialpage.php">Αποσύνδεση</a>
            </div>
        </button>
    </div>
<!-- task table that will inform the rescuer about his tasks and the orders that are included in each task -->
    <div id="task">
        <button class="top-left-button" id="Tasks" onclick="triggerTasksTable()">
            <img src="../../img/task.png" alt="taskimg">
        </button>

        <div id="popuptable1" class="popuptable" onmousedown="dragElement(this)">
            <a id="close-btn" class="closebtn" onclick="closePopup(popuptable1)">&times;</a>
            <table id="tasksTable">
                <thead>
                 
                </thead>
                <tbody id="tasksTableBody">
                    <!-- table rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
    </div>

    <div id="popuptable2" class="popuptable" onmousedown="dragElement(this)">
    <a id="close-btn-2" class="closebtn" onclick="closePopup(popuptable2)">&times;</a>
    <table id="ordersTable" >
        <thead>
        </thead>
        <tbody id="ordersTableBody">
            <!-- table rows will be dynamically added here -->
        </tbody>
    </table>
</div>

<div id="markers">
    <button id="imageButton1" onclick="toggleMarkers()">
        <img src="../../img/toggle.png" alt="Markers Pins">
    </button>
    <div id="markerstoggle" class="dropdown-content">
    <button id="toggleMarkersBtn1" class="marker-button" data-marker-group="markersGroupActiveTaskCar">
    <img class="icon-next-to-button" src="../../img/bluecar.png" alt="ActiveTaskCar Icon">
    Toggle ActiveTaskCar
</button>

<button id="toggleMarkersBtn2" class="marker-button" data-marker-group="markersGroupInactiveTaskCar">
    <img class="icon-next-to-button" src="../../img/yellowcar.png" alt="InactiveTaskCar Icon">
    Toggle InactiveTaskCar
</button>

<button id="toggleMarkersBtn3" class="marker-button" data-marker-group="markersGroupActiveDonation">
    <img class="icon-next-to-button" src="../../img/greendonate.png" alt="ActiveDonation Icon">
    Toggle ActiveDonation
</button>

<button id="toggleMarkersBtn4" class="marker-button" data-marker-group="markersGroupInactiveDonation">
    <img class="icon-next-to-button" src="../../img/orangedonate.png" alt="InactiveDonation Icon">
    Toggle InactiveDonation
</button>

<button id="toggleMarkersBtn5" class="marker-button" data-marker-group="markersGroupActiveRequest">
    <img class="icon-next-to-button" src="../../img/greenrequests.png" alt="ActiveRequest Icon">
    Toggle ActiveRequest
</button>

<button id="toggleMarkersBtn6" class="marker-button" data-marker-group="markersGroupInactiveRequest">
    <img class="icon-next-to-button" src="../../img/orangerequest.png" alt="InactiveRequest Icon">
    Toggle InactiveRequest
</button>

    </div>
</div>


<div id="map"></div> 

<footer>
    <p>&copy; 2024 Volunteer-Platfmorm. All rights reserved.</p>
</footer>
</body>
<script>  
var popuptable1 = document.getElementById("popuptable1");
var popuptable2 = document.getElementById("popuptable2");


// function to fetch tasks from the database and display them in a table
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

// function to populate the tasks table with the fetched tasks
function populateTasksTable(tasks) {
     var tableBody = document.getElementById("tasksTableBody");
     tableBody.innerHTML = "";
     
     
     var tableHeader = document.createElement("tr");
     tableHeader.innerHTML = "<th>Task ID</th><th>Κατάσταση</th><th>Ημερομηνία</th><th>Όχημα</th>";
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
      
         });
     });
}

// function to display the orders of a task in a table
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

// function to create the orders table of task
function createOrdersTable(orders) {
    var ordersTableBody = document.getElementById("ordersTableBody");
    ordersTableBody.innerHTML = "";
  
    var tableHeader = document.createElement("tr");
    tableHeader.innerHTML = "<th>Παραγγελία ID</th><th>Tύπος</th><th>Κατάσταση</th>";
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

function showPopup(popupElement) {
    popupElement.classList.add("open-popuptable");
}

function closePopup(x) {
    x.classList.remove("open-popuptable");
}

// function to drag the popups
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



function logout() {
    window.location.href = '../../initialpage.php';}

     
var map = L.map('map').setView([38.2488, 21.7345], 14); 

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// function to fetch all markers from the database
function fetchMarkers() {
    $.ajax({
        url: 'getmarkersRescuer.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Fetched markers:', data);
            addMarkers(data);
           // location.reload();
        },
        error: function(error) {
            console.error('Error fetching markers:', error);
        }
    });
}


// function to add markers to the map based on the fetched data from the database 
function addMarkers(markersData) {
   
   var vehicleMarker=[];
   var lines = [];

    markersData.forEach(function(markerData) {
        var marker_id= markerData.marker_id;
        var latitude = markerData.latitude;  
        var longitude = markerData.longitude;
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
                    updateMarkerCoordinates(markerData.marker_id, newCoords.lat, newCoords.lng);
                    updateLines([marker], lines);
                    checkDistanceAndDisplayButton(marker, markersData);
                });
            }

                
            if (markerData.marker_type === 'activeTaskCar') {
                marker.on('click', function() {
                    if (marker.getPopup().isOpen()) {
                        updateLines([marker], lines);
                        lines.forEach(function(line) {
                            line.addTo(map);
                        });
                    } else {
                        lines.forEach(function(line) {
                            map.removeLayer(line);
                        });
                    }
                });
                
                marker.on('dragend', function(event) {
                    var newCoords = event.target.getLatLng();
                    updateMarkerCoordinates(markerData.id, newCoords.lat, newCoords.lng);
                    updateLines([marker], lines);
                    checkDistanceAndDisplayButton(marker, markersData);
                });
            }
            
            // handle lines between activeTaskCar and activeRequest/activeDonation markers
            if (markerData.marker_type === 'activeTaskCar') {
                var requestMarkers = markersData.filter(function(m) {
                    return m.marker_type === 'activeRequest' || m.marker_type === 'activeDonation';
                });

                requestMarkers.forEach(function(requestMarker) {
                    var lineColor = requestMarker.marker_type === 'activeRequest' ? 'tomato' : 'darkcyan';
                    var line = L.polyline([marker.getLatLng(), L.latLng(requestMarker.latitude, requestMarker.longitude)], { color: lineColor });
                    lines.push(line);
                });

                // show/hide lines when popup opens/closes
                marker.on('popupopen', function() {
                    lines.forEach(function(line) {
                        line.addTo(map);
                    });
                });

                marker.on('popupclose', function() {
                    lines.forEach(function(line) {
                        map.removeLayer(line);
                    });
                });
            }

        } else {
            console.error('Latitude and/or Longitude missing for Order ID:', markerData.or_id);
        }

    });

    map.vehicleMarkers = vehicleMarkers;
    map.lines = lines;
}


// update the lines that associate with a vehicle marker 
function updateLines(vehicleMarkers, lines) {
    vehicleMarkers.forEach(function(marker, index) {
        var newCoords = marker.getLatLng();
        lines.forEach(function(line) {
            var lineCoords = line.getLatLngs();
            lineCoords[0] = newCoords;
            line.setLatLngs(lineCoords);
        });
    });
}


// function to update marker coordinates in the database
function updateMarkerCoordinates(markerId, newLatitude, newLongitude) {

        console.log(markerId);
        $.ajax({
            url: 'update_marker_cordinates.php',
            type: 'POST',
            data: {
                id: markerId,
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


// custom icons for markers
var baseIcon = L.icon({
    iconUrl: '../../img/home.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});


var activeTaskCarIcon = L.icon({
    iconUrl: '../../img/bluecar.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});


var inactiveTaskCarIcon = L.icon({
    iconUrl: '../../img/yellowcar.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});


var activeDonationIcon = L.icon({
    iconUrl: '../../img/greendonate.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});


var inactiveDonationIcon = L.icon({
    iconUrl: '../../img/orangedonate.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});


var activeRequestIcon = L.icon({
    iconUrl: '../../img/greenrequests.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});


var inactiveRequestIcon = L.icon({
    iconUrl: '../../img/orangerequest.png',
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


// function to get the icon based on the marker type
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
           return "Όνομα οχήματος: " + data.ve_username +
                  "<br>Όχημα ID: " + data.ve_id +
                  "<br>Task ID: " + data.t_id;
       case 'inactiveTaskCar':
           return "Όνομα οχήματος: " + data.ve_username ;
       case 'activeRequest':
           return "Active Aίτημα ID: " + data.or_id +
                  "<br>Όνομα: " + data.name +
                  "<br>Επίθετο: " + data.lastname +
                  "<br>Τηλέφωνο: " + data.phone +
                  "<br>Όχημα: " + data.ve_username +
                  "<br>Ημερομηνία: " + data.or_date +
                  "<br>Τύπος: " + data.or_type +
                  "<br>Κατάσταση: " + data.order_state +
                  "<br>Task ID: " + data.t_id+
                  "<br><button class='dropbutton' onclick='DropButton(" + data.or_id + ")'>Drop</button>";
       case 'inactiveRequest':
           return "Inactive Αίτημα  ID: " + data.or_id +
                  "<br>Όνομα: " + data.name +
                  "<br>Επίθετο: " + data.lastname +
                  "<br>Τηλέφωνο: " + data.phone +
                  "<br>Ημερομηνία: " + data.or_date +
                  "<br>Τύπος: " + data.or_type +
                  "<br>Κατάσταση: " + data.order_state +
                  "<br><button class='takebutton' onclick='TakeButton(" + data.or_id + ")'>Take</button>";
       case 'activeDonation':
           return "Active Προσφορά ID: " + data.or_id +
                  "<br>Όνομα: " + data.name +
                  "<br>Επίθετο: " + data.lastname +
                  "<br>Τηλέφωνο: " + data.phone +
                  "<br>Όχημα: " + data.ve_username +
                  "<br>Ημερομηνία: " + data.or_date +
                  "<br>Τύπος: " + data.or_type +
                  "<br>Κατάσταση: " + data.order_state +
                  "<br>Task ID: " + data.t_id+
                  "<br><button class='dropbutton' onclick='DropButton(" + data.or_id + ")'>Drop</button>";
       case 'inactiveDonation':
           return "Inactive Προσφορά ID: " + data.or_id +
                  "<br>Όνομα: " + data.name +
                  "<br>Επίθετο: " + data.lastname +
                  "<br>Τηλέφωο: " + data.phone +
                  "<br>Ημερομηνία: " + data.or_date +
                  "<br>Τύπος: " + data.or_type +
                  "<br>Κατάσταση: " + data.order_state +
                  "<br><button class='takebutton' onclick='TakeButton(" + data.or_id + ")'>Take</button>";
       default:
           return 'Default Popup: Order ID ' + data.or_id;
   }
}
   
// function to toggle markers group

function toggleMarkersGroup(group) {
    if (map.hasLayer(group)) {
        map.removeLayer(group);
    } else {
        map.addLayer(group);
    }
}

//  function to add marker to group 
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

// function to check the distance between the dragged marker and the other markers and display a button if the distance is less than 100 meters
function checkDistanceAndDisplayButton(draggedMarker, allMarkers) {
    var vehicleCoords = draggedMarker.getLatLng();
    var shortestDistance = Infinity;
    var activeMarkerType = null;
    var closestMarkerOrId = null;
    var taskId = [];

    allMarkers.forEach(function (markerData) {
        var markerCoords = L.latLng(markerData.latitude, markerData.longitude);
        var distance = vehicleCoords.distanceTo(markerCoords);


        if (distance <= 100 && distance < shortestDistance) {
            shortestDistance = distance;
            activeMarkerType = markerData.marker_type;
            closestMarkerOrId = markerData.or_id;

        }
  
        if (markerData.t_id !== null && markerData.t_id !== undefined && markerData.t_state === 'inprocess'){
                 if(!(taskId.includes(markerData.t_id)))
                taskId.push(markerData.t_id);

            }
    });
    if (activeMarkerType !== null) {
        var smallestTaskId = null;
        if (taskId.length > 1) {
         
            smallestTaskId = Math.min(...taskId);
        } else if (taskId.length === 1) {
            smallestTaskId = taskId[0];
           
        }
    
        displayButton(vehicleCoords, activeMarkerType, smallestTaskId, closestMarkerOrId);
    } else {
        buttonContainer.innerHTML = ''; 
    }
}

// function to display a button based on marker type
function displayButton(vehicleCoords, markerType,taskId,orderId) {
       
        var task_id =taskId;
        var order_id = orderId;

        switch (markerType) {
            case 'activeDonation':
                     if (!buttonContainer) {
                    buttonContainer = document.createElement('div');
                    buttonContainer.id = 'button-container';
                    document.body.appendChild(buttonContainer);
                }

                var button = document.createElement('button');
                button.id = 'perform-action-button-load'; 

                button.innerHTML = 'Φόρτωση';
                button.addEventListener('click', function () {
                
                    console.log('Button clicked for ' + markerType + ' Marker');
                    FortosiDonation(order_id) ;
                    
                
                    var popup = L.popup()
                        .setLatLng([vehicleCoords.lat, vehicleCoords.lng])
                        .setContent('Unloaded order ' + markerType)
                        .openOn(map); 
                });

                buttonContainer.innerHTML = ''; 
                buttonContainer.appendChild(button);
                break;
            case 'activeRequest':
          
                if (!buttonContainer) {
                    buttonContainer = document.createElement('div');
                    buttonContainer.id = 'button-container';
                    document.body.appendChild(buttonContainer);
                }

                var button = document.createElement('button');
                button.id = 'perform-action-button-unload'; 
                button.innerHTML = 'Εκφόρτωση';
                button.addEventListener('click', function () {
                   
                    console.log('Button clicked for ' + markerType + ' Marker');
                    EkfortosiRequest(orderId);

                
                    var popup = L.popup()
                        .setLatLng([vehicleCoords.lat, vehicleCoords.lng])
                        .setContent('Unloaded order ' + markerType)
                        .openOn(map); 
                });

                buttonContainer.innerHTML = ''; 
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
                button1.id = 'perform-action-button-load'; 
               
                button1.innerHTML = 'Φόρτωση';
                button1.addEventListener('click', function () {
                    
                    console.log('Button clicked for ' + markerType + ' Marker');
                    FortosiBase(task_id);
                  
                    
             
                    var popup1 = L.popup()
                        .setLatLng([vehicleCoords.lat, vehicleCoords.lng])
                        .setContent('Loaded order ' + markerType)
                        .openOn(map); 
                });

                var button2 = document.createElement('button');
                button2.id = 'perform-action-button-unload'; 

                button2.innerHTML = 'Εκφόρτωση';
                button2.addEventListener('click', function () {
            
                    console.log('Button clicked for ' + markerType + ' Marker');
                    EkfortosiBase(taskId);
  
                    var popup2 = L.popup()
                        .setLatLng([vehicleCoords.lat, vehicleCoords.lng])
                        .setContent('Uloaded order ' + markerType)
                        .openOn(map); 
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
               
                break;
        
        }   
}
// function to deliver the donations from  the  civilians to base from a specific task
function EkfortosiBase(taskId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "ekfortosi_base.php", true);

    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            location.reload();
        }
    };

    xhr.send("t_id=" + taskId);
}

// function to deliver the requests for the order to the civilians
function EkfortosiRequest(orderId) {

    var xhr = new XMLHttpRequest();

    xhr.open("POST", "ekfortosi_request.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
     
            console.log(xhr.responseText);
            location.reload();
        }
    };

    xhr.send("or_id=" + orderId);
}

// function to load the donations to vehicle
function FortosiDonation(orderId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "fortosi_donation.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
    
            console.log(xhr.responseText);
            location.reload();
        }
    };


    xhr.send("or_id=" + orderId);
}

// function to load the orders to vehicle of a specific task
function FortosiBase(taskId) {

    var xhr = new XMLHttpRequest();
    console.log(' FORTWSH SE function  ');
    xhr.open("POST", "fortosi_base.php", true);

    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            location.reload();
        }
    };

    xhr.send("t_id=" + taskId);
}

// function to associate a inactive request/offer with a vehicle
function TakeButton(orderId) {
    var xhr = new XMLHttpRequest();

    xhr.open("POST", "take_order.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
    
            console.log(xhr.responseText);
            location.reload();
        }
    };

    xhr.send("order_id=" + orderId);
}


// function to disassociate a request/offer from a vehicle
function DropButton(orderId) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "drop_order.php", true);

    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            location.reload();
        }
    };

    xhr.send("order_id=" + orderId);
}


fetchMarkers();

</script>

</html>
