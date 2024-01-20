<?php
/*
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // extract data from the POST request
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];

    // ppdate user information in the database
    $sql = "UPDATE users SET name=?, lastname=?, address=?, phone=?, username=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $lastname, $address, $phone, $username, $user_id);

    if ($stmt->execute()) {
        // Handle success, e.g., send a success response
        echo json_encode(['success' => true]);
    } else {
        
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }

    $stmt->close();
}

$conn->close();*/

/* works
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // extract data from the POST request
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];

    // fetch existing user data from the database
    $sqlSelect = "SELECT name, lastname, address, phone, username FROM users WHERE user_id = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    $stmtSelect->bind_param("i", $user_id);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();

    if ($result->num_rows > 0) {
        $existingUserData = $result->fetch_assoc();

        // compare submitted data with existing data
        $name = ($name != $existingUserData['name']) ? $name : $existingUserData['name'];
        $lastname = ($lastname != $existingUserData['lastname']) ? $lastname : $existingUserData['lastname'];
        $address = ($address != $existingUserData['address']) ? $address : $existingUserData['address'];
        $phone = ($phone != $existingUserData['phone']) ? $phone : $existingUserData['phone'];
        $username = ($username != $existingUserData['username']) ? $username : $existingUserData['username'];

        // update user information in the database
        $sqlUpdate = "UPDATE users SET name=?, lastname=?, address=?, phone=?, username=? WHERE user_id=?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("sssssi", $name, $lastname, $address, $phone, $username, $user_id);

        if ($stmtUpdate->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error']);
        }

        $stmtUpdate->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'User not found']);
    }

    $stmtSelect->close();
}

$conn->close();
*/

/*
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // extract data from the POST request
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $newUsername = $_POST['username'];

    // check if the new username already exists
    $checkUsernameSql = "SELECT user_id FROM users WHERE username = ? AND user_id != ?";
    $checkUsernameStmt = $conn->prepare($checkUsernameSql);
    $checkUsernameStmt->bind_param("si", $newUsername, $user_id);
    $checkUsernameStmt->execute();
    $checkUsernameResult = $checkUsernameStmt->get_result();

    if ($checkUsernameResult->num_rows > 0) {
        // username already exists, send an error response
        console.log("Username" + $newUsername);
        echo json_encode(['success' => false, 'error' => 'Username already exists. Choose a different one.']);
    } else {
        // Username is unique, proceed with the update
        $updateSql = "UPDATE users SET name=?, lastname=?, address=?, phone=?, username=? WHERE user_id=?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sssssi", $name, $lastname, $address, $phone, $newUsername, $user_id);

        if ($updateStmt->execute()) {
    
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Database error']);
        }

        $updateStmt->close();
    }

    $checkUsernameStmt->close();
}

$conn->close();
*/
/* works fine 
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // extract data from the POST request
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $newUsername = $_POST['username'];

    // check if the new username already exists
    $checkUsernameSql = "SELECT user_id FROM users WHERE username = ? AND user_id != ?";
    $checkUsernameStmt = $conn->prepare($checkUsernameSql);
    $checkUsernameStmt->bind_param("si", $newUsername, $user_id);
    $checkUsernameStmt->execute();
    $checkUsernameResult = $checkUsernameStmt->get_result();

    if ($checkUsernameResult->num_rows > 0) {
        // username already exists, send an error response
        echo json_encode(['success' => false, 'error' => 'Username already exists. Choose a different one.']);
    } else {
        // update the username
        $updateUsernameSql = "UPDATE users SET username=? WHERE user_id=?";
        $updateUsernameStmt = $conn->prepare($updateUsernameSql);
        $updateUsernameStmt->bind_param("si", $newUsername, $user_id);

        if ($updateUsernameStmt->execute()) {
            // update other fields if the username is updated successfully
            $updateSql = "UPDATE users SET name=?, lastname=?, address=?, phone=? WHERE user_id=?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ssssi", $name, $lastname, $address, $phone, $user_id);

            if ($updateStmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error updating other fields.']);
            }

            $updateStmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Error updating username.']);
        }

        $updateUsernameStmt->close();
    }

    $checkUsernameStmt->close();
}

$conn->close();
*/

session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: initialpage.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // extract data from the POST request
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $newUsername = $_POST['username'];

    // check if the new username already exists
    $checkUsernameSql = "SELECT user_id FROM users WHERE username = ? AND user_id != ?";
    $checkUsernameStmt = $conn->prepare($checkUsernameSql);
    $checkUsernameStmt->bind_param("si", $newUsername, $user_id);
    $checkUsernameStmt->execute();
    $checkUsernameResult = $checkUsernameStmt->get_result();

    if ($checkUsernameResult->num_rows > 0) {
        // username already exists
        echo json_encode(['success' => false, 'error' => 'Username already exists.']);
    } else {
        // username is unique, proceed with updating all fields
        $updateAllSql = "UPDATE users SET name=?, lastname=?, address=?, phone=?, username=? WHERE user_id=?";
        $updateAllStmt = $conn->prepare($updateAllSql);
        $updateAllStmt->bind_param("sssssi", $name, $lastname, $address, $phone, $newUsername, $user_id);

        if ($updateAllStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error updating all fields.']);
        }

        $updateAllStmt->close();
    }

    $checkUsernameStmt->close();
}

$conn->close();
?>


