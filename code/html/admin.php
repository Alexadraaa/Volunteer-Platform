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
    
    <!-- Add these modifications to your existing HTML for each button -->
<button id="toggleMarkersBtn1">
    <img class="icon-next-to-button" src="home.png" alt="Base Icon">
    Toggle Base
</button>

<button id="toggleMarkersBtn2">
    <img class="icon-next-to-button" src="yellowcar.png" alt="NonActiveTaskCar Icon">
    Toggle nonActiveTaskCar
</button>

<button id="toggleMarkersBtn3">
    <img class="icon-next-to-button" src="bluecar.png" alt="ActiveTaskCar Icon">
    Toggle ActiveTaskCar
</button>

<button id="toggleMarkersBtn4">
    <img class="icon-next-to-button" src="greendonate.png" alt="TakenDonation Icon">
    Toggle TakenDonation
</button>

<button id="toggleMarkersBtn5">
    <img class="icon-next-to-button" src="orangedonate.png" alt="NonTakenDonation Icon">
    Toggle nonTakenDonation
</button>

<button id="toggleMarkersBtn6">
    <img class="icon-next-to-button" src="greenexclamation-mark.png" alt="TakenRequest Icon">
    Toggle TakenRequest
</button>

<button id="toggleMarkersBtn7">
    <img class="icon-next-to-button" src="orangeexclamation-mark.png" alt="NonTakenRequest Icon">
    Toggle nonTakenRequest
