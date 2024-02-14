<?php
//main admin page to view all orders,vehicles in a map and manage them 
           session_start();
           include("../../connection.php");
           if (isset($_SESSION['user_id'])) {
               $userId = $_SESSION['user_id'];
             // echo "User ID: $userId";
            } 
            $sql = "SELECT o.*, u.address
            FROM orders o
            LEFT JOIN markers m ON o.or_id = m.or_id
            JOIN users u ON o.or_c_id = u.user_id
            WHERE o.order_state IN ('Σε επεξεργασία')
            AND m.marker_id IS NULL";
            $result = $conn->query($sql);    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
    <script src="https://unpkg.com/draggablejs@1.1.0/lib/draggable.bundle.legacy.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnMBeBA0mgvQvW2SIliuCDZ0gfFusdVGE&libraries=places" defer></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
</head>
<body>

    <!-- Menu Toggle Button -->
    <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

    <header>
        <h1>Διαχειριστής</h1>
    </header>

    <!-- Side Navigation Menu -->
    <div id="mySidenav">
        <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
        <a href="announcementscreate.php" onclick="toggleMenu()">Δημιουργία Ανακοινώσεων</a>
        <a href="fetchVehiclebyRescuers.php" onclick="toggleMenu()">Δημιουργία Λογιαριασμών</a>
        <a href="storage.php" onclick="toggleMenu()">Διαχείρηση Αποθήκης</a>
        <a href="#" onclick="openOrdersPopup()">Παραγγελίες</a>
    </div>

    
<div id="user-container">
  <button id="imageButton" onclick="toggleUserMenu()">
      <img src="../../img/alesis.jpg" alt="Button Image">
      <div id="userMenu" class="dropdown-content">
          <a href="adminorders.php">Λίστα Αιτημάτων/Προσφορών</a>
          <a href="../../initialpage.php">Αποσύνδεση</a>
      </div>
  </button>
</div>

<div id="markers">
    <button id="imageButton1" onclick="toggleMarkers()">
        <img src="../../img/toggle.png" alt="Markers Pins">
    </button>
    <div id="markerstoggle" class="dropdown-content">
    <button id="toggleMarkersBtn1" class="marker-button" markersData-marker-group="markersGroupActiveTaskCar">
    <img class="icon-next-to-button" src="../../img/bluecar.png" alt="ActiveTaskCar Icon">
    Εμφάνιση Ενεργών Οχημάτων
</button>

<button id="toggleMarkersBtn2" class="marker-button" markersData-marker-group="markersGroupInactiveTaskCar">
    <img class="icon-next-to-button" src="../../img/yellowcar.png" alt="InactiveTaskCar Icon">
    Εμφάνιση Μη Ενεργών Οχημάτων
</button>

<button id="toggleMarkersBtn3" class="marker-button" markersData-marker-group="markersGroupActiveDonation">
    <img class="icon-next-to-button" src="../../img/greendonate.png" alt="ActiveDonation Icon">
    Εμφάνιση Ενεργών Προσφορών
</button>

<button id="toggleMarkersBtn4" class="marker-button" markersData-marker-group="markersGroupInactiveDonation">
    <img class="icon-next-to-button" src="../../img/orangedonate.png" alt="InactiveDonation Icon">
    Εμφάνιση Μη Ενεργών Προσφορών
</button>

<button id="toggleMarkersBtn5" class="marker-button" markersData-marker-group="markersGroupActiveRequest">
    <img class="icon-next-to-button" src="../../img/greenrequests.png" alt="ActiveRequest Icon">
    Εμφάνιση Ενεργών Αιτημάτων
</button>

<button id="toggleMarkersBtn6" class="marker-button" markersData-marker-group="markersGroupInactiveRequest">
    <img class="icon-next-to-button" src="../../img/orangerequest.png" alt="InactiveRequest Icon">
    Εμφάνιση Μη Ενεργών Αιτημάτων
