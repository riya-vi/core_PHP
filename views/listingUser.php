<?php
/**
 * Handles the logic for listing user abd  fetching , counting records from the database for pagination , searching, sorting, and filtering user data.
 *
 * @param mysqli $connection The database connection object.
 * @return array An array containing paginated results, total pages, country-state filter, sorting and other query parameters.
 */

function listUser($connection) {
    $recordsPerPage = 5;

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $startFrom = ($page - 1) * $recordsPerPage;

    $searchResult = isset($_GET['search']) ? $_GET['search'] : '';
    $sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'id';
    $sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] === 'DESC' ? 'DESC' : 'ASC';

    $allowedColumns = ['id', 'first_name', 'last_name', 'email'];
    $sortColumn = in_array($sortColumn, $allowedColumns) ? $sortColumn : 'id';

    $countryFilter = isset($_GET['countryFilter']) ? $_GET['countryFilter'] : '';
    $stateFilter = isset($_GET['stateFilter']) ? $_GET['stateFilter'] : '';

    $whereClause = "1=1";
    if (!empty($searchResult)) {
        $whereClause .= " AND CONCAT(first_name, last_name, email) LIKE '%$searchResult%'";
    }
    if (!empty($countryFilter)) {
        $whereClause .= " AND country = '$countryFilter' AND CONCAT(first_name, last_name, email) LIKE '%$searchResult%'";
    }
    if (!empty($stateFilter)) {
        $whereClause .= " AND state = '$stateFilter' AND CONCAT(first_name, last_name, email) LIKE '%$searchResult%'";
    }
    
    $sql = "SELECT * FROM `users` WHERE $whereClause 
            ORDER BY $sortColumn $sortOrder 
            LIMIT $startFrom, $recordsPerPage";

    $result = $connection->query($sql);
    if (!$result) {
        die("SQL Query Error: " . $connection->error . " - Query: " . $sql);
    }
    
    $countSql = "SELECT COUNT(*) AS total FROM `users` WHERE $whereClause";
    $countResult = $connection->query($countSql);
    if (!$countResult) {
        die("Count Query Error: " . $connection->error . " - Query: " . $countSql);
    }
    $countRow = $countResult->fetch_assoc();
    $totalRecords = $countRow['total'];

    $totalPages = ceil($totalRecords / $recordsPerPage);

    return [
        'result' => $result,
        'totalPages' => $totalPages,
        'search' => $searchResult,
        'currentPage' => $page,
        'sortColumn' => $sortColumn,
        'sortOrder' => $sortOrder,
        'countryFilter' => $countryFilter,
        'stateFilter' => $stateFilter,
    ];
}
?>
