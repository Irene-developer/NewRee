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

// Include database connection
require_once 'db_connection.php';

// Default category is 'all'
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Modify SQL query based on the button clicked
switch ($category) {
    case 'all':
        $stmt = $conn->prepare("SELECT u.user_id, u.username, u.name, r.role_name AS role_name FROM users u JOIN roles r ON u.role_id = r.id");
        break;
    case 'locked':
        $stmt = $conn->prepare("SELECT u.user_id, u.username, u.name, r.role_name AS role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.user_status = 'Locked'");
        break;
    case 'disabled':
        $stmt = $conn->prepare("SELECT u.user_id, u.username, u.name, r.role_name AS role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.user_status = 'Disabled'");
        break;
    default:
        // Default to fetching all users
        $stmt = $conn->prepare("SELECT u.user_id, u.username, u.name, r.role_name AS role_name FROM users u JOIN roles r ON u.role_id = r.id");
        break;
}

// Check if prepare() succeeded
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Execute the statement
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}

// Retrieve the result set
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Admin Dashboard</title>
    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        /* Header Styling */
        .header {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header img {
            height: 100px;
            margin-right: 20px;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            list-style: none;
            margin: 10px 0;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #4CAF50;
        }
        h2 {
            text-align: center;
            align-content: center;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Background overlay */
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 5px;
            width: 50%; /* Adjust width as needed */
        }

        .close-btn {
            float: right;
            font-size: 24px;
            cursor: pointer;
        }

        /* Add New User Form Styling */
        #add-user-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        #add-user-form input,
        #add-user-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        #add-user-form input[type="submit"] {
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        #add-user-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Manage Users Section Styling */
        #manage-users-section {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #manage-users-section table {
            width: 100%;
            border-collapse: collapse;
        }

        #manage-users-section th,
        #manage-users-section td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        #manage-users-section th {
            background-color: #f2f2f2;
        }

        #manage-users-section tr:hover {
            background-color: #f1f1f1;
        }

        /* Actions Styling */
        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons form {
            margin: 0;
        }

        .action-buttons form input[type="submit"] {
            background-color: #333;
            color: white;
            border-radius: 4px;
            padding: 8px 15px;
            transition: background-color 0.3s ease;
        }

        .action-buttons form input[type="submit"]:hover {
            background-color: #555;
        }

        /* Button Container Styling */
        .user-categories {
            text-align: center;
            margin-top: 20px;
        }

        /* Button Styling */
        .user-categories button {
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 15px 30px; /* Adjust padding for increased width */
            margin-right: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .user-categories button:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div>
            <img src="kcbl_logo.png" alt="Logo">
        </div>
        <h1>Admin Dashboard</h1>
        <ul class="nav-links">
            <li><a href="#" id="openModalBtn">Add User</a></li>
            <li><a href="#manage-users">Manage Users</a></li>
        </ul>
    </div>

    <!-- Add buttons for different user categories -->
    <div class="user-categories">
        <a href="?category=all"><button>All Users</button></a>
        <a href="?category=locked"><button>Locked Users</button></a>
        <a href="?category=disabled"><button>Disabled Users</button></a>
    </div>

    <!-- Modal for Adding User -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <!-- Close button -->
            <span class="close-btn" id="closeModalBtn">&times;</span>
            
            <!-- Add New User Form -->
<form id="add-user-form" action="add_user.php" method="post">
    <h2>Add New User</h2>
    <label for="name">Full Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <label for="role">Role:</label>
    <select id="role" name="role_id" required>
        <option value="1">Registrar</option>
        <option value="2">Approval</option>
        <option value="3">Report Generation</option>
        <option value="4">Registrar and Report Generation</option>
        <option value="5">Admin</option>
        <option value="6">All Roles</option>
    </select>

    <input type="submit" value="Add User">
</form>

        </div>
    </div>

    <!-- Modal for Unlocking User -->
    <div id="unlockUserModal" class="modal">
        <div class="modal-content">
            <!-- Close button -->
            <span class="close-btn" id="closeUnlockModalBtn">&times;</span>

            <!-- Unlock User Form -->
            <form id="unlock-user-form" action="unlock_user.php" method="post">
                <h2>Unlock User</h2>
                <input type="hidden" id="unlock_user_id" name="user_id">
                <label for="unlock_username">Username:</label>
                <input type="text" id="unlock_username" name="username" readonly>

                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>

                <input type="submit" value="Unlock User">
            </form>
        </div>
    </div>

    <!-- Manage Existing Users -->
<section id="manage-users-section">
    <h2>Manage Users</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['role_name']); ?></td>
                <td>
                    <div class="action-buttons">
                        <?php if($category == 'locked'): ?>
                            <!-- Form to unlock user -->
                            <button class="unlock-btn" data-user-id="<?php echo htmlspecialchars($row['user_id']); ?>" data-username="<?php echo htmlspecialchars($row['username']); ?>">Unlock</button>
                        <?php elseif($category == 'disabled'): ?>
                            <form action="disable_user.php" method="post">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['user_id']); ?>">
                                <input type="submit" value="Enable">
                            </form>
                        <?php else: ?>
                            <!-- Default action for other categories (e.g., all users) -->
                            <form action="update_user.php" method="post">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['user_id']); ?>">
                                <select name="role_id" required>
                                    <?php
                                    // Retrieve roles from the database and populate options
                                    $stmt_roles = $conn->prepare("SELECT id, role_name FROM roles");
                                    $stmt_roles->execute();
                                    $result_roles = $stmt_roles->get_result();

                                    while ($row_roles = $result_roles->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row_roles['id']) . '">' . htmlspecialchars($row_roles['role_name']) . '</option>';
                                    }

                                    $stmt_roles->close();
                                    ?>
                                </select>
                                <input type="submit" value="Update Role">
                            </form>
                            <form action="delete_user.php" method="post">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['user_id']); ?>">
                                <input type="submit" value="Disable">
                            </form>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

    <!-- JavaScript -->
    <script>
        // Get modal and buttons
        const addUserModal = document.getElementById("addUserModal");
        const openModalBtn = document.getElementById("openModalBtn");
        const closeModalBtn = document.getElementById("closeModalBtn");
        const addUserForm = document.getElementById("add-user-form");

        const unlockUserModal = document.getElementById("unlockUserModal");
        const closeUnlockModalBtn = document.getElementById("closeUnlockModalBtn");
        const unlockUserForm = document.getElementById("unlock-user-form");
        const unlockUserIdInput = document.getElementById("unlock_user_id");
        const unlockUsernameInput = document.getElementById("unlock_username");

        // Function to open the modal
        function openModal(modal) {
            modal.style.display = "block";
        }

        // Function to close the modal
        function closeModal(modal) {
            modal.style.display = "none";
        }

        // Add event listeners to buttons
        openModalBtn.addEventListener("click", (e) => {
            e.preventDefault();
            openModal(addUserModal);
        });

        closeModalBtn.addEventListener("click", () => closeModal(addUserModal));
        closeUnlockModalBtn.addEventListener("click", () => closeModal(unlockUserModal));

        // Close modal when clicking outside of the modal content
        window.addEventListener("click", (event) => {
            if (event.target === addUserModal) {
                closeModal(addUserModal);
            }
            if (event.target === unlockUserModal) {
                closeModal(unlockUserModal);
            }
        });

        // Function to generate username from name