</button>

    </div>
</div>

<div id="ordersPopupOverlay" class="popup-overlay">
    <div id="ordersPopup" class="popup">
        <div class="popup-header">
            <span id="closeOrdersPopup" class="closebtn" onclick="closeOrdersPopup()">&times;</span>
            <h2>Popup Παραγγελιών</h2>
        </div>
        <div class="popup-content">
        <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Παραγγελίας ID</th><th>Πελάτης ID</th><th>CΔιεύθυνση</th><th>Ημερομηνία</th><th>Τύπος</th><th>Κατάσταση</th><th>Ενέργεια</th></tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["or_id"] . "</td>";
            echo "<td>" . $row["or_c_id"] . "</td>";


            $customerId = $row["or_c_id"];
            $addressQuery = "SELECT address FROM users WHERE user_id = $customerId";
            $addressResult = $conn->query($addressQuery);

            if ($addressResult->num_rows > 0) {
                $addressRow = $addressResult->fetch_assoc();
                echo "<td>" . $addressRow["address"] . "</td>";
            } else {
                echo "<td></td>";
            }

            echo "<td>" . $row["or_date"] . "</td>";
            echo "<td>" . $row["or_type"] . "</td>";
            echo "<td>" . $row["order_state"] . "</td>";

            if ($row["order_state"] == "Σε επεξεργασία") {
                echo "<td><button onclick='addMarker(" . $row["or_id"] . ", \"" .$addressRow["address"] . "\", \"" . $row["or_type"] . "\")'>Πρόσθεσε Marker</button></td>";

            } else {
                echo "<td></td>";
            }

            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "Δεν υπάρχουν διαθέσιμες παραγγελίες.";
    }
    ?>
        </div>
    </div>
</div>

<div id="map"></div>
 
<footer>
    <p>&copy; 2024 Volunteer-Platfmorm. All rights reserved.</p>
</footer>

<script>

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

function toggleMap() {
     var map = document.getElementById('map');
     map.style.display = map.style.display === 'none' ? 'block' : 'none';}

function logout() {
    window.location.href = '../../initialpage.php';}

     
var map = L.map('map').setView([38.2488, 21.7345], 16); 

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

var lines = [];


