<?php
           session_start();
           include("connection.php");
           if (isset($_SESSION['user_id'])) {
               $userId = $_SESSION['user_id'];
             // echo "User ID: $userId";
            } 
            $sql = "SELECT o.*, u.address
            FROM orders o
            LEFT JOIN markers m ON o.or_id = m.or_id
            JOIN users u ON o.or_c_id = u.user_id
            WHERE o.order_state IN ('Σε επεξεργασία', 'Προς Παράδοση')
            AND m.marker_id IS NULL";
            $result = $conn->query($sql);      
          
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
    <script src="https://unpkg.com/draggablejs@1.1.0/lib/draggable.bundle.legacy.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAnMBeBA0mgvQvW2SIliuCDZ0gfFusdVGE&libraries=places" defer></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
    .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 200%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 2;
        }

        .popup {
            position: absolute;
            background: #fff;
            width: 500px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin: 50px auto;
            overflow-y: auto; 
            z-index: 3;
        }

        .popup-header {
            position: relative;
            cursor: grab;
            margin-bottom: 10px;
        }

        .popup-header h2 {
            margin: 0;
        }

        .closebtn {
            position: absolute;
            top: 0;
            right: 0;
            cursor: pointer;
            font-size: 20px;
            padding: 10px;
        }

        #markers {
          display: flex;
          align-items: center;
          position: absolute;
          top: 10px; 
          right: 70px; 
          z-index: 2; 
          
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
           width: 30px;
           height: 30px; 
           object-fit: contain; 
       }

       #markerstoggle {
          display: none; 
          position: absolute;
          background-color: hsl(0, 20%, 98%);
          min-width: 160px;
          box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
          z-index: 1;
          top: 70px; 
          right: 0;
      }

       #markerstoggle button {
         color: #333;
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

    </style>   
</head>
<body>

    <!-- Menu Toggle Button -->
    <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

    <header>
        <h1>Admin</h1>
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

<div id="markers">
    <button id="imageButton1" onclick="toggleMarkers()">
        <img src="alesis.jpg" alt="Markers Pins">
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

<div id="ordersPopupOverlay" class="popup-overlay">
    <div id="ordersPopup" class="popup">
        <div class="popup-header">
            <span id="closeOrdersPopup" class="closebtn" onclick="closeOrdersPopup()">&times;</span>
            <h2>Orders Popup</h2>
        </div>
        <div class="popup-content">
        <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Order ID</th><th>Customer ID</th><th>Customer Address</th><th>Order Date</th><th>Order Type</th><th>Order State</th><th>Action</th></tr>";

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
        echo "No orders found.";
    }
    ?>
        </div>
    </div>
</div>

<div id="map"></div>
 
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
    window.location.href = 'initialpage.php';}

     
var map = L.map('map').setView([38.2488, 21.7345], 16); 

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

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
/*
function addMarker(orderId, address, orderType) {
    console.log('Adding marker for Order ID:', orderId, 'Address:', address);
    geocodeAddress(address, function (lat, lng) {
        if (lat !== null && lng !== null) {
            console.log('Geocoding successful. Coordinates:', lat, lng);

            var customIcon = getCustomIconForOrderType(orderType);
            var marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
            marker.bindPopup('Order ID: ' + orderId + '<br>Address: ' + address).openPopup();

            insertMarkerIntoDatabase(lat, lng, orderType, orderId);
            addMarkerToGroup(marker, orderType);
        } else {
            console.error('Failed to geocode address.');
            alert('Failed to geocode address.');
        }
    });
}

function addMarker(orderId, address, orderType) {
    console.log('Adding marker for Order ID:', orderId, 'Address:', address);
    geocodeAddress(address, function (lat, lng) {
        if (lat !== null && lng !== null) {
            console.log('Geocoding successful. Coordinates:', lat, lng);

            var customIcon = getCustomIconForOrderType(orderType);
            var marker = L.marker([lat, lng], { icon: customIcon });

            insertMarkerIntoDatabase(lat, lng, orderType, orderId);
            addMarkerToGroup(marker, orderType); 

            marker.addTo(map);
            marker.bindPopup('Order ID: ' + orderId + '<br>Address: ' + address).openPopup();
        } else {
            console.error('Failed to geocode address.');
            alert('Failed to geocode address.');
        }
    });
}
*/

function addMarker(orderId, address, orderType) {
    console.log('Adding marker for Order ID:', orderId, 'Address:', address);
    geocodeAddress(address, function (lat, lng) {
        if (lat !== null && lng !== null) {
            console.log('Geocoding successful. Coordinates:', lat, lng);

            // Conditionally set marker_type based on order_type
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




function addMarkers(markersData) {
    markersData.forEach(function(markerData) {
        geocodeAddress(markerData.address, function(lat, lng) {
            if (lat !== null && lng !== null) {
                console.log(markerData.markerType);
                var customIcon =getIconWhenFetchingMarkers(markerData.markerType);
                var marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);
                marker.bindPopup('Order ID: ' + markerData.orderId + '<br>Address: ' + markerData.address).openPopup();
                addMarkerToGroup(marker,markerData.markerType);
                marker.addTo(map);
            } else {
                console.error('Failed to geocode address:', markerData.address);
            }
        });
    });
}
function fetchMarkers() {
    $.ajax({
        url: 'get_markers.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            addMarkers(data);
        },
        error: function(error) {
            console.error('Error fetching markers:', error);
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

/*
function printMarkersInGroup(layerGroup) {
    var markers = layerGroup.getLayers();

    if (markers.length > 0) {
        console.log('Markers in Group:');
        markers.forEach(function (marker, index) {
            console.log('Marker ' + (index + 1) + ':', marker);
        });
    } else {
        console.log('No markers in the group.');
    }
}

// Example usage
printMarkersInGroup(markersGroupInactiveRequest);

*/
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
</html>