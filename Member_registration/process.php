<?php

require_once "../config.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['create'])) {
    $member_id = $_POST['member_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];

    // Prepare an SQL statement with parameterized query
    $sql = "INSERT INTO member (member_id, first_name, last_name, birthday, email) VALUES (?, ?, ?, ?, ?)";

    try {
        $stmt = $database->prepare($sql);
        $stmt->bind_param("sssss", $member_id, $first_name, $last_name, $birthday, $email);
        $stmt->execute();

        $_SESSION['message'] = "User added successfully.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    header("Location: index.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['update'])) {
    $member_id = $_POST['member_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];

    // Prepare an SQL statement with parameterized query
    $sql = "UPDATE member SET first_name = ?, last_name = ?, birthday = ?, email = ? WHERE member_id = ?";

    try {
        $stmt = $database->prepare($sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $birthday, $email, $member_id);
        $stmt->execute();

        $_SESSION['message'] = "User updated successfully.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    header("Location: index.php");
}

if (isset($_GET['delete'])) {
    $member_id = $_GET['member_id'];

    // Prepare an SQL statement with parameterized query
    $sql = "DELETE FROM member WHERE member_id = ?";

    try {
        $stmt = $database->prepare($sql);
        $stmt->bind_param("s", $member_id);
        $stmt->execute();

        $_SESSION['message'] = "User deleted successfully.";
        $_SESSION['message_type'] = "success";
    } catch (Exception $e) {
        $_SESSION['message'] = $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }

    header("Location: index.php");
}
?>
