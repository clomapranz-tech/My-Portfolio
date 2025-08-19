<?php
include('authentication.php');
include('includes/header.php');
include('includes/scripts.php');

//Initialize Variable
$admin = null;
$super_user = null;
$department_editor = null;
//Check level
if($_SESSION['auth_role']==1)
{
    $admin = true;
    $super_user = false;
    $department_editor = false;
}
elseif($_SESSION['auth_role']==2)
{
    $admin = false;
    $super_user = true;
    $department_editor = false;
}
elseif($_SESSION['auth_role']==3)
{
    $admin = false;
    $super_user = false;
    $department_editor = true;
}
//PHP spreadsheet
// Include PhpSpreadsheet autoloader
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Load the template Excel file
$templateFile = 'template.xlsx';
$spreadsheet = IOFactory::load($templateFile);

// Get the active sheet
$sheet = $spreadsheet->getActiveSheet();


// Get purchase request ID from the URL
$purchase_request_id = $_GET['id'];

//Query purchase request data from the database
$purchase_request_query = "SELECT * FROM purchase_requests WHERE id = '$purchase_request_id'";
$purchase_request_result = mysqli_query($con, $purchase_request_query);
$request_row = mysqli_fetch_array($purchase_request_result);

// Query item data from the database
$item_query = "SELECT * FROM items WHERE purchase_request_id = '$purchase_request_id'";
$item_result = mysqli_query($con, $item_query);

// Initialize arrays to store item data
$itemNumbers = array();
$itemQtys = array();
$itemDescriptions = array();
$itemJustifications = array();

// Fetch item data and populate arrays
while ($row = mysqli_fetch_assoc($item_result)) {
    $itemNumbers[] = $row['item_number'];
    $itemQtys[] = $row['item_qty'];
    $itemDescriptions[] = $row['item_description'];
    $itemJustifications[] = $row['item_justification'];
}

// Start row for inserting items in Excel
$startRow = 6;

// Loop through items and insert them into the Excel template
for ($i = 0; $i < count($itemNumbers); $i++) {
    $row = $startRow + $i;
    $sheet->setCellValue('A' . $row, $itemNumbers[$i]);
    $sheet->setCellValue('B' . $row, $itemQtys[$i]);

    // Set item descriptions spanning from columns C to H
    $sheet->mergeCells('C' . $row . ':H' . $row);
    $sheet->setCellValue('C' . $row, $itemDescriptions[$i]);
    // Apply border and alignment for description cells
    $sheet->getStyle('C' . $row . ':H' . $row)->applyFromArray([
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ]);

    // Set item justifications spanning from columns I to L
    $sheet->mergeCells('I' . $row . ':L' . $row);
    $sheet->setCellValue('I' . $row, $itemJustifications[$i]);
    // Apply border and alignment for justification cells
    $sheet->getStyle('I' . $row . ':L' . $row)->applyFromArray([
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ]);

    // Apply alignment for columns A and B
    $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ]);

    // Apply border for the right side of column A and left side of column B
    $sheet->getStyle('A' . $row)->applyFromArray([
        'borders' => [
            'right' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ]);
    $sheet->getStyle('B' . $row)->applyFromArray([
        'borders' => [
            'left' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            ],
        ],
    ]);
}





// Shift down other fields beneath the items
$endRow = $startRow + count($itemNumbers) - 1;
$minShiftDownRows = 1; // Minimum number of rows to shift down

// Calculate the actual number of rows to shift down based on the number of items
$shiftDownRows = max($minShiftDownRows, count($itemNumbers));

// Shift down rows beneath the items
$sheet->insertNewRowBefore($endRow + 1, $shiftDownRows);

// Update the end row after shifting
$endRow += $shiftDownRows;

// Value to search for
$searchValue_UnitDept = 'Department:________________________________';

