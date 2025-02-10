<?php
include '../config/dataBaseConnect.php';
require_once '../common/sqlQueries.php' ;

/**
 * Lists users with filters, search, pagination, and sorting.
 * @param  $connection The database connection object.
 * @return array An array containing user results, pagination, and filter values.
 */
function listUser($connection) {
    $recordsPerPage = 5;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $countryFilter = isset($_GET['countryFilter']) ? $_GET['countryFilter'] : '';
    $stateFilter = isset($_GET['stateFilter']) ? $_GET['stateFilter'] : '';
    $sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'id';
    $sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] === 'ASC' ? 'ASC' : 'DESC';
 
    $sql = listingUserQuery($search, $countryFilter, $stateFilter, $sortColumn, $sortOrder, $page, $recordsPerPage, $connection) ;

    $result = $connection->query($sql);
    if (!$result) {
        die("SQL Query Error: " . $connection->error . " - Query: " . $sql);
    }

    $countSql = countUserQuery($search, $countryFilter, $stateFilter,  $connection);
    $countResult = $connection->query($countSql);

    if (!$countResult) {
        die("Count Query Error:" . $connection->error . " - Query: " . $countSql);
    }

    $countRow = $countResult->fetch_assoc();
    $totalRecords = $countRow['total'];
    $totalPages = ceil($totalRecords / $recordsPerPage);

    return [
        'result' => $result,
        'totalPages' => $totalPages,
        'search' => $search,
        'currentPage' => $page,
        'sortColumn' => $sortColumn,
        'sortOrder' => $sortOrder,
        'countryFilter' => $countryFilter,
        'stateFilter' => $stateFilter,
    ];
}
?>