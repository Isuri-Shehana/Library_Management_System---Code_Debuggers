<?php

require_once('process.php');
require_once('../config.php');

// Include CSS for styling
echo '<link rel="stylesheet" href="styles.css">';
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>

    <title>User Registration</title>

    <style>
        .topnav {
            overflow: hidden;
            background-color: #333; /* Change this color as per your design */
        }

        .topnav a {
            float: left;
            display: block;
            color: #f2f2f2; /* Text color */
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .topnav a:hover {
            background-color: #ddd; /* Change this color as per your design */
            color: black; /* Text color on hover */
        }

        .topnav .active {
            background-color: #4CAF50; /* Change this color as per your design */
            color: white; /* Text color for active link */
        }

        .topnav-right {
            float: right;
        }

        @media screen and (max-width: 600px) {
            .topnav a, .topnav-right a {
                float: none;
                display: block;
                text-align: left;
            }
        }

    
    </style>

</head>

<body style="background-color: lightblue;">
    <!--Top nav bar-->
    <div class="topnav">
        <a href="../dashboard/dashboard.php">Home</a>
        <a href="../user_registration/user.php">Users</a>
        <a href="../book_registration/book.php">Books</a>
        <a href="../book_category/book_category.php">Book Categories</a>
        <a href="#contact">Library Members</a>
        <a href="#contact">Book Borrows</a>
        <div class="topnav-right">
          <a href="#" onclick="return confirmLogout()">Logout</a>
          <a href="#about">About</a>
        </div>
    </div>
    <br />
    <div class="container">
        <h1 style="color: #005f69;"><center>User Details</center></h1>
        <?php
        if (isset($_SESSION['message'])): ?>

            <div style="display:flex; top:30px;" class="alert alert-<?= $_SESSION['msg_type'] ?> fade show" role="alert">

                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                unset($_SESSION['msg_type']);

                ?>


                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!--view user details in a table-->
        <div class="container">

            <div style="margin-bottom:5em;">

                <table id="tbl" class="table table-hover dt-responsive" style="width: 100%;background-color: white;">
                    <thead class="thead-dark">

                        <tr>
                            <th>User ID</th>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Usename</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM user";
                        $result = $database->query($sql);

                        if ($result->num_rows > 0) {
                            
                            while ($row = $result->fetch_assoc()) {
                                ?>

                                <tr>
                                    <td>
                                        <?php echo $row['user_id']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['email']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['first_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['last_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['username']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['password']; ?>
                                    </td>

                                    <td>
                                        <a href="user.php?edit=<?php echo $row['user_id']; ?>"><button
                                        id="toggleFormBtn" class="btn btn-success">Edit</button></a>

                                        <a href="user.php?delete=<?php echo $row['user_id'] ?>" class="btn btn-danger btn-xl"
                                            style="display: inline !important;">Delete</a>
                                    </td>
                                </tr>
                            </tbody>

                        <?php
                            }
                        } else {
                            echo "0 results";
                        }
                        $database->close();
                        ?>


                </table>


            </div>


            <!--update user-->
            <div id="userForm">
                <div>


                    <?php
                    if ($update == true):
                        ?>
                        <h4 style="color: #005f69;">Edit User</h4>

                    <?php else: ?>
                        <h4 style="color: #005f69;">Edit User</h4>

                    <?php endif; ?>

                </div>
                <div>
                    <div class="container" style="margin-top: 40px;">
                        <form action="process.php" method="POST" >

                            <div class="form-group row">

                                <label for="user_id" class="col-sm-2 col-form-label">User Id</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="user_id" name="user_id"
                                        placeholder="user id" style="width: 300px;"
                                        value="<?php echo $user_id; ?>" Required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">

                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="email" style="width: 300px;"
                                        value="<?php echo $email; ?>" Required>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="first name" style="width: 300px;"
                                        value="<?php echo $first_name; ?>" Required>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        placeholder="last name" style="width: 300px;"
                                        value="<?php echo $last_name; ?>" Required>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label for="username" class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="username" name="username"
                                        placeholder="username" style="width: 300px;"
                                        value="<?php echo $username; ?>" Required>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="********" minlength="8" style="width: 300px;"
                                        value="<?php echo $password; ?>" Required>
                                </div>
                            </div>

                            

                            


                    </div>
                    <div>
                        <?php
                        if ($update == true):
                            ?>
                            <button type="submit" class="btn btn-primary" name="update" >Edit</button>
                            
 
                        

                        <?php endif; ?>

                    </div>

                    </form>

                </div>

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
