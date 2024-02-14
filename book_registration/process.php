<?php
require_once('../config.php');
session_start();

$update = false;
$book_id = "";
$book_name = "";
$category_id = "";

// Function to validate the Book ID format
function isValidBookID($book_id)
{
    // Use a regular expression to check if the Book ID follows the format 'B<BOOK_ID>'
    return preg_match("/^B\d{3}$/", $book_id) === 1;
}

function isCategoryValid($database, $category_id)
{
    $check_category_stmt = $database->prepare("SELECT category_id FROM bookcategory WHERE category_id = ?");
    $check_category_stmt->bind_param("s", $category_id);
    $check_category_stmt->execute();
    $result = $check_category_stmt->get_result();

    return $result->num_rows == 1;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['save'])) {
        $book_id = $_POST['book_id'];
        $book_name = $_POST['book_name'];
        $category_id = $_POST['book_category'];

        // Validate the Book ID format
        if (!isValidBookID($book_id)) {
            $_SESSION['message'] = "Invalid Book ID format. Please use 'B001' format.";
            $_SESSION['msg_type'] = "danger";
            header("Location: book.php");
            exit();
        }

        // Check if the book ID already exists
        $check_existing_stmt = $database->prepare("SELECT * FROM book WHERE book_id = ?");
        $check_existing_stmt->bind_param("s", $book_id);
        $check_existing_stmt->execute();
        $existing_result = $check_existing_stmt->get_result();

        if ($existing_result->num_rows > 0) {
            // Book ID already exists, show error message
            $_SESSION['message'] = "Book ID already in use. Please choose a different Book ID.";
            $_SESSION['msg_type'] = "danger";
            $check_existing_stmt->close();
            header("Location: book.php");
            exit();
        }

        // Check if the selected category_id exists in the bookcategory table
        if (isCategoryValid($database, $category_id)) {
            // Valid category_id, proceed with insertion

            // Perform the insertion into the database
            $insert_stmt = $database->prepare("INSERT INTO book (book_id, book_name, category_id) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("sss", $book_id, $book_name, $category_id);

            if ($insert_stmt->execute()) {
                $_SESSION['message'] = "Record has been saved!";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "Error in saving the record: " . $insert_stmt->error;
                $_SESSION['msg_type'] = "danger";
            }

            $insert_stmt->close();
        } else {
            // Invalid category_id
            $_SESSION['message'] = "Invalid Category ID selected.";
            $_SESSION['msg_type'] = "danger";
        }

        header("Location: book.php");
        exit();
    }

// Handle update functionality
// Handle update functionality
if (isset($_POST['update'])) {
    $book_id = $_POST['edit_id'];
    $new_book_id = $_POST['book_id'];
    $book_name = $_POST['book_name'];
    $category_id = $_POST['book_category'];

    // Validate the new Book ID format
    if (!isValidBookID($new_book_id)) {
        $_SESSION['message'] = "Invalid Book ID format. Please use 'B001' format.";
        $_SESSION['msg_type'] = "danger";
        header("Location: book.php");
        exit();
    }

    // Check if the selected category_id exists in bookcategory table
    if (isCategoryValid($database, $category_id)) {
        // Check if the new book ID already exists
        $check_existing_stmt = $database->prepare("SELECT * FROM book WHERE book_id = ? AND book_id != ?");
        $check_existing_stmt->bind_param("ss", $new_book_id, $book_id);
        $check_existing_stmt->execute();
        $existing_result = $check_existing_stmt->get_result();

        if ($existing_result->num_rows > 0) {
            // New Book ID already exists, show error message
            $_SESSION['message'] = "Cannot update book ID. The ID '$new_book_id' is already in use by another book.";
            $_SESSION['msg_type'] = "danger";
        } else {
            // New Book ID doesn't exist, proceed with update
            $update_stmt = $database->prepare("UPDATE book SET book_id=?, book_name=?, category_id=? WHERE book_id=?");
            $update_stmt->bind_param("ssss", $new_book_id, $book_name, $category_id, $book_id);

            if ($update_stmt->execute()) {
                $_SESSION['message'] = "Record has been updated!";
                $_SESSION['msg_type'] = "warning";
            } else {
                $_SESSION['message'] = "Error in updating the record: " . $update_stmt->error;
                $_SESSION['msg_type'] = "danger";
            }

            $update_stmt->close();
        }
        $check_existing_stmt->close();
    } else {
        // Invalid category_id
        $_SESSION['message'] = "Invalid Category ID selected.";
        $_SESSION['msg_type'] = "danger";
    }

    header("Location: book.php");
    exit();
}

}

// Handle delete functionality
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Using prepared statements to prevent SQL injection
    // First, delete associated records from dependent tables
    $delete_dependent_stmt_fine = $database->prepare("DELETE FROM fine WHERE book_id = ?");
    $delete_dependent_stmt_fine->bind_param("s", $id);

    if ($delete_dependent_stmt_fine->execute()) {
        $delete_dependent_stmt_borrower = $database->prepare("DELETE FROM bookborrower WHERE book_id = ?");
        $delete_dependent_stmt_borrower->bind_param("s", $id);

        if ($delete_dependent_stmt_borrower->execute()) {
            // After deleting associated records, delete the record from the book table
            $delete_stmt = $database->prepare("DELETE FROM book WHERE book_id = ?");
            $delete_stmt->bind_param("s", $id);

            if ($delete_stmt->execute()) {
                $_SESSION['message'] = "Record has been deleted!";
                $_SESSION['msg_type'] = "danger";
            } else {
                $_SESSION['message'] = "Error in deleting the record: " . $delete_stmt->error;
                $_SESSION['msg_type'] = "danger";
            }

            $delete_stmt->close();
        } else {
            $_SESSION['message'] = "Error in deleting associated records (bookborrower): " . $delete_dependent_stmt_borrower->error;
            $_SESSION['msg_type'] = "danger";
        }

        $delete_dependent_stmt_borrower->close();
    } else {
        $_SESSION['message'] = "Error in deleting associated records (fine): " . $delete_dependent_stmt_fine->error;
        $_SESSION['msg_type'] = "danger";
    }

    $delete_dependent_stmt_fine->close();
    header("Location: book.php");
    exit();
}

// Handle edit functionality
if (isset($_GET['edit'])) {
    $u_id = $_GET['edit'];
    $update = true;

    // Using prepared statements to prevent SQL injection
    $edit_stmt = $database->prepare("SELECT * FROM book WHERE book_id = ?");
    $edit_stmt->bind_param("s", $u_id);
    $edit_stmt->execute();
    $result = $edit_stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $book_id = $row['book_id'];
        $book_name = $row['book_name'];
        $category_id = $row['category_id'];
    }

    $edit_stmt->close();
}
?>

