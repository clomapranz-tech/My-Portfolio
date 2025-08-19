<?php
include('authentication.php');
include('includes/header.php');
include('includes/scripts.php');


//Initialize Variable
$admin = null;
$super_user = null;
$department_editor = null;
//Check level
if($_SESSION['auth_role']==1)
{
    $admin = true;
    $super_user = false;
    $department_editor = false;
}
elseif($_SESSION['auth_role']==2)
{
    $admin = false;
    $super_user = true;
    $department_editor = false;
}
elseif($_SESSION['auth_role']==3)
{
    $admin = false;
    $super_user = false;
    $department_editor = true;
}

$request_id = $_GET['request_id'];
//Check if at least 1 item is completed, then update purchase request status to partially completed
$query = "SELECT * FROM items WHERE purchase_request_id = $request_id AND item_status = 'completed'";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result) > 0) {
    $query = "UPDATE purchase_requests SET status = 'partially-completed' WHERE id = $request_id";
    mysqli_query($con, $query);
//Check if all items are completed, then update purchase request status to completed
$query = "SELECT * FROM items WHERE purchase_request_id = $request_id AND item_status = 'completed'";
$result = mysqli_query($con, $query);
if (mysqli_num_rows($result) > 0) {
    $query = "UPDATE purchase_requests SET status = 'completed' WHERE id = $request_id";
    mysqli_query($con, $query);
}


}

?>


