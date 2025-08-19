<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('authentication.php');

//Initialize Variable
$admin = null;
$super_user = null;
$department_editor = null;
//Check level
if ($_SESSION['auth_role'] == 1) {
    $admin = true;
    $super_user = false;
    $department_editor = false;
} elseif ($_SESSION['auth_role'] == 2) {
    $admin = false;
    $super_user = true;
    $department_editor = false;
} elseif ($_SESSION['auth_role'] == 3) {
    $admin = false;
    $super_user = false;
    $department_editor = true;
}

//PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

//Google Calendar API
use Google\Client;
use Google\Service\Calendar as Google_Service_Calendar;
use Google\Service\Calendar\Event as Google_Service_Calendar_Event;

$credentials = __DIR__ . '/vendor/credentials.json';
require_once __DIR__ . '/vendor/autoload.php';

//Update Purchase Request
if (isset($_POST['request_update_btn_front'])) {
    // Purchase Request Information
    $id = $_POST['request_id'] ?? '';
    $fields_to_update = [];

    // Dynamically add fields to update if they are not empty
    if (!empty($_POST['purchase_request_number'])) {
        $fields_to_update[] = "purchase_request_number = '" . $con->real_escape_string($_POST['purchase_request_number']) . "'";
    }
    if (!empty($_POST['printed_name'])) {
        $fields_to_update[] = "printed_name = '" . $con->real_escape_string($_POST['printed_name']) . "'";
    }
    if (!empty($_POST['unit_dept_college'])) {
        $fields_to_update[] = "unit_dept_college = '" . $con->real_escape_string($_POST['unit_dept_college']) . "'";
    }
    if (!empty($_POST['iptel_email'])) {
        $fields_to_update[] = "iptel_email = '" . $con->real_escape_string($_POST['iptel_email']) . "'";
    }
    if (!empty($_POST['signed_Requestor'])) {
        $signature_field = 'signed_Requestor';
        $folderPath = "../uploads/signatures/";
        // Check if the value is already a filename (e.g., 4123123.jpg)
        if (strpos($_POST[$signature_field], 'data:image/') === 0) {
            // Process the signature
            $image_parts = explode(";base64,", $_POST[$signature_field]);
            if (count($image_parts) === 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1]; // Extract the file extension (e.g., png)
                $image_base64 = base64_decode($image_parts[1]); // Decode the base64 string
                $filename = uniqid() . ".$image_type"; // Generate a unique filename
                $file = $folderPath . $filename;

                // Save the signature to the server
                if (file_put_contents($file, $image_base64) !== false) {
                    // Save only the filename in the database
                    $fields_to_update[] = "$signature_field = '" . $con->real_escape_string($filename) . "'";
                } else {
                    $_SESSION['message'] = "Error saving signature image.";
                    header('location: purchase_request-view.php');
                    exit;
                }
            } else {
                $_SESSION['message'] = "Invalid signature in signed_Requestor.";
                header('location: purchase_request-view.php');
                exit;
            }
        } else {
            // Skip updating if the value is already a filename
            error_log("Skipping update for $signature_field as it is already a filename.");
        }
        // Process the signature

    }
    if (!empty($_POST['signed_1'])) {
        $signature_field = 'signed_1';
        $folderPath = "../uploads/signatures/";

        // Check if the value is already a filename (e.g., 4123123.jpg)
        if (strpos($_POST[$signature_field], 'data:image/') === 0) {
            // Process the signature
            $image_parts = explode(";base64,", $_POST[$signature_field]);
            if (count($image_parts) === 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1]; // Extract the file extension (e.g., png)
                $image_base64 = base64_decode($image_parts[1]); // Decode the base64 string
                $filename = uniqid() . ".$image_type"; // Generate a unique filename
                $file = $folderPath . $filename;

                // Save the signature to the server
                if (file_put_contents($file, $image_base64) !== false) {
                    // Save only the filename in the database
                    $fields_to_update[] = "$signature_field = '" . $con->real_escape_string($filename) . "'";
                } else {
                    $_SESSION['message'] = "Error saving signature image.";
                    header('location: purchase_request-view.php');
                    exit;
                }
            } else {
                $_SESSION['message'] = "Invalid signature signed_1.";
                header('location: purchase_request-view.php');
                exit;
            }
        } else {
            // Skip updating if the value is already a filename
            error_log("Skipping update for $signature_field as it is already a filename.");
        }

        // Process the signature

    }
    if (!empty($_POST['signed_2'])) {
        $signature_field = 'signed_2';
        $folderPath = "../uploads/signatures/";
        // Check if the value is already a filename (e.g., 4123123.jpg)
        if (strpos($_POST[$signature_field], 'data:image/') === 0) {
            // Process the signature
            // Process the signature
            $image_parts = explode(";base64,", $_POST[$signature_field]);
            if (count($image_parts) === 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1]; // Extract the file extension (e.g., png)
                $image_base64 = base64_decode($image_parts[1]); // Decode the base64 string
                $filename = uniqid() . ".$image_type"; // Generate a unique filename
                $file = $folderPath . $filename;

                // Save the signature to the server
                if (file_put_contents($file, $image_base64) !== false) {
                    // Save only the filename in the database
                    $fields_to_update[] = "$signature_field = '" . $con->real_escape_string($filename) . "'";
                } else {
                    $_SESSION['message'] = "Error saving signature image.";
                    header('location: purchase_request-view.php');
                    exit;
                }
            } else {
                $_SESSION['message'] = "Invalid signature signed_2.";
                header('location: purchase_request-view.php');
                exit;
            }
        } else {
            // Skip updating if the value is already a filename
            error_log("Skipping update for $signature_field as it is already a filename.");
        }
    }
    if (!empty($_POST['signed_3'])) {
        $signature_field = 'signed_3';
        $folderPath = "../uploads/signatures/";
        // Check if the value is already a filename (e.g., 4123123.jpg)
        if (strpos($_POST[$signature_field], 'data:image/') === 0) {
            // Process the signature
            // Process the signature
            $image_parts = explode(";base64,", $_POST[$signature_field]);
            if (count($image_parts) === 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1]; // Extract the file extension (e.g., png)
                $image_base64 = base64_decode($image_parts[1]); // Decode the base64 string
                $filename = uniqid() . ".$image_type"; // Generate a unique filename
                $file = $folderPath . $filename;

                // Save the signature to the server
                if (file_put_contents($file, $image_base64) !== false) {
                    // Save only the filename in the database
                    $fields_to_update[] = "$signature_field = '" . $con->real_escape_string($filename) . "'";
                } else {
                    $_SESSION['message'] = "Error saving signature image.";
                    header('location: purchase_request-view.php');
                    exit;
                }
            } else {
                $_SESSION['message'] = "Invalid signature signed_3.";
                header('location: purchase_request-view.php');
                exit;
            }
        } else {
            // Skip updating if the value is already a filename
            error_log("Skipping update for $signature_field as it is already a filename.");
        }
    }
    if (!empty($_POST['signed_4'])) {
        $signature_field = 'signed_4';
        $folderPath = "../uploads/signatures/";
        // Check if the value is already a filename (e.g., 4123123.jpg)
        if (strpos($_POST[$signature_field], 'data:image/') === 0) {
            // Process the signature
            $image_parts = explode(";base64,", $_POST[$signature_field]);
            if (count($image_parts) === 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1]; // Extract the file extension (e.g., png)
                $image_base64 = base64_decode($image_parts[1]); // Decode the base64 string
                $filename = uniqid() . ".$image_type"; // Generate a unique filename
                $file = $folderPath . $filename;

                // Save the signature to the server
                if (file_put_contents($file, $image_base64) !== false) {
                    // Save only the filename in the database
                    $fields_to_update[] = "$signature_field = '" . $con->real_escape_string($filename) . "'";
                } else {
                    $_SESSION['message'] = "Error saving signature image.";
                    header('location: purchase_request-view.php');
                    exit;
                }
            } else {
                $_SESSION['message'] = "Invalid signature signed_4.";
                header('location: purchase_request-view.php');
                exit;
            }
        } else {
            // Skip updating if the value is already a filename
            error_log("Skipping update for $signature_field as it is already a filename.");
        }
        // Process the signature

    }
    if (!empty($_POST['signed_5'])) {
        $signature_field = 'signed_5';
        $folderPath = "../uploads/signatures/";
        // Check if the value is already a filename (e.g., 4123123.jpg)
        if (strpos($_POST[$signature_field], 'data:image/') === 0) {
            // Process the signature
            // Process the signature
            $image_parts = explode(";base64,", $_POST[$signature_field]);
            if (count($image_parts) === 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1]; // Extract the file extension (e.g., png)
                $image_base64 = base64_decode($image_parts[1]); // Decode the base64 string
                $filename = uniqid() . ".$image_type"; // Generate a unique filename
                $file = $folderPath . $filename;

                // Save the signature to the server
                if (file_put_contents($file, $image_base64) !== false) {
                    // Save only the filename in the database
                    $fields_to_update[] = "$signature_field = '" . $con->real_escape_string($filename) . "'";
                } else {
                    $_SESSION['message'] = "Error saving signature image.";
                    header('location: purchase_request-view.php');
                    exit;
                }
            } else {
                $_SESSION['message'] = "Invalid signature signed_5.";
                header('location: purchase_request-view.php');
                exit;
            }
        } else {
            // Skip updating if the value is already a filename
            error_log("Skipping update for $signature_field as it is already a filename.");
        }
    }

    if (!empty($_POST['signed_request'])) {
        $signature_field = 'signed_request';
        $folderPath = "../uploads/signatures/";

        // Check if the value is already a filename (e.g., 4123123.jpg)
        if (strpos($_POST[$signature_field], 'data:image/') === 0) {
            // Process the signature
            $image_parts = explode(";base64,", $_POST[$signature_field]);
            if (count($image_parts) === 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1]; // Extract the file extension (e.g., png)
                $image_base64 = base64_decode($image_parts[1]); // Decode the base64 string
                $filename = uniqid() . ".$image_type"; // Generate a unique filename
                $file = $folderPath . $filename;

                // Save the signature to the server
                if (file_put_contents($file, $image_base64) !== false) {
                    // Save only the filename in the database
                    $fields_to_update[] = "$signature_field = '" . $con->real_escape_string($filename) . "'";
                } else {
                    $_SESSION['message'] = "Error saving signature image for CPU.";
                    header('location: purchase_request-view.php');
                    exit;
                }
            } else {
                $_SESSION['message'] = "Invalid signature format for signed_request.";
                header('location: purchase_request-view.php');
                exit;
            }
        } else {
            // Skip updating if the value is already a filename
            error_log("Skipping update for $signature_field as it is already a filename.");
        }
    }
    if (!empty($_POST['signed_by_cpu'])) {
        $signature_field = 'signed_by_cpu';
        $folderPath = "../uploads/signatures/";
        // Check if the value is already a filename (e.g., 4123123.jpg)
     
        if (strpos($_POST[$signature_field], 'data:image/') === 0) {
            // Process the signature
            $image_parts = explode(";base64,", $_POST[$signature_field]);

            if (count($image_parts) === 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1]; // Extract the file extension (e.g., png)
                $image_base64 = base64_decode($image_parts[1]); // Decode the base64 string
                $filename = uniqid() . ".$image_type"; // Generate a unique filename
                $file = $folderPath . $filename;

                // Save the signature to the server
                if (file_put_contents($file, $image_base64) !== false) {
                    // Save only the filename in the database
                    $fields_to_update[] = "$signature_field = '" . $con->real_escape_string($filename) . "'";
                } else {
                    $_SESSION['message'] = "Error saving signature image for CPU.";
                    header('location: purchase_request-view.php');
                    exit;
                }
            } else {
                $_SESSION['message'] = "Invalid signature format for CPU.";
                header('location: purchase_request-view.php');
                exit;
            }
        } else {
            // Skip updating if the value is already a filename
            error_log("Skipping update for $signature_field as it is already a filename.");
        }
        // Process the signature
        $image_parts = explode(";base64,", $_POST[$signature_field]);
    }
    if (isset($_POST['acknowledged_by_cpu'])) {
        $fields_to_update[] = "acknowledged_by_cpu = '1'";
    }
    if (isset($_POST['above_50000'])) {
        $fields_to_update[] = "above_50000 = '1'";
    }
    if (!empty($_POST['unit_head_approval_by'])) {
        $fields_to_update[] = "unit_head_approval_by = '" . $con->real_escape_string($_POST['unit_head_approval_by']) . "'";
    }
    if (!empty($_POST['unit_head_approval'])) {
        $fields_to_update[] = "unit_head_approval = '" . $con->real_escape_string($_POST['unit_head_approval']) . "'";
    }
    if (!empty($_POST['vice_president_remarks'])) {
        $fields_to_update[] = "vice_president_remarks = '" . $con->real_escape_string($_POST['vice_president_remarks']) . "'";
    }
    if (!empty($_POST['vice_president_approved'])) {
        $fields_to_update[] = "vice_president_approved = '" . $con->real_escape_string($_POST['vice_president_approved']) . "'";
    }
    if (!empty($_POST['vice_president_administration_remarks'])) {
        $fields_to_update[] = "vice_president_administration_remarks = '" . $con->real_escape_string($_POST['vice_president_administration_remarks']) . "'";
    }
    if (!empty($_POST['vice_president_administration_approved'])) {
        $fields_to_update[] = "vice_president_administration_approved = '" . $con->real_escape_string($_POST['vice_president_administration_approved']) . "'";
    }
    if (!empty($_POST['budget_controller_remarks'])) {
        $fields_to_update[] = "budget_controller_remarks = '" . $con->real_escape_string($_POST['budget_controller_remarks']) . "'";
    }
    if (!empty($_POST['budget_controller_approved'])) {
        $fields_to_update[] = "budget_controller_approved = '" . $con->real_escape_string($_POST['budget_controller_approved']) . "'";
    }
    if (!empty($_POST['budget_controller_code'])) {
        $fields_to_update[] = "budget_controller_code = '" . $con->real_escape_string($_POST['budget_controller_code']) . "'";
    }
    if (!empty($_POST['university_treasurer_remarks'])) {
        $fields_to_update[] = "university_treasurer_remarks = '" . $con->real_escape_string($_POST['university_treasurer_remarks']) . "'";
    }
    if (!empty($_POST['university_treasurer_approved'])) {
        $fields_to_update[] = "university_treasurer_approved = '" . $con->real_escape_string($_POST['university_treasurer_approved']) . "'";
    }
    if (!empty($_POST['office_of_the_president_remarks'])) {
        $fields_to_update[] = "office_of_the_president_remarks = '" . $con->real_escape_string($_POST['office_of_the_president_remarks']) . "'";
    }
    if (!empty($_POST['office_of_the_president_approved'])) {
        $fields_to_update[] = "office_of_the_president_approved = '" . $con->real_escape_string($_POST['office_of_the_president_approved']) . "'";
    }

    // Build the SQL query dynamically
    if (!empty($fields_to_update)) {
        // Log the fields to update for debugging
        error_log("Fields to update: " . print_r($fields_to_update, true));

        $sql_purchase_request = "UPDATE purchase_requests SET " . implode(", ", $fields_to_update) . " WHERE id = '$id'";

        // Execute the query
        if ($con->query($sql_purchase_request) === TRUE) {
            $_SESSION['message'] = "Successfully Updated Request.";
            header('location: purchase_request-view.php');
        } else {
            $_SESSION['message'] = "Error: " . $sql_purchase_request . "<br>" . $con->error;
            header('location: purchase_request-view.php');
        }
    } else {
        $_SESSION['message'] = "No fields to update.";
        header('location: purchase_request-view.php');
    }
}

