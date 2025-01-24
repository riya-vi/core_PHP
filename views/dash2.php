<?php
include '../config/dataBaseConnect.php';

session_start();
if (!isset($_SESSION['email']) && !isset($_SESSION['password'])) {
    echo "<script> alert('Please login first') </script>";
    header("Location: login.php");
    exit;
}

//pagination
$recordsPerPage = 5;

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$startFrom = ($page - 1) * $recordsPerPage;


$sql = "SELECT * FROM `users` LIMIT $startFrom, $recordsPerPage";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchResult = $_GET['search'];
    $sql = "SELECT * FROM `users` WHERE CONCAT(first_name, last_name, email) LIKE '%$searchResult%' LIMIT $startFrom, $recordsPerPage";
}

$result = $connection->query($sql);

$countSql = "SELECT COUNT(*) AS total FROM `users`";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $countSql = "SELECT COUNT(*) AS total FROM `users` WHERE CONCAT(first_name, last_name, email) LIKE '%$searchResult%'";
}
$countResult = $connection->query($countSql);
$countRow = $countResult->fetch_assoc();
$totalRecords = $countRow['total'];

$totalPages = ceil($totalRecords / $recordsPerPage);


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <link rel="stylesheet" href="./dashboardStyle.css">
</head>

<body>
    <h1>Dashboard</h1>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <a href="./logout.php"><button>Logout</button></a>

    <!-- Messages -->
    <div>
        <?php
        if (isset($_SESSION["edit_message"])) {
            echo '<div class="alert alert-success">Record Updated Successfully!</div>';
            unset($_SESSION["edit_message"]);
        } elseif (isset($_SESSION["delete_message"])) {
            echo '<div class="alert alert-success">Record Deleted Successfully!</div>';
            unset($_SESSION["delete_message"]);
        } elseif (isset($_SESSION["add_message"])) {
            echo '<div class="alert alert-success">User Added Successfully!</div>';
            unset($_SESSION["add_message"]);
        }
        ?>
    </div>

    <!-- Search & Add User -->
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <form class="d-flex" action="" method="get">
                <input class="form-control me-2" type="search" placeholder="Search" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            <a href="./addUser.php"><button>+ Add User</button></a>
        </div>
    </nav>


    <!-- Table -->
    <div class="table-wrapper">
        <table class="fl-table">
            <thead>
                <tr>
                    <th> <a href="dashboard.php?column=id&order=<?php echo $asc_or_desc; ?>">Id <i class="fas fa-sort<?php echo $column == 'id' ? '-' . $up_or_down : ''; ?>"></i></a></th>

                    <th> <a href="dashboard.php?column=first_name&order=<?php echo $asc_or_desc; ?>">First Name <i class="fas fa-sort<?php echo $column == 'first_name' ? '-' . $up_or_down : ''; ?>"></i></a></th>

                    <th> <a href="dashboard.php?column=last_name&order=<?php echo $asc_or_desc; ?>">Last Name <i class="fas fa-sort<?php echo $column == 'last_name' ? '-' . $up_or_down : ''; ?>"></i> </a></th>

                    <th> <a href="dashboard.php?column=email&order=<?php echo $asc_or_desc; ?>">Email <i class="fas fa-sort<?php echo $column == 'email' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                    <th>Phone NO.</th>
                    <th>Address</th>
                    <th>Country </th>
                    <th>State</th>
                    <th>Pincode</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($rows = $result->fetch_assoc()) {
                ?>
                        <tr>
                            <td <?php echo $column == 'id' ? $add_class : '' ?>><?php echo $rows['id']; ?></td>
                            <td <?php echo $column == 'first_name' ? $add_class : '' ?>><?php echo $rows['first_name']; ?></td>
                            <td <?php echo $column == 'last_name' ? $add_class : '' ?>><?php echo $rows['last_name']; ?></td>
                            <td <?php echo $column == 'email' ? $add_class : '' ?>><?php echo $rows['email']; ?></td>
                            <td><?php echo $rows['phone_no']; ?></td>
                            <td><?php echo $rows['address']; ?></td>
                            <td><?php echo $rows['country']; ?></td>
                            <td><?php echo $rows['state']; ?></td>
                            <td><?php echo $rows['pincode']; ?></td>
                            <td>
                                <a href="./editUser.php?id=<?php echo $rows['id']; ?>"><button type="button" class="btn btn-outline-warning">Edit</button></a>
                                <a href="./deleteUser.php?id=<?php echo $rows['id']; ?>"><button type="button" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button></a>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="10">No Record Found</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <div class="pagination">
            <?php
            // Previous 
            if ($page > 1) {
                echo '<a href="?page=' . ($page) . '&search=' . $searchResult . '">Previous</a>';
            }

            // Page Links
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a  href="?page=' . $i . '&search=' . $searchResult . '">' . $i . '</a>';
            }

            // Next Page Link
            if ($page < $totalPages) {
                echo '<a  href="?page=' . ($page) . '&search=' . $searchResult . '">Next</a>';
            }
            ?>
        </div>
    </nav>



</body>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

</html>