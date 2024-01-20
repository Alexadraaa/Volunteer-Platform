<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Καλωσήρθατε στην ιστοσελίδα μας!</title>
<link rel="shortcut icon" href="#">
<link rel="stylesheet" type="text/css" href="..\css\initialpage.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <style>
    .error-message {
      color: red;
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
        $checkUserQuery = "SELECT user_id, role FROM users WHERE username = ? AND password = ?";
        $checkUserStmt = $conn->prepare($checkUserQuery);
        $checkUserStmt->bind_param("ss", $username, $password);
        $checkUserStmt->execute();
        $checkUserResult = $checkUserStmt->get_result();

        if ($checkUserResult->num_rows > 0) {
            $userData = $checkUserResult->fetch_assoc();
            $_SESSION['user_id'] = $userData['user_id'];
            $_SESSION['user_role'] = $userData['role'];

       
            switch ($_SESSION['user_role']) {
                case 'admin':
                    header("Location: admin.php");
                    break;
                case 'rescuer':
                    header("Location: rescuer.php");
                    break;
                case 'civilian':
                    header("Location: mainpagecitizen.php");
                    break;
                default:
               
  
            }
            exit();
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
    <input type="password" placeholder="Password" name="password" id="password">
    <p class="error-message" id="error-message"></p>
    <p>Δεν έχετε λογαριασμό? <a href="registration.php">Δημιουργήστε έναν!</a></p>
    <div class="button-container">
      <input type="submit" id="btn" value="Login" name="submit" >
    </div>
  </form>
</body>
</html>

</body>
</html>










