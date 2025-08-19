<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');
include('config/dbcon.php');
include('authentication.php');

if (isset($_GET['id'])) {
    $request_id = mysqli_real_escape_string($con, $_GET['id']);
    $user_email = $_SESSION['auth_user']['user_email'];
    $query = "SELECT * FROM purchase_requests WHERE id = '$request_id' AND requestor_user_email = '$user_email'";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $request = mysqli_fetch_assoc($query_run);

        $attach_query = "SELECT * FROM purchase_requests_attachments WHERE purchase_request_id = '$request_id'";
        $attach_result = mysqli_query($con, $attach_query);
        $attachments = mysqli_fetch_all($attach_result, MYSQLI_ASSOC);
?>
        <div class="container p-5">
            <?php if (!empty($attachments)): ?>
                <h3>Attachments</h3>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Size</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attachments as $attachment): ?>
                            <tr>
                                <td class="text-center"><img src="<?= $attachment['file_path']; ?>" alt="<?= $attachment['file_name']; ?>" class="img-fluid d-block mx-auto" style="max-width:150px;"></td>
                                <td><?= $attachment['file_name']; ?></td>
                                <td><?= $attachment['file_type']; ?></td>
                                <td><?= $attachment['file_size']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No attachments available.</p>
            <?php endif; ?>

            <h2>Purchase Request Details</h2>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Field</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ID</td>
                        <td><?= $request['purchase_request_number']; ?></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><?= $request['printed_name']; ?></td>
                    </tr>
                    <tr>
                        <td>IPTel/Email</td>
                        <td><?= $request['iptel_email']; ?></td>
                    </tr>
                    <tr>
                        <td>Requested Date</td>
                        <td><?= date('F j Y h:i A', strtotime($request['requested_date'])); ?></td>
                    </tr>
                    <tr>
                        <td>Signature:</td>
                        <td>
                            <?php if ($request['signed_request']): ?>
                                <div class="mb-3 kbw-signature">
                                    <label for="signed_Requestor">Signature:</label>
                                    <img src="uploads/signatures/<?= $request['signed_request']; ?>" alt="signature" class="img-fluid">
                                </div>
                            <?php else: ?>
                                <p>No signature provided.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Department</td>
                        <td><?= $request['unit_dept_college']; ?></td>
                    </tr>
                    <tr>
                        <td>Department Head</td>
                        <td><?= $request['unit_head_approval_by']; ?></td>
                    </tr>
                    <tr>
                        <td>Signature:</td>
                        <td>
                            <?php if ($request['signed_Requestor']): ?>
                                <div class="mb-3 kbw-signature">
                                    <label for="signed_Requestor">Signature:</label>
                                    <img src="uploads/signatures/<?= $request['signed_Requestor']; ?>" alt="signature" class="img-fluid">
                                </div>
                            <?php else: ?>
                                <p>No signature provided.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>CPU Acknowledge</td>
                        <td>
                            <?php
                            if ($request['acknowledged_by_cpu'] == 1) {
                                echo "Accepted";
                            } elseif ($request['acknowledged_by_cpu'] == 2) {
                                echo "Rejected";
                            } else {
                                echo "Pending";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Signature:</td>
                        <td>
                            <?php if ($request['signed_by_cpu']): ?>
                                <div class="mb-3 kbw-signature">
                                    <label for="signed_by_cpu">Signature:</label>
                                    <img src="uploads/signatures/<?= $request['signed_by_cpu']; ?>" alt="signature" class="img-fluid">
                                </div>
                            <?php else: ?>
                                <p>No signature provided.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Finance Approval</td>
                        <td>
                            <?php
                            if ($request['university_treasurer_approved'] == 1) {
                                echo "Accepted";
                            } elseif ($request['university_treasurer_approved'] == 2) {
                                echo "Rejected";
                            } else {
                                echo "Pending";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Remarks:</td>
                        <td><?= $request['university_treasurer_remarks']; ?></td>
                    </tr>
                    <tr>
                        <td>Signed By:</td>
                        <td><?= $request['signed_4_by']; ?></td>
                    </tr>
                   
                    <tr>
                        <td>Signature:</td>
                        <td>
                            <?php if ($request['signed_4']): ?>
                                <div class="mb-3 kbw-signature">
                                    <label for="signed_4">Signature:</label>
                                    <img src="uploads/signatures/<?= $request['signed_4']; ?>" alt="signature" class="img-fluid">
                                </div>
                            <?php else: ?>
                                <p>No signature provided.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td><?= $request['status']; ?></td>
                    </tr>
                </tbody>
            </table>
            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
<?php
    } else {
        echo "<div class='container p-5'><h4>No such request found.</h4><a href='index.php' class='btn btn-secondary'>Back to Dashboard</a></div>";
    }
} else {
    echo "<div class='container p-5'><h4>Invalid request.</h4><a href='index.php' class='btn btn-secondary'>Back to Dashboard</a></div>";
}

include('includes/footer.php');
?>