//Delete Purchase Request
if (isset($_POST['purchase_request_delete_btn'])) {
    $request_id = $_POST['id'];
    // Your SQL query to delete data from the database
    $delete_query = "DELETE FROM purchase_requests WHERE id = '$request_id'";
    // Executing the query
    $query_run = mysqli_query($con, $delete_query);

    if ($query_run) {
        $_SESSION['message'] = "Request was deleted!";
        header('Location: purchase_request-view.php');
    } else {
        // If there was an error in executing the query
        $_SESSION['message'] = "Something went wrong";
        header('Location: purchase_request-view.php');
    }
}

//Hide Purchase request
if (isset($_POST['purchase_request_mark_complete'])) {
    $request_id = $_POST['request_id'];
    $user_id = $_POST['user_id'];
    // Your SQL query to insert into user_purchase_request_completion table in the database
    $hide_query = "INSERT INTO purchase_requests (user_id, purchase_request_id, completed) VALUES ('$request_id','$user_id','1')";
    // Executing the query
    $query_run = mysqli_query($con, $hide_query);

    if ($query_run) {
        $_SESSION['message'] = "Request was Hidden!";
        header('Location: purchase_request-view.php');
    } else {
        // If there was an error in executing the query
        $_SESSION['message'] = "Something went wrong";
        header('Location: purchase_request-view.php');
    }
}

