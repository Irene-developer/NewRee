<?php
// Check the session status and start a session only if one hasn't been started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header('Location: new_login.php');
    exit();
}
// Retrieve the username from the session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #444;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
            min-width: 400px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        thead tr {
            background-color: #f4f4f4;
            color: #fff;
            border-bottom: 2px solid #ccc;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9f5e9;
        }
        tbody tr td:nth-child(11) {
            font-weight: bold;
        }
        tbody tr td:nth-child(11).status-approved {
            color: green;
        }
        tbody tr td:nth-child(11).status-pending {
            color: orange;
        }
        tbody tr td:nth-child(11).status-rejected {
            color: red;
        }
   .header {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    .container {
      max-width: 1400px;
      margin: 20px auto;
    }
    .tabs {
      display: flex;
      justify-content: space-between;
    }
    .tab {
      flex: 1;
      padding: 10px 0;
      background-color: #333;
      border: 1px solid #ddd;
      border-radius: 5px;
      cursor: pointer;
      text-align: center;
      color: white;
    }
    .tab.active {
      background-color: #333;
      color: #fff;
    }
    .tabulate{
    }
    .page {
      display: none;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-top: 20px;
    }
    .page.active {
      display: block;
    }

    .container1 {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            max-width: 1200px;
            margin: 20px auto;
        }

        /* Styling for form labels */
        label {
            text-decoration-color: black;
            display: block;
            margin-bottom: 5px;
            font-size: 16px; /* Increase font size */
            font-weight: bold;
        }

        /* Styling for input fields */
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        /* Styling for each input element */
        .form-group input {
            width: 48%; /* Adjust width to fit two inputs in a row */
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px; /* Increase font size */
        }

        /* Add a highlight effect when an input field is focused */
        .form-group input:focus {
            background-color: #e6f7ff; /* Light blue highlight color */
            outline: none; /* Remove default outline */
        }

        /* Styling for submit button */
        input[type="submit"] {
    background-color: #333;
    color: white;
    padding: 15px 50px; /* Adjust padding to increase size */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: block;
    margin: 0 auto;
    width: 200px; /* Set a fixed width */
}

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
        }
        h2{
            text-align: center;
        }
        .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    padding: 30px;
    width: 100%;
    height: 100%;
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 50%; /* Could be more or less, depending on screen size */
    border-radius: 5px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
/* CSS for form elements */
.modal-form {
    margin-top: 20px;
}

.modal-form-group {
    margin-bottom: 10px;
}

.modal-label {
    display: block;
    font-weight: bold;
}

.modal-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box; /* Ensure padding is included in width */
}

