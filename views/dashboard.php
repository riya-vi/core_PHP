<?php
include '../config/dataBaseConnect.php';


session_start();
if (!isset($_SESSION['email']) && !isset($_SESSION['password'])) {
    echo "<script> alert('please login first') </script>";
    header("Location: login.php");
} else {
    echo "";
}
// session_destroy();



// Display Database Data in html table
$sql = "SELECT * FROM `users` ORDER BY `id` DESC";
$result = $connection->query($sql);

// $connection->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="./dashboardStyle.css">
</head>

<body>
    <h1>Dashboard</h1>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">about</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <a href="./logout.php"><button>Logout</button></a>

    <div>
        <?php
        session_start();
        if (isset($_SESSION["edit_message"])) {
            echo '<div class="alert alert-success">Record Updated Successfully ! </div>';
            unset($_SESSION["edit_message"]);
        } elseif (isset($_SESSION["delete_message"])) {
            echo '<div class="alert alert-success">Record Deleted Successfully ! </div>';
            unset($_SESSION["delete_message"]);
        } elseif (isset($_SESSION["add_message"])) {
            echo '<div class="alert alert-success">User Added Successfully ! </div>';
            unset($_SESSION["add_message"]);
        }
        ?>
    </div>


    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <!-- search bar -->
            <form class="d-flex" action="" method="get">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <a href="./addUser.php"><button>+ Add User</button></a>


        </div>
    </nav>

    <div class="table-wrapper">
        <table class="fl-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone NO.</th>
                    <th>Address</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>Pincode</th>
                    <!-- <th>Password</th> -->
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php
                if (isset($_GET['search'])) {
                    $searchResult = $_GET['search'];
                    $sql = "SELECT * FROM `users` WHERE CONCAT(first_name,last_name,email) like '%$searchResult%'";

                    $result = $connection->query($sql);

                    if ($result->num_rows > 0) {
                        while ($rows = $result->fetch_assoc()) 
                        {
                            ?>

                    <tr>
                        <td><?php echo $rows['id']; ?></td>
                        <td><?php echo $rows['first_name']; ?></td>
                        <td><?php echo $rows['last_name']; ?></td>
                        <td><?php echo $rows['email']; ?></td>
                        <td><?php echo $rows['phone_no']; ?></td>
                        <td><?php echo $rows['address']; ?></td>
                        <td><?php echo $rows['country']; ?></td>
                        <td><?php echo $rows['state']; ?></td>
                        <td><?php echo $rows['pincode']; ?></td>
                        <!-- <td><?php echo $rows['password']; ?></td> -->
                        <td>
                            <a href="./editUser.php?id=<?php echo $rows['id']; ?>"><button type="button" class="btn btn-outline-warning">Edit</button></a>
                            <a href="./deleteUser.php?id=<?php echo $rows['id']; ?>"><button type="button" class="btn btn-outline-danger" style="color: red;" onclick="return confirm('Are you sure you want to delete this record ?')">Delete</button></a>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                   else
                   {
                    ?>
                     <tr>
                        <td>No Record Found</td>
                     </tr>
                
                    
                <?php
                }
            }
                ?>
            <tbody>

        </table>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</html>