<?php
include('authentication.php');
include('includes/header.php');
include('includes/scripts.php');

// Initialize Variables
$admin = false;
$super_user = false;
$department_editor = false;
$unit_head = false;
$finance_dep = false;

// Check User Role
if (isset($_SESSION['auth_role'])) {
    switch ($_SESSION['auth_role']) {
        case 1:
            $admin = true;
            break;
        case 2:
            $super_user = true;
            break;
        case 3:
            $department_editor = true;
            break;
        case 4:
            $unit_head = true;
            break;
        case 5:
            $finance_dep = true;
            break;
    }
}

// Get Request ID
$request_id = $_GET['id'] ?? null;

// Item Status Choices
$status_choices = ['pending', 'approved', 'for_pricing', 'for_po', 'issued_po', 'for_delivery_by_supplier', 'for_pickup_at_supplier', 'for_tagging', 'for_delivery_to_requesting_unit', 'rejected', 'completed'];

// Fetch Request Details
if ($request_id) {
    $request_query = "SELECT * FROM purchase_requests WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $request_query);
    mysqli_stmt_bind_param($stmt, "s", $request_id);
    mysqli_stmt_execute($stmt);
    $request_query_run = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($request_query_run) > 0) {
        $request_row = mysqli_fetch_assoc($request_query_run);
    } else {
        // Handle case where request ID is invalid
        $_SESSION['message'] = "Invalid Request ID.";
        header("Location: purchase_request-view.php"); // Redirect to view page
        exit(0);
    }
    mysqli_stmt_close($stmt);
} else {
    // Handle case where request ID is missing
    $_SESSION['message'] = "Request ID not provided.";
    header("Location: purchase_request-view.php"); // Redirect to view page
    exit(0);
}

// Function to generate a unique purchase request number
function generateUniquePRNumber($con)
{
    $prefix = 'req-#';
    $counter = 1;
    $max_attempts = 1000; // Prevent infinite loops

    while ($counter <= $max_attempts) {
        $number = sprintf('%05d', $counter);
        $pr_number = $prefix . $number;

        $check_query = "SELECT COUNT(*) AS count FROM purchase_requests WHERE purchase_request_number = ?";
        $stmt_check = mysqli_prepare($con, $check_query);
        mysqli_stmt_bind_param($stmt_check, "s", $pr_number);
        mysqli_stmt_execute($stmt_check);
        $check_result = mysqli_stmt_get_result($stmt_check);
        $row_check = mysqli_fetch_assoc($check_result);
        mysqli_stmt_close($stmt_check);

        if ($row_check['count'] == 0) {
            return $pr_number;
        }
        $counter++;
    }
    // If after max attempts, still no unique number, return a fallback
    return $prefix . uniqid();
}

// If the purchase request number is not yet set, generate and set a unique one
if (empty($request_row['purchase_request_number'])) {
    $unique_pr_number = generateUniquePRNumber($con);
    $update_number_query = "UPDATE purchase_requests SET purchase_request_number = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($con, $update_number_query);
    mysqli_stmt_bind_param($stmt_update, "ss", $unique_pr_number, $request_id);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    // Refetch the request row to get the updated purchase_request_number
    $request_query = "SELECT * FROM purchase_requests WHERE id = ? LIMIT 1";
    $stmt_refetch = mysqli_prepare($con, $request_query);
    mysqli_stmt_bind_param($stmt_refetch, "s", $request_id);
    mysqli_stmt_execute($stmt_refetch);
    $request_query_run = mysqli_stmt_get_result($stmt_refetch);
    $request_row = mysqli_fetch_assoc($request_query_run);
    mysqli_stmt_close($stmt_refetch);
}

$disable_finance_fields = ($request_row['acknowledged_by_cpu'] ?? '') == '0' || ($request_row['acknowledged_by_cpu'] ?? '') == '2';
?>
<link rel="stylesheet" type="text/css" href="css/jquery.signature.css">
</link>

