<?php
//page for the user to view and update their profile details 
session_start();
include("../../connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, lastname, address, phone, username, password FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $user_data['masked_password'] = str_repeat('*', strlen($user_data['password']));
   // echo json_encode($user_data);
} else {
    echo "Προέκυψε κάποιο σφάλμα. Προσπαθήστε ξανά.";
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_nXA2oQ_YYbhvUp2MComLx7GwZLWVAxw&callback=initMap"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="..\css\profil.css">
    <link rel="stylesheet" type="text/css" href="..\css\umf.css">
    <script src="..\js\umf.js" ></script>
    <title>Λεπτομέρειες Χρήστη</title>
</head>
<body>

  <!-- menu toggle Button -->
  <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>
    
  <header>
      <h1> Λεπτομέρειες Χρήστη</h1>
  </header>

    <!-- Side Navigation Menu -->
    <div id="mySidenav">
        <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
        <a href="mainpagecitizen.php" onclick="toggleMenu()">Αρχική</a>
        <a href="announcementscitizen.php" onclick="toggleMenu()">Ανακοινώσεις</a>
        <a href="requests.php" onclick="toggleMenu()">Υπηρεσίες</a>
        <a href="contact.php" onclick="toggleMenu()">Επικοινωνία</a>
    </div>

    <div id="user-container">
        <button id="imageButton" onclick="toggleUserMenu()">
            <img src="../../img/ssmvtnogc7ue0jufjd03h6mj89.png" alt="Button Image">
            <div id="userMenu" class="dropdown-content">
                <a href="orders.php">Λίστα Αιτημάτων/Προσφορών</a>
                <a href="profilsection.php">Προφίλ</a>
                <a href="../../initialpage.php">Αποσύνδεση</a>
            </div>
        </button>
    </div>
 <div id="main-content">
    <div class="page-content">
        <div class="info-text">
          <p>Σε αυτήν τη σελίδα μπορείτε να τροποποιήσετε το προφίλ σας. Παρακαλούμε συμπληρώστε τα πεδία με Ελληνικούς χαρακτήρες. Τα στοιχεία που συμπληρώνονται εδώ χρησιμοποιούνται αποκλειστικά από την εταιρεία μας.</p>
    </div>  
    
  <form id="profileForm">
    <!-- Personal Information Section -->
  <section>
      <h2>Προσωπικές Πληροφορίες</h2>
      <label for="username">Username:</label>
  <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" required>

      <label for="password">Κωδικός:</label>
      <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user_data['masked_password']); ?>" required>


      <label for="confirmPassword">Επιβεβαίωση Κωδικού:</label>
      <input type="password" id="confirmPassword" name="confirmPassword" value="<?php echo htmlspecialchars($user_data['masked_password']); ?>" required>
    </section>

    <section>
      <h2>Πληροφορίες Αποστολής</h2>
      <label for="name">Όνομα:</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>

      <label for="lastname">Επίθετο:</label>
      <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user_data['lastname']); ?>" required>

      <label for="phone">Κινητό:</label>
      <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>">

      <label for="address">Διεύθυνση:</label>
      <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user_data['address']); ?>">

   


</section>

    <section>
      <h2>Οδηγίες Παράδοσης</h2>
      <label for="deliveryInstructions">Οδηγίες Παράδοσης:</label>
      <textarea id="deliveryInstructions" name="deliveryInstructions"></textarea>
    </section>

    <div class="button-container">
      <button type="button" onclick="updateProfile()">Αποθήκευση</button>
    </div>
  </form>
</div>


<footer>
        <div class="footer-section">
          <div></div>  
          <p>Με την βοήθειά σου ,στηρίζεις τον καθημερινό διαμερισμό προιόντων σε ανθρώπους που το έχουν ανάγκη.Δώρισε σήμερα!</p>
          <a href="requests.php" class="button">
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

<script>

function updateProfile() {
    var formElement = document.getElementById('profileForm');
    var formData = new FormData(formElement);

    fetch('update_profil.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data);
        if (data.success) {
            alert(data.message);
        } else {
            if (data.errors) {
                alert( data.errors.join("\n"));
            } else {
                alert("An error occurred. Please try again.");
            }
        }
    })
    .catch(error => {
        console.error('Error updating profile:', error);
        alert("Error updating profile. Please try again.");
    });
}
</script>



</html>   