<?php
include('config/dbcon.php');
include('authentication.php');
include('authentication_cpu_staff_only.php');
include('includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Generation Charts</title>
    <link href="css/daterangepicker.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="css/dataTables.dataTables.min.css" />

</head>

<body>

    <div class="container-fluid">
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

        <div class="row mt-3">
            <div class="col-md-4 offset-md-4">
                <!-- Date range picker input field -->
                <input type="text" id="dateRangePicker" name="dateRangePicker" class="form-control bg-primary text-center" style="height:50px; font-size: 24px; color:white; font-family:Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;" />
            </div>
        </div>
        <div class="row mt-3" style="align-items: center; justify-content: center; text-align:center;">
            <!-- Chart 1 -->
            <div class="col-md-6">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-area me-1"></i>
                            Items Grouped by Date and Status
                        </div>

                        <div class="card-body">
                            <!-- Chart will be rendered here -->
                            <div class="chart-container1" style=" height:40vh; width:100vw">
                                <canvas id="pieChart1"></canvas>
                            </div>

                            <div id="total-count"></div>

                            <!-- Custom legend for percentage breakdown -->
                            <div id="legend1">
                                <!-- Legend for percentage breakdown -->
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Additional information will be rendered here -->
                        <div id="additionalInfo" class="TableDiv">
                            <table id="additionalTable" class=" table-bordered table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:1%">ID</th>
                                        <th style="width:10%">Purchase Request ID</th>
                                        <th style="width:10%">Item Qty</th>
                                        <th style="width:5%">Item Description</th>
                                        <th style="width:5%">Item Requested Date</th>
                                        <th style="width:5%">Item Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Additional information will be rendered here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/daterangepicker.min.js"></script>
    <script src="js/chart.js"></script>
    <script src="js/dataTables.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/chartjs-plugin-datalabels.js"></script>

    <script>
        $(document).ready(function() {

            // Initialize date range picker
            $('#dateRangePicker').daterangepicker({
                opens: 'right', // Set the calendar to open on the right
                startDate: moment().subtract(1, 'days'), // Default start date (7 days ago)
                endDate: moment(),
                ranges: {
                    'Last 7 Days': [moment().subtract(7, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function(start, end, label) {
                // Fetch data for the selected date range
                fetchData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });



            // Function to fetch data for the selected date range
            function fetchData(startDate, endDate) {
                $.ajax({
                    url: 'javascript-fetch_report-items.php', // PHP script to fetch data from database
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(response) {
                        console.log('testing', response);
                        var items = response.items; // Items data

                        // Update chart with fetched data
                        updateChart1(items);

                        // Calculate total count
                        var totalCount = items.length;

                        // Display total count
                        $('#total-count').text('Total Count: ' + totalCount);

                        //Display total count per item_status and percentage breakdown, with proper coloring
                        var groupedData = groupItemsByDateAndStatus(items);
                        var legend = '';
                        for (var status in groupedData) {
                            var count = 0;
                            for (var date in groupedData[status]) {
                                count += groupedData[status][date];
                            }
                            var percentage = ((count / totalCount) * 100).toFixed(2);
                            legend += '<div>' + status + ': ' + count + ' (' + percentage + '%)</div>';
                        }
                        $('#legend1').html(legend);

                        //Color the legend based on the chart colors with background
                        $('#legend1 div').each(function(index) {
                            $(this).css('background-color', getStatusColor(Object.keys(groupedData)[index]));
                        });


                        // Render additional information
                        renderAdditionalInfo(items);
                    }

                });
            }

            // Function to update chart with item data
            function updateChart1(data) {
                // Group items by date and status
                var groupedData = groupItemsByDateAndStatus(data);

                var totalCount = data.reduce((total, point) => total + point.count, 0);


                // Extract unique dates for x-axis labels
                var uniqueDates = [...new Set(data.map(item => formatDate(item.item_date_requested)))];

                // Create datasets for each status
                var datasets = [];
                for (var status in groupedData) {
                    var itemCounts = [];
                    uniqueDates.forEach(date => {
                        var count = groupedData[status][date] || 0;
                        itemCounts.push(count);
                    });
                    datasets.push({
                        label: status,
                        data: itemCounts,
                        backgroundColor: getStatusColor(status),
                        borderColor: getStatusBorderColor(status),
                        borderWidth: 1,
                        hidden: false // By default, datasets are visible
                    });
                }

                // Calculate total count
                var totalCount = 0;
                data.forEach(item => totalCount++);

                // Clear canvas and create a new chart
                $('#pieChart1').remove();
                $('.chart-container1').append('<canvas id="pieChart1"><canvas>');
                var ctx = document.getElementById('pieChart1').getContext('2d');

                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: uniqueDates, // Use unique dates for x-axis labels
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'right'
                            },

                        },
                        scales: {
                            x: {
                                type: 'category',
                                position: 'bottom',
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: [{
                            afterDatasetsDraw: function(chart) {
                                var ctx = chart.ctx;
                                ctx.save();
                                ctx.fillStyle = 'black';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.font = 'bold 12px Arial';

                                // Display total count
                                ctx.fillText('Total Count: ' + totalCount, chart.width - 50, chart.height - 40);

                                ctx.restore();
                            }
                        }],
                        tooltips: {
                            callbacks: {
                                label: function(context) {
                                    var date = context.label;
                                    var status = context.dataset.label;
                                    var count = groupedData[status][date] || 0;
                                    return 'Date: ' + date + '\nStatus: ' + status + '\nCount: ' + count;
                                }
                            }
                        }
                    }
                });
            }





            // Function to group items by date and status
            function groupItemsByDateAndStatus(data) {
                var groupedData = {};
                data.forEach(item => {
                    var date = formatDate(item.item_date_requested);
                    var status = item.item_status;
                    if (!groupedData[status]) {
                        groupedData[status] = {};
                    }
                    if (!groupedData[status][date]) {
                        groupedData[status][date] = 0;
                    }
                    groupedData[status][date]++;
                });
                return groupedData;
            }

            // Function to format date in YYYY-MM-DD format
            function formatDate(dateString) {
                var date = new Date(dateString);
                var year = date.getFullYear();
                var month = String(date.getMonth() + 1).padStart(2, '0');
                var day = String(date.getDate()).padStart(2, '0');
                return year + '-' + month + '-' + day;
            }

            // Other functions remain unchanged



            function getStatusColor(status) {
                switch (status) {
                    case 'pending':
                        return 'rgba(200, 200, 200, 0.2)';
                    case 'approved':
                        return 'rgba(50, 50, 255, 0.2)';
                    case 'rejected':
                        return 'rgba(255, 50, 50, 0.2)';
                    case 'completed':
                        return 'rgba(0, 255, 0, 0.2)';
                    case 'partially-completed':
                        return 'rgba(255, 255, 50, 0.2)';
                    default:
                        return 'rgba(0, 0, 0, 0.2)'; // Default color
                }
            }

            function getStatusBorderColor(status) {
                switch (status) {
                    case 'pending':
                        return 'rgba(200, 200, 200, 1)';
                    case 'approved':
                        return 'rgba(50, 50, 255, 1)';
                    case 'rejected':
                        return 'rgba(255, 50, 50, 1)';
                    case 'completed':
                        return 'rgba(0, 255, 0, 1)';
                    case 'partially-completed':
                        return 'rgba(255, 255, 50, 1)';
                    default:
                        return 'rgba(0, 0, 0, 1)'; // Default border color
                }
            }




            // Function to render additional information
            function renderAdditionalInfo(info) {
                var table = $('#additionalTable').DataTable();

                // Check if DataTable is already initialized
                if ($.fn.dataTable.isDataTable('#additionalTable')) {
                    // If DataTable is already initialized, just redraw the table
                    table.clear().draw();
                } else {
                    // If DataTable is not initialized, initialize it
                    table = $('#additionalTable').DataTable({
                        "order": [
                            [0, "desc"]
                        ]
                    });
                }

                // Add rows to the table
                for (var i = 0; i < info.length; i++) {
                    var rowData = info[i];
                    var row = [
                        rowData.id,
                        rowData.purchase_request_id,
                        rowData.item_qty,
                        rowData.item_description,
                        moment(rowData.item_requested_date).format('MMMM D YYYY'),
                        rowData.item_status
                    ];
                    table.row.add(row).draw();
                }
            }

            // Initially fetch data for the default date range
            var startDate = moment().subtract(7, 'days').format('YYYY-MM-DD'); // Default start date (7 days ago)
            var endDate = moment().format('YYYY-MM-DD'); // Default end date (today)
            fetchData(startDate, endDate);
        });

        // Function to print chart and date range picker
        function printChart() {
            // Hide unwanted elements before printing
            $('.TableDiv').hide(); // Hide the table
            $('#printBtn').hide(); // Hide the print button
            window.print(); // Print the page
            $('.TableDiv').show(); // Show the table again after printing
            $('#printBtn').show(); // Show the print button again after printing
        }
    </script>
</body>

</html>


<?php
include('includes/footer.php');
?>