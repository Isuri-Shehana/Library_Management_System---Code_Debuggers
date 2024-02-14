<?php
// Check if a session is already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../config.php');

// Initialize variables
$user_id = "";
$first_name = "";
$last_name = "";
$username = "";
$password = "";
$email = "";
$update = false;

// Check if the registration form is submitted
if (isset($_POST['register'])) {
    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $database->prepare("INSERT INTO user (user_id, email, first_name, last_name, username, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $user_id, $email, $first_name, $last_name, $username, $password);

    // Check if the statement executed successfully
    if ($stmt->execute()) {
        echo "Registration successful!";

        // Redirect to user.php after successful registration
        header("Location: ../dashboard/dashboard.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

//delete user details
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Use prepared statement to prevent SQL injection
    $stmt = $database->prepare("DELETE FROM user WHERE user_id = ?");
    $stmt->bind_param("s", $id);

    // Check if the statement executed successfully
    if ($stmt->execute()) {
        $_SESSION['message'] = "Record has been deleted!";
        $_SESSION['msg_type'] = "danger";
        header("Location: user.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

//edit user details
if (isset($_GET['edit'])) {
    $u_id = $_GET['edit'];
    $update = true;

    // Use prepared statement to prevent SQL injection
    $stmt = $database->prepare("SELECT * FROM user WHERE user_id = ?");
    $stmt->bind_param("s", $u_id);

    // Check if the statement executed successfully
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if there is a matching user
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user_id = $row['user_id'];
            $email = $row['email'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $username = $row['username'];
            $password = $row['password'];
        } else {
            echo "No matching user found.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

//update user details
if (isset($_POST['update'])) {

    $user_id = $_POST['user_id'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    //check if the user if matches the format<U001>
    if(preg_match('/^U\d{3}$/',$user_id)){
        $sql = "UPDATE user SET email='$email', first_name='$first_name', last_name='$last_name', username='$username', password='$password' WHERE user_id = '$user_id'";

        
        $database->query($sql) or die($database->error);

    
        $_SESSION['message'] = "Record has been Updated!";
        $_SESSION['msg_type'] = "warning";
        header("Location: user.php");
    } else{
         // Invalid user_id format
         $_SESSION['message'] = "User ID should be in the 'U<USER_ID>' format (e.g., U001).";
         $_SESSION['msg_type'] = "danger";
         header("Location: user.php");
         exit();

    }
}





?>