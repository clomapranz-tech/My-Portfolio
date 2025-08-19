<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xavier University Procurement Request Form</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <!--Include the jQuery and jQuery UI libraries so that Signatures work-->
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
  <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <script type="text/javascript" src="assets/js/jquery.signature.min.js"></script>
  <link rel="stylesheet" type="text/css" href="assets/css/jquery.signature.css"></link>

  <!--Signature Styles-->
  <style>
    .kbw-signature { width: 800px; height: 200px; }
    #sig canvas { width: 100% !important; height: auto; }
  </style>

</head>
<body class="bg-gray-100">
  <div class="container mx-auto p-6">
    <?php include('message.php'); ?>
    <h1 class="text-3xl font-bold mt-8 mb-4">XAVIER UNIVERSITY CENTRAL PURCHASING UNIT</h1>
    <form action="allcode.php" method="post">
      <!-- Hidden user_name, user_id, user_email -->
    <input type="hidden" name="user_name" value="<?php echo $_SESSION['auth_user']['user_name']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['auth_user']['user_id']; ?>">
    <input type="hidden" name="user_email" value="<?php echo $_SESSION['auth_user']['user_email']; ?>">

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Purchase Request</legend>
        <div class="mb-3">
          <label for="purchase_request_number" class="form-label">PURCHASE REQUEST#:</label>
          <input type="text" id="purchase_request_number" name="purchase_request_number" class="form-control" required>
        </div>
        
        <div class="mb-3">
          <label class="form-label">REQUEST TO PURCHASE:</label>
          <div class="form-check">
            <input type="checkbox" id="capex" name="purchase_type[]" value="CAPEX" class="form-check-input">
            <label for="capex" class="form-check-label">CAPEX</label>
          </div>
          <div class="form-check">
            <input type="checkbox" id="ict_items" name="purchase_type[]" value="ICT_ITEMS" class="form-check-input">
            <label for="ict_items" class="form-check-label">ICT ITEMS</label>
          </div>
          <div class="form-check">
            <input type="checkbox" id="consumables" name="purchase_type[]" value="CONSUMABLES" class="form-check-input">
            <label for="consumables" class="form-check-label">CONSUMABLES</label>
          </div>
          <div class="form-check">
            <input type="checkbox" id="services" name="purchase_type[]" value="SERVICES" class="form-check-input">
            <label for="services" class="form-check-label">SERVICES</label>
          </div>
          <!-- Add other checkboxes for purchase types -->
        </div>
      </fieldset>

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Items</legend>
        <p class="mb-2">Please include complete specifications/details or attach additional information on items. Use the back of this sheet if needed.</p>
        <p class="mb-2">CPU will refuse to receive request without complete specifications or details.</p>
        <div class="mb-3" id="itemRows">
          <!-- Dynamic item rows will be added here -->
        </div>
        <button type="button" class="btn btn-primary btn-add-item">Add Item</button>
      </fieldset>


      <!-- Requesting Person Information -->
      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Requesting Person Information</legend>
        <p class="italic m-3">Requesting person will be contacted if more information is needed</p>
        <div class="mb-3">
          <label for="printed_name" class="form-label">Requested by:</label>
          <input type="text" id="printed_name" name="printed_name" class="form-control" required placeholder="Printed Name">
        </div>
        
        <!-- Signature Requestor-->
        <div class="mb-3">
          <label for="signatureRequestor">Signature:</label>
          <div id="sigRequestor" class="kbw-signature"></div>
          <button id="clearRequestor" class="btn btn-primary">Clear Signature</button>
          <textarea id="signature64_Requestor" name="signed_Requestor" style="display:none"></textarea>
        </div>

        <div class="mb-3">
          <label for="unit_dept_college" class="form-label">Department/College:</label>
          <input type="text" id="unit_dept_college" name="unit_dept_college" class="form-control">
        </div>
        <div class="mb-3">
          <label for="iptel_email" class="form-label">E-mail Address:</label>
          <input type="text" id="iptel_email" name="iptel_email" class="form-control">
        </div>
      </fieldset>

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Remarks by College Dean/Principal</legend>
        <div class="mb-3">
          <textarea id="remarks_dean" name="remarks_dean" class="form-control" rows="4"></textarea>
        </div>
      </fieldset>

      <fieldset class="mb-4 bg-white shadow-md rounded p-4">
        <legend class="font-bold">Endorsed by: College Dean/Principal</legend>
        <div class="mb-3">
          <input type="text" id="endorsed_by_dean" name="endorsed_by_dean" class="form-control">
        </div>
      </fieldset>

      

      <button type="submit" name="request_add_btn_front" class="btn btn-primary">Submit Request</button>
    </form>
  </div>

  <!-- Bootstrap JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const itemRows = document.getElementById('itemRows');
      const addItemButton = document.querySelector('.btn-add-item');

      addItemButton.addEventListener('click', function () {
        const itemRow = document.createElement('div');
        itemRow.classList.add('item-row', 'mb-2');
        itemRow.innerHTML = `
        <label class="form-label">Item:</label>
        <div class="row bg-gray-100">
            <div class="col-md-3">
                <label for="item_qty">Qty/Unit:</label>
                <input type="text" name="item_qty[]" id="item_qty" class="form-control" placeholder="Qty/Unit">
            </div>
            <div class="col-md-3">
                <label for="item_type">Item:</label>
                <textarea name="item_type[]" id="item_type" class="form-control" placeholder="ITEMS -Please include complete specifications/details -CPU will refuse to receive request without complete specifications or details"></textarea>
            </div>
            <div class="col-md-5">
                <label for="item_type">Justification:</label>
                <div class="form-check">
                    <input type="checkbox" id="additional" name="item_justification[]" value="additional" class="form-check-input">
                    <label for="additional" class="form-check-label">Additional</label>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" id="replacement" name="item_justification[]" value="replacement" class="form-check-input">
                    <label for="replacement" class="form-check-label">Replacement</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="new" name="item_justification[]" value="new" class="form-check-input">
                    <label for="new" class="form-check-label">New</label>
                </div>
                <div>
                    <label for="item_reason">Reason for request:</label>
                    <input type="text" name="item_reason[]" id="item_reason" class="form-control mt-1" placeholder="Pls specify needs & reasons" required>
                </div>
                <div>
                    <label for="item_date_condition">Date Purchased & Condition (if replacement):</label>
                    <input type="text" name="item_date_condition[]" id="item_date_condition" class="form-control mt-1" placeholder="Indicate date purchased & condition if replacement">
                </div>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-danger btn-remove-item">Remove</button>
            </div>
        </div>
        `;
        itemRows.appendChild(itemRow);
      });

      itemRows.addEventListener('click', function (event) {
        if (event.target.classList.contains('btn-remove-item')) {
          event.target.closest('.item-row').remove();
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

</body>
</html>
