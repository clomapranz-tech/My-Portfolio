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
                            Requests Grouped by Date and Status
                        </div>

                        <div class="card-body">
                            <!-- Chart will be rendered here -->
                            <div class="chart-container1" style=" height:40vh; width:100vw">
                                <canvas id="pieChart1"></canvas>
                            </div>

                            <!-- Custom legend for percentage breakdown -->
                            <div id="legend1">
                                <!-- Legend for percentage breakdown -->
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <!-- Chart 2 -->
            <div class="col-md-6">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-area me-1"></i>
                            Requests by Acknowledgement
                        </div>

                        <div class="card-body">
                            <!-- Chart will be rendered here -->
                            <div class="chart-container2" style=" height:40vh; width:100vw">
                                <canvas id="pieChart2"></canvas>
                            </div>

                            <!-- Custom legend for percentage breakdown -->
                            <div id="legend2">
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
                                        <th style="width:10%">Purchase Request Number</th>
                                        <th style="width:10%">Department/College</th>
                                        <th style="width:5%">Requestor User Email</th>
                                        <th style="width:5%">Requested Date</th>
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
                    url: 'javascript-fetch_report-status.php', // PHP script to fetch data from database
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function(response) {
                        var chartData = response.dataPoints; // Chart data
                        var additionalInfo = response.additionalInfo; // Additional information

                        // Update chart with fetched data
                        updateChart1(chartData);
                        updateChart2(additionalInfo);

                        // Render additional information
                        renderAdditionalInfo(additionalInfo);
                    }
                });
            }

            // Function to update the chart with new data
            function updateChart1(data) {
                // Calculate total count
                var totalCount = data.reduce((total, point) => total + point.count, 0);

                //clear the canvas and create a new chart
                $('#pieChart1').remove(); // This is my <canvas> element
                $('.chart-container1').append('<canvas id="pieChart1"><canvas>'); // Redraw chart in the container

                var ctx = document.getElementById('pieChart1').getContext('2d');

                // Define labels array outside the datasets loop
                var labels = data.map(function(point) {
                    return point.status;
                });

                // Extract unique statuses from the data
                var uniqueStatuses = [...new Set(data.map(point => point.status))];

                // Create datasets for each unique status
                var datasets = uniqueStatuses.map(status => {
                    return {
                        label: status,
                        data: data.filter(point => point.status === status).map(point => point.count),
                        backgroundColor: getStatusColor(status),
                        borderColor: getStatusBorderColor(status),
                        borderWidth: 1,
                        hidden: false // By default, datasets are visible
                    };
                });

                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels, // Use the labels array here
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true, // Display the legend
                                position: 'right'
                            }
                        },
                        scales: {
                            x: {
                                type: 'category',
                                labels: data.map(function(point) {
                                    return new Date(point.date).toLocaleDateString();
                                }),
                                position: 'bottom', // Position the x-axis at the top
                                grid: {
                                    display: false // Hide the grid lines
                                }
                            },

                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1 // Set the step size for the y-axis
                                }
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

                            // Calculate and display total count for all statuses
                            var totalCount = chart.data.datasets.reduce((acc, dataset) => {
                                return acc + dataset.data.reduce((a, b) => a + b, 0);
                            }, 0);
                            ctx.fillText('Total: ' + totalCount, chart.width - 60, chart.height - 20);

                            // Calculate and display count for each status
                            chart.data.datasets.forEach(function(dataset, i) {
                                var statusCount = dataset.data.reduce((a, b) => a + b, 0);
                                ctx.fillText(dataset.label + ': ' + statusCount, chart.width - 60, chart.height - 40 - (i * 20));
                            });

                            ctx.restore();
                        }
                    }]

                });

                // Toggle dataset visibility on legend click
                document.getElementById('pieChart1').onclick = function(evt) {
                    var activePoints = myChart.getElementsAtEventForMode(evt, 'dataset', {
                        intersect: true,
                        hidden: true
                    });
                    if (activePoints.length > 0) {
                        var index = activePoints[0].index;
                        var dataset = myChart.data.datasets[index];
                        dataset.hidden = !dataset.hidden;
                        myChart.update();
                    }
                };
            }

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



            // Update chart 2
            function updateChart2(data) {
                // Convert the 'acknowledged_by_cpu' property to numbers for accurate counting
                var acknowledgedCount = data.filter(point => Number(point.acknowledged_by_cpu) === 1).length;
                var unacknowledgedCount = data.filter(point => Number(point.acknowledged_by_cpu) === 0).length;

                //clear the canvas and create a new chart
                $('#pieChart2').remove(); // This is my <canvas> element
                $('.chart-container2').append('<canvas id="pieChart2"><canvas>'); // Redraw chart in the container

                var ctx = document.getElementById('pieChart2').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        // Labels for the sections in the chart
                        labels: ['Acknowledged', 'Unacknowledged'],
                        datasets: [{
                            // Data to be displayed (number of requests)
                            data: [acknowledgedCount, unacknowledgedCount],
                            backgroundColor: ['rgba(50, 255, 50, 0.2)', 'rgba(255, 50, 50, 0.2)'],
                            borderColor: ['rgba(50, 255, 50, 1)', 'rgba(255, 50, 50, 1)'],
                            borderWidth: 1,
                            label: 'Number of Requests'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true, // Display the legend
                                position: 'right'
                            },
                            datalabels: {
                                anchor: 'end',
                                align: 'end',
                                formatter: function(value, context) {
                                    if (context.dataset.label === 'Number of Requests') {
                                        return value;
                                    }
                                    return null;
                                }
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

                            // Calculate and display total count for both acknowledged and unacknowledged
                            var total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);

                            // Retrieve count for acknowledged and unacknowledged sections
                            var acknowledged = chart.data.datasets[0].data[0];
                            var unacknowledged = chart.data.datasets[0].data[1];

                            // Display the counts separately
                            ctx.fillText('Acknowledged: ' + acknowledged, chart.width - 100, chart.height - 50);
                            ctx.fillText('Unacknowledged: ' + unacknowledged, chart.width - 100, chart.height - 30);

                            // Display the total count
                            ctx.fillText('Total: ' + total, chart.width - 100, chart.height - 10);

                            ctx.restore();
                        }
                    }]

                });
            }


            //End of update chart 2




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
                        rowData.purchase_request_number,
                        rowData.unit_dept_college,
                        rowData.requestor_user_email,
                        moment(rowData.requested_date).format('MMMM D YYYY')
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