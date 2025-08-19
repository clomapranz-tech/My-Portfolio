<?php
// Include database connection
include('config/dbcon.php');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    $status = $_POST['status'];
    $requestId = $_POST['request_id'];

    // Update the status in the database
    $sql = "UPDATE purchase_requests SET status = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('si', $status, $requestId);

    // Execute the statement
    if ($stmt->execute()) {
        // Status updated successfully
        echo "Status updated successfully";
    } else {
        // Error updating status
        echo "Error updating status";
    }
} else {
    // If the request is not a POST request, return an error message
    echo "Invalid request";
}

// Close the database connection
mysqli_close($con);
?>