</button>



    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

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
        

        // Marker Example
        L.marker([38.2488, 21.7345]).addTo(map)
            .bindPopup('Georgiou Square, Patras, Greece.');

      // Custom Icon for Markers
    var base = L.icon({
        iconUrl: '../html/home.png',
        iconSize: [30, 30], // Adjust the size of your icon
        iconAnchor: [20, 15], // Adjust the anchor point of your icon
        popupAnchor: [-5, -10] // Adjust the popup anchor of your icon
    });

       // Custom Icon for Markers
    var ActiveTaskCar = L.icon({
        iconUrl: '../html/bluecar.png',
        iconSize: [30, 30], // Adjust the size of your icon
        iconAnchor: [20, 15], // Adjust the anchor point of your icon
        popupAnchor: [-5, -10] // Adjust the popup anchor of your icon
    });

    // Custom Icon for Markers
    var nonActiveTaskCar = L.icon({
        iconUrl: '../html/yellowcar.png',
        iconSize: [30, 30], // Adjust the size of your icon
        iconAnchor: [20, 15], // Adjust the anchor point of your icon
        popupAnchor: [-5, -10] // Adjust the popup anchor of your icon
    });

       // Custom Icon for Markers
    var TakenDonation = L.icon({
        iconUrl: '../html/greendonate.png',
        iconSize: [30, 30], // Adjust the size of your icon
        iconAnchor: [20, 15], // Adjust the anchor point of your icon
        popupAnchor: [-5, -10] // Adjust the popup anchor of your icon
    });
    
    // Custom Icon for Markers
    var nonTakenDonation = L.icon({
        iconUrl: '../html/orangedonate.png',
        iconSize: [30, 30], // Adjust the size of your icon
        iconAnchor: [20, 15], // Adjust the anchor point of your icon
        popupAnchor: [-5, -10] // Adjust the popup anchor of your icon
    });

       // Custom Icon for Markers
    var TakenRequest = L.icon({
        iconUrl: '../html/greenexclamation-mark.png',
        iconSize: [30, 30], // Adjust the size of your icon
        iconAnchor: [20, 15], // Adjust the anchor point of your icon
        popupAnchor: [-5, -10] // Adjust the popup anchor of your icon
    });

    var nonTakenRequest = L.icon({
        iconUrl: '../html/orangeexclamation-mark.png',
        iconSize: [30, 30], // Adjust the size of your icon
        iconAnchor: [20, 15], // Adjust the anchor point of your icon
        popupAnchor: [-5, -10] // Adjust the popup anchor of your icon
    });
  
    var markersGroup1 = L.layerGroup();
    var markersGroup2 = L.layerGroup();
    var markersGroup3 = L.layerGroup();
    var markersGroup4 = L.layerGroup();
    var markersGroup5 = L.layerGroup();
    var markersGroup6 = L.layerGroup();
    var markersGroup7 = L.layerGroup();

        // Add markers of each type
        var marker1  = L.marker([38.245823, 21.735651], { icon: base }).bindPopup('base');
        var marker2  = L.marker([38.246644, 21.734562], { icon: base }).bindPopup('base');
        
        var marker3  = L.marker([38.244517, 21.732309], { icon: nonActiveTaskCar }).bindPopup('NonActiveCar');
        var marker4  = L.marker([38.243287, 21.733747], { icon: nonActiveTaskCar }).bindPopup('NonActiveCar');
        
        var marker5  = L.marker([38.246859, 21.732566], { icon: ActiveTaskCar }).bindPopup('ActiveCar');
        var marker6  = L.marker([38.248679, 21.737266], { icon: ActiveTaskCar }).bindPopup('ActiveCar');

        var marker7  = L.marker([38.246741, 21.738961], { icon: TakenDonation }).bindPopup('TakenDonation');
        var marker8  = L.marker([38.243944, 21.740098], { icon: TakenDonation }).bindPopup('TakenDonation');

        var marker9  = L.marker([38.247719, 21.740227], { icon: nonTakenDonation }).bindPopup('NonTakenDonation');
        var marker10 = L.marker([38.243287, 21.728798], { icon: nonTakenDonation }).bindPopup('NonTakenDontion');

        var marker11 = L.marker([38.252117, 21.738038], { icon: TakenRequest }).bindPopup('TakenRequest');
        var marker12 = L.marker([38.256582, 21.743338], { icon: TakenRequest }).bindPopup('TakenRequest');

        var marker13 = L.marker([38.249134, 21.743767], { icon: nonTakenRequest }).bindPopup('nonTakenRequest');
        var marker14 = L.marker([38.240877, 21.733832], { icon: nonTakenRequest }).bindPopup('nonTakenRequest');

        markersGroup1.addLayer(marker1).addLayer(marker2);
        markersGroup1.addTo(map);

        markersGroup2.addLayer(marker3).addLayer(marker4);
        markersGroup2.addTo(map);

        markersGroup3.addLayer(marker5).addLayer(marker6);
        markersGroup3.addTo(map);

        markersGroup4.addLayer(marker7).addLayer(marker8);
        markersGroup4.addTo(map);

        markersGroup5.addLayer(marker9).addLayer(marker10);
        markersGroup5.addTo(map);

        markersGroup6.addLayer(marker11).addLayer(marker12);
        markersGroup6.addTo(map);

        markersGroup7.addLayer(marker13).addLayer(marker14);
        markersGroup7.addTo(map);

        function toggleMarkers1() {
            if (map.hasLayer(markersGroup1)) {
                map.removeLayer(markersGroup1);
            } else {
                map.addLayer(markersGroup1);
            }
        }

        function toggleMarkers2() {
            if (map.hasLayer(markersGroup2)) {
                map.removeLayer(markersGroup2);
            } else {
                map.addLayer(markersGroup2);
            }
        }

        function toggleMarkers3() {
            if (map.hasLayer(markersGroup3)) {
                map.removeLayer(markersGroup3);
            } else {
                map.addLayer(markersGroup3);
            }
        }

        function toggleMarkers4() {
            if (map.hasLayer(markersGroup4)) {
                map.removeLayer(markersGroup4);
            } else {
                map.addLayer(markersGroup4);
            }
        }

        function toggleMarkers5() {
            if (map.hasLayer(markersGroup5)) {
                map.removeLayer(markersGroup5);
            } else {
                map.addLayer(markersGroup5);
            }
        }

        function toggleMarkers6() {
            if (map.hasLayer(markersGroup6)) {
                map.removeLayer(markersGroup6);
            } else {
                map.addLayer(markersGroup6);
            }
        }

        function toggleMarkers7() {
            if (map.hasLayer(markersGroup7)) {
                map.removeLayer(markersGroup7);
            } else {
                map.addLayer(markersGroup7);
            }
        }
    

    // Event listener for button click to toggle markers
    var toggleMarkersButton1 = document.getElementById('toggleMarkersBtn1');
    toggleMarkersButton1.addEventListener('click', toggleMarkers1);
    

    var toggleMarkersButton2 = document.getElementById('toggleMarkersBtn2');
    toggleMarkersButton2.addEventListener('click', toggleMarkers2);
    
    var toggleMarkersButton3 = document.getElementById('toggleMarkersBtn3');
    toggleMarkersButton3.addEventListener('click', toggleMarkers3);

    var toggleMarkersButton4 = document.getElementById('toggleMarkersBtn4');
    toggleMarkersButton4.addEventListener('click', toggleMarkers4);

    var toggleMarkersButton5 = document.getElementById('toggleMarkersBtn5');
    toggleMarkersButton5.addEventListener('click', toggleMarkers5);

    var toggleMarkersButton6 = document.getElementById('toggleMarkersBtn6');
    toggleMarkersButton6.addEventListener('click', toggleMarkers6);

    var toggleMarkersButton7 = document.getElementById('toggleMarkersBtn7');
    toggleMarkersButton7.addEventListener('click', toggleMarkers7);

    </script>
</body>
</html>