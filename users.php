<?php
$conn = new mysqli("localhost", "username", "password", "database");

$username = $_GET['username'];
$password = $_GET['password'];

// Vulnerable to SQL Injection
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($query);
?>
