<?php
//page for the user to contact the organization and store its message in the database
session_start();
include("../../connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST["message"];


    $sql = "INSERT INTO contact (c_id, message, contact_date) VALUES (?, ?, current_timestamp)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("is", $userId, $message);
        $result = $stmt->execute();

        if ($result) {
            echo "Message submitted successfully!";
            header("Location: contact.php");
            exit();
        } else {
            echo "Error submitting message. Please try again.";
        }

        $stmt->close();
    } else {
        echo "Error preparing statement. Please try again.";
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head><script type="text/javascript" src="/___vscode_livepreview_injected_script"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact us</title>
  <link rel="stylesheet" type="text/css" href="..\css\contact.css">
  <link rel="stylesheet" type="text/css" href="..\css\umf.css">
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_nXA2oQ_YYbhvUp2MComLx7GwZLWVAxw&callback=initMap"></script>
  <script src="..\js\umf.js" ></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>



  <!-- Side Navigation Menu -->
  <div id="mySidenav">
    <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
       <a href="mainpagecitizen.php" onclick="toggleMenu()">Αρχική</a>
        <a href="announcementscitizen.php" onclick="toggleMenu()">Ανακοινώσεις</a>
        <a href="requests.php" onclick="toggleMenu()">Υπηρεσίες</a>
</div>

  <div id="user-container">
    <button id="imageButton" onclick="toggleUserMenu()">
        <img src="../../img/profil.png" alt="Button Image">
        <div id="userMenu" class="dropdown-content">
            <a href="orders.php">Λίστα Αιτημάτων/Προσφορών</a>
            <a href="profilsection.php">Προφίλ</a>
            <a href="../../initialpage.php">Αποσύνδεση</a>
        </div>
    </button>
</div>

  <div id="header-container">
    <img src="../../img/contact.jpg" alt="Background Image" id="header-background">
    <div id="menu-toggle" onclick="toggleMenu()">&#9776; </div>
    <div id="header-content">
      <h1>Είμαστε εδώ για να βοηθήσουμε!</h1>
      <p> Επικοινωνήστε μαζί μας ανά πάσα στιγμή και θα απαντήσουμε στις ερωτήσεις σας.</p>
    </div>
    <div class="arrow-down-circle">
      <a href="#contact-form" onclick="scrollToContactForm()">&#9660;</a>
    </div>
  </div>

  <div id="container">
    <div class="row">
        <div class="column">
            <form action="contact.php" method="post">
                <label for="fname">Όνομα</label>
                <input type="text" id="fname" name="firstname" placeholder="πχ Μαριάνθη...">
                <label for="lname">Επώνυμο</label>
                <input type="text" id="lname" name="lastname" placeholder="πχ Θώδη...">
                <label for="township">Δήμος</label>
                <select id="township" name="township">
                    <option value="patra">Πατρέων</option>
                    <option value="aigialeia">Αιγιάλεια</option>
                    <option value="kalabruta">Καλαβρύτων</option>
                    <option value="dutikhaxaia">Δυτική Αχαΐα</option>
                    <option value="erumanthos">Ερυμάνθου</option>
                </select>
                <label for="message">Μήνυμα</label>
                <textarea id="message" name="message" placeholder="Γράψε κάτι.." style="height:170px"></textarea>
                <input type="submit" value="Υπέβαλε">
            </form>
        </div>
    </div>
</div>

</div>

<footer>
  <div class="footer-section">
    <div></div>  
    <p>Με την βοήθειά σου ,στηρίζεις τον καθημερινό διαμερισμό προιόντων σε ανθρώπους που το έχουν ανάγκη.Δώρισε σήμερα!</p>
    <a href="announcementscitizen.php" class="button">
      <img src="../../img/donate.png" alt="Donate Now">
  </a>
  </div>
      <hr class="divider"> <!-- Add a horizontal line as a divider -->
      <div class="section2">
              <div class="column">
                  <h3>Επικοινωνία</h3>
                  <ul>
                    <li>Τηλέφωνο(χωρίς χρέωση):+306946930521</li>
                    <li>Κινητό:+306946930521</li>
                </ul>
                <div id="social-media" class="left-align-icons" style="margin-top: 10px;">
                  <a href="#" class="fa fa-facebook icon-small" target="_blank" rel="noopener noreferrer"></a>
                  <a href="#" class="fa fa-twitter icon-medium" target="_blank" rel="noopener noreferrer"></a>
                  <a href="mailto:thebestteam@outlook.com" class="fa fa-envelope icon-small"></a>
              </div>
              
              </div>
              <div class="column">
                  <h3>Links</h3>
                  <ul>
                      <li><a href="mainpagecitizen.php">Η Ομάδα Μας</a></li>
                      <li><a href="requests.php">Υπηρεσίες</a></li>
                      <li><a href="contact.php">Επικοινωνία</a></li>
                  </ul>
              </div>
              <div class="column">
                  <h3>Τοποθεσία</h3>
                  <p>Πλατεία Γεωργίου,Πάτρα</p>
                 <div id="map"></div>  
           </div>
          </div>
</footer>
</body>
</html>
