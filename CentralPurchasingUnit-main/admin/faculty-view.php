<?php
include('authentication.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Faculty</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Faculty</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <?php include('message.php'); ?>
                <div class="card">
                    <div class="card-header">
                        <h4>View Faculty
                        <a href="faculty-add.php" class="btn btn-primary float-end">Add Faculty</a>
                        </h4>
                    </div>
                    <div class="card-body">

                    <table id="myFaculty" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>College</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Image</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //$posts = "SELECT * FROM posts WHERE status != '2'";
                            $students = "SELECT * FROM faculty";
                            $students_run = mysqli_query($con, $students);
                            if (mysqli_num_rows($students_run) > 0) {
                                foreach ($students_run as $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['fname']; ?></td>
                                        <td><?= $row['lname']; ?></td>
                                        <td><?= $row['email']; ?></td>
                                        <td>
                                                <?php 
                                                if($row['college_id'] > 0)
                                                {
                                                    $college_query = "SELECT name FROM college WHERE id = ".$row['college_id'];
                                                    $college_query_run = mysqli_query($con, $college_query);
                                                    if(mysqli_num_rows($college_query_run) > 0)
                                                    {
                                                        foreach($college_query_run as $college_list)
                                                        {
                                                            echo $college_list['name'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No College Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No College Found";
                                                }
                                                
                                                ?>
                                            
                                            </td>

                                            <td>
                                                <?php 
                                                if($row['department_id'] > 0)
                                                {
                                                    $department_query = "SELECT name FROM department WHERE id = ".$row['department_id'];
                                                    $department_query_run = mysqli_query($con, $department_query);
                                                    if(mysqli_num_rows($department_query_run) > 0)
                                                    {
                                                        foreach($department_query_run as $department_list)
                                                        {
                                                            echo $department_list['name'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No Department Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No Department Found";
                                                }
                                                
                                                ?>
                                            
                                            </td>

                                            <td><?php 
                                            if ($row['role']==0) {
                                                echo "Faculty";
                                            } elseif ($row['role'] == 1) {
                                                echo "Coordinator";
                                            } elseif ($row['role'] == 2) {
                                                echo "Department Head";
                                            } elseif ($row['role'] == 3) {
                                                echo "Dean";
                                            } else {
                                                echo "Unknown Role";
                                            }
                                            ?>
                                        </td>
                                        
                                        <td><img src="../uploads/faculty/<?=$row['image']; ?>" width="60px" height="60px"></td>

                                        <td>
                                            <a href="faculty-edit.php?id=<?= $row['id']; ?>" class="btn btn-primary">Edit</a>
                                        </td>
                                        <td>
                                            <form action="code.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <button type="submit" name="faculty_delete_btn" value="<?=$post['id']?>" class="btn btn-danger deleteButton">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">No Record Found</td>
                                </tr>
                                <?php
                            }
                            ?>
                           
                        </tbody>
                    </table>
                        
                    </div>
            </div>
        </div>
</div>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>