function generateUsername(name) {
    const [firstName, lastName] = name.trim().split(" ");
    if (firstName && lastName) {
        return (firstName.charAt(0) + lastName).toLowerCase();
    }
    return '';
}

// Event listener for name input to automatically generate username
document.getElementById("name").addEventListener("input", (event) => {
    const name = event.target.value;
    const username = generateUsername(name);
    document.getElementById("username").value = username;
});

// Handle form submission and display success message for adding user
addUserForm.addEventListener("submit", async (event) => {
    event.preventDefault(); // Prevent default form submission behavior

    // Create a FormData object to send form data
    const formData = new FormData(addUserForm);

    try {
        // Make a POST request to add_user.php
        const response = await fetch(addUserForm.action, {
            method: "POST",
            body: formData,
        });

        // Parse the JSON response
        const result = await response.json();

        // Check the status and display the appropriate message
        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: result.message,
            });
            closeModal(addUserModal); // Ensure closeModal is defined and works as expected
            addUserForm.reset();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: result.message,
            });
        }
    } catch (error) {
        console.error("Error:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An unexpected error occurred.',
        });
    }
});
        // Add event listeners to unlock buttons
        document.querySelectorAll(".unlock-btn").forEach(button => {
            button.addEventListener("click", () => {
                const userId = button.dataset.userId;
                const username = button.dataset.username;
                unlockUserIdInput.value = userId;
                unlockUsernameInput.value = username;
                openModal(unlockUserModal);
            });
        });

        // Handle form submission for unlocking user
        unlockUserForm.addEventListener("submit", async (event) => {
            event.preventDefault(); // Prevent default form submission behavior

            // Create a FormData object to send form data
            const formData = new FormData(unlockUserForm);

            try {
                // Make a POST request to unlock_user.php
                const response = await fetch(unlockUserForm.action, {
                    method: "POST",
                    body: formData,
                });

                // Check if the response was successful
                if (response.ok) {
                    alert("User has been unlocked successfully!");
                    closeModal(unlockUserModal);
                    unlockUserForm.reset();
                } else {
                    console.error("Failed to unlock user:", response.statusText);
                }
            } catch (error) {
                console.error("Error:", error);
            }
        });
    </script>
</body>
</html>
<<?php 
$stmt->close();
$conn->close();
 ?>