<div class="container-fluid px-4">
        <h4 class="mt-4">Purchase Item Details</h4>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
                <li class="breadcrumb-item">Purchase Request Details</li>
            </ol>
            <div class="row">

            <div class="col-md-12">
                <?php include('message.php'); ?>
                <div class="card">
                    <!-- Request Details Card -->
                    <div class="card-header">
                        <h4>Request Details
                        <a href="purchase_request-view.php" class="btn btn-danger float-end">Back</a>
                        </h4>
                        <div class="btn-group float-end" role="group" aria-label="Basic example">
    

                    </div>
                    <div class="card-body">
                        
                    </div>



                    <!-- Request Details Table -->
                    <table  class="table table-bordered table-striped">
                        <thead>
                            <tr>
                            <th>ID</th>
                                <th>Purchase Request Number</th>
                                <th>Requestor Name</th>
                                <th>Department/College</th>
                                <th>Iptel#/Email</th>
                                <th>Above P50,000?</th>
                                <th>Department Head Approval By</th>
                                <th>Requested Date</th>
                                <th>Status</th>
                                <?php if ($super_user) { ?><th>Assigned To</th><?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $request = "SELECT * FROM purchase_requests WHERE id = $_GET[request_id]";
                            $request_run = mysqli_query($con, $request);
                            if (mysqli_num_rows($request_run) > 0) {
                                foreach ($request_run as $row) {
                                     // Check if requested_date is older than 30 days from the current day
                                    $received_date = strtotime($row['requested_date']);
                                    $current_date = strtotime(date('Y-m-d'));
                                    $difference = ($current_date - $received_date) / (60 * 60 * 24); // Difference in days

                                    // Add a CSS class based on the condition
                                    $row_class = '';
                                    $Changetext_color = 'black';
                                    if ($difference >= 30 && $row['status'] != 'approved') {
                                        $row_class = 'bg-danger'; // Older than or equal to 30 days, set background to red
                                        $Changetext_color = 'white'; // Set text color to white
                                    } elseif ($difference >= 15 && $row['status'] != 'approved') {
                                        $row_class = 'bg-warning'; // Older than or equal to 15 days but less than 30, set background to yellow
                                        $Changetext_color = 'black'; // Set text color to dark
                                    } elseif ($row['status'] == 'approved') {
                                        $row_class = 'bg-success'; // Status is 'Approved', set background to green
                                        $Changetext_color = 'white'; // Set text color to white
                                    }
                                     
                                    ?>
                                    <tr class="<?= $row_class ?>">
                                        <td style="color:<?= $Changetext_color ?>">
                                            <a href="purchase_request_history.php?request_id=<?= $row['id']; ?>">
                                                <?= $row['id']; ?> 
                                            </a>
                                        
                                        </td>
                                        
                                        <td style="color:<?= $Changetext_color ?>">
                                            
                                                <?php 
                                                echo $row['purchase_request_number']
                                                ?>
                                            </td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['printed_name']; ?></td>
                                        <td style="color:<?= $Changetext_color ?>">
                                                <?php 
                                                echo $row['unit_dept_college']
                                                
                                                ?>
                                            
                                            </td>
                                            <td style="color:<?= $Changetext_color ?>">
                                                <?php 
                                                echo $row['iptel_email']
                                                ?>
                                            </td>
                                            <td style="color:<?= $Changetext_color ?>">
                                            <?php 
                                            if ($row['above_50000'] == 0) {
                                                echo "Below P50,000";
                                            } elseif ($row['above_50000'] == 1) {
                                                echo "Above P50,000";
                                            }
                                            ?>
                                            </td>
                                        </td>
                                        <td style="color:<?= $Changetext_color ?>">
                                        <?= !empty($row['unit_head_approval_by']) ? $row['unit_head_approval_by'] : 'Pending Department Head Approval'; ?>
                                        </td>
                                        <td style="color:<?= $Changetext_color ?>"><?= date('F j Y h:i A', strtotime($row['requested_date'])); ?></td>
                                        <td style="color:<?= $Changetext_color ?>"><?= $row['status']; ?></td>
                                        
                                        
                                       
                                        <!-- If Super User, see Assigned User -->
                                        <?php if ($super_user) { ?>
                                        <td style="color:<?= $Changetext_color ?>">
                                        <?php 
                                                if($row['assigned_user_id'] > 0)
                                                {
                                                    $user_query = "SELECT * FROM users WHERE id = ".$row['assigned_user_id'];
                                                    $user_query_run = mysqli_query($con, $user_query);
                                                    if(mysqli_num_rows($user_query_run) > 0)
                                                    {
                                                        foreach($user_query_run as $user_list)
                                                        {
                                                            echo $user_list['email'].' | '.$user_list['fname'].' '.$user_list['lname'];;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "No Assigned User Found";
                                                    }
                                                }
                                                else
                                                {
                                                    echo "No Assigned User Found";
                                                }
                                                
                                                ?>
                                        </td>
                                            <?php } ?>
                                    </tr>
                                    <?php
                                }
                            } 
                            if ($difference >= 30 && $row['status'] != 'approved') {
                                echo "<tr><td colspan='100%' class='text-center text-white bg-danger'>This request is older than 30 days</td></tr>";
                            } 
                            elseif ($difference >= 15 && $row['status'] != 'approved') {
                                echo "<tr><td colspan='100%' class='text-center text-white bg-warning'>This request is older than 15 days</td></tr>";
                            } 
                            elseif ($row['status'] == 'pending') {
                                echo "<tr><td colspan='100%' class='text-center'>This request is still pending</td></tr>";
                            }
                            elseif ($row['status'] == 'partially-completed') {
                                echo "<tr><td colspan='100%' class='text-center'>This request is still partially-completed</td></tr>";
                            }
                            elseif ($row['status'] == 'completed') {
                                echo "<tr><td colspan='100%' class='text-center text-white bg-success'>This request is completed</td></tr>";
                            }
                            elseif ($row['status'] == 'rejected') {
                                echo "<tr><td colspan='100%' class='text-center text-white bg-danger'>This request has been rejected</td></tr>";
                            }
                            elseif ($row['status'] == 'approved') {
                                echo "<tr><td colspan='100%' class='text-center text-white bg-success'>This request has been approved</td></tr>";
                            }
                            ?>
                            
                        </tbody>
                    </table>


                    
                    
                    </div>
                    <!-- Items Card -->
                    <div class="card-header">
                        <h4>Items
                        </h4>
                        <div class="btn-group float-end" role="group" aria-label="Basic example">
                        </div>
                    <div class="card-body">
                                <!-- Items Table-->
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Item Number</th>
                            <th>Item Qty</th>
                            <th>Item Description</th>
                            <th>Item Justification</th>
                            <th>Item Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $items = "SELECT * FROM items WHERE purchase_request_id = $_GET[request_id]";
                        $items_run = mysqli_query($con, $items);
                        if (mysqli_num_rows($items_run) > 0) {
                            foreach ($items_run as $item_row) {
                                ?>
                                <tr>
                                    <td><?= $item_row['id']; ?></td>
                                    <td><?= $item_row['item_number']; ?></td>
                                    <td><?= $item_row['item_qty']; ?></td>
                                    <td><?= $item_row['item_description']; ?></td>
                                    <td><?= $item_row['item_justification']; ?></td>
                                    <td>
                                        <select class="item-status" data-item-id="<?= $item_row['id'];?>" style="width:300px;">
                                            <option value="pending" <?= ($item_row['item_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="approved" <?= ($item_row['item_status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>

                                            <option value="for_pricing" <?= ($item_row['item_status'] == 'for_pricing') ? 'selected' : ''; ?>>For Pricing</option>
                                            <option value="for_po" <?= ($item_row['item_status'] == 'for_po') ? 'selected' : ''; ?>>For Pricing Officer</option>
                                            <option value="issued_po" <?= ($item_row['item_status'] == 'issued_po') ? 'selected' : ''; ?>>Issued Pricing Officer</option>

                                            <option value="for_delivery_by_supplier" <?= ($item_row['item_status'] == 'for_delivery_by_supplier') ? 'selected' : ''; ?>>For Delivery by Supplier</option>
                                            <option value="for_pickup_at_supplier" <?= ($item_row['item_status'] == 'for_pickup_at_supplier') ? 'selected' : ''; ?>>For Pickup at Supplier</option>
                                            <option value="for_tagging" <?= ($item_row['item_status'] == 'for_tagging') ? 'selected' : ''; ?>>For Tagging</option>
                                            <option value="for_delivery_to_requesting_unit" <?= ($item_row['item_status'] == 'for_delivery_to_requesting_unit') ? 'selected' : ''; ?>>For Delivery to Requesting Unit</option>

                                            <option value="completed" <?= ($item_row['item_status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                            <option value="rejected" <?= ($item_row['item_status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                        </select>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "No Items Found";
                        }
                        ?>
                    </tbody>
                </table>

                    </div>
                    <!-- JavaScript to update item status (Redirects to javascript-update_item_status.php)-->
                    <script>
                    // Add event listener to all item status dropdowns
                    document.querySelectorAll('.item-status').forEach(function(select) {
                        select.addEventListener('change', function() {
                            <?php
                            // Get the current user from PHP session
                            $CURRENT_USER = $_SESSION['auth_user']['user_name'];
                            // Echo the PHP variable into JavaScript
                            echo "var user = '$CURRENT_USER';";
                            ?>
                            // Get the selected value and item id, and other values for history logs
                            var newStatus = this.value;
                            var itemId = this.getAttribute('data-item-id');
                            var purchase_request_id = <?= $_GET['request_id']; ?>;
                            var change_made = 'Item status changed to ' + newStatus;
                            var last_modified_by = user;

                            // Send AJAX request to update item status
                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', 'javascript-update_item_status.php', true);
                            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            xhr.onload = function() {
                                // Update table cell with new status
                                if (xhr.status === 200) {
                                    // Assuming the response contains the updated status
                                    var response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        // Update the table cell text
                                        var statusCell = document.querySelector('.item-status[data-item-id="'+ itemId +'"]');
                                        statusCell.textContent = newStatus;
                                        location.reload();
                                    } else {
                                        console.error('Error updating item status');
                                    }
                                }
                            };
                            xhr.send('id=' + itemId + '&new_status=' + newStatus + '&purchase_request_id=' + purchase_request_id + '&change_made=' + change_made + '&last_modified_by=' + last_modified_by);
                        });
                    });
                </script>
                <!-- JavaScript to check and update purchase request status -->
                <script>
                    function checkAndUpdatePurchaseStatus() {
                        var purchase_request_id = <?= $_GET['request_id']; ?>;
                        // Send AJAX request to check and update purchase request status
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'javascript-check_purchase_status.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                // Optional: You can handle the response here if needed
                            } else {
                                console.error('Error checking and updating purchase request status');
                            }
                        };
                        xhr.send('purchase_request_id=' + purchase_request_id);
                    }

                    // Call checkAndUpdatePurchaseStatus when the page is fully loaded
                    window.onload = function() {
                        checkAndUpdatePurchaseStatus();
                    };
                </script>




                    

                    <!-- Item History Card -->
                    <div class="card-header">
                        <h4>Item History Logs
                        </h4>
                        <div class="btn-group float-end" role="group" aria-label="Basic example">
    

                    </div>
                    <div class="card-body">
                        
                    </div>



                    <!-- Item History Table -->
                    <table class="table table-bordered table-striped" id="myItemHistory">
                        <thead>
                            <tr>
                                <th>Item ID</th>
                                <th>Change Made</th>
                                <th>Last Modified by</th>
                                <th>DateTime Occured</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $request_history = "SELECT * FROM items_history WHERE purchase_request_id = $_GET[request_id] ORDER BY datetime_occured DESC";
                            $request_history_run = mysqli_query($con, $request_history);
                            if (mysqli_num_rows($request_history_run) > 0) {
                                foreach ($request_history_run as $row) {
                                    ?>
                                    <tr>
                                        <td><?= $row['item_id']; ?></td>
                                        <td><?= $row['change_made']; ?></td>
                                        <td><?= $row['last_modified_by']; ?></td>

                                        
                                        <td><?= date('F j Y h:i A', strtotime($row['datetime_occured'])); ?></td>

                                        
                                        
                                            
                                        
                                     
                                    </tr>
                                    <?php
                                }
                            }
                            else{echo "No Item History Found";} 
                            ?>
                            
                        </tbody>
                    </table>


                    
                    
                    </div>
            </div>
        </div>
</div>
        
<?php
include('includes/footer.php');



?>

