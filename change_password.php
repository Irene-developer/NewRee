<?php
// Start the session at the beginning of the script
session_start();

// Include database connection
require_once 'db_connection.php';

// Function to sanitize input data
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $current_password = sanitize_input($_POST['current_password']);
    $new_password = sanitize_input($_POST['new_password']);
    $confirm_password = sanitize_input($_POST['confirm_password']);

    // Check if new password and confirm password match
    if ($new_password !== $confirm_password) {
        // Passwords don't match
        $error_message = "New password and confirm password do not match.";
    } else {
        // Retrieve the user's current password hash from the database
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT password FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];

            // Verify the current password
            if (password_verify($current_password, $stored_password)) {
                // Generate a hash for the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $sql = "UPDATE users SET password = ?, change_password = 0 WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('si', $hashed_password, $user_id);
                if ($stmt->execute()) {
                    // Password updated successfully
                    $success_message = "Password changed successfully.";
                } else {
                    // Error updating password
                    $error_message = "Error changing password. Please try again.";
                }
            } else {
                // Current password is incorrect
                $error_message = "Incorrect current password.";
            }
        } else {
            // User not found
            $error_message = "User not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <!-- Add your CSS styles here -->
    <style>
        /* CSS styles for the password change form */
    </style>
</head>
<body>
    <h1>Change Password</h1>

    <!-- Password Change Form -->
    <form action="" method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <input type="submit" value="Change Password">
    </form>

    <!-- Display success or error message -->
    <?php if (isset($success_message)) { ?>
        <p><?php echo $success_message; ?></p>
    <?php } ?>
    <?php if (isset($error_message)) { ?>
        <p><?php echo $error_message; ?></p>
    <?php } ?>

    <!-- Include your JavaScript file if needed -->
    <script src="scripts.js"></script>
</body>
</html>
