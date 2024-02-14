<?php
require_once('process.php');
require_once('config.php');
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

    <title>Book Borrow Details</title>

    <style>
        .topnav {
            overflow: hidden;
            background-color: #333;
        }

        .topnav a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .topnav .active {
            background-color: #4CAF50;
            color: white;
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
        <a class="active" href="https://ekel.kln.ac.lk/login/index.php">LMS</a>
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
            <center>Book Borrow Details</center>
        </h1><br>
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

        <!-- Display table for book borrow details -->

        <table id="tbl" class="table table-hover dt-responsive" style="width: 100%; background-color: white;">
            <thead class="thead-dark">
                <tr>
                    <th>Borrow ID</th>
                    <th>Book ID</th>
                    <th>Member ID</th>
                    <th>Borrow Status</th>
                    <th>Date Modified</th>
                    <th style="padding-left: 50px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM bookborrower";
                $result = $database->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $row['borrow_id']; ?></td>
                            <td><?php echo $row['book_id']; ?></td>
                            <td><?php echo $row['member_id']; ?></td>
                            <td><?php echo $row['borrow_status']; ?></td>
                            <td><?php echo $row['borrower_date_modified']; ?></td>
                            <td>
                                <a href="book_borrow.php?edit=<?php echo $row['borrow_id']; ?>"><button class="btn btn-success">Edit</button></a>
                                <a href="book_borrow.php?delete=<?php echo $row['borrow_id']; ?>" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    echo "0 results";
                }
                $database->close();
                ?>
            </tbody>
        </table>
    </div>
<div>
    <div>
        <?php
        if ($update == true):
        ?>
            <h4 style="color: #005f69;">Edit Borrow Details</h4>
        <?php else: ?>
            
        <?php endif; ?>
    </div>

    <div>
        <div class="container" style="margin-top: 40px;">
        <h4 style="color: #005f69;">Insert Borrow Details</h4>
            <form action="process.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <input type="hidden" id="borrow_id" name="borrow_id" value="<?php echo $borrow_id; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="borrow_id" class="col-sm-2 col-form-label"><strong>Borrow ID</strong> (e.g., BR001)</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="borrow_id" name="borrow_id" placeholder="Enter Borrow ID" style="width: 300px;" value="<?php echo $borrow_id; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="book_id" class="col-sm-2 col-form-label"><strong>Book ID</strong> (e.g., B001)</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="book_id" name="book_id" placeholder="Enter Book ID" style="width: 300px;" value="<?php echo $book_id; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="member_id" class="col-sm-2 col-form-label"><strong>Member ID</strong> (e.g., M001)</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="member_id" name="member_id" placeholder="Enter Member ID" style="width: 300px;" value="<?php echo $member_id; ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="borrow_status" class="col-sm-2 col-form-label"><strong>Borrow Status</strong></label>
                    <div class="col-sm-10">
                        <select class="form-control" id="borrow_status" name="borrow_status" style="width: 300px;" required>
                            <option value="borrowed" <?php if ($borrow_status == 'borrowed') echo 'selected'; ?>>Borrowed</option>
                            <option value="available" <?php if ($borrow_status == 'available') echo 'selected'; ?>>Available</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="borrower_date_modified" class="col-sm-2 col-form-label"><strong>Date Modified</strong></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="borrower_date_modified" name="borrower_date_modified" placeholder="Date Modified" style="width: 300px;" value="<?php echo $borrower_date_modified; ?>" readonly>
                    </div>
                </div>
                <div>
            <?php
            if ($update == true):
            ?>
                <button type="submit" class="btn btn-primary" name="update_borrow">Edit</button>
            <?php else: ?>
                <button type="submit" class="btn btn-primary" name="save_borrow">Save</button>
            <?php endif; ?>
        </div>
        </div>
        
        </form>
      </div>




</body>

</html>