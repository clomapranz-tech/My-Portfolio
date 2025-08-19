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
            <?php include('message.php'); ?>
            <div class="card">
                <div class="card-header">
                    <h4>Registered User
                        <a href="register-add.php" class="btn btn-primary float-end">Add Account</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table id="myUsers" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registered Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT id, fname, lname, email, role_as, created_at FROM users";
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                $roles = [
                                    '0' => 'User',
                                    '1' => 'Admin',
                                    '2' => 'Super Admin',
                                    '3' => 'Department Editor',
                                    '4' => 'Department Head',
                                    '5' => 'Finance Department',
                                ];
                                foreach ($query_run as $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['fname']; ?></td>
                                        <td><?= $row['lname']; ?></td>
                                        <td><?= $row['email']; ?></td>
                                        <td>
                                            <?= isset($roles[$row['role_as']]) ? $roles[$row['role_as']] : 'Unknown Role'; ?>
                                        </td>
                                        <td><?= $row['created_at']; ?></td>
                                        <td>
                                            <a href="register-edit.php?id=<?= $row['id']; ?>" class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i></a>
                                            <form action="code.php" method="POST" class="delete-form d-inline">
                                                <button type="submit" name="user_delete" value="<?= $row['id']; ?>" class="btn btn-danger btn-sm deleteButton"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="7">No Records Found</td>
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
</div>
<?php
include('includes/footer.php');
include('includes/scripts.php');
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!confirm('Are you sure you want to delete this user?')) {
                    event.preventDefault(); // Prevent form submission
                }
            });
        });
    });
</script>