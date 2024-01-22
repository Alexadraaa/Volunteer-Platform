<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
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
        <a href="admin.html" onclick="toggleMenu()">Αρχική</a>
        <a href="announcementscreate.html" onclick="toggleMenu()">Δημιουργία Ανακοινώσεων</a>
        <a href="acountcreate.html" onclick="toggleMenu()">Δημιουργία Λογιαριασμών</a>
        <a href="storage.html" onclick="toggleMenu()">Διαχείρηση Αποθήκης</a>
        <a href="statistics.html" onclick="toggleMenu()">Στατιστικά</a>
        <a href="#logout" onclick="logout()">Έξοδος<span class="arrow">&#8594;</span></a>
    </div>

    <div id="user-container">
        <button id="imageButton" onclick="toggleUserMenu()">
            <img src="ssmvtnogc7ue0jufjd03h6mj89.png" alt="Button Image">
            <div id="userMenu" class="dropdown-content">
                <a href="profil.html">Προφιλ</a>
                <a href="#logout" onclick="logout()">Αποσύνδεση</a>
            </div>
        </button>
    </div>

    

    <!-- Leaflet Map Container -->
    <div id="map"></div>
    
    <!-- Toggle Markers Button -->
   


<button id="toggleMarkersBtn1">
    <img class="icon-next-to-button" src="bluecar.png" alt="ActiveTaskCar Icon">
    Toggle ActiveTaskCar
</button>

<button id="toggleMarkersBtn2">
    <img class="icon-next-to-button" src="yellowcar.png" alt="InactiveTaskCar Icon">
    Toggle InactiveTaskCar
</button>

<button id="toggleMarkersBtn3">
    <img class="icon-next-to-button" src="greendonate.png" alt="ActiveDonation Icon">
    Toggle ActiveDonation
</button>

<button id="toggleMarkersBtn4">
    <img class="icon-next-to-button" src="orangedonate.png" alt="InactiveDonation Icon">
    Toggle InactiveDonation

<button id="toggleMarkersBtn5">
    <img class="icon-next-to-button" src="greenrequest.png" alt="ActiveRequest Icon">
    Toggle ActiveRequest
</button>

<button id="toggleMarkersBtn6">
    <img class="icon-next-to-button" src="orangerequest.png" alt="InactiveRequest Icon">
    Toggle InactiveRequest
</button>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- jQuery (for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        // Function to toggle the menu visibility
        function toggleMenu() {
            var sidenav = document.getElementById('mySidenav');
            var menuToggle = document.getElementById('menu-toggle');
            var headerContent = document.getElementById('header-content');
            var announcementsHeader = document.querySelector('header h1');

            if (sidenav.style.width === '0px' || sidenav.style.width === '') {
                sidenav.style.width = '250px';
                menuToggle.style.display = 'none';
                document.body.style.backgroundColor = "";
                // Adjust the margin when the menu is open
                headerContent.style.marginLeft = '250px';
                // Move the Announcements header
                announcementsHeader.style.marginLeft = '250px';
            } else {
                sidenav.style.width = '0';
                menuToggle.style.display = 'block';
                document.body.style.backgroundColor = "";
                // Reset the margin when the menu is closed
                headerContent.style.marginLeft = '0';
                // Reset the margin for the Announcements header
                announcementsHeader.style.marginLeft = '0';
            }
        }

        // Function to toggle the map visibility
        function toggleMap() {
            var map = document.getElementById('map');
            map.style.display = map.style.display === 'none' ? 'block' : 'none';
        }
        
        function logout() {
            // Assuming initialpage.html is in the same directory, adjust the path as needed
             window.location.href = 'initialpage.html';
        }

     // Leaflet Map Initialization
     var map = L.map('map').setView([38.2488, 21.7345], 16); // Set the coordinates for Georgiou Square in Patras

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
    iconUrl: '../html/yellowcar.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var activeDonationIcon = L.icon({
    iconUrl: '../html/greendonate.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var inactiveDonationIcon = L.icon({
    iconUrl: '../html/orangedonate.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var activeRequestIcon = L.icon({
    iconUrl: '../html/greenrequest.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});

// Custom Icon for Markers
var inactiveRequestIcon = L.icon({
    iconUrl: '../html/orangerequest.png',
    iconSize: [30, 30],
    iconAnchor: [20, 15],
    popupAnchor: [-5, -10]
});



// Function to fetch marker data from PHP script
function fetchMarkers() {
    $.ajax({
        url: 'get_markers.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Add markers to the map dynamically
            addMarkers(data);
        },
        error: function(error) {
            console.error('Error fetching markers:', error);
        }
    });
}

