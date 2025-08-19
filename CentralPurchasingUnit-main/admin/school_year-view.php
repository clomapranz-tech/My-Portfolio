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
                <?php include('message.php'); ?>
                <div class="card">
                    <div class="card-header">
                        <h4>School Years
                        <a href="school_year-add.php" class="btn btn-primary float-end">Add School Years</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table id="mySchoolyear" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>School Year</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM school_year";
                                $query_run = mysqli_query($con, $query);

                                if(mysqli_num_rows($query_run) > 0)
                                {
                                    foreach($query_run as $row)
                                    {
                                        ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td><?= $row['school_year']; ?></td>
                                           
                                            <td><a href="school_year-edit.php?id=<?=$row['id'];?>" class="btn btn-success">Edit</a></td>
                                            <td>
                                                <form action="code.php" method="POST">
                                                <button type="submit" name="school_year_delete" value="<?=$row['id'];?>" class="btn btn-danger deleteButton">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else
                                {
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