<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['check_username'])) {
        $username = $_POST['username'];

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Username already exists.']);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Username is available.']);
        }

        $stmt->close();
        $conn->close();
        exit;
    }

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role_id = $_POST['role_id'];

    $stmt = $conn->prepare("INSERT INTO users (name, username, password, role_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $username, $password, $role_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding user: ' . $conn->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
