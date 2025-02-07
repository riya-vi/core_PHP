<?php
$baseUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__ . '/../');

// /php/layout/../
// echo $baseUrl;
// die;
?>

<ul>
      <li><a href="<?php echo $baseUrl ?>frontend/dashboard.php" style="font-size: larger;">Dashboard</a></li>

      <li> <a href="<?php echo $baseUrl ?>frontend/home.php">Home</a></li>

      <li style="float:right">
            <a href="<?php echo $baseUrl ?>backend/logout.php" onclick="return confirm('Are You Sure You Want to Log OUT ?')">
            Logout</a>
      </li>
</ul>
