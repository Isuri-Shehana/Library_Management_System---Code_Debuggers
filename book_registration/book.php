<?php
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    

    <title>Books Registration</title>

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
        <h1 style="color: #005f69;"><center>Books Registration</center></h1><br>
        <?php
if (isset($_SESSION['message'])):
?>
    <div style="display:flex; top:30px;" class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php
unset($_SESSION['message']); // Remove the message from session after displaying it
endif;
?>

        <div class="container">
            <div style="margin-bottom:5em;">
                <table id="tbl" class="table table-hover dt-responsive" style="width: 100%;background-color: white;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Book Id</th>
                            <th>Book Name</th>
                            <th>Category Name</th>
                            <th style="padding-left: 50px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT b.book_id, b.book_name, bc.category_name FROM book b INNER JOIN bookcategory bc ON b.category_id = bc.category_id";
                        $result = $database->query($sql);

                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                        ?>
                                <tr>
                                    <td><?= $row['book_id']; ?></td>
                                    <td><?= $row['book_name']; ?></td>
                                    <td><?= $row['category_name']; ?></td>
                                    <td>
                                        <a href="book.php?edit=<?= $row['book_id']; ?>"><button class="btn btn-success">Edit</button></a>
                                        <a href="book.php?delete=<?= $row['book_id']; ?>" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php
                            endwhile;
                        else:
                            echo "0 results";
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Update and Add Form -->
            <div>
                <div>
                    <?php if ($update == true): ?>
                        <h4 style="color: #005f69;">Edit Details</h4>
                    <?php else: ?>
                        <h4 style="color: #005f69;">Insert Details</h4>
                    <?php endif; ?>
                </div>

                <div>
                    <form action="process.php" method="POST">
                        <input type="hidden" id="book_id" name="book_id" value="<?= $book_id; ?>">
                        <div class="form-group row">
                            <label for="book_id" class="col-sm-2 col-form-label"><strong>Book Id</strong> (e.g., B001)</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="book_id" name="book_id" placeholder="Enter the Book id" style="width: 300px;" value="<?= $book_id; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="book_name" class="col-sm-2 col-form-label"><strong>Book Name</strong></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="book_name" name="book_name" placeholder="Enter the Name" style="width: 300px;" value="<?= $book_name; ?>" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="book_category" class="col-sm-2 col-form-label"><strong>Book Category</strong></label>
                            <div class="col-sm-10">
                                <select class="form-control" id="category_id" name="book_category" style="width: 300px;" required>
                                    <option value="C001" <?= ($category_id == 'C001') ? 'selected' : ''; ?>>Sci-fi</option>
                                    <option value="C002" <?= ($category_id == 'C002') ? 'selected' : ''; ?>>Adventure</option>
                                    <option value="C003" <?= ($category_id == 'C003') ? 'selected' : ''; ?>>Horror</option>
                                    <option value="C004" <?= ($category_id == 'C004') ? 'selected' : ''; ?>>Fantasy</option>

                                </select>
                            </div>
                        </div>
                        <div>
                            <?php if ($update): ?>
                                <input type="hidden" name="edit_id" value="<?= $book_id; ?>">
                                <button type="submit" class="btn btn-primary" name="update">Edit</button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-primary" name="save">Save</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    $database->close(); // Close the database connection here
    ?>
</body>

</html>