//Approve Purchase Request
if (isset($_POST['request_approve_btn'])) {
    $request_id = $_POST['request_id'];
    $approval_remarks = $_POST['approval_remarks'];
    //Hidden User name
    $last_modified_by = $_POST['user_name'];
    //Specify change made Unique to each action
    $change_made = "Request Approved";
    $approved = 'approved';

    // Your SQL query to update data in the database
    $update_query = "UPDATE purchase_requests SET status = '$approved', approval_remarks = '$approval_remarks' WHERE id = '$request_id'";
    // Executing the query
    $query_run = mysqli_query($con, $update_query);

    //get the requestor email
    $sql = "SELECT requestor_user_email, purchase_request_number FROM purchase_requests WHERE id = '$request_id'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $requestor_email = $row['requestor_user_email'];
    $purchase_request_number = $row['purchase_request_number'];


    sendEmail($requestor_email, $approved, $request_id, $purchase_request_number, $approval_remarks);

    if ($query_run) {
        // If query executed successfully, Insert into purchase_requests_history
        $insert_query = "INSERT INTO purchase_requests_history (purchase_request_id, change_made, last_modified_by, datetime_occured) VALUES ('$request_id','$change_made', '$last_modified_by', NOW())";
        $insert_query_run = mysqli_query($con, $insert_query);
        $_SESSION['message'] = "Request was approved!";
        header('Location: purchase_request-view.php');
    } else {
        // If there was an error in executing the query
        $_SESSION['message'] = "Something went wrong";
        header('Location: purchase_request-view.php');
    }
}

