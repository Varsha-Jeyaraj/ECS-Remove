<?php
session_start();
include 'config.php';

// Initialize error variable
$error_message = '';

// Assume a simple database query for user validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the username exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            if( $user['usertype'] == "Management Staff")
            {
                header('Location: dashboard.php');
                exit;// Important to stop script execution after redirection
            }
            if( $user['usertype'] == "DCS Staff")
            {
                header('Location: dashboardDCSStaff.php');
                exit;// Important to stop script execution after redirection
            }
            if( $user['usertype'] == "DCS Head")
            {
                header('Location:dashboardDCSHead.php');
                exit;// Important to stop script execution after redirection
            }
        } else {
            // Password is incorrect
            $error_message = 'Incorrect password. Please try again.';
        }
    } else {
        // Username not found
        $error_message = 'Username not found. Please check your username.';
    }
}
?>

