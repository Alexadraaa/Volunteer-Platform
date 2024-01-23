<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rescuer</title>
       

             <!--leaflet css-->
              <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
             <!--leaflet js-->
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
                crossorigin=""></script>

                

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
z-index: -1 ; }

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
    z-index=3;
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
z-index=4;

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
        <div id="task">
            <button class="top-left-button" id="Tasks" onclick="TasksTable()">
        <img src="task.png" alt="taskimg"> </button>
</div>
        <div id="popuptable1" class="popuptable">
        <a id="close-btn" class="closebtn" onclick="closePopup(popuptable1)">&times;</a>
            <table>
            <tr>
                <th>Tasks</th>
                <th>State</th>
                <th>Orders</th>
</tr>    
<tr>
    <td>Task1</td>
    <td>Active</td>
    <td><button class="Tablebutton" onclick="OrdersTable()">Orders</button></td>
</tr>
<tr>
    <td>Task2</td>
    <td>inactive</td>
    <td><button class="Tablebutton" onclick="OrdersTable()">Orders</button></td>
</tr>
</table>
</div>

<div id="popuptable2" class="popuptable">
        <a id="close-btn" class="closebtn" onclick="closePopup(popuptable2)">&times;</a>
            <table>
            <tr>
                <th>order id</th>
                <th>Type</th>
                <th>State</th>
</tr>    
<tr>
    <td>2563</td>
    <td>request</td>
    <td>delivered</td>
</tr>
<tr>
    <td>2564</td>
    <td>offer</td>
    <td>on hold</td>
</tr>
</table>
</div>
        
        
            <header>
                <h1>Διασώστης</h1>
            </header>


            <div id="user-container">
        <button id="imageButton" onclick="toggleUserMenu()">
            <img src="ssmvtnogc7ue0jufjd03h6mj89.png" alt="Button Image">
            <div id="userMenu" class="dropdown-content">
                <a href="profil.html">Προφιλ</a>
                <a href="#logout" onclick="logout()">Αποσύνδεση</a>
            </div>
        </button>
    </div>
        
             <!--leaflet map container-->
            <div id="map"></div> 

         <script>  

         let popuptable1 = document.getElementById("popuptable1");
         let popuptable2 = document.getElementById("popuptable2");

          //func to show the task table and orders 
          function TasksTable(){
            closePopup(popuptable2);
            popuptable1.classList.add("open-popuptable");

          }

          //to close popup
          function closePopup(x){
            x.classList.remove("open-popuptable");
          }

        function OrdersTable(){
            closePopup(popuptable1);
            popuptable2.classList.add("open-popuptable");
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

//map inisialisation
 var map = L.map('map').setView([38.246639, 21.734573], 15.5)

 //osm layer
 osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
     maxZoom: 19,
     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
 });
 osm.addTo(map);

//base location
 var base = L.icon({
    iconUrl: 'home.png',

    iconSize:     [30, 30], // size of the icon
    iconAnchor:   [20, 15], // point of the icon which will correspond to marker's location
    popupAnchor:  [-5, -10] // point from which the popup should open relative to the iconAnchor
});


 L.marker([38.245823, 21.735651], { icon: base }).addTo(map);
 L.marker([38.246644, 21.734562], { icon: base }).addTo(map);

</script>

    </body>
 
</html>
