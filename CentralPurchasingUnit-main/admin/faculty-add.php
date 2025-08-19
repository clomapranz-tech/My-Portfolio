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
                        <h4>Add Faculty
                        <a href="faculty-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                    
                    <form action="code.php" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="">First Name</label>
                                    <input type="text" name="fname" required class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Last Name</label>
                                    <input type="text" name="lname" required class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Email</label>
                                    <input type="text" name="email" required class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                            <label for="">College</label>
                                <?php
                                $college_query = "SELECT * FROM college";
                                $college_query_run = mysqli_query($con, $college_query);
                                if(mysqli_num_rows($college_query_run) > 0) {
                                ?>
                                    <select name="college_id" required class="form-control select2">
                                        <option value="">--Select College--</option>
                                        <?php
                                        foreach($college_query_run as $college_list) {
                                        ?>
                                            <option value="<?=$college_list['id']; ?>"> <?=$college_list['name'];?> </option>
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

                            <div class="col-md-6 mb-3">
                            <label for="">Department</label>
                                <?php   
                                $department_query = "SELECT * FROM department";
                                $department_query_run = mysqli_query($con, $department_query);
                                if(mysqli_num_rows($department_query_run) > 0) {
                                ?>
                                    <select name="department_id" required class="form-control select2">
                                        <option value="">--Select Department--</option>
                                        <?php
                                        foreach($department_query_run as $department_list) {
                                        ?>
                                            <option value="<?=$department_list['id']; ?>"> <?=$department_list['name'];?> </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                <?php
                                } else {
                                    echo "No Department Found";
                                }
                                ?>
                            </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Role</label>
                                    <select name="role" required class="form-control">
                                        <option value="">--Select Role--</option>
                                        <option value="0">Faculty</option>
                                        <option value="1">Coordinator</option>
                                        <option value="2">Department Head</option>
                                        <option value="3">Dean</option>
                                    </select>

                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">Image</label>
                                    <input type="file" name="image" required class="form-control">
                                </div>
                                
                            
                                
                                
                                <div class="col-md-12 mb-3">
                                    <button type="submit" name = "add_faculty" class="btn btn-primary">Add Faculty</button>
                                
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
</div>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>