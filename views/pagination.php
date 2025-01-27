<?php

/**
 * Handles pagination logic for fetching and counting records from the database.
 *
 * @param mysqli $connection The database connection object.
 * @return array An array containing paginated results, total pages, and other query parameters.
 */
function pagination($connection)
{
    // Define how many records to show per page
    $recordsPerPage = 5;

    $page = getCurrentPage();
    $startFrom = calculate($page, $recordsPerPage);

    $searchResult = getRequestParam('search', '');
    $sort_column = getSortColumn(['id', 'first_name', 'last_name', 'email'], 'id');
    $sort_order = getSortOrder('ASC');
    $country_filter = getRequestParam('country_filter', '');
    $state_filter = getRequestParam('state_filter', '');

    $whereClause = buildWhereClause($searchResult, $country_filter, $state_filter);

    $sql = buildSelectQuery($whereClause, $sort_column, $sort_order, $startFrom, $recordsPerPage);
    $result = executeQuery($connection, $sql);

    $total_records = getTotalRecords($connection, $whereClause);
    $total_pages = calculateTotalPages($total_records, $recordsPerPage);
    return [
        'result' => $result,
        'total_pages' => $total_pages,
        'search' => $searchResult,
        'current_page' => $page,
        'sort_column' => $sort_column,
        'sort_order' => $sort_order,
        'country_filter' => $country_filter,
        'state_filter' => $state_filter,
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

function getSortColumn($allowed_columns, $default)
{
    return isset($_GET['sort_column']) && in_array($_GET['sort_column'], $allowed_columns)
        ? $_GET['sort_column']
        : $default;
}

function getSortOrder($default = 'ASC')
{
    return isset($_GET['sort_order']) && strtoupper($_GET['sort_order']) === 'DESC' ? 'DESC' : $default;
}

function buildWhereClause($searchResult, $country_filter, $state_filter)
{
    $whereClause = "1=1";
    if (!empty($searchResult)) {
        $whereClause .= " AND CONCAT(first_name, last_name, email) LIKE '%$searchResult%'";
    }
    if (!empty($country_filter)) {
        $whereClause .= " AND country = '$country_filter'";
    }
    if (!empty($state_filter)) {
        $whereClause .= " AND state = '$state_filter'";
    }
    return $whereClause;
}

function buildSelectQuery($whereClause, $sort_column, $sort_order, $startFrom, $recordsPerPage)
{
    return "SELECT * FROM `users` WHERE $whereClause 
            ORDER BY $sort_column $sort_order 
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
    $count_sql = "SELECT COUNT(*) AS total FROM `users` WHERE $whereClause";
    $count_result = $connection->query($count_sql);
    if (!$count_result) {
        die("Count Query Error: " . $connection->error . " - Query: " . $count_sql);
    }
    $count_row = $count_result->fetch_assoc();
    return $count_row['total'];
}

function calculateTotalPages($total_records, $recordsPerPage)
{
    return ceil($total_records / $recordsPerPage);
}
