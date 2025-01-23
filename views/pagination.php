<?php

function pagination($connection) {

    $recordsPerPage = 5;

    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $startFrom = ($page - 1) * $recordsPerPage;

    $searchResult = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT * FROM `users`  LIMIT $startFrom, $recordsPerPage";

    // Modify query if search is performed
    if (!empty($searchResult)) {
        $sql = "SELECT * FROM `users` WHERE CONCAT(first_name, last_name, email) LIKE '%$searchResult%' LIMIT $startFrom, $recordsPerPage";
    }

    $result = $connection->query($sql);

    //  count total records
    $countSql = "SELECT COUNT(*) AS total FROM `users`";
    if (!empty($searchResult)) {
        $countSql = "SELECT COUNT(*) AS total FROM `users` WHERE CONCAT(first_name, last_name, email) LIKE '%$searchResult%'";
    }

    $countResult = $connection->query($countSql);
    $countRow = $countResult->fetch_assoc();
    $totalRecords = $countRow['total'];

    
    $totalPages = ceil($totalRecords / $recordsPerPage);

    
    return [
        'result' => $result,
        'totalPages' => $totalPages,
        'search' => $searchResult,
        'currentPage' => $page
    ];
}
?>