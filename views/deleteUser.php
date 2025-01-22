<?php
include '../config/dataBaseConnect.php';

// delete user 
    $id = $_GET["id"];    
    $sql = "DELETE FROM `users` WHERE `id` = '$id'";
    echo $sql;
    echo $id ;
    echo "here2" ;

    if ($connection->query($sql)) {
        session_start();
        $_SESSION["delete_message"]="Record deleted Successfully !";
        header("Location: dashboard.php");
    } else {
        echo "Something went wrong. Please try again later.";
        echo "Error:" . $sql . "<br>" . $connection->error;
    }

// $connection->close();

?>


