<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Καλωσήρθατε στην ιστοσελίδα μας!</title>
<link rel="shortcut icon" href="#">
<link rel="stylesheet" type="text/css" href="initialpage.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <style>
    .error-message {
      color: red;
    }

    #btn {
        background-color:  rgb(12, 45, 109); 
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 13px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 6px;
    }

    #btn:hover {
        background-color: #45a049;
    }
  </style>
</head>
<body>
<div id="header-container">
    <div id="header">Καλωσήρθατε στην πλατφόρμα εθελοντών</div>
  </div>
  <?php
session_start();
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['login_errors'] = ["Και τα δύο πεδία πρέπει να συμπληρωθούν"];
    } else {
        $checkUserQuery = "SELECT user_id, role, password FROM users WHERE username = ?";
        $checkUserStmt = $conn->prepare($checkUserQuery);
        $checkUserStmt->bind_param("s", $username);
        $checkUserStmt->execute();
        $checkUserResult = $checkUserStmt->get_result();
 

        if ($checkUserResult->num_rows > 0) {
            $userData = $checkUserResult->fetch_assoc();
            $hashedPassword = $userData['password'];

            // Verify the entered password against the hashed password
        //   if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $userData['user_id'];
                $_SESSION['user_role'] = $userData['role'];

                switch ($_SESSION['user_role']) {
                    case 'admin':
                        header("Location: admin/php_html/admin.php");
                        break;
                    case 'rescuer':
                        header("Location: rescuer/php_html/rescuer.php");
                        break;
                    case 'civilian':
                        header("Location: civilian/php_html/mainpagecitizen.php");
                        break;
                    default:
                }
                exit();
         //  } else {
           //     $_SESSION['login_errors'] = ["Μη έγκυρος χρήστης. Ξανά προσπάθησε!"];
          //  }
        } else {
            $_SESSION['login_errors'] = ["Μη έγκυρος χρήστης. Ξανά προσπάθησε!"];
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (!empty($_SESSION['login_errors'])) {
    echo '<div style="color: #ff0000; background-color: #ffebee; padding: 10px; border: 1px solid #ff0000; border-radius: 5px; margin-bottom: 10px;">';
    foreach ($_SESSION['login_errors'] as $error) {
        echo '<div style="margin-bottom: 5px;">' . $error . '</div>';
    }
    echo '</div>';
}

unset($_SESSION['login_errors']);
$conn->close();
?>
  <form id="login-form" action=""method="post">
    <input type="text" placeholder="Username" name="username" id="username">
    <input type="password" placeholder="Κωδικός" name="password" id="password">
    <p class="error-message" id="error-message"></p>
    <p>Δεν έχετε λογαριασμό? <a href="civilian/php_html/registration.php">Δημιουργήστε έναν!</a></p>
    <div class="button-container">
      <input type="submit" id="btn" value="Σύνδεση" name="submit" >
    </div>
  </form>
</body>
</html>

</body>
</html>










