<?php
include('authentication.php');
$request_id = $_GET['id'] ?? null;
if (!$request_id) {
    header("Location: purchase_request-view.php");
    exit();
}
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


// Item Status Choices (for display purposes only)
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
?>
<link rel="stylesheet" type="text/css" href="css/jquery.signature.css">
</link>

<style>
    .kbw-signature {
        width: 100%;
        height: 200px;
        border: 1px solid #ccc;
    }

    #sig canvas {
        width: 100% !important;
        height: auto;
    }

    .view-only {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        border-radius: 0.25rem;
        width: 100%;
        box-sizing: border-box;
    }
</style>
<div class="container mx-auto p-6 bg-blue-100 shadow-md rounded-md">
    <?php include('message.php'); ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-3xl font-bold">PURCHASE REQUEST DETAILS</h1>
        <a href="purchase_request-edit.php?id=<?= $request_id ?>" class="btn btn-warning">
            <i class="bi bi-pencil-square"></i> Edit
        </a>
    </div>

    <div class="bg-white rounded p-4">
        <div class="mb-3">
            <label for="request_number" class="form-label font-weight-bold">PURCHASE REQUEST#:</label>
            <div class="view-only"><?= htmlspecialchars($request_row['purchase_request_number'] ?? '') ?></div>
        </div>

        <fieldset class="mb-4 border p-3 rounded">
            <legend class="font-weight-bold">Items</legend>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th>ITEM#</th>
                            <th>QTY/UNIT</th>
                            <th>DESCRIPTION</th>
                            <th>JUSTIFICATION</th>
                            <th>ITEM STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $items_query = "SELECT * FROM items WHERE purchase_request_id = ?";
                        $stmt_items = mysqli_prepare($con, $items_query);
                        mysqli_stmt_bind_param($stmt_items, "s", $request_id);
                        mysqli_stmt_execute($stmt_items);
                        $items_query_run = mysqli_stmt_get_result($stmt_items);

                        if (mysqli_num_rows($items_query_run) > 0) {
                            while ($item_row = mysqli_fetch_assoc($items_query_run)) {
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($item_row['id']) ?></td>
                                    <td><?= htmlspecialchars($item_row['item_qty']) ?></td>
                                    <td><?= nl2br(htmlspecialchars($item_row['item_description'])) ?></td>
                                    <td><?= nl2br(htmlspecialchars($item_row['item_justification'])) ?></td>
                                    <td>
                                        <?php
                                        $status_name = '';
                                        foreach ($status_choices as $status) {
                                            if ($status == ($item_row['item_status'] ?? '')) {
                                                $status_name = ucfirst(str_replace('_', ' ', $status));
                                                break;
                                            }
                                        }
                                        echo htmlspecialchars($status_name);
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="5">No items found for this request.</td></tr>';
                        }
                        mysqli_stmt_close($stmt_items);
                        ?>
                    </tbody>
                </table>
            </div>
        </fieldset>

        <?php
        $attachments_query = "SELECT * FROM purchase_requests_attachments WHERE purchase_request_id = ?";
        $stmt_attachments = mysqli_prepare($con, $attachments_query);
        mysqli_stmt_bind_param($stmt_attachments, "s", $request_id);
        mysqli_stmt_execute($stmt_attachments);
        $attachments_query_run = mysqli_stmt_get_result($stmt_attachments);

        if (mysqli_num_rows($attachments_query_run) > 0) {
            echo '<fieldset class="mb-4 border p-3 rounded">';
            echo '<legend class="font-weight-bold">Attachments</legend>';
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

        <fieldset class="mb-4 border p-3 rounded">
            <legend class="font-weight-bold">Requestor Information</legend>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="font-weight-bold">ID:</td>
                        <td><div class="view-only"><?= htmlspecialchars($request_row['id'] ?? '') ?></div></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Name:</td>
                        <td><div class="view-only"><?= htmlspecialchars($request_row['printed_name'] ?? '') ?></div></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Email:</td>
                        <td><div class="view-only"><?= htmlspecialchars($request_row['requestor_user_name'] ?? '') ?></div></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Requested Date:</td>
                        <td><div class="view-only"><?= htmlspecialchars($request_row['requested_date'] ?? '') ?></div></td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Requestor Signature:</td>
                        <td>
                            <?php if (!empty($request_row['signed_request'])): ?>
                                <img src="../uploads/signatures/<?= htmlspecialchars($request_row['signed_request']) ?>" alt="signature" class="img-fluid" style="max-width: 300px;">
                            <?php else: ?>
                                <div class="view-only">No Signature</div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if ($super_user || $unit_head || $admin): ?>
                        <tr>
                            <td class="font-weight-bold">Department:</td>
                            <td><div class="view-only"><?= htmlspecialchars($request_row['unit_dept_college'] ?? '') ?></div></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Approved by: (Department Head)</td>
                            <td><div class="view-only"><?= htmlspecialchars($request_row['unit_head_approval_by'] ?? '') ?></div></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Department Head Signature:</td>
                            <td>
                                <?php if (!empty($request_row['signed_Requestor'])): ?>
                                    <img src="../uploads/signatures/<?= htmlspecialchars($request_row['signed_Requestor']) ?>" alt="signature" class="img-fluid" style="max-width: 300px;">
                                <?php else: ?>
                                    <div class="view-only">No Signature</div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($super_user || $admin): ?>
                        <tr>
                            <td class="font-weight-bold">Acknowledge by CPU:</td>
                            <td>
                                <div class="view-only">
                                    <?php
                                    if (($request_row['acknowledged_by_cpu'] ?? '') == '0') echo 'Pending';
                                    elseif (($request_row['acknowledged_by_cpu'] ?? '') == '1') echo 'Accepted';
                                    elseif (($request_row['acknowledged_by_cpu'] ?? '') == '2') echo 'Rejected';
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">CPU Signature:</td>
                            <td>
                                <?php if (!empty($request_row['signed_by_cpu'])): ?>
                                    <img src="../uploads/signatures/<?= htmlspecialchars($request_row['signed_by_cpu']) ?>" alt="signature" class="img-fluid" style="max-width: 300px;">
                                <?php else: ?>
                                    <div class="view-only">No Signature</div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if ($super_user || $finance_dep || $admin): ?>
                        <tr>
                            <td class="font-weight-bold">Finance Approval:</td>
                            <td>
                                <div class="view-only">
                                    <?php
                                    if (($request_row['university_treasurer_approved'] ?? '') == '0') echo 'Pending';
                                    elseif (($request_row['university_treasurer_approved'] ?? '') == '1') echo 'Accepted';
                                    elseif (($request_row['university_treasurer_approved'] ?? '') == '2') echo 'Rejected';
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Remarks:</td>
                            <td><div class="view-only"><?= htmlspecialchars($request_row['university_treasurer_remarks'] ?? '') ?></div></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Finance Signature:</td>
                            <td>
                                <?php if (!empty($request_row['signed_4'])): ?>
                                    <img src="../uploads/signatures/<?= htmlspecialchars($request_row['signed_4']) ?>" alt="signature" class="img-fluid" style="max-width: 300px;">
                                <?php else: ?>
                                    <div class="view-only">No Signature</div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="font-weight-bold">E-mail Address:</td>
                        <td><div class="view-only"><?= htmlspecialchars($request_row['iptel_email'] ?? '') ?></div></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <a href="purchase_request-view.php" class="btn btn-secondary">Back to List</a>
    </div>
</div>

<?php
include('includes/footer.php');
?>