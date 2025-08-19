<?php
include('authentication.php');
include('includes/header.php');
include('includes/scripts.php');

?>

<div class="container-fluid px-4">
    <h4 class="mt-4">Full Purchase Request Details</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">Full Purchase Request Details</li>
    </ol>
    <div class="row">
        <div class="col-md-12">
            <?php include('message.php'); ?>
            <div class="card">
                <!-- Request Details Card -->
                <div class="card-header">
                    <h4>Full Information</h4> <a href="purchase_request-view.php" class="btn btn-danger float-end">BACK</a>
                </div>
                <div class="card-body">
                    <form action="code.php" method="POST">
                        <input type="hidden" name="request_id" value="<?= $_GET['request_id']; ?>">
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
                    </form>
                </div>
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
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
