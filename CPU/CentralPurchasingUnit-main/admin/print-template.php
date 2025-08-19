<?php
include('config/dbcon.php');

// Get purchase_request_id from URL
$purchase_request_id = $_GET['id'];
// echo $purchase_request_id;
// echo '<a href="purchase_request-view.php" class="btn btn-danger float-end">BACK</a>';

// Fetch item data from database
$item_query = "SELECT * FROM items WHERE purchase_request_id = $purchase_request_id";
$item_query_run = mysqli_query($con, $item_query);
$items = mysqli_fetch_all($item_query_run, MYSQLI_ASSOC);

// Define the maximum number of items per page
$max_items_per_page = 10;

// Split items into multiple pages
$pages = array_chunk($items, $max_items_per_page);

// Fetch purchase request data from database
$pr_query = "SELECT * FROM purchase_requests WHERE id = $purchase_request_id";
$pr_query_run = mysqli_query($con, $pr_query);
$pr_row = mysqli_fetch_assoc($pr_query_run);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPU Print Template</title>
    <link rel="stylesheet" href="print-template.css">
    <link rel="icon" href="../admin/css/favicon.ico" type="image/ico">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <style>
        @media print {

            button,
            a {
                display: none !important;
            }
        }
    </style>

    <!-- XU Logo -->
    <div class="logoDiv">
        <img src="../assets/images/XULOGO.png" alt="XU Logo" class="xuLogo" style="width:200px">
    </div>
    <div style="margin:1rem; margin-top: -5rem; display: flex; justify-content: end; gap: 10px;">
        <button class="btn btn-primary" onclick="printPage()">PRINT</button>
        <a href="purchase_request-view.php" class="btn btn-danger">BACK</a>

    </div>
    <?php
    // Loop through each page
    foreach ($pages as $page_items) {
    ?>
        <!-- Header -->
        <div class="prDiv">
            <!-- Purchase Request# (Dynamically fetch from purchase_requests table in database) -->
            <label for="pr_input">Purchase Request#</label>
            <input id="pr_input" type="text" class="pr_input" value="<?= $pr_row['purchase_request_number'] ?>"></input>
        </div>
        <div class="headerTitle">
            <h1>PURCHASE REQUEST FORM</h1>
        </div>

        <!-- Item Table -->
        <div class="tableDiv">
            <table class="tableMain">
                <thead>
                    <tr>
                        <th>ITEM#</th>
                        <th>QTY/UNIT</th>
                        <th>DESCRIPTION</th>
                        <th>JUSTIFICATION</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamically fetch items from items table in database with matching purchase_request_id -->
                    <?php foreach ($page_items as $item) : ?>
                        <tr>
                            <td><?php echo $item['item_number']; ?></td>
                            <td><?php echo $item['item_qty']; ?></td>
                            <td><?php echo $item['item_description']; ?></td>
                            <td><?php echo $item['item_justification']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer (Requestor Information and Signatures)-->
        <div class="footer">
            <div class="box1">
                <!--Row 1-->
                <div class="row">
                    <div class="col">
                        <label for="unit_dept">Department:</label>
                        <input id="unit_dept" type="text" class="unit_dept input_border_bottom" value="<?= $pr_row['unit_dept_college'] ?>"></input>
                    </div>

                </div>
                <!--Row 2-->
                <div class="row">
                    <div class="col">
                        <label for="requested_by">Requested by:</label>
                    </div>
                    <div class="col">
                        <label for="approved_by">Approved by:</label>
                    </div>
                    <div class="empty">
                        <!--Empty div for spacing-->
                    </div>
                    <div class="empty">
                        <!--Empty div for spacing-->
                    </div>


                </div>
                <!--Row 3-->
                <div class="row">
                    <div class="col">
                        <input id="requestor" type="text" class="requestor input_border_bottom" value="<?= $pr_row['printed_name'] ?>"></input>
                    </div>
                    <div class="col">
                        <input id="approved_by" type="text" class="approved_by input_border_bottom" value="<?= $pr_row['unit_head_approval_by'] ?>"></input>
                    </div>
                    <div class="col">
                        <input id="cluster_vice_president" type="text" class="cluster_vice_president input_border_bottom" value="<?= $pr_row['vice_president_approved'] ?>"></input>
                    </div>
                </div>

                <!--Row 4-->
                <div class="row">
                    <div class="col">
                        <label for="requestor">Requestor</label>
                    </div>
                    <div class="col">
                        <label for="unit_head">Department Head</label>
                    </div>
                    <div class="col">
                        <label for="cluster_vice_president">Cluster Vice President</label><br>
                        <label for="cluster_vice_president">(If above P50,000)</label>
                    </div>
                </div>
            </div>

            <div class="box2">
                <!--Row 1-->
                <div class="row">
                    <div class="col">
                        <label for="finance_office_use">For Finance Office Use</label>
                    </div>
                </div>
                <!--Row 2-->
                <div class="row">

                    <div class="col">
                        <label for="account_code">Acct. Code</label>
                        <input id="account_code" type="text" class="account_code input_border_bottom" value="<?= $pr_row['budget_controller_code'] ?>"></input>
                    </div>
                </div>
                <!--Row 3-->
                <div class="row">
                    <div class="col">
                        <input id="budget_controller" type="text" class="budget_controller input_border_bottom" value="Marilyn M. Castanares"></input>
                    </div>
                    <div class="col">
                        <input id="university_treasurer" type="text" class="university_treasurer input_border_bottom" value="Lennie K. Ong"></input>
                    </div>
                </div>

                <!--Row 4-->
                <div class="row">
                    <div class="col">
                        <label for="budget_controller">Budget Controller</label>
                        <br>
                        <br>
                    </div>
                    <div class="col">
                        <label for="university_treasurer">University Treasurer</label>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>

    <?php
    }
    ?>

</body>
<script>
    function printPage() {
        window.print();
    }
</script>

</html>