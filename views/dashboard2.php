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
    <?php include './layout/navbar.php'; ?>

    <!-- Success Messages -->
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
                <input type="text" id="search-box" name="search" placeholder="Search users..." value="<?= htmlspecialchars($searchResult) ?>" />
            </form>
            <a href="./addUser.php"><button class="btn btn-success">+ Add New</button></a>
        </div>
    </nav>

    <!-- Filter Form -->
    <form action="" method="GET">
        <div class="row">
            <select name="country_filter">
                <option value="">Filter by Country</option>
                <?php
                $countries = $connection->query("SELECT DISTINCT country FROM `users`");
                while ($row = $countries->fetch_assoc()) {
                    if (preg_match("/^[a-zA-Z\s]+$/", $row['country'])) { // Validate only alphabetic characters
                        $selected = $country_filter == $row['country'] ? 'selected' : '';
                        echo "<option value='{$row['country']}' $selected>{$row['country']}</option>";
                    }
                }
                ?>
            </select>

            <select name="state_filter">
                <option value="">Filter by State</option>
                <?php
                $states = $connection->query("SELECT DISTINCT state FROM `users`");
                while ($row = $states->fetch_assoc()) {
                    if (preg_match("/^[a-zA-Z\s]+$/", $row['state'])) { // Validate only alphabetic characters
                        $selected = $state_filter == $row['state'] ? 'selected' : '';
                        echo "<option value='{$row['state']}' $selected>{$row['state']}</option>";
                    }
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
                    <th>
                        <a href="?sort_column=id&sort_order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">
                            Id
                            <i class="fas fa-sort sort-icon <?= $sort_column === 'id' ? ($sort_order === 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : '' ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="?sort_column=first_name&sort_order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">
                            First Name
                            <i class="fas fa-sort sort-icon <?= $sort_column === 'first_name' ? ($sort_order === 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : '' ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="?sort_column=last_name&sort_order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">
                            Last Name
                            <i class="fas fa-sort sort-icon <?= $sort_column === 'last_name' ? ($sort_order === 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : '' ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="?sort_column=email&sort_order=<?= $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">
                            Email
                            <i class="fas fa-sort sort-icon <?= $sort_column === 'email' ? ($sort_order === 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : '' ?>"></i>
                        </a>
                    </th>
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
                            <td><?= $rows['id'] ?></td>
                            <td><?= $rows['first_name'] ?></td>
                            <td><?= $rows['last_name'] ?></td>
                            <td><?= $rows['email'] ?></td>
                            <td><?= $rows['phone_no'] ?></td>
                            <td><?= $rows['address'] ?></td>
                            <td><?= $rows['country'] ?></td>
                            <td><?= $rows['state'] ?></td>
                            <td><?= $rows['pincode'] ?></td>
                            <td>
                                <a href="./editUser.php?id=<?= $rows['id'] ?>"><button type="button" class="btn btn-outline-warning">Edit</button></a>
                                <a href="./deleteUser.php?id=<?= $rows['id'] ?>"><button type="button" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button></a>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='10'>No Record Found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <div class="pagination" style="margin-right: 20px;">
            <?php
            if ($page > 1) {
                echo '<a href="?page=' . ($page - 1) . '&search=' . $searchResult . '">Previous</a>';
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<a href="?page=' . $i . '&search=' . $searchResult . '">' . $i . '</a>';
            }

            if ($page < $total_pages) {
                echo '<a href="?page=' . ($page + 1) . '&search=' . $searchResult . '">Next</a>';
            }
            ?>
        </div>
    </nav>

    <!-- JavaScript -->
    <script>
        const searchBox = document.getElementById("search-box");

        // Restore focus and caret position after reload
        window.addEventListener("load", function() {
            searchBox.focus();
            const value = searchBox.value;
            searchBox.value = "";
            searchBox.value = value;
        });

        // Submit the form on every input
        searchBox.addEventListener("input", function() {
            document.getElementById("search-form").submit();
        });
    </script>

</body>
</html>