//Complete Purchase Request
if (isset($_POST['request_complete_btn'])) {
    $request_id = $_POST['request_id'];
    $completion_remarks = $_POST['completion_remarks'];
    //Hidden User name
    $last_modified_by = $_POST['user_name'];
    //Specify change made Unique to each action
    $change_made = "Request Completed";
    $completed = 'completed';


    // Your SQL query to update data in the database
    $update_query = "UPDATE purchase_requests SET status = '$completed', completed_remarks = '$completion_remarks' WHERE id = '$request_id'";
    // Executing the query
    $query_run = mysqli_query($con, $update_query);

    //get the requestor email
    $sql = "SELECT requestor_user_email, purchase_request_number FROM purchase_requests WHERE id = '$request_id'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $requestor_email = $row['requestor_user_email'];
    $purchase_request_number = $row['purchase_request_number'];

    sendEmail($requestor_email, $approved, $request_id, $purchase_request_number, $completion_remarks);

    if ($query_run) {
        // If query executed successfully, Insert into purchase_requests_history
        $insert_query = "INSERT INTO purchase_requests_history (purchase_request_id, change_made, last_modified_by, datetime_occured) VALUES ('$request_id','$change_made', '$last_modified_by', NOW())";
        $insert_query_run = mysqli_query($con, $insert_query);
        $_SESSION['message'] = "Request was completed!";
        header('Location: purchase_request-view.php');
    } else {
        // If there was an error in executing the query
        $_SESSION['message'] = "Something went wrong";
        header('Location: purchase_request-view.php');
    }
}

