<?php
// Include database connection file
include('config/dbcon.php');

// Define array to hold categorized overdue counts
$overdueCounts = array(
    '0-3 Days' => array(),
    '4-6 Days' => array(),
    '7-9 Days' => array(),
    '10+ Days' => array()
);

// Perform a query to fetch overdue purchase requests
$status_query = "SELECT * FROM purchase_requests WHERE status != 'completed' AND status != 'rejected' AND DATEDIFF(CURDATE(), requested_date) > 3";
$status_result = mysqli_query($con, $status_query);

// Check if the query was successful
if ($status_result) {
    // Fetch data and categorize overdue requests
    while ($row = mysqli_fetch_assoc($status_result)) {
        $days_overdue = floor((time() - strtotime($row['requested_date'])) / (60 * 60 * 24)); // Calculate days overdue
        
        // Determine the 3-day increment category
        if ($days_overdue >= 0 && $days_overdue <= 3) {
            $increment = '0-3 Days';
        } elseif ($days_overdue >= 4 && $days_overdue <= 6) {
            $increment = '4-6 Days';
        } elseif ($days_overdue >= 7 && $days_overdue <= 9) {
            $increment = '7-9 Days';
        } else {
            $increment = '10+ Days';
        }

        // Categorize by status
        $status = $row['status'];
        if (!isset($overdueCounts[$increment][$status])) {
            $overdueCounts[$increment][$status] = 0;
        }
        $overdueCounts[$increment][$status]++;
    }

    // Close database connection
    mysqli_close($con);

    // Send the categorized counts as JSON response
    echo json_encode($overdueCounts);
} else {
    // If the query fails, return an error message
    echo json_encode(array('error' => 'Failed to fetch data from the purchase_requests table.'));
}
?>
