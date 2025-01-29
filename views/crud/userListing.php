<?php

/**
 * Handles pagination logic for fetching and counting records from the database.
 *
 * @param mysqli $connection The database connection object.
 * @return array An array containing paginated results, total pages, and other query parameters.
 */
function listUser($connection)
{
    // Define how many records to show per page
    $recordsPerPage = 5;

    $page = getCurrentPage();
    $startFrom = calculate($page, $recordsPerPage);

    $searchResult = getRequestParam('search', '');
    $sortColumn = getSortColumn(['id', 'first_name', 'last_name', 'email'], 'id');
    $sortOrder = getSortOrder('ASC');
    $countryFilter = getRequestParam('countryFilter', '');
    $stateFilter = getRequestParam('stateFilter', '');

    $whereClause = buildWhereClause($searchResult, $countryFilter, $stateFilter);

    $sql = buildSelectQuery($whereClause, $sortColumn, $sortOrder, $startFrom, $recordsPerPage);
    $result = executeQuery($connection, $sql);

    $totalRecords = getTotalRecords($connection, $whereClause);
    $totalPages = calculateTotalPages($totalRecords, $recordsPerPage);
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

function getCurrentPage()
{
    return isset($_GET['page']) ?  ($_GET['page']) : 1;
}

function calculate($page, $recordsPerPage)
{
    return ($page - 1) * $recordsPerPage;
}

function getRequestParam($key, $default = '')
{
    return isset($_GET[$key]) ? $_GET[$key] : $default;
}

function getSortColumn($allowedColumns, $default)
{
    return isset($_GET['sortColumn']) && in_array($_GET['sortColumn'], $allowedColumns)
        ? $_GET['sortColumn']
        : $default;
}

function getSortOrder($default = 'ASC')
{
    return isset($_GET['sortOrder']) && strtoupper($_GET['sortOrder']) === 'DESC' ? 'DESC' : $default;
}

function buildWhereClause($searchResult, $countryFilter, $stateFilter)
{
    $whereClause = "1=1";
    if (!empty($searchResult)) {
        $whereClause .= " AND CONCAT(first_name, last_name, email) LIKE '%$searchResult%'";
    }
    if (!empty($countryFilter)) {
        $whereClause .= " AND country = '$countryFilter'";
    }
    if (!empty($stateFilter)) {
        $whereClause .= " AND state = '$stateFilter'";
    }
    return $whereClause;
}

function buildSelectQuery($whereClause, $sortColumn, $sortOrder, $startFrom, $recordsPerPage)
{
    return "SELECT * FROM `users` WHERE $whereClause 
            ORDER BY $sortColumn $sortOrder 
            LIMIT $startFrom, $recordsPerPage";
}

function executeQuery($connection, $sql)
{
    $result = $connection->query($sql);
    if (!$result) {
        die("SQL Query Error: " . $connection->error . " - Query: " . $sql);
    }
    return $result;
}

function getTotalRecords($connection, $whereClause)
{
    $countSql = "SELECT COUNT(*) AS total FROM `users` WHERE $whereClause";
    $countResult = $connection->query($countSql);
    if (!$countResult) {
        die("Count Query Error: " . $connection->error . " - Query: " . $countSql);
    }
    $count_row = $countResult->fetch_assoc();
    return $count_row['total'];
}

function calculateTotalPages($totalRecords, $recordsPerPage)
{
    return ceil($totalRecords / $recordsPerPage);
}
