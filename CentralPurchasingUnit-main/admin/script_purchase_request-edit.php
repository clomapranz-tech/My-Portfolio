<!-- DYNAMIC UPDATE JS STATUS -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script>
    document.getElementById('status-select').addEventListener('change', function() {
        var status = this.value;
        var requestId = <?php echo $request_row['id']; ?>; // Assuming you have the request ID available

        // Make an AJAX request to update the status
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'javscript-update_status.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle response if needed
                console.log(xhr.responseText);
            }
        };
        xhr.send('status=' + encodeURIComponent(status) + '&request_id=' + encodeURIComponent(requestId));
    });
</script>
<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const itemRows = document.getElementById('itemRows');
        const addItemButton = document.querySelector('.btn-add-item');
        <?php
        // Get Biggest item number by Quering the items table with the largest item number
        $query = "SELECT * FROM items WHERE purchase_request_id = '$request_id'";
        $query_run = mysqli_query($con, $query);
        if (mysqli_num_rows($query_run) > 0) {
            // Get the largest item number
            $item_number = mysqli_num_rows($query_run) + 1;
        } else {
            $item_number = 1;
        }
        ?>
        itemNumber = <?php echo $item_number; ?>;
        addItemButton.addEventListener('click', function() {
            const itemRow = document.createElement('tr');
            itemRow.classList.add('item-row', 'mb-2');
            itemRow.innerHTML = `
            <td style="width:60px">
                <!-- ITEM# -->
                <input type="text" name="item_number[]" class="form-control"  value="${itemNumber}" disabled>
            </td>
            <td>
                <!-- QTY/UNIT -->
                <input type="text" name="item_qty[]" class="form-control"  required>
            </td>
            <td>
                <!-- DESCRIPTION -->
                <textarea name="item_description[]" class="form-control" required></textarea>
            </td>
            <td>
                <!-- JUSTIFICATION -->
                <textarea type="text" name="item_justification[]" class="form-control" required> </textarea>
            </td>
            <td>
                <!-- ITEM STATUS -->
                <select name="item_status[]" class="form-control"  required> 
                      <option value='pending'>pending</option>;
                </select>

            </td>
            <td>
                <!-- Remove Button -->
                <button type="button" class="btn btn-danger btn-remove-item" style="width:80px" required>Remove</button>
            </td>
        `;
            itemRows.appendChild(itemRow);
            itemNumber++; // Increment item number
        });


        itemRows.addEventListener('click', function(event) {
            if (event.target.classList.contains('btn-remove-item')) {
                event.target.closest('.item-row').remove();
                itemNumber--; // Decrement item number
            }
        });

        // Signatures Script (Add var sig per Approval person and button id per approval person)
        var sigrequest = $('#sigrequest').signature({
            syncField: '#signature64',
            syncFormat: 'PNG'
        });
        $('#clearreq').click(function(e) {
            e.preventDefault();
            sigrequest.signature('clear');
            $("#signature64").val('');
        });
        var sig1 = $('#sig1').signature({
            syncField: '#signature64_1',
            syncFormat: 'PNG'
        });
        $('#clear1').click(function(e) {
            e.preventDefault();
            sig1.signature('clear');
            $("#signature64_1").val('');
        });

        var sig2 = $('#sig2').signature({
            syncField: '#signature64_2',
            syncFormat: 'PNG'
        });
        $('#clear2').click(function(e) {
            e.preventDefault();
            sig2.signature('clear');
            $("#signature64_2").val('');
        });

        var sig3 = $('#sig3').signature({
            syncField: '#signature64_3',
            syncFormat: 'PNG'
        });
        $('#clear3').click(function(e) {
            e.preventDefault();
            sig3.signature('clear');
            $("#signature64_3").val('');
        });

        var sig4 = $('#sig4').signature({
            syncField: '#signature64_4',
            syncFormat: 'PNG'
        });
        $('#clear4').click(function(e) {
            e.preventDefault();
            sig4.signature('clear');
            $("#signature64_4").val('');
        });

        var sig5 = $('#sig5').signature({
            syncField: '#signature64_5',
            syncFormat: 'PNG'
        });
        $('#clear5').click(function(e) {
            e.preventDefault();
            sig5.signature('clear');
            $("#signature64_5").val('');
        });

        var sigRequestor = $('#sigRequestor').signature({
            syncField: '#signature64_Requestor',
            syncFormat: 'PNG'
        });
        $('#clearRequestor').click(function(e) {
            e.preventDefault();
            sigRequestor.signature('clear');
            $("#signature64_Requestor").val('');
        });

        // Add more signature scripts as needed

    });
