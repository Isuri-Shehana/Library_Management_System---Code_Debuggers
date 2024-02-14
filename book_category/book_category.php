<?php
// Include the processing logic and database configuration
require_once('process.php');
require_once('../config.php');
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

    <title>Book category</title>

    <style>
        .topnav {
            overflow: hidden;
            background-color: #333;
            /* Change this color as per your design */
        }

        .topnav a {
            float: left;
            display: block;
            color: #f2f2f2;
            /* Text color */
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .topnav a:hover {
            background-color: #ddd;
            /* Change this color as per your design */
            color: black;
            /* Text color on hover */
        }

        .topnav .active {
            background-color: #4CAF50;
            /* Change this color as per your design */
            color: white;
            /* Text color for active link */
        }

        .topnav-right {
            float: right;
        }

        @media screen and (max-width: 600px) {

            .topnav a,
            .topnav-right a {
                float: none;
                display: block;
                text-align: left;
            }
        }
    </style>

</head>

<body style="background-color: lightblue;">

    <div class="topnav">
        <a href="../dashboard/dashboard.php">Home</a>
        <a href="../user_registration/user.php">Users</a>
        <a href="../book_registration/book.php">Books</a>
        <a href="../book_category/book_category.php">Book Categories</a>
        <a href="#contact">Library Members</a>
        <a href="#contact">Book Borrows</a>
        <div class="topnav-right">
            <a href="#">Logout</a>
            <a href="#about">About</a>
        </div>
    </div>
    <br />
    <div class="container">
        <h1 style="color: #005f69;">
            <center>Book Category Registration</center>
        </h1><br>

        <!-- Display success or error messages -->

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

        <!-- Display book category data in a table -->

        <div class="container">

            <div style="margin-bottom:5em;">

                <table id="tbl" class="table table-hover dt-responsive" style=" width: 100%;background-color: white;">
                    <thead class="thead-dark">

                        <tr>
                            <th>Category Id</th>
                            <th>Category Name</th>
                            <th>Date modified</th>

                            <th style="padding-left: 50px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display book category data from the database 
                        $sql = "SELECT * FROM bookcategory";
                        $result = $database->query($sql);

                        if ($result->num_rows > 0) {

                            while ($row = $result->fetch_assoc()) {
                                ?>

                                <tr>
                                    <td>
                                        <?php echo $row['category_id']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['category_Name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['date_modified']; ?>
                                    </td>

                                    <td>
                                        <a href="book_category.php?edit=<?php echo $row['category_id']; ?>"><button
                                                class="btn btn-success">Edit</button></a>

                                        <a href="book_category.php?delete=<?php echo $row['category_id']; ?>"
                                            class="btn btn-danger">Delete</a>

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

            <!-- Form for updating or adding book categories -->

            <div>
                <div>
                    <?php
                    if ($update == true):
                        ?>
                        <h4 style="color: #005f69;">Edit Categories</h4>

                    <?php else: ?>
                        <h4 style="color: #005f69;">Insert Categories</h4>

                    <?php endif; ?>

                </div>
                <!-- Book category form -->
                <div>
                    <div class="container" style="margin-top: 40px;">
                        <form action="process.php" method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <input type="hidden" id="category_id" name="category_id"
                                        value="<?php echo $category_id; ?>">

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="category_id" class="col-sm-2 col-form-label"><strong>Category Id</strong>
                                    (e.g., C001)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="category_id" name="category_id"
                                        placeholder="Enter the category id" style="width: 300px;"
                                        value="<?php echo $category_id; ?>" required>
                                </div>
                            </div>

                            <div class="form-group row">

                                <label for="category_Name" class="col-sm-2 col-form-label"><strong>Category
                                        Name</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="category_Name" name="category_Name"
                                        placeholder="Enter the Name" style="width: 300px;"
                                        value="<?php echo $category_Name; ?>" required>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date_modified" class="col-sm-2 col-form-label"><strong>Date
                                        Modified</strong></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="date_modified" name="date_modified"
                                        placeholder="Date Modified" style="width: 300px;"
                                        value="<?php echo $date_modified; ?>" readonly>
                                </div>
                            </div>

                    </div>

                    <!-- Save or Edit button based on the operation -->
                    <div>
                        <?php
                        if ($update == true):
                            ?>
                            <button type="submit" class="btn btn-primary" name="update">Edit</button>


                        <?php else: ?>
                            <button type="submit" class="btn btn-primary" name="save">Save</button>

                        <?php endif; ?>

                    </div>

                    </form>

                </div>

            </div>
        </div>


</body>

</html>