//Reject Purchase Request
if (isset($_POST['request_reject_btn'])) {
    $request_id = $_POST['request_id'];
    $rejection_reason = $_POST['rejection_reason'];
    //Hidden User name
    $last_modified_by = $_POST['user_name'];
    //Specify change made Unique to each action
    $change_made = "Request Rejected";
    $rejected = 'rejected';

    // Your SQL query to update data in the database
    $update_query = "UPDATE purchase_requests SET status = '$rejected', rejection_reason = '$rejection_reason' WHERE id = '$request_id'";
    // Executing the query
    $query_run = mysqli_query($con, $update_query);

    //get the requestor email
    $sql = "SELECT requestor_user_email, purchase_request_number FROM purchase_requests WHERE id = '$request_id'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $requestor_email = $row['requestor_user_email'];
    $purchase_request_number = $row['purchase_request_number'];

    sendEmail($requestor_email, $rejected, $request_id, $purchase_request_number, $rejection_reason);

    if ($query_run) {
        // If query executed successfully, Insert into purchase_requests_history
        $insert_query = "INSERT INTO purchase_requests_history (purchase_request_id, change_made, last_modified_by, datetime_occured) VALUES ('$request_id', '$change_made', '$last_modified_by', NOW())";
        $insert_query_run = mysqli_query($con, $insert_query);
        $_SESSION['message'] = "Request was rejected!";
        header('Location: purchase_request-view.php');
    } else {
        // If there was an error in executing the query
        $_SESSION['message'] = "Something went wrong";
        header('Location: purchase_request-view.php');
    }
}


