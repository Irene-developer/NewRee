<?php
session_start(); // Start a session to manage user authentication

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include 'db_connection.php';

    // Get the username and password submitted from the login form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the provided credentials match the admin credentials
    if ($username === 'irene' && $password === '123456780') {
        // Admin credentials are correct
        $_SESSION['username'] = $username;
        $_SESSION['is_maker'] = true;
        // Redirect the user to the admin dashboard
        header("Location: display_shareholders.php");
        exit(); // Stop further execution
    }

    // Query the database to retrieve the user's record
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    // Check if the user exists in the database
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Verify the submitted password against the hashed password stored in the database
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variables to indicate user is logged in
            $_SESSION['username'] = $username;
            // Redirect the user to the dashboard or another protected page
            header("Location: dashboard.php");
            exit(); // Stop further execution
        } else {
            // Password is incorrect, redirect back to the login page with an error message
            header("Location: login.php?error=1");
            exit(); // Stop further execution
        }
    } else {
        // User does not exist, redirect back to the login page with an error message
        header("Location: login.php?error=1");
        exit(); // Stop further execution
    }
} else {
    // Redirect to the login page if accessed directly without a POST request
    header("Location: login.php");
    exit(); // Stop further execution
}
?>
