<?php
session_start();
include('../../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
   // validate the form data
    if (empty($username) || empty($password) || empty($confirmpassword) || empty($name) || empty($lastname) || empty($phone) || empty($address)) {
        $_SESSION['registration_errors'] = ['Όλα τα πεδία πρέπει να συμπληρωθούν'];
    } elseif ($password !== $confirmpassword) {
        $_SESSION['registration_errors'] = ['Οι κωδικοί πρόσβασης δεν ταιριάζουν'];
    } elseif (!preg_match('/^\d{10}$/', $phone)) {
        $_SESSION['registration_errors'][] = 'Εισαγάγετε έναν έγκυρο 10ψήφιο αριθμό τηλεφώνου';
    } else {
        // check if the username is already taken
        $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
        $checkUsernameStmt = $conn->prepare($checkUsernameQuery);
        $checkUsernameStmt->bind_param("s", $username);
        $checkUsernameStmt->execute();
        $checkUsernameResult = $checkUsernameStmt->get_result();

        if ($checkUsernameResult === false) {
            $_SESSION['registration_errors'] = ['Σφάλμα βάσης δεδομένων κατά τον έλεγχο ονόματος χρήστη'];
        } else {
            if ($checkUsernameResult->num_rows > 0) {
               // echo "hello";
                $_SESSION['registration_errors'] = ['Το όνομα χρήστη υπάρχει ήδη. Επιλέξτε διαφορετικό όνομα χρήστη.'];
            } else {
                $_SESSION['registration_success'] = true;
            }
        }
    }

    // send response to javascript
 //   header('Content-Type: application/json'); 
    $response = isset($_SESSION['registration_errors']) ? array('status' => 'error', 'message' => $_SESSION['registration_errors']) : array('status' => 'success', 'message' => 'Registration successful');
    echo json_encode($response);
    unset($_SESSION['registration_errors']);
    exit();
}

$query = "SELECT v.ve_username AS vehicle_username
FROM vehicle v
LEFT JOIN rescuer r ON v.ve_id = r.resc_ve_id
WHERE r.resc_id IS NULL;";

$result = $conn->query($query);

if ($result) {
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- <link rel="stylesheet" type="text/css" href="..\css\registration.css">-->
    <link rel="stylesheet" type="text/css" href="..\css\umf.css">
    <link rel="stylesheet" type="text/css" href="..\css\admin.css">
    <link rel="stylesheet" type="text/css" href="..\css\createrescuers.css">
    <script src="..\js\umf.js" ></script>

    <title>Register Rescuer </title>

</head>

<body>
    <header>
        <h1>Δημιούργησε λογαριασμό για διασώστη</h1>
    </header>


<div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>


<div id="mySidenav">
    <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
    <a href="admin.php" onclick="toggleMenu()">Αρχική</a>
    <a href="announcementscreate.php" onclick="toggleMenu()">Δημιουργία Ανακοινώσεων</a>
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

 <div>
        <form id="registration-form"  method="post" >
            <input type="text" name=username placeholder="Username">
            <input type="password" name=password placeholder="Κωδικός">
            <input type="password" name=confirmpassword placeholder="Επιβεβαίωση κωδικού">
            <input type="text" name=name placeholder="Όνομα">
            <input type="text" name=lastname placeholder="Επίθετο">
            <input type="text" name=phone placeholder="Κινητό Τηλέφωνο">
            <input type="text" name=address placeholder="Διεύθυνση">
          <div id="button-container">
        <!--  <button id="proceed-button" type="submit" name="proceed-button">Προσθήκη</button>-->
           <button id="proceed-button" type="submit" name="proceed-button" onclick="validateForm(event)">Επόμενο</button>
            <button id="cancel-button" type="button" onclick="redirectToLogin()">Ακύρωση</button>
          </div>
        </form>
    </div>

<div id="success-popup" class="popup" onmousedown="dragElement(document.getElementById('success-popup'))">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <button id= "button1">Προσθήκη</button>
        <div id="table-container">
        <table id="data-table">
            <thead>
                <tr>
                    <th>Vehicle Username</th>
                   <!-- <th>Number of Rescuers</th>-->
                    <th>Επιλογή</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody>
        </table>
    </div>
</div>
</div>

    <div id="loading-container" style="display:none;">
        <div id="loading-circle"></div>
        <div id="loading-message">Επεξεργαζόμαστε τα στοιχεία σας...</div>
    </div>

<script>
var data = <?php echo json_encode($data); ?>;
var selectedVehicles = [];  // array to store selected vehicle usernames

function validateForm(event) {
    event.preventDefault();
    console.log("validateForm");

    var errorContainer = document.getElementById('error-container');
    if (errorContainer) {
        errorContainer.style.display = 'none';
    }

    var formData = new FormData(document.getElementById('registration-form'));

    fetch('fetchVehiclebyRescuers.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'error') {
            console.log("validateForm");
            displayErrorMessages(data.message);
        } else {
            showPopup();
            buildTable();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error communicating with the server');
    });
}

