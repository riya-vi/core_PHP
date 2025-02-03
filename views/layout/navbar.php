<?php
$baseUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__. '/../') ;

?>

<ul>
  
   <li><a href="<?php echo $baseUrl?>dashboard.php" style="font-size: larger;">Dashboard</a></li>
   <li> <a href="<?php echo $baseUrl?>home.php">Home</a></li>
   <li><a href="">about</a></li>
   <li><a href="">contact</a></li>
   <li style="float:right"><a href="<?php echo $baseUrl?>logout.php" onclick="return confirm('Are You Sure You Want to Log OUT ?')">
         Logout</a></li>
</ul>