.modal-submit {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.modal-submit:hover {
    background-color: #0056b3;
}
.menu {
    text-align: center;
    margin-top: 20px;
    align-content: center;
    align-items: center;
}

.menu-item {
    display: inline-block;
    margin-right: 20px;
    color: white; /* Menu item color */
    text-decoration: none;
    font-size: 18px;
}

.menu-item:hover {
    text-decoration: underline; /* Underline on hover */
}
.registration-container {
      text-align: center;
    }

    .registration-container label,
    .registration-container select {
      display: inline-block;
      vertical-align: middle;
      font-size: 16px; /* Increase font size as needed */
      padding: 8px; /* Add padding to improve visibility */
    }

    label {
      margin-right: 10px; /* Adjust margin as needed */
    }
    .header img {
            height: 100px;
            margin-right: 20px;
        }
        /* CSS for Modal */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

  </style>
</head>
<body>

<div class="header">
        <div>
            <img src="kcbl_logo.png" alt="Logo">
        </div>

    <!-- Display the user's name on the left side -->
    <div id="userName" style="float: left;">
        <?php echo htmlspecialchars($username); ?>
    </div>
    
    <!-- Display today's date on the right side -->
    <div id="todayDate" style="float: right;">
        <?php echo date('F j, Y'); // Display today's date in the format "Month Day, Year" ?>
    </div>
    <!-- Menu -->
<div class="menu">
    <a href="#" class="menu-item">Dashboard</a>
    <a href="report_generation_dashboard.php" class="menu-item">Reports</a>
</div>
</div>


<div class="container">
  <div class="tabs">
    <div class="tab" onclick="openPage('metrics1')">Individual</div>
    <div class="tab" onclick="openPage('metrics2')">Company</div>
    <div class="tab" onclick="openPage('metrics3')">Co-operatives</div>
    <div class="tab tabulate" onclick="openPage('additionalPage')">User Activities</div>
  </div>

  <div class="pages">
    <div class="page" id="metrics1">
      <h2>Individual Shareholder Information Form</h2>

<div class="container1">
        <form id="shareholderForm" method="post" enctype="multipart/form-data" action="individual_info.php">

      <div class="registration-container">
      <label for="registrationType">Register a:</label>
      <select id="registrationType" name="registrationType" required onchange="checkRegistrationType()">
        <option value="" disabled selected>Select an option</option>
        <option value="topUp">Top Up</option>
        <option value="newInvestment">New Investment</option>
      </select>
    </div><br>
            <div class="form-group">
                <label for="name">Name (Full name of a shareholder):</label>
                <input type="text" id="name" name="name" required placeholder="Name">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number (Should contain numbers only):</label>
                <input type="tel" id="phone" name="phone" required placeholder="Phone Number">
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required placeholder="Address">
            </div>

            <div class="form-group">
                <label for="region">Region:</label>
                <input type="text" id="region" name="region" required placeholder="Region">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="Email">
            </div>

            <div class="form-group">
                <label for="amount">Amount (TZS):</label>
                <input type="number" id="amount" name="amount" required placeholder="Amount">
            </div>

            <div class="form-group">
                <label for="amount_in_words">Amount in Words:</label>
                <input type="text" id="amount_in_words" name="amount_in_words" required placeholder="Amount in Words">
            </div>

            <div class="form-group">
                <label for="amount_in_words">Shareholder Account Number:</label>
                <input type="text" id="shareholder_account_number" name="shareholder_account_number" required placeholder="Shareholder Account Number">
            </div>

            <div class="form-group">
                <label for="number_share">Number of Shares:</label>
                <input type="text" id="number_share" name="number_share" readonly>
            </div>

            <div class="form-group">
                <label for="nida_id">NIDA ID:</label>
                <input type="file" id="nida_id" name="nida_id" required>
            </div>

            <div class="form-group">
                <label for="form">Form:</label>
                <input type="file" id="form" name="form" required>
            </div>

            <div class="form-group">
                <label for="payment_slip">Payment Slip:</label>
                <input type="file" id="payment_slip" name="payment_slip">
            </div>

            <input type="submit" value="Submit">
        </form>
    </div>    </div>
    <div class="page" id="metrics2">
      <h2>Company Shareholder Information Form</h2>
<div class="container1">
        <form id="shareholderForm" method="post" enctype="multipart/form-data" action="company_info.php">
            <div class="registration-container">
      <label for="registrationType_company">Register a:</label>
      <select id="registrationType_company" name="registrationType" required onchange="checkRegistrationType_company()">
        <option value="" disabled selected>Select an option</option>
        <option value="topUp">Top Up</option>
        <option value="newInvestment_company">New Investment</option>
      </select>
    </div><br>
            <div class="form-group">
                <label for="name">Company Name:</label>
                <input type="text" id="name" name="name" required placeholder="Name">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required placeholder="Phone Number">
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required placeholder="Address">
            </div>

            <div class="form-group">
                <label for="region">Region:</label>
                <input type="text" id="region" name="region" required placeholder="Region">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="Email">
            </div>

            <div class="form-group">
                <label for="amount_company">Amount (TZS):</label>
                <input type="number" id="amount_company" name="amount" required placeholder="Amount">
            </div>

            <div class="form-group">
                <label for="amount_in_words">Amount in Words:</label>
                <input type="text" id="amount_in_words" name="amount_in_words" required placeholder="Amount in Words">
            </div>

            <div class="form-group">
                <label for="amount_in_words">Shareholder Account Number:</label>
                <input type="text" id="shareholder_account_number" name="shareholder_account_number" required placeholder="Shareholder Account Number">
            </div>

            <div class="form-group">
                <label for="number_share_company">Number of Shares:</label>
                <input type="text" id="number_share_company" name="number_share" readonly>
            </div>

            <div class="form-group">
                <label for="nida_id">NIDA ID:</label>
                <input type="file" id="nida_id" name="nida_id" required>
            </div>

            <div class="form-group">
                <label for="form">Form:</label>
                <input type="file" id="form" name="form" required>
            </div>

            <div class="form-group">
                <label for="payment_slip">Payment Slip:</label>
                <input type="file" id="payment_slip" name="payment_slip">
            </div>

            <input type="submit" value="Submit">
        </form>    </div></div>



    <div class="page" id="metrics3">
      <h2>Co-operative Society Shareholder Information Form</h2>
<div class="container1">
        <form id="shareholderForm" method="post" enctype="multipart/form-data" action="cooperative_info.php">
            <div class="registration-container">
  <label for="registrationType_cooperative">Register a:</label>
  <select id="registrationType_cooperative" name="registrationType" required onchange="checkRegistrationType_cooperative()">
    <option value="" disabled selected>Select an option</option>
    <option value="topUp">Top Up</option>
    <option value="newInvestment_cooperative">New Investment</option>
  </select>
      </div><br>
            <div class="form-group">
                <label for="name">Name of the Cooperative:</label>
                <input type="text" id="name" name="name" required placeholder="Cooperative's Name">
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required placeholder="Phone Number">
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required placeholder="Address">
            </div>

            <div class="form-group">
                <label for="region">Region:</label>
                <input type="text" id="region" name="region" required placeholder="Region">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="Email">
            </div>

            <div class="form-group">
                <label for="amount_cooperative">Amount (TZS):</label>
                <input type="number" id="amount_cooperative" name="amount" required placeholder="Amount">
            </div>

            <div class="form-group">
                <label for="amount_in_words">Amount in Words:</label>
                <input type="text" id="amount_in_words" name="amount_in_words" required placeholder="Amount in Words">
            </div>

            <div class="form-group">
                <label for="amount_in_words">Shareholder Account Number:</label>
                <input type="text" id="shareholder_account_number" name="shareholder_account_number" required placeholder="Shareholder Account Number">
            </div>

            <div class="form-group">
                <label for="number_share_cooperative">Number of Shares:</label>
                <input type="text" id="number_share_cooperative" name="number_share" readonly>
            </div>

            <div class="form-group">
                <label for="nida_id">NIDA ID:</label>
                <input type="file" id="nida_id" name="nida_id" required>
            </div>

            <div class="form-group">
                <label for="form">Form:</label>
                <input type="file" id="form" name="form" required>
            </div>

            <div class="form-group">
                <label for="payment_slip">Payment Slip:</label>
                <input type="file" id="payment_slip" name="payment_slip">
            </div>

            <input type="submit" value="Submit">
        </form>    </div>    </div>
        
    <div id="additionalPage" class="page">
    <h2>User Activities</h2>
   <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2; border-bottom: 2px solid #ccc;">
                <th style="padding: 1px; border-right: 1px solid #ccc;">Shareholder's Name</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Phone</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Address</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Region</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Email</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Amount (TZS)</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Number of Shares</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">NIDA ID</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Form</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Payment slip</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Status</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Reason</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">Action by</th>
                <th style="padding: 1px; border-right: 1px solid #ccc;">crt</th>
            </tr>
        </thead>
        <tbody>
           <?php
// Include necessary files and functions
include 'db_connection.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header('Location: new_login.php');
    exit();
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Query the database for data created by the logged-in user
$sql = "SELECT *
FROM (
    SELECT * FROM individual_info WHERE created_by = ?
    UNION ALL
    SELECT * FROM company_info WHERE created_by = ?
    UNION ALL
    SELECT * FROM cooperative_info WHERE created_by = ?
) AS combined_data";

// Prepare the SQL statement
$stmt = $conn->prepare($sql);

// Check if preparation was successful
if (!$stmt) {
    die('Error preparing statement: ' . $conn->error);
}

// Bind the username parameter
$stmt->bind_param('sss', $username, $username, $username);

// Execute the query
$stmt->execute();

// Fetch the result set
$result = $stmt->get_result();

// Check if there are rows returned
if ($result->num_rows > 0) {
    // Iterate through the rows and fetch data
    while ($row = $result->fetch_assoc()) {
        // Access data from each row
        $name = $row['name'];
        $phone = $row['phone_number'];
        $address = $row['address'];
        $region = $row['region'];
        $email = $row['email'];
        $amount = $row['amount'];
        $number_share = $row['number_share'];
        $status = $row['status'];
        $discard_reason = $row['discard_reason'];
        $status_updated_by = $row['status_updated_by'];
        $certificate_taken = $row['certificate_taken'];
        
        
         // Display the data in table rows and cells
                    echo '<tr>';
                    echo '<td style="padding: 10px; border-right: 1px solid #ccc;">' . $row['name'] . '</td>';
                    echo '<td style="padding: 10px; border-right: 1px solid #ccc;">' . $row['phone_number'] . '</td>';
                    echo '<td style="padding: 10px; border-right: 1px solid #ccc;">' . $row['address'] . '</td>';
                    echo '<td style="padding: 10px; border-right: 1px solid #ccc;">' . $row['region'] . '</td>';
                    echo '<td style="padding: 10px; border-right: 1px solid #ccc;">' . $row['email'] . '</td>';
                    echo '<td style="padding: 10px; border-right: 1px solid #ccc;">' . $row['amount'] . '</td>';
                    echo '<td style="padding: 10px; border-right: 1px solid #ccc;">' . $row['number_share'] . '</td>';
                    echo '<td>';
                    echo "<a href='" . $row['nida_id_path'] . "' style='text-decoration: none; color: inherit;' target='_blank'>View</a>";
                    echo '</td>';
                    echo "<td><a href='" . $row["form_path"] . "' style='text-decoration: none; color: inherit;' target='_blank'>View</a></td>";
                   if (!empty($row["payment_slip_path"])) {
                   echo "<td><a href='" . $row["payment_slip_path"] . "' style='text-decoration: none; color: inherit;' target='_blank'>View</a></td>";} else {
                   echo "<td><span class='no-payment-slip'>Not Available</span></td>";
                    }                    
                    echo '<td style="padding: 10px; border-right: 1px solid #ccc;">' . $row['status'] . '</td>';
                    
                    if ($row['status'] == 'discarded'){
                        echo '<td style="padding: 10px; border-right: 1px solid #ccc;">' . $row['discard_reason'] . '</td>';
                    }else{
                        echo '<td style="padding: 10px; border-right: 1px solid #ccc;">&#8230;</td>';
                    }

                    echo '<td style="padding: 10px; border-right: 1px solid #ccc;">' . $row['status_updated_by'].'</td>';

                    if ($row['status'] == 'approved') {
        echo '<td style="padding: 10px; border-right: 1px solid #ccc;">';
        echo '<a href="#" class="approve-link" data-id="' . htmlspecialchars($row['id']) . '">&#128077;</a>';
        echo '</td>';
    } else {
        echo '<td style="padding: 10px; border-right: 1px solid #ccc;">&#8230;</td>';
    }

                    echo '<td></td>';

                    // Conditionally display the "Edit" button
                    if ($row['status'] === 'pending' || $row['status'] === 'discarded') {
                    echo '<td><a href="edit_form.php?id=' . htmlspecialchars($row['id']) . '" style="text-decoration: none; color: inherit; cursor: pointer;">Edit</a></td>';
                          } else {
                     echo '<td></td>';  // Display "N/A" if the "Edit" button is not available
                    }
    
                    echo '</tr>';
                }
} else {
    // If no rows are returned, display a message
    echo "No records found for the logged-in user.";
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();
?>
        </tbody>
    </table>
</div>

<!-- Modal HTML Structure -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p>Are you sure you want to approve this record?</p>
        <form id="updateForm" action="update_certificate.php" method="POST">
            <input type="hidden" name="id" id="certificateId">
            <input type="submit" value="Confirm">
        </form>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
    const modal = document.getElementById("confirmationModal");
    const span = document.getElementsByClassName("close")[0];
    const certificateIdInput = document.getElementById("certificateId");

    document.querySelectorAll('.approve-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const id = this.getAttribute('data-id');
            certificateIdInput.value = id;
            modal.style.display = "block";
        });
    });

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});