function displayErrorMessages(errors) {
    // remove any existing error messages
    var errorContainer = document.getElementById('error-container');
    if (errorContainer) {
        errorContainer.remove();
    }

    // create a new container for error messages
    var errorDiv = document.createElement('div');
    errorDiv.id = 'error-container';
    errorDiv.style.color = '#ff0000';
    errorDiv.style.backgroundColor = '#ffebee';
    errorDiv.style.padding = '10px';
    errorDiv.style.border = '1px solid #ff0000';
    errorDiv.style.borderRadius = '5px';
    errorDiv.style.marginTop = '30px';
    errorDiv.style.width = '250px';
    errorDiv.style.marginLeft = '630px';
    errorDiv.style.display = 'none';

    errors.forEach(function (error) {
        var errorItem = document.createElement('div');
        errorItem.style.marginBottom = '5px';
        errorItem.textContent = error;
        errorDiv.appendChild(errorItem);
    });

    errorDiv.style.display = 'block';
    var form = document.getElementById('registration-form');
    form.parentNode.insertBefore(errorDiv, form);
    //document.body.appendChild(errorDiv);
}

function buildTable() {
    var tableBody = document.getElementById('table-body');
    tableBody.innerHTML = '';

    data.forEach(function (row) {
        var newRow = document.createElement('tr');
        var usernameCell = document.createElement('td');
     //   var rescuersCell = document.createElement('td');
        var selectionCell = document.createElement('td');
        var checkbox = document.createElement('input');

        checkbox.type = 'checkbox';
        checkbox.name = 'selection';
        checkbox.value = row.vehicle_username;
        console.log(row.vehicle_username);
        checkbox.checked = selectedVehicles.includes(row.vehicle_username);  
        checkbox.onclick = function () {
            updateSelectedVehicles(checkbox);
        };

        usernameCell.textContent = row.vehicle_username;
    //    rescuersCell.textContent = row.number_of_rescuers;

        selectionCell.appendChild(checkbox);

        newRow.appendChild(usernameCell);
      //  newRow.appendChild(rescuersCell);
        newRow.appendChild(selectionCell);
        tableBody.appendChild(newRow);
    });
}

function updateSelectedVehicles(clickedCheckbox) {
    var vehicleUsername = clickedCheckbox.value;

    //uncheck all the previous
    document.querySelectorAll('input[name="selection"]').forEach(function (checkbox) {
        if (checkbox !== clickedCheckbox) {
            checkbox.checked = false;
        }
    });


    if (clickedCheckbox.checked) {
        selectedVehicles = [vehicleUsername];
    } else {
        selectedVehicles = [];
    }
}

document.getElementById('button1').addEventListener('click', function (e) {
    e.preventDefault();
    console.log('Selected Vehicles:', selectedVehicles);
   
});


document.getElementById('button1').addEventListener('click', function (e) {
    e.preventDefault(); // prevent default form submission behavior
    document.getElementById('loading-container').style.display = 'flex';
    var formData = new FormData(document.getElementById('registration-form'));
    var selectedVehicleUsername = selectedVehicles[0];

    formData.append('selectedVehicleUsername', selectedVehicleUsername);

    fetch('process_form.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        console.log('Raw Response:', data);
        try {
            var response = JSON.parse(data);
            if (response.status === 'success') {
               // alert(response.message);
               
            } else {
                alert('Inserts failed: ' + response.message);
            }
        } catch (error) {
            window.location.href = 'admin.php';
         //   console.error('Error parsing JSON response:', error);
          //  alert('Error parsing JSON response');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error communicating with the server');
    })
    .finally(() => {
        document.getElementById('loading-container').style.display = 'none';
    });
});

function showPopup() {
    console.log("showPopup");
    document.getElementById("success-popup").style.display = "block";
}

function closePopup() {
    document.getElementById("success-popup").style.display = "none";
}
     
function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

    // calculate the initial position to center the element
    var rect = elmnt.getBoundingClientRect();
    pos3 = rect.left + rect.width / 2;
    pos4 = rect.top + rect.height / 2;

   var header = document.getElementById(elmnt.id + "-header");
       if (header) {
          header.onmousedown = dragMouseDown;
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

  function redirectToLogin() {
    window.location.href = 'admin.php';
}


    </script>
</body>
<footer>
    <p>&copy; 2024 Volunteer-Platfmorm. All rights reserved.</p>
</footer>
</html>