// Function to add markers dynamically to the map
function addMarkers(markerData) {
    // Loop through the marker data and add markers to the map
    markerData.forEach(function(marker) {
        var customIcon = getCustomIcon(marker.type,marker.activity);

        var newMarker = L.marker([marker.lat, marker.lng], { icon: customIcon }).bindPopup(marker.type);
        getMarkersGroup(marker.type,marker.activity).addLayer(newMarker);
    });

    // Add the marker groups to the map
    addMarkerGroupsToMap();
}

// Function to get the appropriate custom icon based on marker type
function getCustomIcon(type,activity) {
    switch (type) {
        case 'vehicle':
            return activity === 'active' ? activeTaskCarIcon : inactiveTaskCarIcon;
        case 'offer':
            return activity === 'active' ? activeDonationIcon : inactiveDonationIcon;
        case 'request':
            return activity === 'active' ? activeRequestIcon : inactiveRequestIcon;
        default:
            return baseIcon; // Return a default icon if the type is not recognized
    }
}

// Function to get the corresponding marker group based on marker type
function getMarkersGroup(type,activity) {
    switch (type) {
        case 'vehicle':
            return activity === 'active' ? markersGroup1Active : markersGroup1Inactive;
        case 'offer':
            return activity === 'active' ? markersGroup2Active : markersGroup2Inactive;
        case 'request':
            return activity === 'active' ? markersGroup3Active : markersGroup3Inactive;
      
        // Add more cases as needed
    }
}

var markersGroup1Active = L.layerGroup();
var markersGroup1Inactive = L.layerGroup();
var markersGroup2Active = L.layerGroup();
var markersGroup2Inactive = L.layerGroup();
var markersGroup3Active = L.layerGroup();
var markersGroup3Inactive = L.layerGroup();
var markersGroup4Active = L.layerGroup();
var markersGroup4Inactive = L.layerGroup();

// Function to add marker groups to the map
function addMarkerGroupsToMap() {
    if (!map.hasLayer(markersGroup1Active)) {
        map.addLayer(markersGroup1Active);
    }
    if (!map.hasLayer(markersGroup1Inactive)) {
        map.addLayer(markersGroup1Inactive);
    }
    if (!map.hasLayer(markersGroup2Active)) {
        map.addLayer(markersGroup2Active);
    }
    if (!map.hasLayer(markersGroup2Inactive)) {
        map.addLayer(markersGroup2Inactive);
    }
    if (!map.hasLayer(markersGroup3Active)) {
        map.addLayer(markersGroup3Active);
    }
    if (!map.hasLayer(markersGroup3Inactive)) {
        map.addLayer(markersGroup3Inactive);
    }    
   
    
}

// Function to toggle markers of a specific type
function toggleMarkersByType(markersGroup) {
    if (map.hasLayer(markersGroup)) {
        map.removeLayer(markersGroup);
    } else {
        map.addLayer(markersGroup);
    }
}

// Event listener for button click to toggle markers
var toggleMarkersButton1 = document.getElementById('toggleMarkersBtn1');
toggleMarkersButton1.addEventListener('click', function() {
    toggleMarkersByType(markersGroup1Active);
});

var toggleMarkersButton2 = document.getElementById('toggleMarkersBtn2');
toggleMarkersButton2.addEventListener('click', function() {
    toggleMarkersByType(markersGroup1Inactive);
});

var toggleMarkersButton3 = document.getElementById('toggleMarkersBtn3');
toggleMarkersButton3.addEventListener('click', function() {
    toggleMarkersByType(markersGroup2Active);
});

var toggleMarkersButton4 = document.getElementById('toggleMarkersBtn4');
toggleMarkersButton4.addEventListener('click', function() {
    toggleMarkersByType(markersGroup2Inactive);
});

var toggleMarkersButton5 = document.getElementById('toggleMarkersBtn5');
toggleMarkersButton5.addEventListener('click', function() {
    toggleMarkersByType(markersGroup3Active);
})

var toggleMarkersButton6 = document.getElementById('toggleMarkersBtn6');
toggleMarkersButton6.addEventListener('click', function() {
    toggleMarkersByType(markersGroup3Inactive);
})

// Fetch markers when the page is ready
fetchMarkers();

    </script>
</body>
