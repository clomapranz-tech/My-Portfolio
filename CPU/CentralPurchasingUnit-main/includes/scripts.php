<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script> <!-- Move this line up -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" /> <!-- Move this line up -->

<script src="js/scripts.js"></script>
<script src="js/datatables-simple-demo.js"></script>

<script>
            $(document).ready( function () {
            $('#myCategory').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myCollege').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myProject').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myDepartment').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myFaculty').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myPartner').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myPost').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myUsers').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myInventory').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myRequests').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myProject').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#mySchoolyear').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myStudent').DataTable({
                "order": [[ 0, "desc" ]]
            });
            $('#myPurchaseRequests').DataTable({
                "order": [[ 0, "desc" ]]
            });

            } );
        </script>

<!-- Summernote JS - CDN Link -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        //$("#summernote").summernote();
        $('#summernote').summernote({
            placeholder: 'Type your Description',
            tabsize: 2,
            height: 200
        });
        $('.dropdown-toggle').dropdown();
    });
</script>

<!-- CSS For Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- JavaScript for Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    // Initialize Select2 for dropdowns
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<!-- JavaScript for Delete Button Confirmation (Buttons Should have id of deleteButton) -->
<script>
    // Select all elements with the class 'deleteButton'
    var deleteButtons = document.querySelectorAll(".deleteButton");
    
    // Iterate over each delete button and attach event listener
    deleteButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            if (confirm("Are you sure you want to delete this document?")) {
                // Find the closest form and submit it
                this.closest(".deleteForm").submit();
            } else {
                event.preventDefault(); // Prevent form submission
            }
        });
    });
</script>

<!-- JavaScript for Filter Buttons -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const tableRows = document.querySelectorAll('#myRequests tbody tr');

        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const status = this.getAttribute('data-status');

                tableRows.forEach(function (row) {
                    // If the status is 'all' or the row status matches the button status
                    if (status === 'all' || row.cells[5].textContent.trim() === status || (status === 'not approved' && row.cells[5].textContent.trim() !== 'Approved') ){
                        row.style.display = '';
                    }
                    
                    else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });
</script>




</body>
</html>