//Add Department
if (isset($_POST['add_department'])) {
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];

    //Insert the Department
    $query = "INSERT INTO department (name, college_id) VALUES ('$name', '$college_id')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "New Department has been added";
        header('Location: department-add.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: department-add.php');
        exit(0);
    }
}
//Delete the user
if (isset($_POST['department_delete'])) {
    $department_id = $_POST['department_delete'];

    $query = "DELETE FROM department WHERE id = '$department_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Department has been deleted";
        header('Location: department-view.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: department-view.php');
        exit(0);
    }
}
//Update Department
if (isset($_POST['update_department'])) {
    $department_id = $_POST['id'];
    $name = $_POST['name'];
    $college_id = $_POST['college_id'];

    //UPDATE the Department
    $query = "UPDATE department SET name = '$name', college_id = '$college_id' WHERE id = '$department_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Department has been Updated";
        header('Location: department-view.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: department-edit.php');
        exit(0);
    }
}


//Add College
if (isset($_POST['add_college'])) {
    $name = $_POST['name'];

    //Insert the College
    $query = "INSERT INTO college (name) VALUES ('$name')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "New College has been added";
        header('Location: college-add.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: college-add.php');
        exit(0);
    }
}

//Update College
if (isset($_POST['update_college'])) {
    $college_id = $_POST['id'];
    $name = $_POST['name'];

    //UPDATE the College
    $query = "UPDATE college SET name = '$name' WHERE id = '$college_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "College has been Updated";
        header('Location: college-view.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: college-edit.php');
        exit(0);
    }
}



//Add Faculty
if (isset($_POST['add_faculty'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $full_name = $fname . '' . $lname;
    $email = $_POST['email'];
    $college_id = $_POST['college_id'];
    $department_id = $_POST['department_id'];
    $role = $_POST['role'];

    // Image Upload
    $image = $_FILES['image']['name'];
    // Rename this image
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_extension;


    // Insert the Post with the category_id
    $query = "INSERT INTO faculty (fname, lname, full_name, email, college_id, department_id, role, image) 
              VALUES ('$fname', '$lname', '$full_name', '$email', '$college_id', '$department_id', '$role', '$filename')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        // Upload the image to uploads folder
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/faculty/' . $filename);
        $_SESSION['message'] = "New Faculty has been added";
        header('Location: faculty-add.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: faculty-add.php');
        exit(0);
    }
}

//Update Faculty
if (isset($_POST['update_faculty'])) {
    $faculty_id = $_POST['faculty_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $full_name = $fname . '' . $lname;
    $email = $_POST['email'];
    $college_id = $_POST['college_id'];
    $department_id = $_POST['department_id'];
    $role = $_POST['role'];

    // Image Upload
    $image = $_FILES['image']['name'];
    // Rename this image
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time() . '.' . $image_extension;

    $old_filename = $_POST['old_image'];

    // Update the Faculty with the new values
    $query = "UPDATE faculty SET fname = '$fname', lname = '$lname', full_name = '$full_name', email = '$email', college_id = '$college_id', department_id = '$department_id', role = '$role', image = '$filename' WHERE id = '$faculty_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        if ($image != NULL) {
            if (file_exists('../uploads/faculty/' . $_POST['old_image'])) {
                unlink('../uploads/faculty/' . $_POST['old_image']);
            }
            // Upload the image to uploads folder
            move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/faculty/' . $filename);
        }
        $_SESSION['message'] = "Faculty has been updated";
        header('Location: faculty-edit.php?id=' . $faculty_id);
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: faculty-edit.php?id=' . $faculty_id);
        exit(0);
    }
}



