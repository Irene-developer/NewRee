<?php
// Include necessary files and functions
include 'db_connection.php';
include 'functions.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_activities'])) {
    $_SESSION['user_activities'] = [];
}

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header('Location: new_login.php');
    exit();
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and clean it
    $registrationType = clean_input($_POST["registrationType"]);
    $name = clean_input($_POST["name"]);
    $phone = clean_input($_POST["phone"]);
    $address = clean_input($_POST["address"]);
    $region = clean_input($_POST["region"]);
    $email = clean_input($_POST["email"]);
    $amount = clean_input($_POST["amount"]);
    $shareholder_account_number = clean_input($_POST["shareholder_account_number"]);
    $amount_in_words = clean_input($_POST["amount_in_words"]);
    $number_share = clean_input($_POST["number_share"]);

    // Upload documents and get the file paths
    $nida_id_path = upload_document($_FILES["nida_id"], "nida_id");
    $form_path = upload_document($_FILES["form"], "form");
    $payment_slip_path = upload_document($_FILES["payment_slip"], "payment_slip");

    // Define the SQL query to insert the data into the database
    $sql = "INSERT INTO company_info (registrationType, name, phone_number, address, region, email, amount, number_share, amount_in_words, shareholder_account_number, nida_id_path, form_path, payment_slip_path, created_by)
            VALUES ('$registrationType', '$name', '$phone', '$address', '$region', '$email', '$amount', '$number_share', '$amount_in_words', '$shareholder_account_number', '$nida_id_path', '$form_path', '$payment_slip_path', '$username')";
    
    // Execute the SQL query
    if (mysqli_query($conn, $sql)) {
        // Log the successful form submission
        log_user_activity("Form submitted successfully by user: $username");

        // Success: Use JavaScript to display a success message and redirect to dashboard.php
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Form submitted successfully!'
                }).then(function() {
                    window.location.href = 'dashboard.php';
                });
            });
        </script>";
    } else {
        // Log the error if the SQL query fails
        log_user_activity("Error submitting form by user: $username. Error: " . mysqli_error($conn));
        
        // Error: Display an error message
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an error submitting the form.'
                });
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission</title>
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>
<body>
    <!-- Your form HTML goes here -->

    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
</body>
</html>
