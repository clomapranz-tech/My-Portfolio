<?php
include('authentication.php');

if (isset($_POST['id'])) {
    $removedItemid = $_POST['id']; // Expecting an array of item IDs
    // Delete the item from the database
    $sql_delete = "DELETE FROM items WHERE id = ?";
    $stmt = $con->prepare($sql_delete);
    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("i", $removedItemid);
        if ($stmt->execute()) {
            // Signature deleted successfully
            echo json_encode(["status" => "success", "message" => "Items removed successfully"]);
            exit;
        } else {
            // Error executing the statement
            echo json_encode(["status" => "error", "message" => "Something went wrong."]);
            exit;
        }
    } else {
        // Error preparing the statement
        echo "Error preparing statement.";
        exit;
    }
}
