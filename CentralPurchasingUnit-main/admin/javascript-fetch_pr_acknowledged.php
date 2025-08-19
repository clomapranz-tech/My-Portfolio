<?php
// Include database connection file
include('config/dbcon.php');


//Perform a query to fetch acknowledged_by_cpu from the purchase_requests table
$acknowledged_by_cpu_query = "SELECT acknowledged_by_cpu FROM purchase_requests";
$acknowledged_by_cpu_result = mysqli_query($con, $acknowledged_by_cpu_query);

//Check if the query was successful
if ($acknowledged_by_cpu_result) {
    //Fetch data and store it in an array
    $data = array();
    while ($row = mysqli_fetch_assoc($acknowledged_by_cpu_result)) {
        $data[] = $row;
    }

    //Close database connection
    mysqli_close($con);

    //Send the data as JSON response
    echo json_encode($data);
} else {
    //If the query fails, return an error message
    echo json_encode(array('error' => 'Failed to fetch data from the purchase_requests table.'));
}
?>
