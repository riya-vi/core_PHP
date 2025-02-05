<?php
include '../config/dataBaseConnect.php';

/**
 * Generates the SQL query for the search condition.
 * @param  $search The search  input by the user.
 * @param  $connection The database connection object.
 * @return SQL WHERE clause for the search.
 */
function getSearchQuery($search, $connection) {
    return !empty($search) ? " AND CONCAT(first_name, ' ', last_name, email) LIKE '%" . $connection->real_escape_string($search) . "%'" : '';
}

/**
 * Generates the SQL query for a filter condition based country and state
 * @param $filter The filter value (e.g., country or state).
 * @param $column The column to filter by (e.g., country name or state name).
 * @param $connection The database connection object.
 * @return SQL WHERE clause for the filter.
 */
function getFilterQuery($filter, $column, $connection) {
    return !empty($filter) ? " AND $column LIKE '" . $connection->real_escape_string($filter) . "'" : '';
}

/**
 * Generates the SQL query for sorting the results.
 * @param  $sortColumn The column to sort by.
 * @param  $sortOrder The order to sort (ASC or DESC).
 * @return  The SQL ORDER BY clause for sorting.
 */
function getSortQuery($sortColumn, $sortOrder) {
    $allowedColumns = ['id', 'first_name', 'last_name', 'email'];
    $sortColumn = in_array($sortColumn, $allowedColumns) ? $sortColumn : 'id';
    return " ORDER BY $sortColumn $sortOrder";
}

/**
 * Generates the SQL query for pagination (LIMIT clause).
 * @param  $page The current page number.
 * @param  $recordsPerPage The number of records per page.
 * @return SQL LIMIT clause for pagination.
 */
function getPaginationQuery($page, $recordsPerPage) {
    $startFrom = ($page - 1) * $recordsPerPage;
    return " LIMIT $startFrom, $recordsPerPage";
}

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