// Loop through all cells to find the cell with the specific value UnitDept
foreach ($sheet->getRowIterator() as $row) {
    foreach ($row->getCellIterator() as $cell) {
        $cellValue = $cell->getValue();
        if ($cellValue == $searchValue_UnitDept) {
            // Found the cell with the specific value
            // Perform operations here, like inserting something near it
            // For example, insert a value in the cell to the right of it

            // Get the column and row of the Department cell convert to int add 2 then convert back to string
            $nextColumn = chr(ord($cell->getColumn()) + 2);
            $nextRow = $cell->getRow();
            $sheet->setCellValue($nextColumn.$nextRow, ''.$request_row['unit_dept_college']);
        }
    }
}

// Remove borders for the empty cells between the last item row and the shifted cells
for ($r = $endRow - $shiftDownRows + 1; $r <= $endRow; $r++) {
    for ($c = 'A'; $c <= 'L'; $c++) {
        $sheet->getStyle($c . $r)->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_NONE,
                ],
            ],
        ]);
    }
}






// Save the modified Excel file
$outputFile = 'output.xlsx';
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($outputFile);

echo "Excel file generated successfully!";
?>

<!-- Display/DownloadPrompt the Excel file -->
echo '<a href="output.xlsx" download>Download Excel File</a>';

  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


  
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <script type="text/javascript" src="js/jquery.signature.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/jquery.signature.css"></link>

  <!--Signature Styles-->
  <style>
    .kbw-signature { width: 800px; height: 200px; }
    #sig canvas { width: 100% !important; height: auto; }
  </style>

    <?php include('message.php'); ?>
    <h1 class="text-3xl font-bold mt-8 mb-4 justify-center items-center">XAVIER UNIVERSITY CENTRAL PURCHASING UNIT<a href="purchase_request-view.php" class="btn btn-danger float-end">BACK</a></h1>
    <form action="code.php" method="post">
    <!-- Hidden user_name, user_id, user_email -->
    <input type="hidden" name="user_name" value="<?php echo $_SESSION['auth_user']['user_name']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id']; ?>">
    <input type="hidden" name="user_email" value="<?php echo $_SESSION['auth_user']['user_email']; ?>">

    <!-- Start of Row1 -->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
            <label for="procurment_request">Procurement Request #</label>
            <input type="text" name="procurment_request" class="form-control" required>
        </div>
    </div>

    <!-- Start of Row2 -->
    <div class="row">
        <div class="col-md-12">
            <!-- Table 1 -->
        <div class="col-md-12">
            <table class="table table-responsive table-bordered table-striped">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty/Unit</th>
                    <th>Description</th>
                    <th>Justification</th>
                    <th>Remove</th>
                </tr>
            </thead>

            <tbody id="itemRows">
                <!-- Dynamically generated rows will be added here -->  
            </tbody>
            
            </table>
        </div>
        <!-- Add Item Button -->
        <button type="button" class="btn btn-info btn-add-item text-white">Add Item</button>
        </div>
    </div>

    <!-- Start of Row3 -->
    <div class="row">
        <!-- Table 2 -->
        <div class="col-md-6">Requestor Information
            <table class="table table-responsive table-bordered table-striped">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Requested by:</th>
                    <th>Approved by</th>
                    <th>Cluster VP (if above P50,000)</th>
                </tr>
            </thead>

            <tbody>
                <!-- Table Body rows -->
                <tr>
                    <td>
                        <input type="text" name="unit_dept" class="form-control" required placeholder="Department">
                    </td>
                    <td>
                        <input type="text" name="requested_by" class="form-control" required placeholder="Requestor Name">
                    </td>
                    <td>
                        <input type="text" name="approved_by" class="form-control" required placeholder="Department Head">
                    </td>
                    <td>
                        <input type="text" name="cluster_vp" class="form-control" required placeholder="Cluster VP">
                    </td>
                </tr>
            </tbody>
            
            </table>
        </div>
        <!-- Table 3 -->
        <div class="col-md-6">For Finance Office Use
            <table class="table table-responsive table-bordered table-striped">
            <thead>
                <tr>
                    <th>Acct.Code</th>
                    <th>Budget Controller</th>
                    <th>University Treasurer</th>
                </tr>
            </thead>

            <tbody>
                <!-- Table Body Rows -->  
                <tr>
                    <td>
                        <input type="text" name="acct_code" class="form-control" required placeholder="Account Code">
                    </td>
                    <td>
                        <input type="text" name="budget_controller" class="form-control" required placeholder="Marilyn M Castanares">
                    </td>
                    <td>
                        <input type="text" name="university_treasurer" class="form-control" required placeholder="Lennie K Ong">
                    </td>
                </tr>
            </tbody>
            
            </table>
        </div>
        
    </div>
    </div>


  <!-- Bootstrap JavaScript -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const itemRows = document.getElementById('itemRows');
      const addItemButton = document.querySelector('.btn-add-item');
      let itemNumber = 1; // Initialize item number

      addItemButton.addEventListener('click', function () {
        const itemRow = document.createElement('tr');
        itemRow.classList.add('item-row', 'mb-2');
        itemRow.innerHTML = `
            <td style="width:60px">
                <!-- ITEM# -->
                <input type="text" name="item_number[]" class="form-control" style="width:60px" value="${itemNumber}">
            </td>
            <td>
                <!-- QTY/UNIT -->
                <input type="text" name="item_qty[]" class="form-control" style="width:60px" required>
            </td>
            <td>
                <!-- DESCRIPTION -->
                <textarea name="item_description[]" class="form-control" style="width:500px" required></textarea>
            </td>
            <td>
                <!-- JUSTIFICATION -->
                <textarea type="text" name="item_justification[]" class="form-control" style="width:500px" required> </textarea>
            </td>
            <td>
                <!-- Remove Button -->
                <button type="button" class="btn btn-danger btn-remove-item" style="width:80px" required>Remove</button>
            </td>
        `;
        itemRows.appendChild(itemRow);
        itemNumber++; // Increment item number
    });


      itemRows.addEventListener('click', function (event) {
        if (event.target.classList.contains('btn-remove-item')) {
          event.target.closest('.item-row').remove();
          itemNumber--; // Decrement item number
        }
      });

      // Signatures Script (Add var sig per Approval person and button id per approval person)
      var sig1 = $('#sig1').signature({syncField: '#signature64_1', syncFormat:'PNG'});
      $('#clear1').click(function(e){
        e.preventDefault();
        sig1.signature('clear');
        $("#signature64_1").val('');
      });

      var sig2 = $('#sig2').signature({syncField: '#signature64_2', syncFormat:'PNG'});
      $('#clear2').click(function(e){
        e.preventDefault();
        sig2.signature('clear');
        $("#signature64_2").val('');
      });

    var sig3 = $('#sig3').signature({syncField: '#signature64_3', syncFormat:'PNG'});
    $('#clear3').click(function(e){
        e.preventDefault();
        sig3.signature('clear');
        $("#signature64_3").val('');
        });

    var sig4 = $('#sig4').signature({syncField: '#signature64_4', syncFormat:'PNG'});
    $('#clear4').click(function(e){
        e.preventDefault();
        sig4.signature('clear');
        $("#signature64_4").val('');
        });

    var sig5 = $('#sig5').signature({syncField: '#signature64_5', syncFormat:'PNG'});
    $('#clear5').click(function(e){
        e.preventDefault();
        sig5.signature('clear');
        $("#signature64_5").val('');
        });

    var sigRequestor = $('#sigRequestor').signature({syncField: '#signature64_Requestor', syncFormat:'PNG'});
    $('#clearRequestor').click(function(e){
        e.preventDefault();
        sigRequestor.signature('clear');
        $("#signature64_Requestor").val('');
        });

      // Add more signature scripts as needed

    });
  </script>

<?php
include('includes/footer.php');

?>


