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
            <?php include('message.php'); ?>
            <div class="card">
                <div class="card-header">
                    <h4>View Departments
                        <a href="department-add.php" class="btn btn-primary float-end">Add Departments</a>
                    </h4>
                </div>
                <div class="card-body">
                    <table id="myDepartment" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Department Name</th>
                                <th>College</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM department";
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $row) {
                            ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['name']; ?></td>
                                        <td>
                                            <?php
                                            if ($row['college_id'] > 0) {
                                                $college_query = "SELECT name FROM college WHERE id = " . $row['college_id'];
                                                $college_query_run = mysqli_query($con, $college_query);
                                                if (mysqli_num_rows($college_query_run) > 0) {
                                                    foreach ($college_query_run as $college_list) {
                                                        echo $college_list['name'];
                                                    }
                                                } else {
                                                    echo "No College Found";
                                                }
                                            } else {
                                                echo "No College Found";
                                            }

                                            ?>

                                        </td>
                                        <td><a href="department-edit.php?id=<?= $row['id']; ?>" class="btn btn-success">Edit</a></td>
                                        <td>
                                            <form action="code.php" method="POST">
                                                <button type="submit" name="department_delete" value="<?= $row['id']; ?>" class="btn btn-danger deleteButton">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6">No Records Found</td>
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