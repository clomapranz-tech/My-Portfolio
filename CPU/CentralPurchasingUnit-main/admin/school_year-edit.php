<?php
include('authentication.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">School Years</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">School Years</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit School Year
                        <a href="school_year-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                    <?php 
                    //Checks if the id is set in the URL
                    if(isset($_GET['id']))
                    {
                        $sy_id = $_GET['id'];
                        $sys = "SELECT * FROM school_year WHERE id = '$sy_id'";
                        $sys_run = mysqli_query($con, $sys);

                        if(mysqli_num_rows($sys_run) > 0)
                        {
                            foreach($sys_run as $sy)
                            {  
                            ?>

                           

                        <form action="code.php" method="POST">
                            <input type="hidden" name="id" value="<?= $sy['id'];?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="">School Year</label>
                                    <input type="text" name="school_year" class="form-control" value="<?= $user['school_year'];?>">
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <button type="submit" name = "update_school_year" class="btn btn-primary">Update School Year</button>
                                
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