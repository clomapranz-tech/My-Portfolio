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
                        <h4>Add School Year
                        <a href="school_year-view.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                    
                    <form action="code.php" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="">School Year</label>
                                    <input type="text" name="school_year" required class="form-control" placeholder="e.x 2023-2024">
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <button type="submit" name = "add_school_year" class="btn btn-primary">Add School Year</button>
                                
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