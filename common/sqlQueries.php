<?php

/** 
 * generates the SQL query for inserting user into database.
 * @param parameters from post data submitted by user
 * @return sql excution 
*/
function addUserQuery($firstName, $lastName, $email, $phoneNo, $address, $country, $state, $pincode, $hashedPassword)
{
    return "INSERT INTO users (first_name, last_name, email, phone_no, address, country_id, state_id, pincode, password) 
        VALUES ('$firstName', '$lastName', '$email', '$phoneNo', '$address', '$country' , '$state','$pincode', '$hashedPassword')";
}

function registerUserQuery($firstName, $lastName, $email, $phoneNo, $address, $country, $state, $pincode, $hashedPassword){
    return "INSERT INTO users (first_name, last_name, email, phone_no, address, country_id, state_id, pincode, password) 
        VALUES ('$firstName', '$lastName', '$email', '$phoneNo', '$address', '$country' , '$state','$pincode', '$hashedPassword')";
}

function updateUserQuery($id, $firstName, $lastName, $email, $phoneNo, $address, $country, $state, $pincode, $filePath )
{
    return "UPDATE users SET first_name = '$firstName', last_name = '$lastName', email = '$email',phone_no = '$phoneNo', address = '$address', country_id = '$country', state_id = '$state', pincode = '$pincode', file_path = '$filePath' WHERE id = '$id'";
}

function deleteUserQuery($id)
{
    return  "DELETE FROM `users` WHERE `id` = '$id'";
}

function loginUserQuery($email)
{
    return "SELECT * FROM users WHERE email = '$email'";
}

/**
 * Generates the SQL query for the search condition.
 * @param  $search The search  input by the user.
 * @param  $connection The database connection object.
 * @return SQL WHERE clause for the search.
 */
function getSearchQuery($search, $connection)
{
    return !empty($search) ? " AND CONCAT(first_name, ' ', last_name, email) LIKE '%" . $connection->real_escape_string($search) . "%'" : '';
}

/**
 * Generates the SQL query for a filter condition based country and state
 * @param $filter The filter value (e.g., country or state).
 * @param $column The column to filter by (e.g., country name or state name).
 * @param $connection The database connection object.
 * @return SQL WHERE clause for the filter.
 */
function getFilterQuery($filter, $column, $connection)
{
    return !empty($filter) ? " AND $column LIKE '" . $connection->real_escape_string($filter) . "'" : '';
}

/**
 * Generates the SQL query for sorting the results.
 * @param  $sortColumn The column to sort by.
 * @param  $sortOrder The order to sort (ASC or DESC).
 * @return  The SQL ORDER BY clause for sorting.
 */
function getSortQuery($sortColumn, $sortOrder)
{
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
function getPaginationQuery($page, $recordsPerPage)
{
    $startFrom = ($page - 1) * $recordsPerPage;
    return " LIMIT $startFrom, $recordsPerPage";
}

/**
 * generates the sql query for listing users on dashboard and fetch user data from database
 * @param $search serching the user data 
 * @param $countryFilter for filtering user data 
 */
function listingUserQuery($search, $countryFilter, $stateFilter, $sortColumn, $sortOrder, $page, $recordsPerPage, $connection)
{
    $whereClause = "1=1" .
        getSearchQuery($search, $connection) .
        getFilterQuery($countryFilter, 'c.name', $connection) .
        getFilterQuery($stateFilter, 's.name', $connection);

    return "SELECT u.*, c.name AS country, s.name AS state
            FROM users u
            LEFT JOIN countries c ON u.country_id = c.id
            LEFT JOIN states s ON u.state_id = s.id
            WHERE $whereClause" .
        getSortQuery($sortColumn, $sortOrder) .
        getPaginationQuery($page, $recordsPerPage);
}

function countUserQuery($search, $countryFilter, $stateFilter,  $connection)
{
    $whereClause = "1=1" .
        getSearchQuery($search, $connection) .
        getFilterQuery($countryFilter, 'c.name', $connection) .
        getFilterQuery($stateFilter, 's.name', $connection);

    return  "SELECT COUNT(*) AS total 
    FROM users u
    LEFT JOIN countries c ON u.country_id = c.id
    LEFT JOIN states s ON u.state_id = s.id
    WHERE $whereClause";
}

function insertTokenQueryForForgotPass($userId , $resetToken ,$expiry){
    return "INSERT INTO password_reset_tokens (`user_id` , `token`, `expiry`) VALUES ('$userId', '$resetToken', '$expiry')";
}

function deleteTokenQueryAfterResetPassword($resetToken){
   return "DELETE FROM password_reset_tokens WHERE `token` = '$resetToken'";
}

function updatePasswordQuery($userId , $hashedPassword){
    return "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";

}

function checkTokenExistQueryToResetPass($resetToken){
    return "SELECT user_id, expiry FROM password_reset_tokens WHERE `token` = '$resetToken'";
}

function getUserWantsToResetPassword($toEmail){
    return "SELECT id FROM users WHERE email = '$toEmail'";
}