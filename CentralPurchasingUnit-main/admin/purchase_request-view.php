<?php
require_once('authentication.php');
require_once('includes/header.php');
require_once('includes/scripts.php');

// Initialize user role variables using constants for better maintenance
define('ROLE_ADMIN', 1);
define('ROLE_SUPER_USER', 2);
define('ROLE_DEPARTMENT_EDITOR', 3);
define('ROLE_UNIT_HEAD', 4);
define('ROLE_FINANCE_DEP', 5);

// Set role flags based on session data
$roles = [
    'admin' => false,
    'super_user' => false,
    'department_editor' => false,
    'unit_head' => false,
    'finance_dep' => false
];

$auth_role = $_SESSION['auth_role'] ?? 0;
switch ($auth_role) {
    case ROLE_ADMIN:
        $roles['admin'] = true;
        break;
    case ROLE_SUPER_USER:
        $roles['super_user'] = true;
        break;
    case ROLE_DEPARTMENT_EDITOR:
        $roles['department_editor'] = true;
        break;
    case ROLE_UNIT_HEAD:
        $roles['unit_head'] = true;
        break;
    case ROLE_FINANCE_DEP:
        $roles['finance_dep'] = true;
        break;
}


// Get current user info
$current_user_email = $_SESSION['auth_user']['user_email'] ?? '';
$current_user_id = $_SESSION['auth_user']['user_id'] ?? 0;

// Prepare database queries based on user role
if ($roles['super_user']) {
    $default_query = "SELECT * FROM purchase_requests WHERE status != 'completed' AND status != 'rejected' AND unit_head_approval = 'recommending-approval' ORDER BY id DESC";
} elseif ($roles['admin']) {
    $default_query = "SELECT * FROM purchase_requests WHERE assigned_user_id = '" . mysqli_real_escape_string($con, $current_user_id) . "' AND status != 'completed' AND status != 'rejected' ORDER BY id DESC";
} elseif ($roles['department_editor']) {
    $default_query = "SELECT * FROM purchase_requests WHERE '$current_user_email' NOT IN (COALESCE(signed_1_by, ''), COALESCE(signed_2_by, ''), COALESCE(signed_3_by, ''), COALESCE(signed_4_by, ''), COALESCE(signed_5_by, '')) AND acknowledged_by_cpu = 1 ORDER BY id DESC";
} elseif ($roles['unit_head']) {
    $default_query = "SELECT * FROM purchase_requests WHERE unit_head_approval = 'pending' AND unit_head = '" . mysqli_real_escape_string($con, $current_user_id) . "' ORDER BY id DESC";
} elseif ($roles['finance_dep']) {
    $default_query = "SELECT * FROM purchase_requests WHERE university_treasurer_approved != '1' ORDER BY requested_date DESC";
} else {
    $default_query = "SELECT * FROM purchase_requests ORDER BY requested_date DESC";
}

// Get the query from the URL or use the default
$request = isset($_GET['request']) ? $_GET['request'] : $default_query;
?>