// JavaScript to handle modal interaction
$(document).ready(function() {
    $('.approve-link').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#certificateId').val(id);
        $('#confirmationModal').show();
    });

    $('.close').click(function() {
        $('#confirmationModal').hide();
    });
});
    // JavaScript function to open the modal
function openModal() {
    var modal = document.getElementById('myModal');
    modal.style.display = 'block';
}

// JavaScript function to close the modal
function closeModal() {
    var modal = document.getElementById('myModal');
    modal.style.display = 'none';
}
  function openPage(pageId) {
    // Hide all pages
    var pages = document.querySelectorAll('.page');
    pages.forEach(function(page) {
      page.classList.remove('active');
    });

    // Show the selected page
    var selectedPage = document.getElementById(pageId);
    selectedPage.classList.add('active');
  }

  // Set "Individual" tab as the default opened page when the page loads
  document.addEventListener('DOMContentLoaded', function() {
    // Simulate a click on the "Individual" tab to open it
    openPage('metrics1');
    // Add the "active" class to the "Individual" tab to show it as active
    var individualTab = document.querySelector('.tab[onclick="openPage(\'metrics1\')"]');
    individualTab.classList.add('active');
  });

  document.getElementById('shareholderForm').addEventListener('submit', function(event) {
    var amountInput = document.getElementById('amount');
    var amountError = document.getElementById('amountError');
    var numberShareInput = document.getElementById('number_share');
    // Calculate number of shares
    var amount = parseFloat(amountInput.value);
    var numberOfShares = Math.floor(amount / 500);
    // Fill number of shares input field
    numberShareInput.value = numberOfShares;

  });

  // Listen for changes in the amount input field
  document.getElementById('amount').addEventListener('input', function() {
    var amountInput = document.getElementById('amount');
    var numberShareInput = document.getElementById('number_share');
    // Calculate number of shares
    var amount = parseFloat(amountInput.value);
    var numberOfShares = Math.floor(amount / 500);
    // Fill number of shares input field
    numberShareInput.value = numberOfShares;
  });

  function showSuccessMessage() {
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Form submitted successfully!',
    });
  }
  function checkRegistrationType() {
    var registrationType = document.getElementById("registrationType").value;
    var amountInput = document.getElementById("amount");

    if (registrationType === "newInvestment") {
      // Set minimum value for amount input
      amountInput.setAttribute("min", 200000);
    } else {
      // Remove minimum value for amount input
      amountInput.removeAttribute("min");
    }
  }

  function checkRegistrationTypeCooperative() {
    var registrationType = document.getElementById("registrationType").value;
    var amountInput = document.getElementById("amount");

    if (registrationType === "newInvestment") {
      // Set minimum value for amount input
      amountInput.setAttribute("min", 200000);
    } else {
      // Remove minimum value for amount input
      amountInput.removeAttribute("min");
    }
  }
  document.addEventListener("DOMContentLoaded", function() {
            const amountInput = document.getElementById("amount_cooperative");
            const numberShareInput = document.getElementById("number_share_cooperative");

            amountInput.addEventListener("input", function() {
                const amount = parseFloat(amountInput.value);
                if (!isNaN(amount)) {
                    numberShareInput.value = (amount / 500).toFixed(2);
                } else {
                    numberShareInput.value = "";
                }
            });
        });
  document.addEventListener("DOMContentLoaded", function() {
            const amountInput = document.getElementById("amount_company");
            const numberShareInput = document.getElementById("number_share_company");

            amountInput.addEventListener("input", function() {
                const amount = parseFloat(amountInput.value);
                if (!isNaN(amount)) {
                    numberShareInput.value = (amount / 500).toFixed(2);
                } else {
                    numberShareInput.value = "";
                }
            });
        });
   function checkRegistrationType_company() {
            const registrationType = document.getElementById("registrationType_company").value;
            const amountInput = document.getElementById("amount_company");

            if (registrationType === "newInvestment_company") {
                amountInput.setAttribute("min", "2000000");
            } else {
                amountInput.removeAttribute("min");
            }
        }
        function checkRegistrationType_cooperative() {
            const registrationType = document.getElementById("registrationType_cooperative").value;
            const amountInput = document.getElementById("amount_cooperative");

            if (registrationType === "newInvestment_cooperative") {
                amountInput.setAttribute("min", "2000000");
            } else {
                amountInput.removeAttribute("min");
            }
        }
  // Call showSuccessMessage after a short delay to allow time for the form to be submitted
</script>

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
    $sql = "INSERT INTO shareholders (registrationType, name, phone_number, address, region, email, amount, number_share, amount_in_words, shareholder_account_number, nida_id_path, form_path, payment_slip_path, created_by)
            VALUES ('$registrationType', '$name', '$phone', '$address', '$region', '$email', '$amount', '$number_share', '$amount_in_words', '$shareholder_account_number', '$nida_id_path', '$form_path', '$payment_slip_path', '$username')";
    
    // Execute the SQL query
    if (mysqli_query($conn, $sql)) {
        // Log the successful form submission
        log_user_activity("Form submitted successfully by user: $username");

        // Success: Display a success message to the user
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Form submitted successfully!'
                }).then(function() {
// Append a query parameter to the URL indicating form submission success
        var url = new URL(window.location);
        url.searchParams.set('submitted', 'true');
        window.location.href = url.href;
            });
        </script>";
    } else {
        // Log the error if the SQL query fails
        log_user_activity("Error submitting form by user: $username. Error: " . mysqli_error($conn));
        
        // Error: Display an error message
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
</body>
</html>
