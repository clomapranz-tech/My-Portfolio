<?php
// Include database connection
include('config/dbcon.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve start and end dates from the POST request
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Prepare and execute the SQL query to fetch items within the specified date range
    $sql = "SELECT * FROM items WHERE DATE (item_date_requested ) BETWEEN ? AND ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('ss', $startDate, $endDate);
    $stmt->execute();

    // Get result set
    $result = $stmt->get_result();

    // Fetch the results as an associative array
    $items = $result->fetch_all(MYSQLI_ASSOC);

    // Prepare the response array
    $response = array(
        'status' => 'success',
        'items' => $items,
        'count' => count($items)
    );

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If the request method is not POST, return an error
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('status' => 'error', 'message' => 'Only POST requests are allowed.'));
}

// Close the database connection
mysqli_close($con);
?>
