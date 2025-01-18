<?php
// Start session
session_start();

// Include database connection
require_once "../utils/connection.php";

// Function to sanitize input
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $username = sanitize_input($_POST["username"]);
    $password = $_POST["password"];

    try {
        // Prepare SQL statement
        $stmt = $conn->prepare(
            "SELECT id, username, password FROM admin_users WHERE username = ?"
        );
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($user_id, $user_username, $user_password);
        $stmt = $stmt->fetch();

        // Verify user exists and password is correct
        if ($user_id && password_verify($password, $user_password)) {
            // Set session variables
            $_SESSION["admin_id"] = $user["id"];
            $_SESSION["admin_username"] = $user["username"];
            $_SESSION["is_admin"] = true;

            // If "Remember Me" is checked
            if (isset($_POST["remember"]) && $_POST["remember"] == "on") {
                // Set cookies that expire in 30 days
                setcookie(
                    "admin_login",
                    $user["username"],
                    time() + 86400 * 30,
                    "/"
                );
            }

            // Redirect to admin dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid credentials
            header("Location: login.php?error=1&");
            exit();
        }
    } catch (PDOException $e) {
        // Log error and show generic error message
        error_log("Login error: " . $e->getMessage());
        header("Location: login.php?error=2");
        exit();
    }
} else {
    // If someone tries to access this file directly
    header("Location: login.php");
    exit();
}

// Close database connection
$conn = null;
?>