<style>
    .kbw-signature {
        width: 100%;
        /* Make signature canvas responsive */
        height: 200px;
        border: 1px solid #ccc;
        /* Add a border for better visibility */
    }

    #sig canvas {
        width: 100% !important;
        height: auto;
    }

    .read-only {
        background-color: #e9ecef;
        opacity: 1;
    }
</style>
<div class="container mx-auto p-6 bg-blue-100 shadow-md rounded-md">
    <?php include('message.php'); ?>
    <h1 class="text-3xl font-bold mt-8 mb-4 text-center">XAVIER UNIVERSITY CENTRAL PURCHASING UNIT<a href="purchase_request-view.php" class="btn btn-outline-info float-end ml-2">BACK</a></h1>
    <form action="code.php" method="POST">
        <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
        <input type="hidden" name="user_name" value="<?php echo $_SESSION['auth_user']['user_name'] ?? ''; ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id'] ?? ''; ?>">
        <input type="hidden" name="user_email" value="<?php echo $_SESSION['auth_user']['user_email'] ?? ''; ?>">

        <input type="hidden" name="requestor_email" value="<?= htmlspecialchars($request_row['requestor_user_email'] ?? '') ?>">

        <?php if ($super_user) { ?>
            <div class="mb-3 bg-white rounded p-3">
                <label for="">Acknowledged by CPU</label>
                <input type="checkbox" name="acknowledged_by_cpu" <?= ($request_row['acknowledged_by_cpu'] ?? '0') == '1' ? 'checked' : ''; ?> class="form-checkbox h-5 w-5 text-blue-600">
            </div>
        <?php } ?>

        <?php if ($department_editor) { ?>
            <div class="mb-3 bg-white rounded p-3">
                <label for="">Mark as Above P50,000</label>
                <input class="form-checkbox h-5 w-5 text-blue-600" type="checkbox" name="above_50000" <?= ($request_row['above_50000'] ?? '0') == '1' ? 'checked' : ''; ?>>
            </div>
        <?php } ?>

        <?php if ($unit_head) { ?>
            <div class="mb-3 bg-white rounded p-3">
                <label for="">Department Head Approval</label>
                <select name="unit_head_approval" class="form-select">
                    <option value="pending" <?= ($request_row['unit_head_approval'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="recommending-approval" <?= ($request_row['unit_head_approval'] ?? '') == 'recommending-approval' ? 'selected' : '' ?>>Recommending Approval</option>
                    <option value="rejected" <?= ($request_row['unit_head_approval'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
        <?php } ?>

        <?php if ($super_user || $admin || $department_editor) { ?>
            <fieldset class="mb-4 bg-white shadow-md rounded p-4">
                <legend class="font-bold">Purchase Request</legend>
                <div class="mb-3">
                    <label for="request_number" class="form-label">PURCHASE REQUEST#:</label>
                    <input type="text" id="request_number" name="purchase_request_number" class="form-control" value="<?= htmlspecialchars($request_row['purchase_request_number'] ?? '') ?>" readonly>
                </div>
            </fieldset>
        <?php } ?>

        <fieldset class="mb-4 bg-white shadow-md rounded p-4">
            <legend class="font-bold">Items</legend>
            <p class="mb-2">Please include complete specifications/details or attach additional information on items.</p>
            <p class="mb-2">CPU may refuse to receive request without complete specifications or details.</p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th>ITEM#</th>
                            <th>QTY/UNIT</th>
                            <th>DESCRIPTION</th>
                            <th>JUSTIFICATION</th>
                            <th>ITEM STATUS</th>
                            <th>REMOVE</th>
                        </tr>
                    </thead>
                    <tbody id="itemRows">
                        <?php
                        $items_query = "SELECT * FROM items WHERE purchase_request_id = ?";
                        $stmt_items = mysqli_prepare($con, $items_query);
                        mysqli_stmt_bind_param($stmt_items, "s", $request_id);
                        mysqli_stmt_execute($stmt_items);
                        $items_query_run = mysqli_stmt_get_result($stmt_items);

                        if (mysqli_num_rows($items_query_run) > 0) {
                            while ($item_row = mysqli_fetch_assoc($items_query_run)) {
                        ?>
                                <tr class="item-row mb-2">
                                    <td>
                                        <input type="text" name="id[]" class="form-control" value="<?php echo htmlspecialchars($item_row['id']); ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="item_qty[]" class="form-control" value="<?php echo htmlspecialchars($item_row['item_qty']); ?>">
                                    </td>
                                    <td>
                                        <textarea name="item_description[]" class="form-control" required><?php echo htmlspecialchars($item_row['item_description']); ?></textarea>
                                    </td>
                                    <td>
                                        <textarea type="text" name="item_justification[]" class="form-control" required><?php echo htmlspecialchars($item_row['item_justification']); ?></textarea>
                                    </td>
                                    <td>
                                        <select name="item_status[]" class="form-control" required>
                                            <?php
                                            foreach ($status_choices as $status) {
                                                echo "<option value='" . htmlspecialchars($status) . "' " . (($status == ($item_row['item_status'] ?? '')) ? 'selected' : '') . ">" . htmlspecialchars(ucfirst(str_replace('_', ' ', $status))) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" data-id="<?php echo htmlspecialchars($item_row['id']); ?>" class="btn btn-danger remove_purchased_item" id="remove_purchased_item">Remove</button>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        mysqli_stmt_close($stmt_items);
                        ?>
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn btn-primary btn-add-item">Add Item</button>
        </fieldset>

        <?php
        $attachments_query = "SELECT * FROM purchase_requests_attachments WHERE purchase_request_id = ?";
        $stmt_attachments = mysqli_prepare($con, $attachments_query);
        mysqli_stmt_bind_param($stmt_attachments, "s", $request_id);
        mysqli_stmt_execute($stmt_attachments);
        $attachments_query_run = mysqli_stmt_get_result($stmt_attachments);

        if (mysqli_num_rows($attachments_query_run) > 0) {
            echo '<fieldset class="mb-4 bg-white shadow-md rounded p-4">';
            echo '<legend class="font-bold">Attachments</legend>';
            echo '<div class="mb-3">';
            echo '<table class="table table-bordered">';
            echo '<thead class="table-light">';
            echo '<tr>';
            echo '<th>Image</th>';
            echo '<th>File Name</th>';
            echo '<th>File Type</th>';
            echo '<th>File Size</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($attachment_row = mysqli_fetch_assoc($attachments_query_run)) {
                echo '<tr>';
                echo '<td>';
                $file_ext = pathinfo($attachment_row['file_name'], PATHINFO_EXTENSION);
                $image_ext = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($file_ext), $image_ext)) {
                    echo '<img src="../uploads/request_documents/' . htmlspecialchars($attachment_row['file_name']) . '" alt="' . htmlspecialchars($attachment_row['file_name']) . '" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">';
                } else {
                    echo 'Not an image';
                }
                echo '</td>';
                echo '<td><a href="../uploads/request_documents/' . htmlspecialchars($attachment_row['file_name']) . '" download="' . htmlspecialchars($attachment_row['file_name']) . '">' . htmlspecialchars($attachment_row['file_name']) . '</a></td>';
                echo '<td>' . htmlspecialchars($attachment_row['file_type']) . '</td>';
                echo '<td>' . htmlspecialchars($attachment_row['file_size']) . ' bytes</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '</fieldset>';
        }
        mysqli_stmt_close($stmt_attachments);
        ?>

        <fieldset class="mb-4 bg-white shadow-md rounded p-4">
            <legend class="font-bold">Requestor Information</legend>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>ID:</td>
                        <td>
                            <input disabled type="text" id="request_id" name="requestor_id" class="form-control read-only" value="<?= htmlspecialchars($request_row['id'] ?? '') ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Name:</td>
                        <td>
                            <input type="text" id="printed_name" name="printed_name" class="form-control read-only" value="<?= htmlspecialchars($request_row['printed_name'] ?? '') ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>
                            <input type="text" id="requestor_user_name" name="requestor_user_name" class="form-control read-only" value="<?= htmlspecialchars($request_row['requestor_user_name'] ?? '') ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Requested Date:</td>
                        <td>
                            <input type="text" id="requested_date" name="requested_date" class="form-control read-only" value="<?= htmlspecialchars($request_row['requested_date'] ?? '') ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Requestor Signature:</td>
                        <td>
                            <?php
                            if (!empty($request_row['signed_request'])) {
                                echo '<input type="hidden" name="signed_request" value="' . htmlspecialchars($request_row['signed_request']) . '">';
                                echo '<div class="mb-3 kbw-signature">';
                                echo '<label for="signed_request">Signature:</label>';
                                echo '<img src="../uploads/signatures/' . htmlspecialchars($request_row['signed_request']) . '" alt="signature" class="img-fluid">';
                                echo '</div>';
                                echo '<button type="button" class="btn btn-danger delete-signature-btn" data-signature-field="signed_request">Delete Signature</button>';
                            } else {
                                echo '<div class="mb-3">';
                                echo '<div id="sigrequest" class="kbw-signature"></div>';
                                echo '<button id="clearreq" class="btn btn-primary">Clear Signature</button>';
                                echo '<textarea id="signature64" name="signed_request" style="display:none"></textarea>';
                                echo '</div>';
                            }
                            ?>
                        </td>
                    </tr>
                        <tr>
                            <td>Department:</td>
                            <td>
                                <?php if ($unit_head) { ?>
                                    <input type="text" id="unit_dept_college" name="unit_dept_college" class="form-control" placeholder="Department" value="<?= htmlspecialchars($request_row['unit_head_approval'] ?? '') ?>">
                                <?php } else { ?>
                                    <input type="text" id="unit_dept_college" class="form-control read-only" placeholder="Department" value="<?= htmlspecialchars($request_row['unit_head_approval'] ?? '') ?>" readonly>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Approved by: (Department Head)</td>
                            <td>
                                <?php if ($unit_head) { ?>
                                    <input type="text" id="unit_head_approval_by" name="unit_head_approval_by" class="form-control" placeholder="Department Head Name" value="<?= htmlspecialchars($request_row['unit_head_approval_by'] ?? '') ?>">
                                <?php } else { ?>
                                    <input type="text" id="unit_head_approval_by" class="form-control read-only" placeholder="Department Head Name" value="<?= htmlspecialchars($request_row['unit_head_approval_by'] ?? '') ?>" readonly>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Department Head Signature:</td>
                            <td>
                                <?= $request_row['signed_Requestor'] ?>
                                <?php if (!empty($request_row['signed_Requestor'])) { ?>
                                    <input type="hidden" name="signed_Requestor" value="<?= htmlspecialchars($request_row['signed_Requestor']) ?>">
                                    <div class="mb-3 kbw-signature">
                                        <label for="signed_Requestor">Signature:</label>
                                        <img src="../uploads/signatures/<?= htmlspecialchars($request_row['signed_Requestor']) ?>" alt="signature" class="img-fluid">
                                    </div>
                                    <?php if ($unit_head) { ?>
                                        <button type="button" class="btn btn-danger delete-signature-btn" data-signature-field="signed_Requestor">Delete Signature</button>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php if ($unit_head) { ?>
                                        <div class="mb-3">
                                            <div id="sig1" class="kbw-signature"></div>
                                            <button id="clear1" class="btn btn-primary">Clear Signature</button>
                                            <textarea id="signature64_1" name="signed_Requestor" style="display:none"></textarea>
                                        </div>
                                    <?php } else { ?>
                                        <p class="text-muted">No signature available.</p>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <tr>
                        <td>Acknowledge by CPU:</td>
                        <td>
                            <select id="acknowledged_by_cpu" name="acknowledged_by_cpu" class="form-control" <?php if (!$super_user && !$admin) echo 'disabled'; ?>>
                                <option value="0" <?= ($request_row['acknowledged_by_cpu'] ?? '') == '0' ? 'selected' : '' ?>>Pending</option>
                                <option value="1" <?= ($request_row['acknowledged_by_cpu'] ?? '') == '1' ? 'selected' : '' ?>>Accepted</option>
                                <option value="2" <?= ($request_row['acknowledged_by_cpu'] ?? '') == '2' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>CPU Signature:</td>
                        <td>
                            <?php
                            if (!empty($request_row['signed_by_cpu'])) {
                                echo '<input type="hidden" name="signed_by_cpu" value="' . htmlspecialchars($request_row['signed_by_cpu']) . '">';
                                echo '<div class="mb-3 kbw-signature">';
                                echo '<label for="signed_by_cpu">Signature:</label>';
                                echo '<img src="../uploads/signatures/' . htmlspecialchars($request_row['signed_by_cpu']) . '" alt="signature" class="img-fluid">';
                                if ($super_user || $admin) {
                                    echo '<button type="button" class="btn btn-danger delete-signature-btn" data-signature-field="signed_by_cpu">Delete Signature</button>';
                                }
                                echo '</div>';
                            } else {
                                echo '<div class="mb-3">';
                                echo '<div id="sig2" class="kbw-signature" style="';
                                if (!$super_user && !$admin) {
                                    echo 'pointer-events: none;';
                                }
                                echo '"></div>';
                                if ($super_user || $admin) {
                                    echo '<button id="clear2" class="btn btn-primary">Clear Signature</button>';
                                    echo '<textarea id="signature64_2" name="signed_by_cpu" style="display:none"></textarea>';
                                } else {
                                    echo '<textarea id="signature64_2" name="signed_by_cpu" style="display:none"></textarea>';
                                }
                                echo '</div>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php if ($super_user || $finance_dep || $admin) { ?>
                        <tr>
                            <td>Finance Approval:</td>
                            <td>
                                <select id="university_treasurer_approved" name="university_treasurer_approved" class="form-control" <?= $disable_finance_fields ? 'disabled' : '' ?>>
                                    <option value="0" <?= ($request_row['university_treasurer_approved'] ?? '') == '0' ? 'selected' : '' ?>>Pending</option>
                                    <option value="1" <?= ($request_row['university_treasurer_approved'] ?? '') == '1' ? 'selected' : '' ?>>Accepted</option>
                                    <option value="2" <?= ($request_row['university_treasurer_approved'] ?? '') == '2' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Remarks:</td>
                            <td>
                                <input type="text" id="university_treasurer_remarks" name="university_treasurer_remarks" class="form-control" placeholder="Finance Remarks" value="<?= htmlspecialchars($request_row['university_treasurer_remarks'] ?? '') ?>" <?= $disable_finance_fields ? 'readonly' : '' ?>>
                            </td>
                        </tr>
                        <tr>
                            <td>Finance Signature:</td>
                            <td>
                                <?php
                                // Determine if finance fields should be disabled
                               

                                // Display a warning note if CPU acknowledgment is pending or rejected
                                if (($request_row['acknowledged_by_cpu'] ?? '') == '0') {
                                    echo '<div class="alert alert-warning" role="alert">';
                                    echo 'CPU acknowledgment is still pending. Please follow up with the CPU user.';
                                    echo '</div>';
                                } elseif (($request_row['acknowledged_by_cpu'] ?? '') == '2') {
                                    echo '<div class="alert alert-danger" role="alert">';
                                    echo 'CPU acknowledgment has been rejected. Please follow up with the CPU user.';
                                    echo '</div>';
                                }
                                ?>
                                <?php
                                if (!empty($request_row['signed_4'])) {
                                    echo '<input type="hidden" name="signed_4" value="' . htmlspecialchars($request_row['signed_4']) . '">';
                                    echo '<div class="mb-3 kbw-signature">';
                                    echo '<label for="signed_4">Signature:</label>';
                                    echo '<img src="../uploads/signatures/' . htmlspecialchars($request_row['signed_4']) . '" alt="signature" class="img-fluid">';
                                    if (!$disable_finance_fields) {
                                        echo '<button type="button" class="btn btn-danger delete-signature-btn" data-signature-field="signed_4">Delete Signature</button>';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '<div class="mb-3">';
                                    echo '<div id="sig3" class="kbw-signature" style="';
                                    if ($disable_finance_fields) {
                                        echo 'pointer-events: none;';
                                    }
                                    echo '"></div>';
                                    if (!$disable_finance_fields) {
                                        echo '<button id="clear3" class="btn btn-primary">Clear Signature</button>';
                                        echo '<textarea id="signature64_3" name="signed_4" style="display:none"></textarea>';
                                    } else {
                                        echo '<textarea id="signature64_3" name="signed_4" style="display:none" readonly></textarea>';
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td>E-mail Address:</td>
                        <td>
                            <input type="text" id="iptel_email" name="iptel_email" class="form-control read-only" value="<?= htmlspecialchars($request_row['iptel_email'] ?? '') ?>" readonly>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <button type="submit" name="request_update_btn_front" class="btn btn-primary">Update Request Details</button>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('.delete_attachment').click(function(e) {
            e.preventDefault();
            var attachment_id = $(this).data('attachment-id');
            if (confirm('Are you sure you want to delete this attachment?')) {
                $.ajax({
                    type: "POST",
                    url: "code.php",
                    data: {
                        'delete_attachment': true,
                        'attachment_id': attachment_id
                    },
                    success: function(response) {
                        if (response == 200) {
                            alert('Attachment Deleted Successfully.');
                            location.reload();
                        } else if (response == 500) {
                            alert('Something went wrong. Please try again.');
                        } else {
                            alert(response);
                        }
                    }
                });
            }
        });
        <?php if ($super_user || $admin) : ?>
            $('#sigrequest').signature({
                syncField: '#signature64',
                guideline: true
            });
            $('#clearreq').click(function(e) {
                e.preventDefault();
                $('#sigrequest').signature('clear');
                $("#signature64").val(null);
            });
        <?php endif; ?>
        <?php if ($super_user || $unit_head || $admin) : ?>
            $('#sig1').signature({
                syncField: '#signature64_1',
                guideline: true
            });
            $('#clear1').click(function(e) {
                e.preventDefault();
                $('#sig1').signature('clear');
                $("#signature64_1").val(null);
            });
        <?php endif; ?>

        <?php if ($super_user || $admin) : ?>
            $('#sig2').signature({
                syncField: '#signature64_2',
                guideline: true
            });
            $('#clear2').click(function(e) {
                e.preventDefault();
                $('#sig2').signature('clear');
                $("#signature64_2").val(null);
            });
        <?php endif; ?>

        <?php if ($super_user || $finance_dep || $admin) : ?>
            $('#sig3').signature({
                syncField: '#signature64_3',
                guideline: true
            });
            $('#clear3').click(function(e) {
                e.preventDefault();
                $('#sig3').signature('clear');
                $("#signature64_3").val(null);
            });
        <?php endif; ?>

        $('.delete-signature-btn').click(function(e) {
            e.preventDefault();
            var signature_field = $(this).data('signature-field');
            var request_id = $('input[name="request_id"]').val();
            if (confirm('Are you sure you want to delete this signature?')) {
                $.ajax({
                    type: "POST",
                    url: "code.php",
                    data: {
                        'delete_signature': true,
                        'signature_field': signature_field,
                        'request_id': request_id
                    },
                    success: function(response) {
                        if (response == 200) {
                            alert('Signature Deleted Successfully.');
                            location.reload();
                        } else {
                            alert(response);
                        }
                    }
                });
            }
        });
    });
</script>
<?php
include('script_purchase_request-edit.php');
?>