<?php
include('authentication.php');
include('includes/header.php');

// Assume $con is your database connection

// Fetch the student details based on ID
if(isset($_GET['id'])) {
    $faculty_id = $_GET['id'];
    $faculty_query = "SELECT * FROM faculty WHERE id = '$faculty_id'";
    $faculty_query_run = mysqli_query($con, $faculty_query);
    if(mysqli_num_rows($faculty_query_run) > 0) {
        $faculty_data = mysqli_fetch_assoc($faculty_query_run);
    } else {
        // Redirect or display error message if student not found
    }
} else {
    // Redirect or display error message if no ID provided
}
?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Edit Faculty</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">Faculty</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <?php include('message.php'); ?>
            <div class="card">
                <div class="card-header">
                    <h4>Edit Faculty
                        <a href="faculty-view.php" class="btn btn-danger float-end">BACK</a>
                    </h4>
                </div>
                <div class="card-body">
                <?php
                        $faculty_id = $_GET['id'];
                        $faculty_query = "SELECT * FROM faculty WHERE id = '$faculty_id' LIMIT 1";
                        $faculty_query_run = mysqli_query($con, $faculty_query);
                        if(mysqli_num_rows($faculty_query_run) > 0) 
                        {
                            $faculty_row = mysqli_fetch_array($faculty_query_run);
                        ?>

                    <form action="code.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="faculty_id" value="<?php echo $faculty_data['id']; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="">First Name</label>
                                <input type="text" name="fname" value="<?php echo $faculty_data['fname']; ?>" required class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="">Last Name</label>
                                <input type="text" name="lname" value="<?php echo $faculty_data['lname']; ?>" required class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">Email</label>
                                <input type="text" name="email" value="<?php echo $faculty_data['email']; ?>" required class="form-control">
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
                                            <option value="<?=$college_list['id']; ?>" <?=$college_list['id'] == $faculty_data['college_id'] ? 'selected' : '' ?>>
                                                <?= $college_list['name'];?>
                                            </option>
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
                                             <option value="<?=$department_list['id']; ?>" <?=$department_list['id'] == $faculty_data['department_id'] ? 'selected' : '' ?>>
                                                <?= $department_list['name'];?>
                                            </option>
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
                                    <?php
                                    $faculty_query = "SELECT * FROM faculty";
                                    $faculty_query_run = mysqli_query($con, $faculty_query);
                                    if(mysqli_num_rows($faculty_query_run) > 0){}
                                    ?>

                                    <select name="role" required class="form-control">
                                        <option value="">--Select Role--</option>
                                        
                                        <option value="0"<?= $faculty_data['role'] =='0' ? 'selected': '' ; ?>>Faculty</option>
                                        <option value="1"<?= $faculty_data['role'] =='1' ? 'selected': '' ; ?>>Coordinator</option>
                                        <option value="2"<?= $faculty_data['role'] =='2' ? 'selected': '' ; ?>>Department Head</option>
                                        <option value="3"<?= $faculty_data['role'] =='3' ? 'selected': '' ; ?>>Dean</option>
                                    </select>


                            </div>

                            <div class="col-md-6 mb-3">
                                        <label for="">Image</label>
                                        <input type="hidden" name="old_image" value="<?= $faculty_row['image']?>">
                                        <input type="file" name="image" class="form-control">
                                    
                                        <div class="navbar navbar-light" style="background-color: #e3f2fd;">
                                        <label for="" >Current Image:</label>
                                        <img src="../uploads/faculty/<?= $faculty_row['image']; ?>" width="60px" height="60px">
                                        </div>
                            </div>


                            <div class="col-md-12 mb-3">
                                <button type="submit" name="update_faculty" class="btn btn-primary">Update Faculty</button>
                            </div>
                        </div>
                    </form>
                    <?php

                        }
                        else
                        {
                            ?>
                            <h4>No Record Found</h4>
                            <?php
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
