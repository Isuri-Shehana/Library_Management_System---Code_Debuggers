<?php
date_default_timezone_set('Asia/Colombo'); // Set Sri Lankan timezone
require_once('../config.php');
session_start();

$update = false;
$category_id = "";
$category_Name = "";
$date_modified = date("Y-m-d H:i:s");

// Check if the 'save' button is clicked

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save'])) {
        $category_id = $_POST['category_id'];
        $category_Name = $_POST['category_Name'];

        // Check if category_id follows the 'C<category id>' format
        if (preg_match('/^C\d+$/', $category_id)) {
            // Valid category_id format, proceed with insertion

            // Insert data into the database
            $stmt = $database->prepare("INSERT INTO bookcategory (category_id, category_Name, date_modified) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $category_id, $category_Name, date("Y-m-d H:i:s"));

            if ($stmt->execute()) {
                $_SESSION['message'] = "Record has been saved!";
                $_SESSION['msg_type'] = "success";
                $stmt->close();
                $database->close();
                header("Location: book_category.php");
                exit();
            } else {
                $_SESSION['message'] = "Error in saving the record: " . $stmt->error;
                $_SESSION['msg_type'] = "danger";
                $stmt->close();
                header("Location: book_category.php");
                exit();
            }
        } else {
            // Invalid category_id format
            $_SESSION['message'] = "Category ID should be in the format 'C<category id>' (e.g., C001).";
            $_SESSION['msg_type'] = "danger";
            header("Location: book_category.php");
            exit();
        }
    }

    // Update functionality

    if (isset($_POST['update'])) {
        $category_id = $_POST['category_id'];
        $category_Name = $_POST['category_Name'];

        // Set the date_modified to the current timestamp
        $date_modified = date("Y-m-d H:i:s");


        if (preg_match('/^C\d+$/', $category_id)) {
            // Using prepared statements to prevent SQL injection
            $stmt = $database->prepare("UPDATE bookcategory SET category_Name=?, date_modified=? WHERE category_id=?");
            $stmt->bind_param("sss", $category_Name, $date_modified, $category_id);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Record has been updated!";
                $_SESSION['msg_type'] = "warning";
                $stmt->close();
                header("Location: book_category.php");
                exit();
            } else {
                echo "Error: " . $database->error;
            }
        } else {
            // Invalid category_id format
            $_SESSION['message'] = "Category ID should be in the format 'C<category id>' (e.g., C001).";
            $_SESSION['msg_type'] = "danger";
            header("Location: book_category.php");
            exit();
        }
    }
}

// delete functionality

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Delete related records in bookborrower table first
    $stmt_bookborrower = $database->prepare("DELETE FROM bookborrower WHERE book_id IN (SELECT book_id FROM book WHERE category_id = ?)");
    $stmt_bookborrower->bind_param("s", $id);
    $stmt_bookborrower->execute();
    $stmt_bookborrower->close();

    // Delete related records in fine table
    $stmt_fine = $database->prepare("DELETE FROM fine WHERE book_id IN (SELECT book_id FROM book WHERE category_id = ?)");
    $stmt_fine->bind_param("s", $id);
    $stmt_fine->execute();
    $stmt_fine->close();

    $stmt = $database->prepare("DELETE FROM bookcategory WHERE category_id = ?");
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Record has been deleted!";
        $_SESSION['msg_type'] = "danger";
    } else {
        $_SESSION['message'] = "Error in deleting the record: " . $stmt->error;
        $_SESSION['msg_type'] = "danger";
    }

    $stmt->close();
    header("Location: book_category.php");
    exit();
}

// edit functionality

if (isset($_GET['edit'])) {
    $u_id = $_GET['edit'];
    $update = true;

    $stmt = $database->prepare("SELECT * FROM bookcategory WHERE category_id = ?");
    $stmt->bind_param("s", $u_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $category_id = $row['category_id'];
        $category_Name = $row['category_Name'];
        $date_modified = $row['date_modified'];
    }
    $stmt->close();
}
?>