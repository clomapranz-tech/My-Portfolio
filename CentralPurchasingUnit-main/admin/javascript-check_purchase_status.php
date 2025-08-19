<?php
include('config/dbcon.php');

// Get purchase request ID from the request
$purchaseRequestId = $_POST['purchase_request_id'];

//Get Original Purchase Request Status
$purchaseRequestQuery = "SELECT status FROM purchase_requests WHERE id = $purchaseRequestId";
$purchaseRequestResult = mysqli_query($con, $purchaseRequestQuery);
$purchaseRequest = mysqli_fetch_assoc($purchaseRequestResult);

// Retrieve all items associated with the purchase request
$itemsQuery = "SELECT * FROM items WHERE purchase_request_id = $purchaseRequestId";
$itemsResult = mysqli_query($con, $itemsQuery);

// Check if any item's status is not "complete"
$allComplete = true;
while ($item = mysqli_fetch_assoc($itemsResult)) {
    if ($item['item_status'] != 'completed') {
        $allComplete = false;
        break;
    }
}

//Check if there is at least 1 item whose status is complete
$atLeastOneComplete = false;
$itemsQuery = "SELECT * FROM items WHERE purchase_request_id = $purchaseRequestId";
$itemsResult = mysqli_query($con, $itemsQuery);
while ($item = mysqli_fetch_assoc($itemsResult)) {
    if ($item['item_status'] == 'completed') {
        $atLeastOneComplete = true;
        break;
    }
}

// Update the purchase request's status if all items are complete AND if original purchase_request status was approved, partially-completed, or completed
if ($allComplete && ($purchaseRequest['status'] == 'approved' || $purchaseRequest['status'] == 'partially-completed' || $purchaseRequest['status'] == 'completed')) {
    $updateStatusQuery = "UPDATE purchase_requests SET status = 'completed' WHERE id = $purchaseRequestId";

    //Update the purchase request status to partially-completed if there is at least 1 item whose status is complete
} else if ($atLeastOneComplete && ($purchaseRequest['status'] == 'approved' || $purchaseRequest['status'] == 'partially-completed' || $purchaseRequest['status'] == 'completed')){
    $updateStatusQuery = "UPDATE purchase_requests SET status = 'partially-completed' WHERE id = $purchaseRequestId";
}
mysqli_query($con, $updateStatusQuery);

// Close the database connection
mysqli_close($con);
?>
