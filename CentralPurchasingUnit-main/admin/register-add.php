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
                        <h4>Add User/Admin
                        <a href="view-register.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                    
                    <form action="code.php" method="POST">
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
                                    <label for="">Password</label>
                                    <input type="text" name="password" required class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="">Role As</label>
                                    <select name="role_as" required class="form-control">
                                        <option value="">--Select Role--</option>    
                                        <option value="5">Finance Department</option>
                                        <option value="4">Department Head</option>
                                        <option value="3">Department Editor</option>
                                        <option value="2">Super Admin</option>
                                        <option value="1">Admin</option>
                                        <option value="0">User</option>
                                    </select>

                                </div>
                                <!-- <div class = "col-md-12 mb-3">
                                    <label for="">Status</label>
                                    <input type = "checkbox" name = "status"  width = "70px" height = "70px">
                                </div> -->
                                <div class="col-md-12 mb-3">
                                    <button type="submit" name = "add_user" class="btn btn-primary">Add Admin/User</button>
                                
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