// Custom Icon for Markers
var baseIcon = L.icon({
    iconUrl: '../../img/home.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});



// Custom Icon for Markers
var activeTaskCarIcon = L.icon({
    iconUrl: '../../img/bluecar.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var inactiveTaskCarIcon = L.icon({
    iconUrl: '../../img/yellowcar.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var activeDonationIcon = L.icon({
    iconUrl: '../../img/greendonate.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var inactiveDonationIcon = L.icon({
    iconUrl: '../../img/orangedonate.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var activeRequestIcon = L.icon({
    iconUrl: '../../img/greenrequests.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
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

function geocodeAddress(address, callback) {
    var modifiedAddress = address + ', Patras, Greece';

    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': modifiedAddress }, function (results, status) {
        if (status === 'OK') {
            var location = results[0].geometry.location;
            console.log('Geocoding successful. Original Address:', address, 'Modified Address:', modifiedAddress, 'Coordinates:', location.lat(), location.lng());
            callback(location.lat(), location.lng());
        } else {
            console.error('Geocode was not successful for the following reason:', status);
            callback(null, null); 
        }
    });
}


function addMarker(orderId, address, orderType) {
    console.log('Adding marker for Order ID:', orderId, 'Address:', address);
    geocodeAddress(address, function (lat, lng) {
        if (lat !== null && lng !== null) {
            console.log('Geocoding successful. Coordinates:', lat, lng);
            var markerType;
            if (orderType === 'Αίτημα') {
                markerType = 'inactiveRequest';
            } else if (orderType === 'Προσφορά') {
                markerType = 'inactiveDonation';
            } 
            var customIcon = getCustomIconForOrderType(orderType);
            var marker = L.marker([lat, lng], { icon: customIcon });
            marker.bindPopup('Order ID: ' + orderId + '<br>Address: ' + address).openPopup();

            insertMarkerIntoDatabase(lat, lng, markerType, orderId);
            addMarkerToGroup(marker, markerType);
            marker.addTo(map);
            removeOrderFromPopup(orderId);
          
        } else {
            console.error('Failed to geocode address.');
            alert('Failed to geocode address.');
        }
    });
}

function removeOrderFromPopup(orderId) {
    $('#ordersPopup .popup-content table tr:has(td:contains(' + orderId + '))').remove();
}


function insertMarkerIntoDatabase(latitude, longitude, markerType, orId) {
    $.ajax({
        url: 'insert_marker.php', 
        type: 'POST',
        data: {
            latitude: latitude,
            longitude: longitude,
            markerType: markerType,
            orId: orId
        },
        success: function(response) {
            console.log('Marker inserted into the database:', response);
        },
        error: function(error) {
            console.error('Error inserting marker into the database:', error);
        }
    });
}
function getCustomIconForOrderType(orderType) {
    switch (orderType) {
        case 'Αίτημα':
            return inactiveRequestIcon;
        case 'Προσφορά':
            return inactiveDonationIcon;
        default:
            return baseIcon; 
    }
}

function getIconWhenFetchingMarkers(markerType) {
    switch (markerType) {
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


document.addEventListener('DOMContentLoaded', function () {
        let isDragging = false;
        let offsetX, offsetY;

        const ordersPopup = document.getElementById('ordersPopup');
        const popupHeader = document.querySelector('.popup-header');

        popupHeader.addEventListener('mousedown', (e) => {
            isDragging = true;
            offsetX = e.clientX - ordersPopup.getBoundingClientRect().left;
            offsetY = e.clientY - ordersPopup.getBoundingClientRect().top;
        });

        document.addEventListener('mousemove', (e) => {
            if (isDragging) {
                ordersPopup.style.left = e.clientX - offsetX + 'px';
                ordersPopup.style.top = e.clientY - offsetY + 'px';
            }
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
        });
    });

function closeOrdersPopup() {
        document.getElementById('ordersPopupOverlay').style.display = 'none';
    }

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


function getPopupContent(data) {
    console.log(data.username);
    switch (data.marker_type) {
        case 'activeTaskCar':
            return ":Όνομα οχήματος: " + data.ve_username +
                   "<br>Όνομα διασώστη: " + data.username +
                   "<br>Όχημα ID: " + data.ve_id +
                   "<br>Κατάσταση: " + data.ve_state +
                   "<br>Task ID: " + data.t_id ;
        case 'inactiveTaskCar':
            return " Όνομα οχήματος: " + data.ve_username +
                   "<br>Όνομα διασώστη: " + data.username +
                   "<br>Κατάσταση: " + data.ve_state ;
        case 'activeRequest':
            return "Ενεργό αίτημα ID: " + data.or_id +
                   "<br>Όνομα: " + data.name +
                   "<br>Επίθετο: " + data.lastname +
                   "<br>Τηλέφωνο: " + data.phone +
                   "<br>Όχημα: " + data.ve_username +
                   "<br>Ημερομηνία: " + data.or_date +
                   "<br>Τύπος: " + data.or_type +
                   "<br>Κατάσταση: " + data.order_state +
                   "<br>Task ID: " + data.t_id;
        case 'inactiveRequest':
            return "Μη ενεργό αίτημα ID: " + data.or_id +
                   "<br>Όνομα: " + data.name +
                   "<br>Επίθετο: " + data.lastname +
                   "<br>Τηλέφωνο: " + data.phone +
                   "<br>Ημερομηνία: " + data.or_date +
                   "<br>Tύπος: " + data.or_type +
                   "<br>Κατάσταση: " + data.order_state ;
                
        case 'activeDonation':
            return "Ενεργή προσφορά ID: " + data.or_id +
                   "<br>Όνομα: " + data.name +
                   "<br>Επίθετο: " + data.lastname +
                   "<br>Τηλέφωνο: " + data.phone +
                   "<br>Όχημα: " + data.ve_username +
                   "<br>Ημερομηνία: " + data.or_date +
                   "<br>Τύπος: " + data.or_type +
                   "<br>Κατάσταση: " + data.order_state +
                   "<br>Task ID: " + data.t_id;
                 
        case 'inactiveDonation':
            return "Μη ενεργή προσφορά ID: " + data.or_id +
                   "<br>Όνομα: " + data.name +
                   "<br>Επίθετο: " + data.lastname +
                   "<br>Τηλέφωνο: " + data.phone +
                   "<br>Ημερομηνία: " + data.or_date +
                   "<br>Tύπος: " + data.or_type +
                   "<br>Κατάσταση: " + data.order_state ;
                
        default:
            return 'Αποθήκη ';
    }
}



function updateMarkerCoordinates(markerId, newLatitude, newLongitude) {
        console.log("eeeeeeee");
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
             //   fetchMarkers();
            },
            error: function (xhr, status, error) {
                console.error("Error Updating Coordinates:", status, error);
            }
        });
}

    // Function to check if the dragged activeTaskCar marker is within 100m of activeRequest or activeDonnation markers
    function checkDistanceAndDisplayButton(draggedMarker, allMarkers) {
        var vehicleCoords = draggedMarker.getLatLng();
        var buttonContainer = document.getElementById('button-container');

        var buttonVisible = false;
        var activeMarkerType;

        allMarkers.forEach(function (marker) {
            if (marker.marker_type !== 'activeTaskCar') {
                var markerCoords = L.latLng(marker.latitude, marker.longitude);
                var distance = vehicleCoords.distanceTo(markerCoords);

                if (distance <= 100) {
                    buttonVisible = true;
                    activeMarkerType = marker.marker_type;
                }
            }
        });

        if (buttonVisible) {
            displayButton(vehicleCoords, activeMarkerType);
        } else {
            buttonContainer.innerHTML = '';
        }
    }

    // Function to display a button based on marker type
    function displayButton(vehicleCoords, markerType) {
        var buttonContainer = document.getElementById('button-container');

        if (!buttonContainer) {
            buttonContainer = document.createElement('div');
            buttonContainer.id = 'button-container';
            document.body.appendChild(buttonContainer);
        }

        var button = document.createElement('button');
        button.id = 'perform-action-button';

        switch (markerType) {
            case 'inactiveRequest':
                button.innerHTML = 'Perform Action for Inactive Request';
                button.addEventListener('click', function () {
                    console.log('Button clicked for inactiveRequest Marker');
                });
                break;
            case 'inactiveDonnation':
                button.innerHTML = 'Perform Action for Inactive Donation';
                button.addEventListener('click', function () {
                    console.log('Button clicked for inactiveDonnation Marker');
                });
                break;
            case 'activeDonnation':
                button.innerHTML = 'Perform Action for active Donation';
                button.addEventListener('click', function () {
                    console.log('Button clicked for activeDonnation Marker');
                });
                break;
            case 'activeRequest':
                button.innerHTML = 'Perform Action for active Request';
                button.addEventListener('click', function () {
                    console.log('Button clicked for activeRequest Marker');
                });
                break;
            default:
                break;
        }

        buttonContainer.innerHTML = '';
        buttonContainer.appendChild(button);
    }

function addMarkers(markersData) {
    var vehicleMarker=[];
    markersData.forEach(function(markerData) {
        var latitude = markerData.latitude;  
        var longitude = markerData.longitude; 
        var base = markerData.marker_type === 'base';
        console.log(markerData.marker_type);
        if (latitude !== null && longitude !== null) {
            var customIcon = getIconWhenFetchingMarkers(markerData.marker_type);
            var marker = L.marker([latitude, longitude], { icon: customIcon, draggable: (/*draggable||*/ base) }).addTo(map);
          
            marker.bindPopup(getPopupContent(markerData));
            if(markerData.marker_type!=='base'){
            addMarkerToGroup(marker, markerData.marker_type);}
            marker.addTo(map);
   
            if (base) {
                marker.on('dragend', function (event) {
                    var newCoords = event.target.getLatLng();
                    console.log("Marker base");
                    updateMarkerCoordinates(markerData.marker_id, newCoords.lat, newCoords.lng);
                    console.log("BEFORE UPDATE")
                 
                });
            }
        } else {
            console.error('Latitude and/or Longitude missing for Order ID:', markerData.or_id);
        }
        if (markerData.marker_type === 'activeTaskCar') {
            marker.on('click', function () {
                // Show/hide lines when the popup is clicked
                if (marker.getPopup().isOpen()) {
                    updateLines([marker], lines);  // Update lines when popup is clicked
                    lines.forEach(function (line) {
                        line.addTo(map);
                    });
                } else {
                    lines.forEach(function (line) {
                        map.removeLayer(line);
                    });
                }
            });

            marker.on('dragend', function (event) {
                var newCoords = event.target.getLatLng();
                updateMarkerCoordinates(markerData.marker_id, newCoords.lat, newCoords.lng);
                updateLines([marker], lines);  // Update lines when marker is dragged
                checkDistanceAndDisplayButton(marker, markersData);
            });
        }


        if (markerData.marker_type === 'activeTaskCar') {
            var requestMarkers = markersData.filter(function (m) {
                return m.marker_type === 'activeRequest' || m.marker_type === 'activeDonation';
            });

            requestMarkers.forEach(function (requestMarker) {
                var lineColor = requestMarker.marker_type === 'activeRequest' ? 'tomato' : 'darkcyan';
                    var line = L.polyline([marker.getLatLng(), L.latLng(requestMarker.latitude, requestMarker.longitude)], { color: lineColor });
                lines.push(line);
            });

            // Store lines as a property of the marker
            marker.lines = lines;

            marker.on('popupopen', function () {
                // Show lines when popup opens
                lines.forEach(function (line) {
                    line.addTo(map);
                });
            });

            marker.on('popupclose', function () {
                // Hide lines when popup closes
                lines.forEach(function (line) {
                    map.removeLayer(line);
                });
            });
        }
    });

    console.log(vehicleMarkers);
    map.vehicleMarkers = vehicleMarkers;
    map.lines = lines;
}

function updateLines(vehicleMarkers, lines) {
    var newCoords = vehicleMarkers[0].getLatLng();

    lines.forEach(function (line, index) {
        var lineCoords = line.getLatLngs();
        lineCoords[0] = newCoords;

        if (vehicleMarkers.length > index + 1) {
            lineCoords[1] = vehicleMarkers[index + 1].getLatLng();
            line.setLatLngs(lineCoords);
        } 
    });
}
function fetchMarkers() {
    $.ajax({
        url: 'get_markers.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Markers successfully fetched:', data);
            addMarkers(data);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching markers. Status:', status, 'Error:', error);
            console.log('XHR response:', xhr.responseText);
        }
    });
}

function toggleMarkersGroup(group) {
    if (map.hasLayer(group)) {
        map.removeLayer(group);
    } else {
        map.addLayer(group);
    }
}

function addMarkerToGroup(marker, markerType) {
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

function openOrdersPopup() {
    console.log('Opening Orders Popup');
    var overlay = document.getElementById('ordersPopupOverlay');
    overlay.style.display = 'block';
}

function closeOrdersPopup() {
    var overlay = document.getElementById('ordersPopupOverlay');
    overlay.style.display = 'none';
}
fetchMarkers();

    </script>
</body>
<footer>
    <p>&copy; 2024 Volunteer-Platfmorm. All rights reserved.</p>
</footer>
</html>








