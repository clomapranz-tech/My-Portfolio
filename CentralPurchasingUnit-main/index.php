<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');
include('config/dbcon.php');
include('authentication.php');

$results_per_page = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

$user_email = $_SESSION['auth_user']['user_email'];
$query = "SELECT * FROM purchase_requests WHERE requestor_user_email = '$user_email' ORDER BY requested_date DESC LIMIT $results_per_page OFFSET $offset";
$query_run = mysqli_query($con, $query);
?>

<?php if (isset($_SESSION['message'])): ?>
    <div style="margin-left: 10rem; margin-right: 10rem;">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong style="font-size: 20px;"> <?= $_SESSION['message'] ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php unset($_SESSION['message']);
endif; ?>

<hr style="width:80%; margin:auto; color:#00478a; background:#429ef5; height: 3px;">

<div class="container p-5">
    <div class="grid grid-cols-3 gap-10">
        <?php
        if (mysqli_num_rows($query_run) > 0) {
            foreach ($query_run as $item) {
                $received_date = strtotime($item['requested_date']);
                $current_date = strtotime(date('Y-m-d'));
                $difference = round(($current_date - $received_date) / (60 * 60 * 24));

                $card_class = '';
                $style_custom = '';
                $text_color = 'black';

                $attach_query = "SELECT * FROM purchase_requests_attachments WHERE purchase_request_id = '" . $item['id'] . "'";
                $attach_result = mysqli_query($con, $attach_query);
                $attachments = [];
                while ($row = mysqli_fetch_assoc($attach_result)) {
                    $attachments[] = $row;
                }

                if ($difference >= 30 && ($item['status'] != 'approved' && $item['status'] != 'completed')) {
                    $style_custom = 'background-color: rgba(255, 193, 7, 0.4); border: 2px solid #FFC107;';
                    $text_color = 'text-black';
                } elseif ($difference >= 15 && ($item['status'] != 'approved' && $item['status'] != 'completed')) {
                    $style_custom = 'background-color: rgba(238, 210, 2, 0.4); border: 2px solid #EED202;';
                    $text_color = 'text-black';
                } elseif ($item['status'] == 'pending' || $item['status'] == 'partially-completed') {
                    $style_custom = 'background-color: rgba(238, 210, 2, 0.4); border: 2px solid #EED202;';
                    $text_color = 'text-black';
                } elseif ($item['status'] === 'rejected') {
                    $style_custom = 'background-color: red; border: 2px solid red;';
                    $text_color = 'text-black';
                } elseif ($item['status'] == 'approved' || $item['status'] == 'completed') {
                    $card_class = 'bg-green-500';
                    $text_color = 'text-white';
                }

                $encodedItem = htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8');
                $encodedAttachments = htmlspecialchars(json_encode($attachments), ENT_QUOTES, 'UTF-8');
        ?>
                <div
                    style="<?= $style_custom ?>"
                    class="max-w-sm rounded overflow-hidden shadow-lg hover:scale-105 hover:outline-dotted hover:text-blue-600 <?= $card_class ?>"
                    data-bs-toggle="modal"
                    data-bs-target="#requestDetailsModal"
                    onclick='showRequestDetails(<?= $encodedItem ?>, <?= $encodedAttachments ?>)'>
                    <div class="px-6 py-4 <?= $text_color ?>">
                        <div class="font-bold text-xl mb-2"><?= $item['printed_name']; ?></div>
                        <p class="text-current text-base mb-2">Department: <?= $item['unit_dept_college']; ?></p>
                        <p class="text-current text-base mb-2">iptel#/email: <?= $item['iptel_email']; ?></p>
                        <p class="text-current text-base mb-2">Acknowledged by CPU: <?= $item['acknowledged_by_cpu'] == '1' ? 'Acknowledge' : 'N/A'; ?></p>
                        <p class="text-current text-base mb-2">Requested Date: <?= date('F j Y h:i A', strtotime($item['requested_date'])); ?></p>
                        <p class="text-current text-base mb-2">Status: <?= $item['status']; ?></p>
                        <p class="text-current text-base mb-2" style="color: red; font-weight: 700;">
                            <?php if ($difference >= 30 && $item['status'] != 'approved' && $item['status'] != 'completed'): ?>
                                This request is older than 30 days
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>

    <br><br><br><br>

    <div class="flex justify-center mt-4">
        <?php
        $pagination_url = "index.php?page=";

        $count_query = "SELECT COUNT(*) AS total FROM purchase_requests WHERE requestor_user_email = '$user_email'";
        $result = mysqli_query($con, $count_query);
        $row = mysqli_fetch_assoc($result);
        $total_pages = ceil($row['total'] / $results_per_page);

        $visible_pages = 5;
        $start_page = max(1, $page - floor($visible_pages / 2));
        $end_page = min($total_pages, $start_page + $visible_pages - 1);

        if ($start_page > 1) {
            echo "<a href='{$pagination_url}1' class='m-2 px-4 py-2 bg-xu-blue text-blue-50 rounded-full hover:bg-blue-400'>1</a>";
            if ($start_page > 2) echo "<span class='mx-2'>...</span>";
        }

        for ($i = $start_page; $i <= $end_page; $i++) {
            $active_class = ($i == $page) ? 'bg-primary' : 'bg-xu-blue hover:bg-blue-400';
            echo "<a href='{$pagination_url}{$i}' class='m-2 px-4 py-2 $active_class text-blue-50 rounded-full'>$i</a>";
        }

        if ($end_page < $total_pages) {
            if ($end_page < $total_pages - 1) echo "<span class='mx-2'>...</span>";
            echo "<a href='{$pagination_url}{$total_pages}' class='m-2 px-4 py-2 bg-xu-blue text-blue-50 rounded-full hover:bg-blue-400'>$total_pages</a>";
        }
        ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-labelledby="requestDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestDetailsModalLabel">Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body" id="modalRequestBody"></div>
            <div class="modal-body" id="modalRequestattachmentBody"></div>
            <div class="modal-footer">
                <a href="#" id="viewMoreButton" class="btn btn-primary" style="float: right;">View More Details</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showRequestDetails(item, attachments) {
        const html = `
        <table class="table table-bordered">
            <thead class="table-light"><tr><th>Field</th><th>Details</th></tr></thead>
            <tbody>
                <tr><td>Name</td><td>${item.printed_name}</td></tr>
                <tr><td>Department</td><td>${item.unit_dept_college}</td></tr>
                <tr><td>IPTel/Email</td><td>${item.iptel_email}</td></tr>
                <tr><td>Acknowledged by CPU</td><td>${item.acknowledged_by_cpu == '1' ? 'Acknowledged' : 'N/A'}</td></tr>
                <tr><td>Requested Date</td><td>${new Date(item.requested_date).toLocaleString()}</td></tr>
                <tr><td>Signature:</td>
                    <td>
                        ${item.signed_request 
                            ? `<div class="mb-3 kbw-signature">
                                <label for="signed_Requestor">Signature:</label>
                                <img src="uploads/signatures/${item.signed_request}" alt="signature" class="img-fluid">
                               </div>`
                            : ``
                        }
                    </td>
                </tr>
                <tr><td>Status</td><td>${item.status}</td></tr>
            </tbody>
        </table>
        `;

        let attachmentHtml = '';
        if (attachments.length > 0) {
            attachmentHtml = `<table class="table table-bordered">
            <thead class="table-light">
                <tr><th>Image</th><th>File Name</th><th>Type</th><th>Size</th></tr>
            </thead>
            <tbody>`;
            attachments.forEach(att => {
                attachmentHtml += `
                <tr>
                    <td class="text-center"><img src="${att.file_path}" alt="${att.file_name}" class="img-fluid d-block mx-auto" style="max-width:100px;"></td>
                    <td>${att.file_name}</td>
                    <td>${att.file_type}</td>
                    <td>${att.file_size}</td>
                </tr>`;
            });
            attachmentHtml += '</tbody></table>';
        } else {
            attachmentHtml = '<p>No attachments available.</p>';
        }

        document.getElementById('modalRequestBody').innerHTML = html;
        document.getElementById('modalRequestattachmentBody').innerHTML = attachmentHtml;
         // Update the "View More" button link
         const viewMoreButton = document.getElementById('viewMoreButton');
        viewMoreButton.href = `purchased_request.php?id=${item.id}`;
    }
</script>

<?php include('includes/footer.php'); ?>