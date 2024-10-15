<?php
session_start();
include 'config.php';

// Assume a simple database query for user validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: dashboard.php');
            exit; // Important to stop script execution after redirection
        }
    }
    
    // Redirect back with an error
    header('Location: login.php?error=Invalid credentials');
    exit; // Important to stop script execution
}
?>
