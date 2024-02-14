<?php require_once "process.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member Registration</title>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        * {
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 2rem 10rem;
            background-color: #f4f4f4;
            background-image: url('—Pngtree—library simple book reading_5592732.png');
        }

        h2 {
            margin: 20px 0 !important;
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 10px;
        }

        form input[type="text"],
        form input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        form button {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        form button {
            padding: 20px 40px;
            background-color: #2e4382;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            align: center;
        }
        
        form button:hover {
            background-color: #2e4382;}
    .topnav {
    background-color: #4d4d4d;
    overflow: hidden;
    border-bottom: #913c3c;
}

        /* Style the links inside the navigation bar */
.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

/* Change the color of links on hover */
.topnav a:hover {
  background-color: #88a4d8;
  color: black;
}

/* Add a color to the active/current link */
.topnav a.active {
  background-color: #6e6f75;
  color: rgb(255, 255, 255);
}

/* Right-aligned section inside the top navigation */
.topnav-right {
  float: right;
}


        .alert.success { background-color: #4CAF50; }
        .alert.error { background-color: #f44336; }
    </style>
</head>
<body>

<div class="topnav">
        <a class="active" href="https://ekel.kln.ac.lk/login/index.php">LMS</a>
        <a href="dashboad.html">Home</a>
        <a href="#news">Books</a>
        <a href="#contact">Member</a>
        <div class="topnav-right">
          <a href="#">Logout</a>
          <a href="#about">About</a>
        </div>
      </div><br>


<?php if (isset($_SESSION['message'])): ?>
    <div class="alert <?= $_SESSION['message_type']; ?>" role="alert">
        <?= $_SESSION['message']; ?>
    </div>

    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
<?php endif; ?>

<?php
$member_id = $_GET['member_id'];

$sql = "SELECT * FROM member WHERE member_id = '$member_id'";

$result = $database->query($sql) or die($database->error);

$row = $result->fetch_row();
?>

<h2>Update Member Registration</h2>
<form method='post' action="process.php?update=true">
    <label for="member_id">Member ID:</label>
    <input type="text" id="member_id" name="member_id" value="<?= $row[0]; ?>" required><br><br>

    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?= $row[1]; ?>" required><br><br>

    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?= $row[2]; ?>" required><br><br>

    <label for="birthday">Birthday:</label>
    <input type="date" name="birthday" id="birthday" value="<?= $row[3]; ?>" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= $row[4]; ?>" required><br><br>

    <button type="submit">Update</button>
</form>
</body>
</html>
