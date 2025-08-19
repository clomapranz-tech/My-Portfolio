<?php
// Include database connection
include('config/dbcon.php');

// Get start date and end date from the AJAX request
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// Fetch data from the database based on the selected date range
$sql = "SELECT DATE(requested_date) AS date, COUNT(*) AS count, GROUP_CONCAT(CONCAT_WS(':', id, purchase_request_number, unit_dept_college, requestor_user_email, requested_date)) AS additional_info
        FROM purchase_requests
        WHERE requested_date >= '$startDate' AND requested_date <= '$endDate'
        GROUP BY DATE(requested_date)
        ORDER BY DATE(requested_date)";

$result = mysqli_query($con, $sql);

$dataPoints = array();
$additionalInfo = array(); // Array to store additional column information

// Process fetched data and structure it for CanvasJS and additional info
while ($row = mysqli_fetch_assoc($result)) {
    // Convert date to UNIX timestamp and format as milliseconds for JavaScript
    $timestamp = strtotime($row['date']) * 1000;
    $count = intval($row['count']);
    
    // Split additional information into an array
    $additionalData = explode(',', $row['additional_info']);

    // Push the formatted data to dataPoints array
    $dataPoints[] = array("x" => $timestamp, "y" => $count);
    
    // For each count, store additional column information
    for ($i = 0; $i < $count; $i++) {
        // Split additional data into its components
        $additionalColumns = explode(':', $additionalData[$i]);
        // Store additional information as an associative array
        $additionalInfo[] = array(
            "id" => $additionalColumns[0],
            "purchase_request_number" => $additionalColumns[1],
            "unit_dept_college" => $additionalColumns[2],
            "requestor_user_email" => $additionalColumns[3],
            "requested_date" => $additionalColumns[4]
        );
    }
}

// Close the database connection
mysqli_close($con);

// Combine chart data and additional info into a single array
$responseData = array(
    "dataPoints" => $dataPoints,
    "additionalInfo" => $additionalInfo
);

// Send JSON response back to the client-side code
header('Content-Type: application/json');
echo json_encode($responseData);
?>
