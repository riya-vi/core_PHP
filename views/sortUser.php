<?php
// Below is optional. Remove if you have already connected to your database.
include '../config/dataBaseConnect.php' ;

// For extra protection these are the columns that the user can sort by (in your database table).
$columns = array('first_name','id','email','country','state');

// Only get the column if it exists in the above columns array, if it doesn't exist the database table will be sorted by the first item in the columns array.
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];

// Get the sort order for the column, ascending or descending, default is ascending.
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

// Get the result...
if ($result = $mysqli->query('SELECT * FROM `users` ORDER BY ' .  $column . ' ' . $sort_order)) {
	// Some variables we need for the table.
	$up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order); 
	$asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
	$add_class = ' class="highlight"';
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>PHP & MySQL Table Sorting by CodeShack</title>
			<meta charset="utf-8">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
			<style>
			html {
				font-family: Tahoma, Geneva, sans-serif;
				padding: 10px;
			}
			table {
				border-collapse: collapse;
				width: 500px;
			}
			th {
				background-color: #54585d;
				border: 1px solid #54585d;
			}
			th:hover {
				background-color: #64686e;
			}
			th a {
				display: block;
				text-decoration:none;
				padding: 10px;
				color: #ffffff;
				font-weight: bold;
				font-size: 13px;
			}
			th a i {
				margin-left: 5px;
				color: rgba(255,255,255,0.4);
			}
			td {
				padding: 10px;
				color: #636363;
				border: 1px solid #dddfe1;
			}
			tr {
				background-color: #ffffff;
			}
			tr .highlight {
				background-color: #f9fafb;
			}
			</style>
		</head>
		<body>
            <h1>sorting</h1>
			<table>
				<tr>
					<th><a href="sortUser.php?column=id&order=<?php echo $asc_or_desc; ?>">Id<i class="fas fa-sort<?php echo $column == 'id' ? '-' . $up_or_down : ''; ?>"></i></a></th>

					<th><a href="sortUser.php?column=first_name&order=<?php echo $asc_or_desc; ?>">First Name<i class="fas fa-sort<?php echo $column == 'first_name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                    <th>Last Name</th>
					<th><a href="sortUser.php?column=email&order=<?php echo $asc_or_desc; ?>">email<i class="fas fa-sort<?php echo $column == 'email' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                    <th>Phone no</th>
                    <th>address</th>
                    <th><a href="sortUser.php?column=country&order=<?php echo $asc_or_desc; ?>">country<i class="fas fa-sort<?php echo $column == 'country' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                    <th><a href="sortUser.php?column=state&order=<?php echo $asc_or_desc; ?>">state<i class="fas fa-sort<?php echo $column == 'state' ? '-' . $up_or_down : ''; ?>"></i></a></th>
                    <th>action</th>
                    
				</tr>
				<?php while ($row = $result->fetch_assoc()): ?>
				<tr>
					<td<?php echo $column == 'id' ? $add_class : ''; ?>><?php echo $row['id']; ?></td>
					<td<?php echo $column == 'first_name' ? $add_class : ''; ?>><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
					<td<?php echo $column == 'email' ? $add_class : ''; ?>><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone_no']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td<?php echo $column == 'country' ? $add_class : ''; ?>><?php echo $row['country']; ?></td>
                    <td<?php echo $column == 'state' ? $add_class : ''; ?>><?php echo $row['state']; ?></td>
                    <td> <td><?php echo $row['pincode']; ?></td></td>

				</tr>
				<?php endwhile; ?>
			</table>
		</body>
	</html>
	<?php
	$result->free();
}
?>