//Delete the user
if (isset($_POST['category_delete'])) {
    $category_id = $_POST['category_delete'];

    $query = "DELETE FROM categories WHERE id = '$category_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Category has been deleted";
        header('Location: category-view.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: category-view.php');
        exit(0);
    }
}



//Delete the user
if (isset($_POST['user_delete'])) {
    $user_id = $_POST['user_delete'];

    $query = "DELETE FROM users WHERE id = '$user_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "User has been deleted";
        header('Location: view-register.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: view-register.php');
        exit(0);
    }
}

//Add the user
if (isset($_POST['add_user'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];
    $status =  '1';

    //Insert the user
    $query = "INSERT INTO users (fname, lname, email, password, role_as) VALUES ('$fname', '$lname', '$email', '$password', '$role_as')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "New User has been added";
        header('Location: view-register.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: view-register.php');
        exit(0);
    }
}

//Update the user
if (isset($_POST['update_user'])) {
    $user_id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];

    //Update the user
    $query = "UPDATE users SET fname = '$fname', lname = '$lname', email = '$email', password = '$password', role_as = '$role_as' WHERE id = '$user_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "User has been updated";
        header('Location: view-register.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: view-register.php');
        exit(0);
    }
}

//Add School Year
if (isset($_POST['add_school_year'])) {
    $school_year = $_POST['school_year'];

    //Insert the School Year
    $query = "INSERT INTO school_year (school_year) VALUES ('$school_year')";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "New School Year has been added";
        header('Location: school_year-add.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: school_year-add.php');
        exit(0);
    }
}

//Update School Year
if (isset($_POST['update_school_year'])) {
    $school_year_id = $_POST['id'];
    $school_year = $_POST['school_year'];

    //UPDATE the School Year
    $query = "UPDATE school_year SET school_year = '$school_year' WHERE id = '$school_year_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "School Year has been Updated";
        header('Location: school_year-view.php');
        exit(0);
    } else {
        $_SESSION['message'] = "Something went wrong";
        header('Location: school_year-edit.php');
        exit(0);
    }
}

//Logout the user
if (isset($_POST['logout_btn'])) {
    //session_destroy();
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);
    unset($_SESSION['auth_role']);

    if (isset($_SESSION['access_token'])) {


        $accesstoken = $_SESSION['access_token'];

        //Reset OAuth access token
        $google_client->revokeToken($accesstoken);
    }

    session_destroy();

    $_SESSION['message'] = "Logged Out Successfully";
    header('location: ../login.php');
    exit(0);
}

//PHPMAILER Function for status email notification
function sendEmail($requestor_user_email, $status_text, $purchase_request_id, $purchase_request_number, $remarks)
{
    // Send email notification to the user_requestor_email
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        // Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'vinniuba1@gmail.com'; // SMTP username (your Gmail email address)TO BE REPLACED WITH WEBSITE EMAIL
        $mail->Password = 'buqn wpcc yhlx lvoz'; // SMTP password USE APP PASSWORD FOUND IN GOOGLE SETTINGS
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Sender and recipient
        $mail->setFrom('vinniuba1@gmail.com', 'EMAIL BOT :)'); // Sender's email address and name
        // Recipient's email address (Requestor's email address)
        $mail->addAddress('' . $requestor_user_email . '');


        // Define a variable to hold the status text


        // Email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Request Status Update';

        // Constructing HTML email body
        $body = '
    <html>
    <head>
        <title>Request Status Update</title>
        <style>
            body {
                font-family: Arial, sans-serif;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 10px;
            }
            h1 {
                color: #007bff;
            }
            p {
                line-height: 1.6;
            }
            .footer {
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid #ccc;
            }
            .footer p {
                font-size: 12px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Purchase Request Notification</h1>
            <h2>Purchase Request ID: ' . $purchase_request_id . '</h2>
            <p>Purchase Request Number: ' . $purchase_request_number . '</p>
            <p>Your request has been updated to: <strong>' . $status_text . '</strong></p>
            <p>Remarks: <strong>' . $remarks . '</strong></p>
            <div class="footer">
                <p>This is an automated email notification. Please do not reply.</p>
            </div>
        </div>
    </body>
    </html>
';

        $mail->Body = $body;

        // Send email
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}
