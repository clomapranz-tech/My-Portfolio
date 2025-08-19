<?php
// Include your database connection or any other necessary files
include('config/dbcon.php');
// Retrieve the status from the AJAX request
$status = $_POST['status'] ?? '';
$current_user_email = $_POST['current_user_email'] ?? '';

// Initialize an empty variable to store the generated query
$request = "";

// Generate query based on selected status
switch ($status) {
    //Cases Filter by Status
    case 'all':
        $request = 'SELECT * FROM purchase_requests ORDER BY id DESC';
        break;
    case 'pending':
        $request = "SELECT * FROM purchase_requests WHERE status = 'pending' ORDER BY id DESC";
        break;
    case 'approved':
        $request = "SELECT * FROM purchase_requests WHERE status = 'approved' ORDER BY id DESC";
        break;
    case 'rejected':
        $request = "SELECT * FROM purchase_requests WHERE status = 'rejected' ORDER BY id DESC";
        break;
    case 'partially-completed':
        $request = "SELECT * FROM purchase_requests WHERE status = 'partially-completed' ORDER BY id DESC";
        break;
    case 'completed':
        $request = "SELECT * FROM purchase_requests WHERE status = 'completed' ORDER BY id DESC";
        break;

    //Cases Filter by Signatures
    case 'all':
        $request = 'SELECT * FROM purchase_requests ORDER BY id DESC';
        break;
    case 'not_signed':
        $request = "SELECT * FROM purchase_requests WHERE '$current_user_email' NOT IN (COALESCE(signed_1_by, ''), COALESCE(signed_2_by, ''), COALESCE(signed_3_by, ''), COALESCE(signed_4_by, ''), COALESCE(signed_5_by, '')) ORDER BY id DESC";

        break;
    case 'signed_by_me':
        $request = "SELECT * FROM purchase_requests WHERE '$current_user_email' IN (signed_1_by, signed_2_by, signed_3_by, signed_4_by, signed_5_by) ORDER BY id DESC";
        break;
    // Add cases for other statuses as needed
}

// Send the generated query back as a JSON response
echo json_encode($request);
?>
