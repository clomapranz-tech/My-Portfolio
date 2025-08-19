<?php
include('authentication.php');
include('authentication_super_only.php');
include('includes/header.php');
?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Users</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Users</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit User
                        <a href="view-register.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                    <?php 
                    //Checks if the id is set in the URL
                    if(isset($_GET['id']))
                    {
                        $user_id = $_GET['id'];
                        $users = "SELECT * FROM users WHERE id = '$user_id'";
                        $users_run = mysqli_query($con, $users);

                        if(mysqli_num_rows($users_run) > 0)
                        {
                            foreach($users_run as $user)
                            {  
                            ?>

                           

                        <form action="code.php" method="POST">
                            <input type="hidden" name="id" value="<?= $user['id'];?>">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="">First Name</label>
                                    <input type="text" name="fname" class="form-control" value="<?= $user['fname'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Last Name</label>
                                    <input type="text" name="lname" class="form-control" value="<?= $user['lname'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Email</label>
                                    <input type="text" name="email" class="form-control" value="<?= $user['email'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Password</label>
                                    <input type="text" name="password" class="form-control" value="<?= $user['password'];?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Role As</label>
                                    <select name="role_as" required class="form-control">
                                        <option value="">--Select Role--</option>
                                        <option value="0"<?= $user['role_as'] =='0' ? 'selected': '' ; ?>>User</option>
                                        <option value="1"<?= $user['role_as'] =='1' ? 'selected': '' ; ?>>Admin</option>
                                        <option value="2"<?= $user['role_as'] =='2' ? 'selected': '' ; ?>>Super Admin</option>
                                        <option value="3"<?= $user['role_as'] =='3' ? 'selected': '' ; ?>>Department Editor</option>
                                        <option value="4"<?= $user['role_as'] =='4' ? 'selected': '' ; ?>>Department Head</option>

                                    </select>

                                </div>
        
                                <div class="col-md-12 mb-3">
                                    <button type="submit" name = "update_user" class="btn btn-primary">Update User</button>
                                
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