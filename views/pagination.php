<?php

function pagination($connection)
{
    
    $recordsPerPage = 5;

    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $startFrom = ($page - 1) * $recordsPerPage;

    //searching
    $searchResult = isset($_GET['search']) ? $_GET['search'] : '';

    // sorting
    $sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id';
    $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';
    $allowed_columns = ['id', 'first_name', 'last_name', 'email'];
    $sort_column = in_array($sort_column, $allowed_columns) ? $sort_column : 'id';
    $sort_order = ($sort_order === 'DESC') ? 'DESC' : 'ASC';

    // filter
    $country_filter = isset($_GET['country_filter']) ? $_GET['country_filter'] : '' ;
    $state_filter = isset($_GET['state_filter']) ? $_GET['state_filter'] : '';

    $sql = "SELECT * FROM `users` WHERE 1=1";
    
    if (!empty($searchResult)) {
        $sql .= "AND CONCAT(first_name, last_name, email) LIKE '%$searchResult%'";
    }
    if(!empty($country_filter)){
        $sql .= "AND country = '$country_filter' ";
    }
    if(!empty($state_filter)){
        $sql .= "AND state = '$state_filter' ";
    }
   

    $sql .= " ORDER BY $sort_column $sort_order LIMIT $startFrom, $recordsPerPage";

    echo $sql ;
    $result = $connection->query($sql);

    // Get total count of records
    $count_sql = "SELECT COUNT(*) AS total FROM `users` WHERE 1=1";
    if (!empty($searchResult)) {
        $count_sql .= " AND CONCAT(first_name, last_name, email) LIKE '%$searchResult%'";
    }
    if(!empty($country_filter)){
        $count_sql .= "AND country = '$country_filter'" ;
    }
    if(!empty($state_filter)){
        $count_sql .= "AND state = '$state_filter'" ;
    }
    $count_result = $connection->query($count_sql);
    $count_row = $count_result->fetch_assoc();
    $total_records = $count_row['total'];

    // Calculate the total number of pages
    $total_pages = ceil($total_records / $recordsPerPage);

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
?>
