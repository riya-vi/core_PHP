<?php
    include '../config/dataBaseConnect.php' ;

    if(isset($_GET['search'])){
        $searchResult = $_GET['search'];
        $sql = "SELECT * FROM `users` WHERE `first_name` like '%$searchResult%'" ;

        $result = $connection->query($sql) ;

        print_r($_GET) ;
        
        if($result->num_rows > 0){
            foreach($result as $item){
                $_SESSION['searched']  == false ;
                $_SESSION['id'] = $item['id'];
                $_SESSION['firstName'] = $item['first_name'];
                $_SESSION['lastName'] = $item['last_name'];
                $_SESSION['email'] = $item['email'];
                $_SESSION['phone'] = $item['phone_no'];
                $_SESSION['address'] = $item['address'];
                $_SESSION['country'] = $item['country'];
                $_SESSION['state'] = $item['state'];
                $_SESSION['pincode'] = $item['pincode'];
                // header("Location: dashboard.php");
            }
        }
    }
    else{
        echo "0 records ";
    }

    // $connection->close();

?>

