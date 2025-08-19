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
        <div class="row mt-3">
            <div id="printBtn" class="row mt-3">
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Chart will be rendered here -->
                        <div class="chart-container" style="display: inline-block;width:800px; height:400px;border:3px dashed; align-items:center; justify-content: center; text-align: center;">
                            <canvas id="myChart"></canvas>
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
                    url: 'javascript-fetch_data.php', // PHP script to fetch data from database
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
                        updateChart(chartData);

                        // Render additional information
                        renderAdditionalInfo(additionalInfo);
                    }
                });
            }

            // Function to update the chart with new data
            function updateChart(data) {
                //clear the canvas and create a new chart
                $('#myChart').remove(); // This is my <canvas> element
                $('.chart-container').append('<canvas id="myChart"><canvas>'); // Redraw chart in the container

                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(function(point) {
                            return new Date(point.x).toLocaleDateString(); // Convert Unix timestamp to Date object and format it
                        }),
                        datasets: [{
                            label: 'Total Number of Requests by date',
                            data: data.map(function(point) {
                                return point.y;
                            }),
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            textBackgroundColor: 'rgba(255, 90, 100, 1)', // Background color for datalabels
                        }]
                    },
                    // Enable datalabels plugin and AfterDraw
                    plugins: [{
                            afterDatasetsDraw: function(chart) {
                                var ctx = chart.ctx;
                                ctx.save();
                                ctx.fillStyle = 'black';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.font = 'bold 12px Arial';
                                var total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                ctx.fillText('Total: ' + total, chart.width - 50, chart.height - 380);
                                ctx.restore();
                            }
                        },
                        ChartDataLabels
                    ],
                    options: {
                        //Datalabel configurations
                        plugins: {
                            datalabels: {
                                backgroundColor: function(context) {
                                    return context.dataset.textBackgroundColor;
                                },
                                color: 'white',
                                font: {
                                    weight: 'bold'
                                },

                                formatter: function(value, ctx) {
                                    let label = ctx.chart.data.labels[ctx.dataIndex];
                                    let dataset = ctx.chart.data.datasets[ctx.datasetIndex];
                                    let total = dataset.data.reduce((acc, data) => acc + data, 0);
                                    return `${value}`;
                                }
                            }
                        },


                        // Customizing chart appearance
                        responsive: true, //Resizes the chart canvas when its container does
                        Animation: {
                            duration: 2000,
                            easing: 'easeInOutCubic'
                        },

                        scales: {
                            x: {
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Date Range'
                                }
                            },
                            y: {
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Number of Requests'
                                },
                                ticks: {
                                    beginAtZero: true,
                                    precision: 0
                                }
                            }
                        }
                    }
                });
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