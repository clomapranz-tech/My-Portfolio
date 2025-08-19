<?php
// Assuming you have already established a database connection
include('config/dbcon.php');

// Set the content type to JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['assigned_user_id'])) {
    // Sanitize and validate inputs
    $requestId = mysqli_real_escape_string($con, $_POST['id']);
    $newUserId = mysqli_real_escape_string($con, $_POST['assigned_user_id']);
    

    // Update assigned user in the database
    $update_query = "UPDATE purchase_requests SET assigned_user_id = '$newUserId', assigned_at_date = NOW() WHERE id = '$requestId'";
    $result = mysqli_query($con, $update_query);

    if ($result && mysqli_affected_rows($con) > 0){
        // If the update was successful, send a JSON response
        echo json_encode(['success' => true]);
    } else {
        // If the update failed, send an error JSON response
        echo json_encode(['success' => false, 'error' => 'Failed to update assigned user']);
    }
} else {
    // If the request method is not POST or required parameters are missing
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
