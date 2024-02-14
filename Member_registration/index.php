<?php require_once "process.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Registration</title>
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
            text-shadow: 2px 2px 5px blue;
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
            padding: 15px 25px;
            background-color: #2e4382;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            align: center;
        }
        
        form button:hover {
            background-color: #2e4382;
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
            background-color: #6c7ba7;
            color: #fff;
        }
        
        tr:hover {
            background-color: #f2f2f2;
        }

        .alert {
            padding: 20px;
            background-color: #6c7ba7;
            color: white;
            border-radius: 8px;
            margin-bottom: 20px;
        }
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

        .alert.success { background-color: #6c7ba7; }
        .alert.error { background-color: #6c7ba7; }
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

    <h2>Member Registration</h2>
    <form method='post' action="process.php?create=true">
    <label for="member_id">Member ID:</label>
    <input type="text" name="member_id" id="member_id" required>

    <label for="first_name">First Name:</label>
    <input type="text" name="first_name" id="first_name" required>

    <label for="last_name">Last Name:</label>
    <input type="text" name="last_name" id="last_name" required>

    <label for="birthday">Birthday:</label>
    <input type="date" name="birthday" id="birthday" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
        
        <button type="submit">Register</button>
    </form>

    <?php
    $sql = "SELECT * FROM member";

    $result = $database->query($sql) or die($database->error);
    ?>
    
    <h2>Member</h2>
    <table>
        <thead>
        <tr>
            <th>Member ID</th>
            <th>First Name:</th>
            <th>Last Name:</th>
            <th>Birthday:</th>
            <th>Email:</th>
            <th>Actions</th>
        </tr>
        <?php if ($result->num_rows > 1) { ?>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['member_id'] ?></td>
                    <td><?= $row['first_name'] ?></td>
                    <td><?= $row['last_name'] ?></td>
                    <td><?= $row['birthday'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td style="display: flex; gap: 10px; font-weight: bold">
                        <a href="edit.php?member_id=<?= $row['member_id'] ?>" class="btn btn-success">UPDATE</a>
                        <a href="process.php?delete=true&member_id=<?= $row['member_id'] ?>" class="btn btn-danger">DELETE</a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="7" style="text-align: center">No records are available!</td>
            </tr>
        <?php } ?>
        </thead>
        <tbody>
        </tbody>
    </table>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</html>
