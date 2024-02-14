<?php
session_start();
require_once("config.php");

// Include CSS for styling
echo '<link rel="stylesheet" href="styles.css">';

// Placeholder for user authentication function
function authenticateUser($mysqli, $username, $password) {
    $username = $mysqli->real_escape_string($username);
    $password = $mysqli->real_escape_string($password);

    $result = $mysqli->query("SELECT * FROM user WHERE username='$username' AND password='$password'");

    // Check if there is a matching user
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// Login form processing logic
if (isset($_POST['loginUsername']) && isset($_POST['loginPassword'])) {
    $username = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    // Perform login validation and authentication
    if (authenticateUser($database, $username, $password)) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $username;
        header('Location: dashboard/dashboard.php');  // Redirect to dashboard after successful login
        exit;
    } else {
        // Set an error message in the session
        $_SESSION['loginError'] = "Invalid username or password.";
        // Redirect to the login page to avoid resubmitting the form on page refresh
        header('Location: index.php');
        exit;
    }
}

if (isset($_SESSION['loginError'])) {
    echo '<script>alert("' . $_SESSION['loginError'] . '");</script>';
    // Clear the login error from the session
    unset($_SESSION['loginError']);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
</head>
<body>

<div class="container">

    <div class="login-form">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="loginUsername">Username:</label>
            <input type="text" name="loginUsername" required placeholder="username">

            <label for="loginPassword">Password:</label>
            <input type="password" name="loginPassword" required placeholder="********">

            <button type="submit">Login</button>
        </form>
    </div>

    <div class="registration-form" style="display: none;">
        <form action="user_registration/process.php" method="post" onsubmit="return validateRegistrationForm();">
            <label for="user_id">User ID: <small>(e.g., U001)</small></label>
            <input type="text" name="user_id" id="user_id" required placeholder="U***">

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required placeholder="user@example.com">
            
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" required placeholder="first name">
            
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" required placeholder="last Name">
            
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required placeholder="username">
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" minlength="8" required placeholder="********">
            
            
            <button type="submit" name="register">Register</button>
        </form>
    </div>

    <div class="toggle-forms">
        <span>Don't have an account? <a href="#" onclick="toggleForms()">Sign Up</a></span>
    </div>

    
</div>

<script>
    function toggleForms() {
        var loginForm = document.querySelector('.login-form');
        var registrationForm = document.querySelector('.registration-form');

        // Toggle visibility of forms
        loginForm.style.display = loginForm.style.display === 'none' ? 'block' : 'none';
        registrationForm.style.display = registrationForm.style.display === 'none' ? 'block' : 'none';
    }

</script>

<script>
        function validateRegistrationForm() {
            var user_id = document.getElementById('user_id').value;
            var email = document.getElementById('email').value;

            // Regular expression for 'U<BOOK_ID>' format
            var user_idRegex = /^U\d{3}$/;

            // Regular expression for a valid email format
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!user_id.match(user_idRegex)) {
                alert("User ID should be in the 'U<BOOK_ID>' format (e.g., U001).");
                return false;
            }

            // if (password.length < 8) {
            //     alert("Password must be at least 8 characters long.");
            //     return false;
            // }

            if (!email.match(emailRegex)) {
                alert("Invalid email format.");
                return false;
            }

            return true;
        }
    </script>

</body>
</html>
