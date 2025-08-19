<?php
include('authentication.php');
include('includes/header.php');
include('includes/scripts.php');

//Initialize Variable
$admin = null;
$super_user = null;
$department_editor = null;
//Check level
if ($_SESSION['auth_role'] == 1) {
    $admin = true;
    $super_user = false;
    $department_editor = false;
} elseif ($_SESSION['auth_role'] == 2) {
    $admin = false;
    $super_user = true;
    $department_editor = false;
} elseif ($_SESSION['auth_role'] == 3) {
    $admin = false;
    $super_user = false;
    $department_editor = true;
}
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript" src="js/jquery.signature.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.signature.css">
</link>

<!--Signature Styles-->
<style>
    .kbw-signature {
        width: 800px;
        height: 200px;
    }

    #sig canvas {
        width: 100% !important;
        height: auto;
    }
</style>

<div class="container p-6 bg-blue-50 shadow-black">
    <?php include('message.php'); ?>
    <h1 class="text-3xl font-bold mt-8 mb-4 justify-center items-center">XAVIER UNIVERSITY CENTRAL PURCHASING UNIT<a href="purchase_request-view.php" class="btn btn-danger float-end">BACK</a></h1>
    <form action="code.php" method="post">
        <!-- Hidden user_name, user_id, user_email -->
        <input type="hidden" name="user_name" value="<?php echo $_SESSION['auth_user']['user_name']; ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id']; ?>">
        <input type="hidden" name="user_email" value="<?php echo $_SESSION['auth_user']['user_email']; ?>">

        <!-- Start of Row1 -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="procurment_request">Procurement Request #</label>
                    <input type="text" name="procurment_request" class="form-control" required>
                </div>
            </div>

            <!-- Start of Row2 -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Table 1 -->
                    <div class="col-md-12">
                        <table class="table table-responsive table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty/Unit</th>
                                    <th>Description</th>
                                    <th>Justification</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>

                            <tbody id="itemRows">
                                <!-- Dynamically generated rows will be added here -->
                            </tbody>

                        </table>
                    </div>
                    <!-- Add Item Button -->
                    <button type="button" class="btn btn-info btn-add-item text-white">Add Item</button>
                </div>
            </div>

            <!-- Start of Row3 -->
            <div class="row">
                <!-- Table 2 -->
                <div class="col-md-6">Requestor Information
                    <table class="table table-responsive table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Requested by:</th>
                                <th>Approved by</th>
                                <th>Cluster VP (if above P50,000)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- Table Body rows -->
                            <tr>
                                <td>
                                    <input type="text" name="unit_dept" class="form-control" required placeholder="Unit/Dept">
                                </td>
                                <td>
                                    <input type="text" name="requested_by" class="form-control" required placeholder="Requestor Name">
                                </td>
                                <td>
                                    <input type="text" name="approved_by" class="form-control" required placeholder="Unit Head">
                                </td>
                                <td>
                                    <input type="text" name="cluster_vp" class="form-control" required placeholder="Cluster VP">
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
                <!-- Table 3 -->
                <div class="col-md-6">For Finance Office Use
                    <table class="table table-responsive table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Acct.Code</th>
                                <th>Budget Controller</th>
                                <th>University Treasurer</th>
                            </tr>
                        </thead>

                        <tbody>
                            <!-- Table Body Rows -->
                            <tr>
                                <td>
                                    <input type="text" name="acct_code" class="form-control" required placeholder="Account Code">
                                </td>
                                <td>
                                    <input type="text" name="budget_controller" class="form-control" required placeholder="Marilyn M Castanares">
                                </td>
                                <td>
                                    <input type="text" name="university_treasurer" class="form-control" required placeholder="Lennie K Ong">
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>


            <!-- Bootstrap JavaScript -->
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const itemRows = document.getElementById('itemRows');
                    const addItemButton = document.querySelector('.btn-add-item');
                    let itemNumber = 1; // Initialize item number

                    addItemButton.addEventListener('click', function() {
                        const itemRow = document.createElement('tr');
                        itemRow.classList.add('item-row', 'mb-2');
                        itemRow.innerHTML = `
            <td style="width:60px">
                <!-- ITEM# -->
                <input type="text" name="item_number[]" class="form-control" style="width:60px" value="${itemNumber}">
            </td>
            <td>
                <!-- QTY/UNIT -->
                <input type="text" name="item_qty[]" class="form-control" style="width:60px" required>
            </td>
            <td>
                <!-- DESCRIPTION -->
                <textarea name="item_description[]" class="form-control" style="width:550px" required></textarea>
            </td>
            <td>
                <!-- JUSTIFICATION -->
                <textarea type="text" name="item_justification[]" class="form-control" style="width:550px" required> </textarea>
            </td>
            <td>
                <!-- Remove Button -->
                <button type="button" class="btn btn-danger btn-remove-item" style="width:80px" required>Remove</button>
            </td>
        `;
                        itemRows.appendChild(itemRow);
                        itemNumber++; // Increment item number
                    });


                    itemRows.addEventListener('click', function(event) {
                        if (event.target.classList.contains('btn-remove-item')) {
                            event.target.closest('.item-row').remove();
                            itemNumber--; // Decrement item number
                        }
                    });

                    // Signatures Script (Add var sig per Approval person and button id per approval person)
                    var sig1 = $('#sig1').signature({
                        syncField: '#signature64_1',
                        syncFormat: 'PNG'
                    });
                    $('#clear1').click(function(e) {
                        e.preventDefault();
                        sig1.signature('clear');
                        $("#signature64_1").val('');
                    });

                    var sig2 = $('#sig2').signature({
                        syncField: '#signature64_2',
                        syncFormat: 'PNG'
                    });
                    $('#clear2').click(function(e) {
                        e.preventDefault();
                        sig2.signature('clear');
                        $("#signature64_2").val('');
                    });

                    var sig3 = $('#sig3').signature({
                        syncField: '#signature64_3',
                        syncFormat: 'PNG'
                    });
                    $('#clear3').click(function(e) {
                        e.preventDefault();
                        sig3.signature('clear');
                        $("#signature64_3").val('');
                    });

                    var sig4 = $('#sig4').signature({
                        syncField: '#signature64_4',
                        syncFormat: 'PNG'
                    });
                    $('#clear4').click(function(e) {
                        e.preventDefault();
                        sig4.signature('clear');
                        $("#signature64_4").val('');
                    });

                    var sig5 = $('#sig5').signature({
                        syncField: '#signature64_5',
                        syncFormat: 'PNG'
                    });
                    $('#clear5').click(function(e) {
                        e.preventDefault();
                        sig5.signature('clear');
                        $("#signature64_5").val('');
                    });

                    var sigRequestor = $('#sigRequestor').signature({
                        syncField: '#signature64_Requestor',
                        syncFormat: 'PNG'
                    });
                    $('#clearRequestor').click(function(e) {
                        e.preventDefault();
                        sigRequestor.signature('clear');
                        $("#signature64_Requestor").val('');
                    });

                    // Add more signature scripts as needed

                });
            </script>

            <?php
            include('includes/footer.php');

            ?>