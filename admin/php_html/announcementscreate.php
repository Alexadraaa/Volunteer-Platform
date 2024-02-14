<?php
session_start();
include("../../connection.php");
$userId = $_SESSION['user_id'];



$sql = "SELECT * FROM base WHERE num <= 100";
$result = $conn->query($sql);

$shortageProducts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $shortageProducts[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcements</title>
    <link rel="stylesheet" type="text/css" href="..\css\umf.css">
    <link rel="stylesheet" type="text/css" href="..\css\anouncementscreate.css">
    <script src="..\js\umf.js" ></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        #container {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin-left: 250px;
            margin-top: 40px;
        }

        #announcement-container {
            max-width: 600px;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 100px;
            padding-top: 40px;
        }

        #announcement-list,
        #shortage-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .announcement,
        .shortage-item {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        #announcement-form {
            margin-top: 20px;
            display: flex; 
            align-items: center; 
        }

        #announcement-content {
            flex-grow: 1;
            margin-right: 20px;
            margin-left: 10px;
            padding: 8px 16px;
        }

        #buttonsubmit {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
        }

         #shortage-table {
            margin-top: 40px;
            overflow-y: auto;
            height: 400px;
        }

        .table{
            margin-top: 20px;
            border-collapse: collapse;
            height: 200px;
            width: 30%; 
            margin-bottom: 20px;
            overflow: auto;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

         tbody {
            overflow: auto;
        }
    </style>
</head>
<body>

<!-- Menu Toggle Button -->
<div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

<header>
    <h1>Δημιουργία Προσφορών</h1>
</header>

<!-- Side Navigation Menu -->
<div id="mySidenav">
    <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
    <a href="admin.php" onclick="toggleMenu()">Αρχική</a>
    <a href="fetchVehiclebyRescuers.php" onclick="toggleMenu()">Δημιουργία Λογιαριασμών</a>
    <a href="storage.php" onclick="toggleMenu()">Διαχείρηση Αποθήκης</a>

</div>

<div id="user-container">
    <button id="imageButton" onclick="toggleUserMenu()">
        <img src="../../img/alesis.jpg" alt="Button Image">
        <div id="userMenu" class="dropdown-content">
            <a href="orders.php">Λίστα Αιτημάτων/Προσφορών</a>
            <a href="../../initialpage.php">Αποσύνδεση</a>
        </div>
    </button>
</div>

<div id="container">
    <div id="announcement-container">
        <h2>Ανακοινώσεις</h2>
        <ul id="announcement-list"></ul>

        <div id="announcement-form">
            <label for="announcement-content">Πρόσθεσε Ανακοίνωση:</label>
            <input type="text" id="announcement-content" placeholder="Πληκτρολόγησε">
            <button id="buttonsubmit" onclick="postAnnouncement()">Υποβολή</button>
        </div>
    </div>
 

<div id="shortage-table">
      <table>  
        <thead>
            <tr>
                <th colspan="3" style="text-align:center; font-weight:bold; background-color:#4CAF50; color:white;">Προιόντα σε έλλειψη</th>
            </tr>    
            <tr>
                <th>Προϊόν</th>
                <th>Ποσότητα</th>
                <th class="shortage-item-checkbox">Επιλογή</th>
            </tr>
        </thead>
        <tbody id="shortage-list">

        </tbody>
    </table>
</div>

<script>
    let selectedProducts = [];
    let displayProducts = [];
    let fetchedAnnouncements;
    let uniqueAnnouncementContentSet = new Set();
    const shortageProducts = <?php echo json_encode($shortageProducts); ?>;

function displayAnnouncements() {
    const announcementList = document.getElementById('announcement-list');
    announcementList.innerHTML = '';

    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                fetchedAnnouncements = JSON.parse(xhr.responseText);
                console.log('Fetched Announcements:', fetchedAnnouncements);

                // iterate through fetched announcements and add unique content to the set
                fetchedAnnouncements.forEach(announcement => {
                    uniqueAnnouncementContentSet.add(announcement.announcement_content);
                    console.log('Adding unique content to set:', announcement.announcement_content);
                    console.log('Current unique content set:', uniqueAnnouncementContentSet);
                });

                uniqueAnnouncementContentSet.forEach(uniqueContent => {
                    const listItem = document.createElement('li');
                    listItem.className = 'announcement';
                    listItem.innerHTML = `<strong>Ανακοίνωση:</strong><br>${uniqueContent}`;
                    announcementList.appendChild(listItem);
                });

                console.log('Finished processing announcements.');
            } else {
                console.log(xhr);
                console.error('Failed to fetch announcements. Status:', xhr.status);
                console.log('Fetched Announcements:', fetchedAnnouncements);
                console.log('Unique Announcement Contents:', [...uniqueAnnouncementContentSet]);
                console.log('Unique Announcement Content Set:', uniqueAnnouncementContentSet);
            }
        }
    };

    console.log('Sending AJAX request to fetch announcements.');
    xhr.open('GET', 'displayInitialAn.php', true);
    xhr.send();
}


function displayShortageProducts() {
    const shortageList = document.getElementById('shortage-list');
    shortageList.innerHTML = '';

    shortageProducts.forEach(item => {
        const row = document.createElement('tr');
        row.className = 'shortage-item';
        row.innerHTML = `
            <td>${item.product}</td>
            <td>${item.num}</td>
            <td class="shortage-item-checkbox">
                <input type="checkbox" onchange="updateAnnouncementContent(this, ${item.product_id}, '${item.product}')">
            </td>
        `;
        shortageList.appendChild(row);
    });
}
function updateAnnouncementContent(checkbox, productId, productName) {
    const announcementContent = document.getElementById('announcement-content');

    if (checkbox.checked) {
        selectedProducts.push(productId);
        displayProducts.push(productName);
       // console.log(`Product with ID ${productId} checked. Selected Products: ${selectedProducts.join(', ')}`);
        
    } else {
        const index = selectedProducts.indexOf(productId);
     //   console.log(`Product with ID ${productId} unchecked. Selected Products: ${selectedProducts.join(', ')}`);
        if (index !== -1) {
            selectedProducts.splice(index, 1);
            displayProducts.splice(index, 1);
        }
    }

    announcementContent.value = displayProducts.join(', ');
    console.log('Selected Products:', selectedProducts);
    console.log(announcementContent.value);
}

function postAnnouncement() {
    const announcementContent = document.getElementById('announcement-content').value;

    if (announcementContent.trim() !== '') {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    console.log(selectedProducts);
                    alert('Επιτυχής Ανακοίνωση.');
                    selectedProducts = [];
                    displayProducts = [];
                    displayAnnouncements();
                    location.reload();
                } else {
                    alert('Ξανά Δοκίμασε.');
                }
            }
        };

        const userId = <?php echo $_SESSION['user_id']; ?>;
        const formData = new FormData();
        formData.append('content', announcementContent);

        // Append each selected product individually
        for (const productId of selectedProducts) {
            formData.append('selectedProducts[]', productId);
        }

        console.log('FormData:', formData);

        xhr.open('POST', 'insertAnnouncement.php', true);
        xhr.send(formData);
    } else {
        alert('Please enter announcement content.');
    }
}

    window.onload = function () {
        // displayAnnouncements();
        displayAnnouncements();
        displayShortageProducts();
    };
</script>
</body>

</html>
