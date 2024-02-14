<?php
session_start();

// Include CSS for styling
echo '<link rel="stylesheet" href="style.css">';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    
</head>
<body>
    
    <div class="topnav">
        <a href="../dashboard/dashboard.php">Home</a>
        <a href="../user_registration/user.php">Users</a>
        <a href="../book_registration/book.php">Books</a>
        <a href="../book_category/book_category.php">Book Categories</a>
        <a href="#contact">Library Members</a>
        <a href="../book_borrow/book_borrow.php">Book Borrows</a>
        <div class="topnav-right">
          <a href="#" onclick="return confirmLogout()">Logout</a>
          <a href="#about">About</a>
        </div>
    </div>
    <div class="dash_container">
        <h1>Library Management System</h1>
        <p>
        <div id="bookList">
            <!-- Book list will be populated dynamically using JavaScript -->
        </div>
        <div class="buttons">
            <button id="registerBookBtn"><a href="../user_registration/user.php">User Registation</button></a>
            <button id="registerBookBtn"><a href="../book_registration/book.php">Book Registation</button></a> 
            <button id="categorizeBookBtn"><a href="../book_category/book_category.php">Book Category Registration </button></a>
            <button id="registerMemberBtn"><a href="#"></a>Member Register </button>
            <button id="borrowBookBtn"><a href="../book_borrow/book_borrow.php">Book Borrow </button></a>
        </div>
    </div>
    <script>
        function confirmLogout() {
            if (confirm('Are you sure you want to log out?')) {
                window.location.href = '../index.php';
            }
            return false;
        }
    </script>
</body>
</html>