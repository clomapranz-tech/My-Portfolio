<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('message.php');
include('authentication.php');

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


// Query to fetch requests with pagination
$query = "SELECT * FROM purchase_requests ORDER BY id DESC";
$query_run = mysqli_query($con, $query);

?>
<link rel="stylesheet" href="css/dataTables.dataTables.css" />
<script src="js/jquery.min.js"></script>
<script src="js/dataTables.min.js"></script>

    <script type="text/javascript" language="javascript" class="init">
        jQuery(document).ready(function($) {
            $('#myTables').DataTable({
                "order": [[ 0, "desc" ]]
            });
});

    </script>

<!-- Container for Table -->
<div class="container mx-auto px-4 sm:px-8 shadow">
<a href="myrequests.php" class="btn btn-danger float-end">BACK</a>
    <div class="py-8">
        <div>
            <h2 class="text-2xl font-semibold leading-tight">My Requests</h2> 
        </div>
        <div class="my-2 flex sm:flex-row flex-col">
        <table class="table table-bordered">
            <tbody>
                <?php
                $request_id = $_GET['request_id'];
                $query = "SELECT * FROM purchase_requests WHERE id = $request_id";
                $result = mysqli_query($con, $query);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    foreach ($row as $key => $value) {
                        echo "<tr>";
                        echo "<td>" . ucwords(str_replace("_", " ", $key)) . "</td>";
                        echo "<td>$value</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        </div>

        <div class="card">
                <!-- Related Items Card -->
                <div class="card-header">
                    <h4>Items</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <?php
                                // Fetch column names for items table
                                $result = mysqli_query($con, "SHOW COLUMNS FROM items");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<th>" . ucwords(str_replace("_", " ", $row['Field'])) . "</th>";
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch All items related to this request
                            $request_id = $_GET['request_id'];
                            $query = "SELECT * FROM items WHERE purchase_request_id = $request_id";
                            $result = mysqli_query($con, $query);
                            foreach ($result as $row) {
                                echo "<tr>";
                                foreach ($row as $key => $value) {
                                    echo "<td>$value</td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <br>
        <br>
        <br>
    </div>
</div>


<!-- Footer -->
<?php include('includes/footer.php'); ?>
