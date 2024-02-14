<?php


session_start();
include("../../connection.php");

$errors = [];

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../initialpage.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $newUsername = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // check if the new username already exists
    $checkUsername = "SELECT * FROM users WHERE username = ?";
    $checkUsernameStmt = $conn->prepare($checkUsername);
    $checkUsernameStmt->bind_param("s", $newUsername);
    $checkUsernameStmt->execute();
    $checkUsernameResult = $checkUsernameStmt->get_result();

    if ($checkUsernameResult->num_rows > 1) {
        array_push($errors, "Το όνομα χρήστη υπάρχει ήδη. Επιλέξτε διαφορετικό όνομα χρήστη.");
    }

    // check if passwords match
    if ($password != $confirmPassword) {
        array_push($errors, "Οι κωδικοί πρόσβασης δεν ταιριάζουν");
    }

    // check if phone number is valid
    if (!is_numeric($phone) || strlen($phone) != 10) {
        array_push($errors, "Εισαγάγετε έναν έγκυρο 10ψήφιο αριθμό τηλεφώνου");
    }

    // ff there are no errors, update user information in the database
    if (empty($errors)) {
        $updateAllSql = "UPDATE users SET name=?, lastname=?, address=?, phone=?, username=? WHERE user_id=?";
        $updateAllStmt = $conn->prepare($updateAllSql);
        $updateAllStmt->bind_param("sssssi", $name, $lastname, $address, $phone, $newUsername, $user_id);

        if ($updateAllStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Ανανέωση στοιχεία λογαριασμού.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error .']);
        }

        $updateAllStmt->close();
    } else {
        // ff there are errors, return them as JSON response
        echo json_encode(['success' => false, 'errors' => $errors]);
    }

    $checkUsernameStmt->close();
}

$conn->close();
?>


