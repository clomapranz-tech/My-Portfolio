<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('config/dbcon.php');

// Check if $_GET['id'] is set
if(isset($_GET['id'])) {
    $requestId = mysqli_real_escape_string($con, $_GET['id']);

    // Query to fetch Request details
    $query1 = "SELECT * FROM requests WHERE id = $requestId";
    $query1_run = mysqli_query($con, $query1);

    // Check if the query was successful
    if($query1_run) {
        // Check if the Request details are found
        if(mysqli_num_rows($query1_run) > 0) {
            include('message.php'); ?>
            <div class="container-fluid custombg-image-row ">
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-md-3 fixed-left" style="width:350px">
                        <?php include('includes/sidebar.php'); ?>
                    </div>
                    <!-- Main Body -->
                    <div class="col-md-9">
                        <div class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-header">
                                        <h4 class="card-title text-center customHome">Request Details</h4>
                                    </div>
                                </div>
                                <?php
                                // Display Request details
                                foreach($query1_run as $item) {
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Request ID</h5>
                                                <p class="card-text"><?= $item['id']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Inventory Item ID</h5>
                                                <!-- Fetch inventory name using inventory_id -->
                                                <p class="card-text"> 
                                                <?php 
                                                if($item['inventory_id'] > 0)
                                                {
                                                    $inventory_query = "SELECT name FROM inventory WHERE id = ".$item['inventory_id'];
                                                    $inventory_query_run = mysqli_query($con, $inventory_query);
                                                    if(mysqli_num_rows($inventory_query_run) > 0)
                                                    {
                                                        foreach($inventory_query_run as $inventory_list)
                                                        {
                                                            echo $inventory_list['name'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No Inventory Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No Inventory Found";
                                                }
                                                
                                                ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Request Name</h5>
                                                <p class="card-text">
                                                    <?= $item['name']; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">College</h5>
                                                <p class="card-text">
                                                <!-- Fetch college name using college_id -->
                                                <?php 
                                                if($item['college_id'] > 0)
                                                {
                                                    $college_query = "SELECT name FROM college WHERE id = ".$item['college_id'];
                                                    $college_query_run = mysqli_query($con, $college_query);
                                                    if(mysqli_num_rows($college_query_run) > 0)
                                                    {
                                                        foreach($college_query_run as $college_list)
                                                        {
                                                            echo $college_list['name'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No College Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No College Found";
                                                }
                                                
                                                ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Department</h5>
                                                <p class="card-text">
                                                <?php 
                                                if($item['department_id'] > 0)
                                                {
                                                    $department_query = "SELECT name FROM department WHERE id = ".$item['department_id'];
                                                    $department_query_run = mysqli_query($con, $department_query);
                                                    if(mysqli_num_rows($department_query_run) > 0)
                                                    {
                                                        foreach($department_query_run as $department_list)
                                                        {
                                                            echo $department_list['name'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No Department Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No Department Found";
                                                }
                                                
                                                ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Status</h5>
                                                <p class="card-text">
                                                <?php 
                                            if ($item['status']==0) {
                                                echo "Received by CPU";
                                            } elseif ($item['status'] == 1) {
                                                echo "Left CPU office";
                                            } elseif ($item['status'] == 2) {
                                                echo "Received by Registrar";
                                            } elseif ($item['status'] == 3) {
                                                echo "Left Registrar office";
                                                
                                            }elseif ($item['status'] == 4) {
                                                echo "Received by VPadmin";
                                                
                                                
                                            }elseif ($item['status'] == 5) {
                                                echo "Left VPadmin office";
                                                
                                                
                                            }elseif ($item['status'] == 6) {
                                                echo "Received by President";
                                                
                                                
                                            }elseif ($item['status'] == 7) {
                                                echo "Left President office";
                                                
                                                
                                            }elseif ($item['status'] == 8) {
                                                echo "Approved";
                                                
                                            } else {
                                                echo "Unknown Status";
                                            }
                                            ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Request Received Date</h5>
                                                <p class="card-text"><?= $item['request_received_date']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Expected Delivery Date</h5>
                                                <p class="card-text"><?= $item['expected_delivery_date']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Actual Delivery Date</h5>
                                                <p class="card-text"><?= $item['actual_delivery_date']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">Semester</h5>
                                                <p class="card-text"><?= $item['semester']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">School Year</h5>
                                                <p class="card-text">
                                                <?php 
                                                if($item['school_year_id'] > 0)
                                                {
                                                    $sy_query = "SELECT school_year FROM school_year WHERE id = ".$item['school_year_id'];
                                                    $sy_query_run = mysqli_query($con, $sy_query);
                                                    if(mysqli_num_rows($sy_query_run) > 0)
                                                    {
                                                        foreach($sy_query_run as $sy_list)
                                                        {
                                                            echo $sy_list['school_year'];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No SY Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No SY Found";
                                                }
                                                
                                                ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                        </div>
                    </div>
                    
                </div>
                <?php include('includes/footer.php'); ?>
            </div>
            
        <?php } else {
            // If project details are not found
            echo "Project not found.";
        }
    } else {
        // Error handling for the first query
        echo "Error: " . mysqli_error($con);
    }
} else {
    // If $_GET['id'] is not set
    echo "No project ID provided.";
}




?>