</script>
<!-- Delete Signature Script -->
<script>
    $(document).ready(function() {
        $(".delete-signature-btn").click(function() {
            var signatureField = $(this).data("signature-field");
            // Ask for confirmation before deleting
            if (confirm("Are you sure you want to delete this signature?")) {
                // AJAX call to delete the signature from the database
                $.ajax({
                    url: "delete_signature.php",
                    type: "POST",
                    data: {
                        signatureField: signatureField,
                        request_id: <?= $request_id ?>
                    },
                    success: function(response) {
                        // Handle success
                        if (response === "success") {
                            // Remove signature from DOM
                            $("input[name='" + signatureField + "']").val("");
                            $("img[src='../uploads/signatures/" + signatureField + "']").remove();
                            $(".delete-signature-btn[data-signature-field='" + signatureField + "']").remove();
                            alert("Signature deleted successfully.");
                            // Reload the page
                            window.location.reload();
                        } else {
                            alert("Error deleting signature: " + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });
</script>
<!-- REMOVE DATA -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tableBody = document.getElementById("itemRows");

        // Load removed items from localStorage
        let removedItems = JSON.parse(localStorage.getItem("removedItems")) || [];

        function removeRow(button) {
            let row = button.closest("tr"); // Get the closest <tr>
            let itemId = row.dataset.id; // Get the unique data-id

            // Capture item details
            let removedItem = {
                id: itemId,
                item_number: row.querySelector("[name='item_number[]']").value,
                item_qty: row.querySelector("[name='item_qty[]']").value,
                item_description: row.querySelector("[name='item_description[]']").value,
                item_justification: row.querySelector("[name='item_justification[]']").value,
                item_status: row.querySelector("[name='item_status[]']").value,
            };

            // Ensure the item ID is valid and not already in the list
            if (itemId !== "new") {
                // Filter out any existing item with the same ID
                removedItems = removedItems.filter(item => item.id !== itemId);

                // Add the new item (ensuring it's not duplicated)
                removedItems.push(removedItem);

                // Save updated list to localStorage
                localStorage.setItem("removedItems", JSON.stringify(removedItems));
            }

            // Remove the row from the table
            row.remove();
        }

        // Attach event listener to remove buttons
        tableBody.addEventListener("click", function(event) {
            if (event.target.classList.contains("btn-remove-item")) {
                removeRow(event.target);
            }
        });

        // Debugging: View removed items in console
        console.log("Removed Items:", removedItems);
    });
</script>


<!-- ajax remove data -->
<script>
    $(document).ready(function() {
        $(".remove_purchased_item").click(function() {
            var button = $(this); // Store the button reference
            var id = button.data("id");

            if (confirm("Are you sure you want to delete this item?")) {
                $.ajax({
                    url: "remove_purchase_request.php",
                    type: "POST",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        console.log("Raw Server Response:", response);

                        // Ensure response is an object
                        if (typeof response === "string") {
                            try {
                                response = JSON.parse(response); // Parse manually if needed
                            } catch (e) {
                                console.error("JSON Parse Error:", e);
                            }
                        }

                        console.log("Parsed Response:", response); // Check actual structure

                        if (response.status && response.status.trim() === "success") {
                            button.closest("tr").remove(); // Remove row on success
                        } else {
                            alert("Failed to remove item: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        console.log("Response Text:", xhr.responseText);
                    }
                });
            }
        });
    });
</script>

<?php
include('includes/footer.php');
?>