<?php
include('authentication.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
    <h4 class="mt-4">Colleges</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">Colleges</li>
    </ol>
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit College
                        <a href="college-view.php" class="btn btn-danger float-end">BACK</a>
                    </h4>
                </div>
                <div class="card-body">

                    <?php
                    //Checks if the id is set in the URL
                    if (isset($_GET['id'])) {
                        $college_id = $_GET['id'];
                        $colleges = "SELECT * FROM college WHERE id = '$college_id'";
                        $colleges_run = mysqli_query($con, $colleges);

                        if (mysqli_num_rows($colleges_run) > 0) {
                            foreach ($colleges_run as $college) {
                    ?>
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $college['id']; ?>">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="">Name</label>
                                            <input type="text" name="name" class="form-control" value="<?= $college['name']; ?>">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <button type="submit" name="update_college" class="btn btn-primary">Update College</button>

                                        </div>

                                </form>
                            <?php
                            }
                        } else {
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