<?php
include('config/dbcon.php');
include('authentication.php');
include('authentication_cpu_staff_only.php');
include('includes/header.php');
include('includes/scripts.php');

// Fetch distinct statuses from the items table
$statusQuery = "SELECT DISTINCT item_status FROM items";
$statusResult = mysqli_query($con, $statusQuery);

$statusOptions = [
    'pending',
    'approved',
    'for_pricing',
    'for_pricing_officer',
    'issued_pricing_officer',
    'for_delivery_by_supplier',
    'for_pickup_at_supplier',
    'for_tagging',
    'for_delivery_to_requesting_unit',
    'completed',
    'rejected'
];


// Set default status filter
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'All';

// Build the SQL query based on the selected status filter
$query = "SELECT * FROM items";
if ($statusFilter !== 'All') {
    $query .= " WHERE item_status = '$statusFilter'";
}

$result = mysqli_query($con, $query);

$deliveryRequests = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $deliveryRequests[] = $row;
    }
}
?>

<!-- Important javascripts for Dashboard -->
<script src="js/moment.min.js"></script>
<script src="js/daterangepicker.min.js"></script>
<script src="js/chart.js"></script>
<script src="js/dataTables.min.js"></script>
<script src="js/chartjs-plugin-datalabels.js"></script>

<!-- CSS for XU LOGO AND INCASE TAILWIND IS DOWN -->
<style>
    .header {
        display: flex;
        flex-direction: column;
        /* Align items vertically */
        align-items: left;
        /* Center items horizontally */
    }

    .logo-container {
        margin-bottom: 30px;
        /* Adjust margin as needed */
        border: 5px solid #283971;
        /* Adjust border color as needed */
    }

    .logo {
        height: 110px;
        width: 200px;
    }

    /* Optional: Style the header text */
    .header-text {
        text-align: left;
        font-size: 30px;
        margin-top: -40px;
    }

    /*Based on Tailwind Custom Classes*/
    .bg-xu-darkblue {
        --tw-bg-opacity: 1;
        background-color: rgb(40 57 113 / var(--tw-bg-opacity));
    }

    .bg-xu-gold {
        --tw-bg-opacity: 1;
        background-color: rgb(161 145 88 / var(--tw-bg-opacity));
    }
</style>


<div class="container-fluid px-4">
    <div id="printBtn" class="row mt-3 mb-4">
        <div class="col-md-12">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">
                    <a href="index.php">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">Purchase Requests</li>
            </ol>
        </div>
        <div class="col-md-12">
            <!-- button for printing chart only -->
            <button id="printBtn" onclick="printChart()" class="btn bg-success" style="color:white; width: 10rem;">Print Chart</button>
        </div>
    </div>

    <!-- Filter Dropdown -->
    <div id="printBtn" class="col-md-3 mb-3">
        <label for="statusFilter" class="form-label">Filter by Status:</label>
        <select class="form-select" id="statusFilter" name="status" onchange="applyFilter()">
            <option value="All">All</option>
            <?php foreach ($statusOptions as $option): ?>
                <option value="<?php echo $option; ?>" <?php if ($statusFilter === $option) echo 'selected'; ?>>
                    <?php echo $option; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="printableArea"> <!-- make this printable -->
        <!-- Chart: Items by Status -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-xu-gold text-white">
                    <i class="fas fa-chart-area me-1"></i>
                    Items by Status
                </div>
                <div class="card-body">
                    <canvas id="itemsByStatusChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Data Table: Detailed Information -->
        <div class="card mb-4">
            <div class="card-header bg-xu-gold text-white">
                <i class="fas fa-table me-1"></i>
                Detailed Information
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Quantity</th>
                                <th>Item Description</th>
                                <th>Item Justification</th>
                                <th>Item Date Requested</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($deliveryRequests as $request): ?>
                                <tr>
                                    <td><?php echo $request['id']; ?></td>
                                    <td><?php echo $request['item_qty']; ?></td>
                                    <td><?php echo $request['item_description']; ?></td>
                                    <td><?php echo $request['item_justification']; ?></td>
                                    <td><?php echo $request['item_date_requested']; ?></td>
                                    <td><?php echo $request['item_status']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>


<script>
    // Function to apply filter
    function applyFilter() {
        var selectedStatus = document.getElementById('statusFilter').value;
        window.location.href = 'delivery-dashboard.php?status=' + selectedStatus;
    }

    // Process data for Chart: Requests by Status
    var statusCounts = {};
    <?php foreach ($deliveryRequests as $request): ?>
        var status = "<?php echo $request['item_status']; ?>";
        statusCounts[status] = (statusCounts[status] || 0) + 1;
    <?php endforeach; ?>

    // Ensure labels and data match
    var statusLabels = Object.keys(statusCounts);
    var statusData = Object.values(statusCounts);

    console.log("Status Counts:", statusCounts);
    console.log("Labels:", statusLabels);
    console.log("Data:", statusData);

    // Draw Chart: Requests by Status
    var ctx = document.getElementById('itemsByStatusChart').getContext('2d');
    var itemsByStatusChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: statusLabels,
            datasets: [{
                label: '', // Ensure dataset has a label
                data: statusData,
                backgroundColor: ['#4CAF50', '#FF9800', '#2196F3', '#FF5722'], // Example colors
            }]
        },
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // ✅ Hides the legend (green bar)
                },
                datalabels: {
                    color: 'black',
                    backgroundColor: 'white',
                    anchor: 'end',
                    align: 'start',
                    offset: 10,
                    clamp: true,
                    rotation: 0,
                    font: {
                        size: 14
                    },
                    borderRadius: 4,
                    padding: {
                        top: 5,
                        bottom: 5,
                        left: 5,
                        right: 5
                    },
                    formatter: function(value, context) {
                        console.log("Context:", context);
                        // Ensure valid index
                        if (context.dataIndex < context.chart.data.labels.length) {
                            return context.chart.data.labels[context.dataIndex] + ': ' + value;
                        }
                        return value;
                    }
                }
            }
        }
    });
</script>

<!-- DataTables CSS -->
<link href="css/dataTables.dataTables.min.css" rel="stylesheet">

<!-- DataTables JavaScript -->
<script src="js/dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
<style>
    @media print {

        @page {
            margin: 0;
            /* Removes default browser headers/footers */
            size: auto;
            /* Uses the default page size */
        }

        body {
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            width: 100%;
        }

        table {
            width: 100% !important;
            border-collapse: collapse;
        }

        th,
        td {
            white-space: nowrap;
            /* Prevents text from wrapping */
            font-size: 12px;
            /* Adjust font size for better fit */
        }

        .table-responsive {
            overflow: visible !important;
            /* Ensures table is fully displayed */
        }

        /* Prevent page break inside a table row */
        tr {
            page-break-inside: avoid !important;
        }

        #printBtn {
            display: none;
            /* Hide print button */
        }

        /* Remove URL display in print */
        a[href]:after {
            content: none !important;
        }


    }
</style>
<script>
    // Function to print chart and date range picker
    function printChart() {
        // Hide unwanted elements before printing
        $('#printBtn').hide(); // Hide the print button
        window.print(); // Print the page
        $('#printBtn').show(); // Show the print button again after printing
    }
</script>