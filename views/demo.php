


            <tbody>
                <?php
                session_start() ;
                if ($_SESSION['searched'] == false) {
                ?>
                    <tr>
                        <td><?php echo $_SESSION['id']; ?></td>
                        <td><?php echo $_SESSION['first_name']; ?></td>
                        <td><?php echo $_SESSION['last_name']; ?></td>
                        <td><?php echo $_SESSION['email']; ?></td>
                        <td><?php echo $_SESSION['phone_no']; ?></td>
                        <td><?php echo $_SESSION['address']; ?></td>
                        <td><?php echo $_SESSION['country']; ?></td>
                        <td><?php echo $_SESSION['state']; ?></td>
                        <td><?php echo $_SESSION['pincode']; ?></td>
                        <td>
                            <a href="./editUser.php?id=<?php echo $rows['id']; ?>"><button type="button" class="btn btn-outline-warning">Edit</button></a>
                            <a href="./deleteUser.php?id=<?php echo $rows['id']; ?>"><button type="button" class="btn btn-outline-danger" style="color: red;" onclick="return confirm('Are you sure you want to delete this record ?')">Delete</button></a>
                        </td>
                    </tr>
                <?php 
                }  
               
                else{             
                        while ($rows = $result->fetch_assoc()) 
                        {
                            ?>

                    <tr>
                        <td><?php echo $rows['id']; ?></td>
                        <td><?php echo $rows['first_name']; ?></td>
                        <td><?php echo $rows['last_name']; ?></td>
                        <td><?php echo $rows['email']; ?></td>
                        <td><?php echo $rows['phone_no']; ?></td>
                        <td><?php echo $rows['address']; ?></td>
                        <td><?php echo $rows['country']; ?></td>
                        <td><?php echo $rows['state']; ?></td>
                        <td><?php echo $rows['pincode']; ?></td>
                        <td>
                            <a href="./editUser.php?id=<?php echo $rows['id']; ?>"><button type="button" class="btn btn-outline-warning">Edit</button></a>
                            <a href="./deleteUser.php?id=<?php echo $rows['id']; ?>"><button type="button" class="btn btn-outline-danger" style="color: red;" onclick="return confirm('Are you sure you want to delete this record ?')">Delete</button></a>
                        </td>
                    </tr>
                    <?php
                        }
                }
                 ?>  
                    
               
            <tbody>