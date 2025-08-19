<?php
include('authentication.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Departments</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Departments</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Department
                        <a href="Department-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                    <?php 
                    //Checks if the id is set in the URL
                    if(isset($_GET['id']))
                    {
                        $department_id = $_GET['id'];
                        $departments = "SELECT * FROM department WHERE id = '$department_id'";
                        $departments_run = mysqli_query($con, $departments);

                        if(mysqli_num_rows($departments_run) > 0)
                        {
                            foreach($departments_run as $department)
                            {  
                            ?>

                           

                        <form action="code.php" method="POST">
                            <input type="hidden" name="id" value="<?= $department['id'];?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?= $department['name'];?>">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="">College</label>
                                    <?php
                                    $college_query = "SELECT * FROM college";
                                    $college_query_run = mysqli_query($con, $college_query);
                                    if(mysqli_num_rows($college_query_run) > 0) {
                                    ?>
                                        <select name="college_id" class="form-control select2">
                                            <option value="">--Select College--</option>
                                            <?php
                                            foreach($college_query_run as $college_list) {
                                                if($department['college_id'] == $college_list['id'])
                                                {
                                                    $selected = "selected";
                                                }
                                                else
                                                {
                                                    $selected = "";
                                                }
                                            ?>
                                                <option value="<?=$college_list['id']; ?>" <?= $selected; ?>> <?=$college_list['name'];?> </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    <?php
                                    } else {
                                        echo "No College Found";
                                    }
                                    ?>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <button type="submit" name = "update_department" class="btn btn-primary">Update Department</button>
                                
                            </div>

                        </form>
                        <?php
                            }
                        }
                        else
                        {
                            ?>
                            <h4>No Records Found</h4>
                            <?php

                        }
                    }

                    ?>


                    </div>
                </div>
            </div>
        </div>
</div>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>