<div class="container-fluid px-4">
    <div id="printBtn" class="row mt-3 mb-2">
        <div class="col-md-12">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Purchase Requests</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?php include('message.php'); ?>
            <div class="card">
                <div class="card-header">
                    <h4>View Purchase Requests</h4>
                </div>

                <div class="card-body" style="overflow-x: auto;">
                    <!-- Filter controls -->
                    <div class="mb-3">
                        <div class="btn-group">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Filter by Status
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                <li><a class="dropdown-item filter-btn" data-status="all">All</a></li>
                                <li><a class="dropdown-item filter-btn" data-status="pending">Pending</a></li>
                                <li><a class="dropdown-item filter-btn" data-status="approved">Approved</a></li>
                                <li><a class="dropdown-item filter-btn" data-status="rejected">Rejected</a></li>
                                <li><a class="dropdown-item filter-btn" data-status="partially-completed">Partially Completed</a></li>
                                <li><a class="dropdown-item filter-btn" data-status="completed">Completed</a></li>
                            </ul>
                        </div>

                        <div class="btn-group mx-2">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="filterView" data-bs-toggle="dropdown" aria-expanded="false">
                                Show (For Signers Only)
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="filterView">
                                <li><a class="dropdown-item filter-view" data-status="all">All</a></li>
                                <li><a class="dropdown-item filter-view" data-status="not_signed">Not Signed by me</a></li>
                                <li><a class="dropdown-item filter-view" data-status="signed_by_me">Signed by me</a></li>
                            </ul>
                        </div>

                        <a class="btn btn-danger" href="purchase_request-view.php">Clear Filters</a>
                    </div>

                    <!-- Purchase requests table -->
                    <table id="myPurchaseRequests" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Requestor Name</th>
                                <th>Email</th>
                                <?php if ($roles['super_user'] || $roles['unit_head']): ?>
                                    <th>Department Head Approval</th>
                                <?php endif; ?>
                                <th>Acknowledged by CPU</th>
                                <?php if ($roles['super_user'] || $roles['finance_dep']): ?>
                                    <th>Finance Approval</th>
                                <?php endif; ?>
                                <th>Status</th>
                                <th>Requested Date</th>
                                <!-- <?php if ($roles['super_user'] || $roles['admin']): ?><th>Item Details</th><?php endif; ?> -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $request_run = mysqli_query($con, $request);

                            if (mysqli_num_rows($request_run) > 0) {
                                foreach ($request_run as $row) {
                            ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['printed_name']) ?></td>
                                        <td><?= htmlspecialchars($row['iptel_email']) ?></td>
                                        <?php if ($roles['super_user'] || $roles['unit_head']): ?>
                                        <td><?= !empty($row['unit_head_approval']) ? htmlspecialchars($row['unit_head_approval']) : 'N/A' ?></td>
                                        <?php endif; ?> 
                                        <td><?= $row['acknowledged_by_cpu'] == 1 ? "CPU Acknowledged" : "Not Acknowledged" ?></td>
                                        <?php if ($roles['super_user'] || $roles['finance_dep']): ?>
                                        <td>
                                            <?= 
                                                $row['university_treasurer_approved'] == 1 ? "Approved" : 
                                                ($row['university_treasurer_approved'] == 2 ? "Rejected" : "Pending") 
                                            ?>
                                        </td>
                                        <?php endif; ?> 
                                        <td><?= htmlspecialchars($row['status']) ?></td>
                                        <td><?= date('F j Y h:i A', strtotime($row['requested_date'])) ?></td>
                                        <!-- <?php if ($roles['super_user'] || $roles['admin']): ?>
                                            <td><a href="item_details.php?id=<?= $row['id'] ?>">View Details</a></td>
                                        <?php endif; ?> -->
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="actionDropdown<?= $row['id'] ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="actionDropdown<?= $row['id'] ?>">
                                                    <li><a class="dropdown-item" href="purchase_request-view-only.php?id=<?= $row['id'] ?>">View</a></li>
                                                    <li><a class="dropdown-item" href="purchase_request-edit.php?id=<?= $row['id'] ?>">Edit</a></li>
                                                    <li><a class="dropdown-item" href="print-template.php?id=<?= $row['id'] ?>">Print</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='12' class='text-center'>No Records Found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Confirmation for delete buttons
    document.querySelectorAll(".deleteButton").forEach(function(button) {
        button.addEventListener("click", function(event) {
            if (!confirm("Are you sure you want to delete this Request?")) {
                event.preventDefault();
            }
        });
    });

    // Confirmation for hide buttons
    document.querySelectorAll(".hideButton").forEach(function(button) {
        button.addEventListener("click", function(event) {
            if (!confirm("Are you sure you want to Hide this Request?")) {
                event.preventDefault();
            }
        });
    });

    // Filter by status
    document.querySelectorAll('.filter-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const status = this.getAttribute('data-status');

            fetch('javascript-generate_query.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'status=' + encodeURIComponent(status)
                })
                .then(response => response.text())
                .then(data => {
                    const cleanData = data.replace(/^"(.*)"$/, '$1');
                    window.location.href = 'purchase_request-view.php?request=' + encodeURIComponent(cleanData);
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Filter by view (for signers)
    document.querySelectorAll('.filter-view').forEach(function(button) {
        button.addEventListener('click', function() {
            const status = this.getAttribute('data-status');
            const current_user_email = '<?= $current_user_email ?>';

            fetch('javascript-generate_query.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'status=' + encodeURIComponent(status) + '&current_user_email=' + encodeURIComponent(current_user_email)
                })
                .then(response => response.text())
                .then(data => {
                    const cleanData = data.replace(/^"(.*)"$/, '$1');
                    window.location.href = 'purchase_request-view.php?request=' + encodeURIComponent(cleanData);
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Update assigned user via AJAX
    document.querySelectorAll('.assigned-user').forEach(function(select) {
        select.addEventListener('change', function() {
            const newUserId = this.value;
            const requestId = this.getAttribute('data-request-id');

            fetch('javascript-update_assigned_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + encodeURIComponent(requestId) + '&assigned_user_id=' + encodeURIComponent(newUserId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        console.error('Error updating assigned user');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>

<?php include('includes/footer.php'); ?>