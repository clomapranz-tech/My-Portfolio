<?php
session_start();
//Header
include('includes/header.php');
include('includes/navbar.php');
include('message.php');
include('config/dbcon.php');
include('authentication.php');

?>
  
<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<link type="text/css" href="assets/css/jquery-ui.css" rel="stylesheet">
<script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
<script src="assets/js/bootstrap5.bundle.min.js"></script>
<script type="text/javascript" src="assets/js/jquery.signature.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets/css/jquery.signature.css">

<style>
    .kbw-signature {
        width: 800px;
        height: 200px;
    }

    #sig canvas {
        width: 100% !important;
        height: auto;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        margin-top: 10px;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 8px;
        border: 1px solid #ddd;
    }

    .btn-full-width {
        width: 100%;
        padding: 12px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
    }

    fieldset {
        border: 1px solid #00478a;
        border-radius: 8px;
        padding: 15px;
        background-color: rgba(0, 72, 138, 0.9);
        margin-bottom: 20px;
        color: #fff;
    }

    legend {
        font-size: 18px;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .btn-add-item {
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .table-fixed {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        .item-row td {
            display: block;
            width: 100%;
            /* Make each cell block-level and fill the container */
            margin-bottom: 10px;
            /* Space between rows */
        }

        /* Make the 'Remove' button more prominent on mobile */
        .btn-remove-item {
            width: 100%;
            max-width: 80px;
        }
    }
</style>

<!-- Horizontal Rule Line -->
<hr style="width:80%; margin:auto; color:#00478a; background:#429ef5; height: 2px;">

<div class="container mx-auto mb-4 p-6" style="background-image: url('assets/images/BG.png'); border-radius:4%;">
    <?php include('message.php'); ?>

    <h1 class="text-3xl font-bold mt-8 mb-6 text-center text-white" style="margin-bottom: 5px;">XAVIER UNIVERSITY CENTRAL PURCHASING UNIT</h1>

    <form action="allcode.php" method="post" enctype="multipart/form-data">
        <!-- Hidden user info -->
        <input type="hidden" name="user_name" value="<?php echo $_SESSION['auth_user']['user_name']; ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id']; ?>">
        <input type="hidden" name="user_email" value="<?php echo $_SESSION['auth_user']['user_email']; ?>">
        <fieldset class="mb-4">
            <legend>Purchase Request</legend>
            <p>Please include complete specifications/details or attach additional information on items.</p>
            <p>CPU may refuse to receive request without complete specifications or details.</p>
            <table class="table-fixed border">
                <thead>
                    <tr>
                        <th>ITEM#</th>
                        <th>QTY/UNIT</th>
                        <th>DESCRIPTION</th>
                        <th>JUSTIFICATION</th>
                        <th>REMOVE</th>
                    </tr>
                </thead>
                <tbody id="itemRows">
                </tbody>
            </table>

            <button type="button" class="btn btn-primary btn-add-item bg-blue-600">Add Item</button>
        </fieldset>

        <fieldset class="mb-4">
            <legend>Requestor Information</legend>
            <table class="table-auto">
                <tbody>
                    <tr>
                        <td>Department:</td>
                        <td><input type="text" id="unit_dept_college" name="unit_dept_college" class="form-control" required placeholder="Department"></td>
                    </tr>
                    <tr>
                        <td>Requested by:</td>
                        <td><input type="text" id="printed_name" name="printed_name" class="form-control" required placeholder="Requestor Name"></td>
                    </tr>
                    <tr>
                        <td>Department Head :</td>
                        <td>
                            <select id="unit_head" name="unit_head" class="form-control" required>
                                <option value="">--Select Department Head --</option>
                                <?php
                                $sql = "SELECT * FROM users WHERE role_as = '4'";
                                $result = mysqli_query($con, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['id'] . '">' . $row['fname'] . ' ' . $row['lname'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>E-mail Address:</td>
                        <td><input type="text" readonly id="iptel_email" name="iptel_email" class="form-control" required value="<?php echo $_SESSION['auth_user']['user_email']; ?>"></td>
                    </tr>
                    <tr>
                        <td>Request File Attachments:</td>
                        <td><input type="file" name="request_documents[]" multiple class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Signature:</td>
                        <td>
                            <div class="mb-3">
                                <div id="sigRequestor" class="kbw-signature"></div>
                                <button id="clearRequestor" class="btn btn-primary">Clear Signature</button>
                                <textarea id="signature64_Requestor" name="signed_Request" style="display:none"></textarea>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <button type="submit" name="request_add_btn_front" class="btn btn-primary bg-blue-600 btn-full-width">Submit Request</button>
    </form>
</div>

<script>
    jQuery.noConflict();
    document.addEventListener("DOMContentLoaded", function() {
        const itemRows = document.getElementById('itemRows');
        const addItemButton = document.querySelector('.btn-add-item');
        const submitButton = document.querySelector('button[name="request_add_btn_front"]');
        let itemNumber = 1;

        // Function to toggle the submit button
        function toggleSubmitButton() {
            if (itemRows.children.length === 0) {
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        }

        // Initially disable the submit button
        toggleSubmitButton();

        addItemButton.addEventListener('click', function() {
            const itemRow = document.createElement('tr');
            itemRow.classList.add('item-row', 'mb-2');
            itemRow.innerHTML = `
                <td class="px-2 py-2" style="max-width: 80px;">
                    <!-- ITEM# -->
                    <input type="text" name="item_number[]" class="form-control" readonly value="${itemNumber}" style="width: 100%;"/>
                </td>
                <td class="px-2 py-2" style="max-width: 100px;">
                    <!-- QTY/UNIT -->
                    <input type="number" min=1 name="item_qty[]" class="form-control" required style="width: 100%;"/>
                </td>
                <td class="px-2 py-2">
                    <!-- DESCRIPTION -->
                    <textarea name="item_description[]" class="form-control" required maxlength="500" style="width: 100%;"></textarea>
                </td>
                <td class="px-2 py-2">
                    <!-- JUSTIFICATION -->
                    <textarea name="item_justification[]" class="form-control" required maxlength="500" style="width: 100%;"></textarea>
                </td>
                <td class="px-2 py-2" style="text-align: center;">
                    <!-- Remove Button -->
                     <button type="button" class="btn btn-danger btn-remove-item bg-red-600" style="width: 100%; max-width: 80px;">
                        <i class="fas fa-trash-alt"></i> <!-- Font Awesome Trash Icon -->
                    </button>
                </td>
            `;
            itemRows.appendChild(itemRow);
            itemNumber++;
            toggleSubmitButton(); // Enable the submit button
        });

        itemRows.addEventListener('click', function(event) {
            if (event.target.classList.contains('btn-remove-item')) {
                event.target.closest('.item-row').remove();
                itemNumber--;
                toggleSubmitButton(); // Disable the submit button if no items remain
            }
        });

        jQuery(document).ready(function() {
            var sigRequestor = jQuery('#sigRequestor').signature({
                syncField: '#signature64_Requestor',
                syncFormat: 'PNG'
            });
            jQuery('#clearRequestor').click(function(e) {
                e.preventDefault();
                sigRequestor.signature('clear');
                jQuery("#signature64_Requestor").val('');
            });
        });
    });
</script>

<footer class="bg-dark text-white py-5">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-between items-start">
            <div class="w-full md:w-1/2 lg:w-2/5 m-4">
                <h2 class="text-xl font-bold mb-3">Contact Us</h2>
                <p class="mb-4">Xavier University - Central Purchasing Unit</p>
                <p class="mb-2">Phone: (049) 403-1000</p>
                <p>Email: info@xu.edu.ph</p>
            </div>
        </div>
    </div>
</footer>