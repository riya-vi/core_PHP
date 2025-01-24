<?php
include '../config/dataBaseConnect.php';
include './pagination2.php';

session_start();
if (!isset($_SESSION['email']) && !isset($_SESSION['password'])) {
    echo "<script> alert('Please login first') </script>";
    header("Location: login.php");
    exit;
}

//pagination
// $recordsPerPage = 5;

// $page = isset($_GET['page']) ? $_GET['page'] : 1;
// $startFrom = ($page - 1) * $recordsPerPage;

// $sql = "SELECT * FROM `users` LIMIT $startFrom, $recordsPerPage";

// // Search functionality
// if (isset($_GET['search']) && !empty($_GET['search'])) {
//     $searchResult = $_GET['search'];
//     $sql = "SELECT * FROM `users` WHERE CONCAT(first_name, last_name, email) LIKE '%$searchResult%' LIMIT $startFrom, $recordsPerPage";
// }

// // Sorting functionality
// $columns = array('first_name', 'id', 'email', 'last_name');

// $column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];
// $sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';
// $sortSql = "SELECT * FROM `users`  ORDER BY $column $sort_order";  

// // Count total records for pagination
// $countSql = "SELECT COUNT(*) AS total FROM `users`";
// if (isset($_GET['search']) && !empty($_GET['search'])) {
//     $countSql = "SELECT COUNT(*) AS total FROM `users` WHERE CONCAT(first_name, last_name, email) LIKE '%$searchResult%'";
// }
// $countResult = $connection->query($countSql);
// $countRow = $countResult->fetch_assoc();
// $totalRecords = $countRow['total'];

// $totalPages = ceil($totalRecords / $recordsPerPage);

// // Execute the final query (sorting and pagination combined)
// $finalSql = $sortSql . " LIMIT $startFrom, $recordsPerPage";
// $result = $connection->query($finalSql);

// Get the pagination results
$paginationData = pagination($connection);
$result = $paginationData['result'];
$total_pages = $paginationData['total_pages'];
$searchResult = $paginationData['search'];
$page = $paginationData['current_page'];
$sort_column = $paginationData['sort_column'];
$sort_order = $paginationData['sort_order'];
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
                    <th> <a href="dashboard1.php?column=id&order=<?php echo $sort_order == 'ASC' ? 'desc' : 'asc'; ?>">Id <i class="fas fa-sort<?php echo $column == 'id' ? '-' . ($sort_order == 'ASC' ? 'up' : 'down') : ''; ?>"></i></a></th>
                    <th> <a href="dashboard1.php?column=first_name&order=<?php echo $sort_order == 'ASC' ? 'desc' : 'asc'; ?>">First Name <i class="fas fa-sort<?php echo $column == 'first_name' ? '-' . ($sort_order == 'ASC' ? 'up' : 'down') : ''; ?>"></i></a></th>
                    <th> <a href="dashboard1.php?column=last_name&order=<?php echo $sort_order == 'ASC' ? 'desc' : 'asc'; ?>">Last Name <i class="fas fa-sort<?php echo $column == 'last_name' ? '-' . ($sort_order == 'ASC' ? 'up' : 'down') : ''; ?>"></i> </a></th>
                    <th> <a href="dashboard1.php?column=email&order=<?php echo $sort_order == 'ASC' ? 'desc' : 'asc'; ?>">Email <i class="fas fa-sort<?php echo $column == 'email' ? '-' . ($sort_order == 'ASC' ? 'up' : 'down') : ''; ?>"></i></a></th>
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
                            <td <?php echo $column == 'id' ? 'class="highlight"' : ''; ?>><?php echo $rows['id']; ?></td>
                            <td <?php echo $column == 'first_name' ? 'class="highlight"' : ''; ?>><?php echo $rows['first_name']; ?></td>
                            <td <?php echo $column == 'last_name' ? 'class="highlight"' : ''; ?>><?php echo $rows['last_name']; ?></td>
                            <td <?php echo $column == 'email' ? 'class="highlight"' : ''; ?>><?php echo $rows['email']; ?></td>
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
                echo '<a href="?page=' . ($page - 1) . '&column=' . $column . '&order=' . $sort_order . '&search=' . $searchResult . '">Previous</a>';
            }

            // Page Links
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a href="?page=' . $i . '&column=' . $column . '&order=' . $sort_order . '&search=' . $searchResult . '">' . $i . '</a>';
            }

            // Next Page Link
            if ($page < $totalPages) {
                echo '<a href="?page=' . ($page + 1) . '&column=' . $column . '&order=' . $sort_order . '&search=' . $searchResult . '">Next</a>';
            }
            ?>
        </div>
    </nav>

</body>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

</html>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Users Dashboard</h1>

    <!-- Search Box -->
    <form method="GET" id="search-form">
        <input type="text" id="search-box" name="search" placeholder="Search users..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" oninput="this.form.submit()">
    </form>

    <!-- Table for displaying users -->
    <table id="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Call the pagination function to get results
            $paginationData = pagination($connection);

            // Loop through the results and display them
            while ($row = $paginationData['result']->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Pagination (will be updated dynamically) -->
    <div id="pagination">
        <?php
        // Display pagination links
        for ($page = 1; $page <= $paginationData['total_pages']; $page++) {
            echo "<a href='?page=$page&search=" . urlencode($paginationData['search']) . "&sort_column=" . $paginationData['sort_column'] . "&sort_order=" . $paginationData['sort_order'] . "'>$page</a> ";
        }
        ?>
    </div>

</body>
</html>
