<?php
include '../config/dataBaseConnect.php' ;

if (isset($_GET['action']) && $_GET['action'] === 'getCountries') {
    $query = "SELECT id , name FROM countries";
    $result = $connection->query($query);
    $countries = [];
    while ($row = $result->fetch_assoc()) {
        $countries[] = $row;
    }
    echo json_encode($countries);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'getStates' && isset($_GET['country_id'])) {
    $countryId = $_GET['country_id'];
    $query = "SELECT id, name FROM states WHERE country_id = $countryId";
    $result = $connection->query($query);
    $states = [];
    while ($row = $result->fetch_assoc()) {
        $states[] = $row;
    }
    echo json_encode($states);
    exit;
}
