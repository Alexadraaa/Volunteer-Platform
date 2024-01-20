<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="..\css\registration.css">
    <title>Register Your Account</title>
</head>

<body>
    <div id="header-container">
        <div id="header">Kάντε εγγραφή του λογαριασμού σας</div>
    </div>

    
        <?php
        session_start();
        include("connection.php");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        

          $username = $_POST['username'];
          $password = $_POST['password'];
          $confirmpassword = $_POST['confirmpassword'];
          $name = $_POST['name'];
          $lastname = $_POST['lastname'];
          $phone = $_POST['phone'];
          $address = $_POST['address'];

          $errors = array();

          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
          $checkUsernameStmt = $conn->prepare($checkUsernameQuery);
          $checkUsernameStmt->bind_param("s", $username);
          $checkUsernameStmt->execute();
          $checkUsernameResult = $checkUsernameStmt->get_result();

          if ($checkUsernameResult->num_rows > 0) {
              array_push($errors, "Το όνομα χρήστη υπάρχει ήδη. Επιλέξτε διαφορετικό όνομα χρήστη.");
          }

          if (empty($username) || empty($password) || empty($confirmpassword) || empty($name) || empty($lastname) || empty($phone) || empty($address)) {
              array_push($errors, "Όλα τα πεδία πρέπει να συμπληρωθούν");
          } else {
                if ($password != $confirmpassword) {
                array_push($errors, "Οι κωδικοί πρόσβασης δεν ταιριάζουν");
            }

            if (!is_numeric($phone) || strlen($phone) != 10) {
                array_push($errors, "Εισαγάγετε έναν έγκυρο 10ψήφιο αριθμό τηλεφώνου");
            }
    
          }
        
            if (count($errors) > 0) {
                $_SESSION['registration_errors'] = $errors;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, password, name, lastname, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $username, $hashedPassword, $name, $lastname, $phone, $address);
                $stmt->execute();

               
                $_SESSION['user_id'] = $stmt->insert_id;

                $stmt->close();
                header("Location: mainpagecitizen.html");
                exit();

    
            }
        }

        if (isset($_SESSION['registration_errors'])) {
          echo '<div style="color: #ff0000; background-color: #ffebee; padding: 10px; border: 1px solid #ff0000; border-radius: 5px; margin-bottom: 10px;">';
          foreach ($_SESSION['registration_errors'] as $error) {
              echo '<div style="margin-bottom: 5px;">' . $error . '</div>';
          }
          echo '</div>';
      }

        unset($_SESSION['registration_errors']);
        $conn->close();
        ?>

     <div>
        <form id="registration-form" action="registration.php" method="post">
            <input type="text" name=username placeholder="Username">
            <input type="password" name=password placeholder="Κωδικός">
            <input type="password" name=confirmpassword placeholder="Επιβεβαίωση κωδικού">
            <input type="text" name=name placeholder="Όνομα">
            <input type="text" name=lastname placeholder="Επίθετο">
            <input type="text" name=phone placeholder="Κινητό Τηλέφωνο">
            <input type="text" name=address placeholder="Διεύθυνση">

            <div class="button-container">
                <button id="proceed-button" type="submit" name="submit" onclick="proceed()">Proceed</button>
                <button id="cancel-button" type="button" onclick="redirectToLogin()">Cancel</button>
            </div>
        </form>
    </div>

    <div id="loading-container" style="display:none;">
        <div id="loading-circle"></div>
        <div id="loading-message">Επεξεργαζόμαστε τα στοιχεία σας...</div>
    </div>

    <script>
        function proceed() {
            var loadingContainer = document.getElementById('loading-container');
            loadingContainer.style.display = 'flex';

            setTimeout(function () {
                window.location.href = 'mainpagecitizen.html';
            }, 3000);
        }

        function redirectToLogin() {
            window.location.href = 'initialpage.html';
        }
    </script>
</body>

</html>