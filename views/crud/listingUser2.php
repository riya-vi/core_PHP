<?php
include '../config/dataBaseConnect.php';

/**
 * Generates the SQL query for the search condition.
 * @param  $search The search string input by the user. and   $connection The database connection object.
 * @return string The SQL WHERE clause for the search.
 */
function getSearchQuery($search, $connection) {
    return !empty($search) ? " AND CONCAT(first_name, ' ', last_name, email) LIKE '%" . $connection->real_escape_string($search) . "%'" : '';
}

// generates the SQL query for filtering user data based on selected country
function getFilterQuery($filter, $column, $connection) {
    return !empty($filter) ? " AND $column LIKE '" . $connection->real_escape_string($filter) . "'" : '';
}


function getSortQuery($sortColumn, $sortOrder) {
    $allowedColumns = ['id', 'first_name', 'last_name', 'email'];
    $sortColumn = in_array($sortColumn, $allowedColumns) ? $sortColumn : 'id';
    return " ORDER BY $sortColumn $sortOrder";
}


function getPaginationQuery($page, $recordsPerPage) {
    $startFrom = ($page - 1) * $recordsPerPage;
    return " LIMIT $startFrom, $recordsPerPage";
}


function listUser($connection) {
    $recordsPerPage = 5;
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $countryFilter = isset($_GET['countryFilter']) ? $_GET['countryFilter'] : '';
    $stateFilter = isset($_GET['stateFilter']) ? $_GET['stateFilter'] : '';
    $sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'id';
    $sortOrder = isset($_GET['sortOrder']) && $_GET['sortOrder'] === 'DESC' ? 'DESC' : 'ASC';

    $whereClause = "1=1" . 
        getSearchQuery($search, $connection) . 
        getFilterQuery($countryFilter, 'c.name', $connection) . 
        getFilterQuery($stateFilter, 's.name', $connection);

    $sql = "SELECT u.*, c.name AS country, s.name AS state
            FROM users u
            LEFT JOIN countries c ON u.country_id = c.id
            LEFT JOIN states s ON u.state_id = s.id
            WHERE $whereClause" . 
            getSortQuery($sortColumn, $sortOrder) . 
            getPaginationQuery($page, $recordsPerPage);

    $result = $connection->query($sql);
    if (!$result) {
        die("SQL Query Error: " . $connection->error . " - Query: " . $sql);
    }

    $countSql = "SELECT COUNT(*) AS total 
                 FROM users u
                 LEFT JOIN countries c ON u.country_id = c.id
                 LEFT JOIN states s ON u.state_id = s.id
                 WHERE $whereClause";
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
        'search' => $search,
        'currentPage' => $page,
        'sortColumn' => $sortColumn,
        'sortOrder' => $sortOrder,
        'countryFilter' => $countryFilter,
        'stateFilter' => $stateFilter,
    ];
}
?>