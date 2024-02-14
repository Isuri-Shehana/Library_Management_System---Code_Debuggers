<?php
date_default_timezone_set('Asia/Colombo'); // Set Sri Lankan timezone
require_once('config.php');
session_start();

$update = false;
$borrow_id = "";
$book_id = "";
$member_id = "";
$borrow_status = "";
$borrower_date_modified = date("Y-m-d H:i:s");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save_borrow'])) {
        $borrow_id = $_POST['borrow_id'];
        $book_id = $_POST['book_id'];
        $member_id = $_POST['member_id'];
        $borrow_status = $_POST['borrow_status'];

        // Check if borrow_id, book_id, and member_id follow the required format
        if (
            preg_match('/^BR\d+$/', $borrow_id) &&
            preg_match('/^B\d+$/', $book_id) &&
            preg_match('/^M\d+$/', $member_id)
        ) {
            // Valid formats, proceed with insertion
            $stmt = $database->prepare("INSERT INTO bookborrower (borrow_id, book_id, member_id, borrow_status, borrower_date_modified) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $borrow_id, $book_id, $member_id, $borrow_status, $borrower_date_modified);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Borrow details have been saved!";
                $_SESSION['msg_type'] = "success";
                $stmt->close();
                $database->close();
                header("Location: book_borrow.php");
                exit();
            } else {
                $_SESSION['message'] = "Error in saving borrow details: " . $stmt->error;
                $_SESSION['msg_type'] = "danger";
                $stmt->close();
                header("Location: book_borrow.php");
                exit();
            }
        } else {
            // Invalid formats
            $_SESSION['message'] = "Invalid format for Borrow ID, Book ID, or Member ID.";
            $_SESSION['msg_type'] = "danger";
            header("Location: book_borrow.php");
            exit();
        }
    }

    // update functionality
    if (isset($_POST['update_borrow'])) {
        $new_borrow_id = $_POST['new_borrow_id']; // New borrow ID
        $book_id = $_POST['book_id'];
        $member_id = $_POST['member_id'];
        $borrow_status = $_POST['borrow_status'];

        // Set the borrower_date_modified to the current timestamp
        $borrower_date_modified = date("Y-m-d H:i:s");

        // Check if the new borrow_id, book_id, and member_id follow the required format
        if (
            preg_match('/^BR\d+$/', $new_borrow_id) &&
            preg_match('/^B\d+$/', $book_id) &&
            preg_match('/^M\d+$/', $member_id)
        ) {
            // Using prepared statements to prevent SQL injection
            $stmt = $database->prepare("UPDATE bookborrower SET borrow_id=?, book_id=?, member_id=?, borrow_status=?, borrower_date_modified=? WHERE borrow_id=?");
            $stmt->bind_param("ssssss", $new_borrow_id, $book_id, $member_id, $borrow_status, $borrower_date_modified, $borrow_id);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Borrow details have been updated!";
                $_SESSION['msg_type'] = "warning";
                $stmt->close();
                header("Location: book_borrow.php");
                exit();
            } else {
                $_SESSION['message'] = "Error: " . $stmt->error;
                $_SESSION['msg_type'] = "danger";
                header("Location: book_borrow.php");
                exit();
            }
        } else {
            // Invalid new borrow_id, book_id, or member_id format
            $_SESSION['message'] = "Invalid format for Borrow ID, Book ID, or Member ID.";
            $_SESSION['msg_type'] = "danger";
            header("Location: book_borrow.php");
            exit();
        }
    }
}

// delete functionality
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $database->prepare("DELETE FROM bookborrower WHERE borrow_id = ?");
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Borrow details have been deleted!";
        $_SESSION['msg_type'] = "danger";
    } else {
        $_SESSION['message'] = "Error in deleting borrow details: " . $stmt->error;
        $_SESSION['msg_type'] = "danger";
    }

    $stmt->close();
    header("Location: book_borrow.php");
    exit();
}

// edit functionality
if (isset($_GET['edit'])) {
    $u_id = $_GET['edit'];
    $update = true;

    $stmt = $database->prepare("SELECT * FROM bookborrower WHERE borrow_id = ?");
    $stmt->bind_param("s", $u_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $borrow_id = $row['borrow_id'];
        $book_id = $row['book_id'];
        $member_id = $row['member_id'];
        $borrow_status = $row['borrow_status'];
        $borrower_date_modified = $row['borrower_date_modified'];
    }
    $stmt->close();
}
?>