<?php
include '../config/dataBaseConnect.php';
include './pagination.php';

session_start();
if (!isset($_SESSION['email']) && !isset($_SESSION['password'])) {
    echo "<script> alert('Please login first') </script>";
    header("Location: login.php");
    exit;
}

// Get the pagination results
$paginationData = pagination($connection);
$result = $paginationData['result'];
$total_pages = $paginationData['total_pages'];
$searchResult = $paginationData['search'];
$page = $paginationData['current_page'];
$sort_column = $paginationData['sort_column'];
$sort_order = $paginationData['sort_order'];
$country_filter = $paginationData['country_filter'];
$state_filter = $paginationData['state_filter'];
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <link rel="stylesheet" href="./css/dashboardStyle.css">
</head>



<body>

    <?php
    include './layout/navbar.php';
    ?>

    <!--success Messages -->
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
            <form method="GET" id="search-form">
                <input type="text" id="search-box" name="search" placeholder="Search users..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" oninput="this.form.submit()">
            </form>
            <a href="./addUser.php"><button class="btn btn-success">+Add New</button></a>
        </div>
    </nav>


    <!-- Filter Form -->
    <form action="" method="get">
        <div class="row">
            <select name="country_filter">
                <option value="">Filter by Country</option>
                <?php
                $countries = $connection->query("SELECT DISTINCT country FROM `users`");
                while ($row = $countries->fetch_assoc()) {
                    $selected = $country_filter == $row['country'] ? 'selected' : '';
                    echo "<option value='{$row['country']}' $selected>{$row['country']}</option>";
                }
                ?>
            </select>

            <select name="state_filter">
                <option value="">Filter by State</option>
                <?php
                $states = $connection->query("SELECT DISTINCT state FROM `users`");
                while ($row = $states->fetch_assoc()) {
                    $selected = $state_filter == $row['state'] ? 'selected' : '';
                    echo "<option value='{$row['state']}' $selected>{$row['state']}</option>";
                }
                ?>
            </select>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Apply Filter</button>
                <a href="dashboard.php" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div>
        <table>
            <thead>
                <tr>

                    <th><a href="?sort_column=id&sort_order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">Id <i class="fas fa-sort"></i></a></th>
                    <th><a href="?sort_column=first_name&sort_order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">First Name <i class="fas fa-sort"></i></a> </th>
                    <th><a href="?sort_column=last_name&sort_order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">Last Name <i class="fas fa-sort"></i></a> </th>
                    <th><a href="?sort_column=email&sort_order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">Email <i class="fas fa-sort"></i></a> </th>
                    <th>Phone NO.</th>
                    <th>Address</th>
                    <th>Country</th>
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
                            <td><?php echo $rows['id']; ?></td>
                            <td><?php echo $rows['first_name']; ?></td>
                            <td><?php echo $rows['last_name']; ?></td>
                            <td><?php echo $rows['email']; ?></td>
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
        <div class="pagination" style="margin-right: 20px;">
            <?php
            // Previous 
            if ($page > 1) {
                echo '<a href="?page=' . ($page) . '&search=' . $searchResult . '">Previous</a>';
            }

            // Page Links
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<a  href="?page=' . $i . '&search=' . $searchResult . '">' . $i . '</a>';
            }

            // Next Page Link
            if ($page < $total_pages) {
                echo '<a  href="?page=' . ($page) . '&search=' . $searchResult . '">Next</a>';
            }
            ?>
        </div>
    </nav>

</body>

</html>

