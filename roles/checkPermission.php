<?php
session_start() ;
function hasPermission($permissionName)
{
    include '../config/dataBaseConnect.php';

    $role_id =  $_SESSION['role_id'];

    $checkPermissionQuery = "SELECT * FROM role_permissions INNER JOIN permissions ON role_permissions.permission_id = permissions.id WHERE role_permissions.role_id = '$role_id 'AND permissions.name = '$permissionName' ";

    $permissionResult = $connection->query($checkPermissionQuery);

    return $permissionResult->fetch_assoc();
}
