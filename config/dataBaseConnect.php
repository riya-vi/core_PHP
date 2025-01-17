<?php

$servername = "localhost";
$username = "root";
$password = "pHp@1189";
$databaseName = "my_project";

// Create connection
$connection = new mysqli($servername, $username , $password ,$databaseName);

// Check connection
if ($connection->connect_error) {
  echo " failed to connect ";
  die("Connection failed: " . $connection->connect_error);
}


echo "connected successfully ";

?>