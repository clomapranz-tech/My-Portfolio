<?php
include('authentication.php');

// Check if the request contains the signature field
if (isset($_POST['signatureField'], $_POST['request_id'])) {
    $signatureField = $_POST['signatureField'];
    $request_id = $_POST['request_id'];

    // Perform necessary sanitization/validation of the signature field
    // Here you should ensure that $signatureField is safe to use in a database query

    // Delete the signature from the database
    $sql_delete_signature = "UPDATE purchase_requests SET $signatureField = NULL WHERE id = ?";
    $stmt = $con->prepare($sql_delete_signature);
    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("i", $request_id);
        if ($stmt->execute()) {
            // Signature deleted successfully
            echo "success";
            exit;
        } else {
            // Error executing the statement
            echo "Error executing statement.";
            exit;
        }
    } else {
        // Error preparing the statement
        echo "Error preparing statement.";
        exit;
    }
} else {
    // No signature field provided in the request
    echo "Invalid request.";
    exit;
}
?>
