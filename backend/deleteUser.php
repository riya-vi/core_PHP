<?php
include '../config/dataBaseConnect.php';
require  '../common/sqlQueries.php' ;

    $id = $_GET["id"];    
    $sql = deleteUserQuery($id) ;
    echo $sql;
    echo $id ;
    echo "here2" ;

    if ($connection->query($sql)) {
        echo 'here 3' ;
        session_start();
        $_SESSION["delete_message"]="Record deleted Successfully !";
        header("Location: ../frontend/dashboard.php");
    } else {
        echo "Something went wrong. Please try again later.";
        echo "Error:" . $sql . "<br>" . $connection->error;
    }

?>


