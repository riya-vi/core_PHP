<?php
include '../config/dataBaseConnect.php';

if (isset($_GET['country'])) {
    $country = $_GET['country']; // Get the country from the GET parameter
    
    // Query the database for distinct states of the selected country
    $states = $connection->query("SELECT DISTINCT state FROM `users` WHERE country = '$country'");

    // Check if states are found and return them as options
    if ($states->num_rows > 0) {
        echo "<option value=''>Select a state</option>";
        while ($row = $states->fetch_assoc()) {
            echo "<option value='{$row['state']}'>{$row['state']}</option>";
        }
    } else {
        echo "<option value=''>No states found</option>";
    }
} else {
    echo "<option value=''>Select a country first</option>";
}
?>