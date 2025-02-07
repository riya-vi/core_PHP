<?php
include '../config/dataBaseConnect.php';
include '../backend/listingUser.php';
require '../roles/checkPermission.php';
require  '../common/sessions.php';

checkLogin();

$listUserData = listUser($connection);
$result = $listUserData['result'];
$totalPages = $listUserData['totalPages'];
$searchResult = $listUserData['search'];
$page = $listUserData['currentPage'];
$sortColumn = $listUserData['sortColumn'];
$sortOrder = $listUserData['sortOrder'];
$countryFilter = $listUserData['countryFilter'];
$stateFilter = $listUserData['stateFilter'];
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/dashboardStyle.css">
</head>

<body>
    <?php
    include '../layout/navbar.php';
    crudSuccessMessages();
    ?>

    <br>
    <?php
    if (hasPermission('add_user')) {
        echo '<a href="../frontend/addUserForm.php" style="float: right;"><button class="btn btn-success">+ Add New</button></a>';
    }
    ?>

    <!-- search and filter  -->
    <form method="GET" id="search-filter-form">
        <div class="row">
            <input type="text" id="search-box" name="search" placeholder="Search users..." value="<?= htmlspecialchars($searchResult) ?>" />
            <br>
            <select name="countryFilter">
                <option value="">Filter by Country</option>
                <?php
                $countries = $connection->query("SELECT id, name FROM countries");
                while ($row = $countries->fetch_assoc()) {
                    $selected = $countryFilter == $row['name'] ? 'selected' : '';
                    echo "<option value='{$row['name']}' $selected>{$row['name']}</option>";
                }
                ?>
            </select>

            <select name="stateFilter" id="state">
                <option value="">Filter by State</option>
                <?php
                $states = $connection->query("SELECT id, name FROM states");
                while ($row = $states->fetch_assoc()) {
                    $selected = $stateFilter == $row['name'] ? 'selected' : '';
                    echo "<option value='{$row['name']}' $selected>{$row['name']}</option>";
                }
                ?>
            </select>

            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Apply</button>
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
                        <a href="?sortColumn=id&sortOrder=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">
                            Id
                            <i class="fas fa-sort sort-icon <?= $sortColumn === 'id' ? ($sortOrder === 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : '' ?>"></i>
                        </a>
                    </th>
                    <th>Profile Photo</th>
                    <th>
                        <a href="?sortColumn=first_name&sortOrder=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">
                            First Name
                            <i class="fas fa-sort sort-icon <?= $sortColumn === 'first_name' ? ($sortOrder === 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : '' ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="?sortColumn=last_name&sortOrder=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">
                            Last Name
                            <i class="fas fa-sort sort-icon <?= $sortColumn === 'last_name' ? ($sortOrder === 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : '' ?>"></i>
                        </a>
                    </th>
                    <th>
                        <a href="?sortColumn=email&sortOrder=<?= $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">
                            Email
                            <i class="fas fa-sort sort-icon <?= $sortColumn === 'email' ? ($sortOrder === 'ASC' ? 'fa-sort-up active' : 'fa-sort-down active') : '' ?>"></i>
                        </a>
                    </th>
                    <th>Phone NO.</th>
                    <th>Address</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>Pincode</th>
                    <th>File Path</th>
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
                            <td> <?php
                                    if (!empty($rows['file_path'])) {
                                        $imagePath = '..' . $rows['file_path'];
                                    } else {
                                        $imagePath = '../storage/default.jpg';
                                    }
                                    ?>
                                <img src="<?= $imagePath ?>" alt="Profile Image" width="50" height="50">
                            </td>
                            <td><?= $rows['first_name'] ?></td>
                            <td><?= $rows['last_name'] ?></td>
                            <td><?= $rows['email'] ?></td>
                            <td><?= $rows['phone_no'] ?></td>
                            <td><?= $rows['address'] ?></td>
                            <td><?= $rows['country'] ?></td>
                            <td><?= $rows['state'] ?></td>
                            <td><?= $rows['pincode'] ?></td>
                            <td><?= $rows['file_path'] ?></td>
                            <td>
                                <?php if (hasPermission('edit_user')) {
                                ?>
                                    <a href="../backend/editUser.php?id=<?= $rows['id'] ?>"><button type="button" class="btn btn-outline-warning">Edit</button></a>
                                <?php } else {
                                    echo "";
                                } ?>

                                <?php if (hasPermission('delete_user')) {
                                ?>
                                    <a href="../backend/deleteUser.php?id=<?= $rows['id'] ?>"><button type="button" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</button></a>
                                <?php  } else {
                                    echo "";
                                } ?>
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

            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a href="?page=' . $i . '&search=' . $searchResult . '">' . $i . '</a>';
            }

            if ($page < $totalPages) {
                echo '<a href="?page=' . ($page + 1) . '&search=' . $searchResult . '">Next</a>';
            }
            ?>
        </div>
    </nav>
</body>

</html>

<script>
    const searchBox = document.getElementById("search-box");

    window.addEventListener("load", function() {
        searchBox.focus();
        const value = searchBox.value;
        searchBox.value = "";
        searchBox.value = value;
    });

    searchBox.addEventListener("input", function() {
        document.getElementById("search-filter-form").submit();
    });

</script>