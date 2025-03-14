<?php
session_start();
require_once "../utils/connection.php";

// Check if user is logged in as admin
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: login.php");
    exit();
}

function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    // Get current user's username from session
    $username = $_SESSION["admin_username"];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        header("Location: reset.php?error=1");
        exit();
    }

    try {
        // Get user's current password hash
        $stmt = $conn->prepare(
            "SELECT password FROM admin_users WHERE username = ?"
        );
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            header("Location: reset.php?error=2");
            exit();
        }

        $user = $result->fetch_assoc();

        // Verify old password
        if (!password_verify($old_password, $user["password"])) {
            header("Location: reset.php?error=4"); // Error 4 for incorrect old password
            exit();
        }

        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password
        $update_stmt = $conn->prepare(
            "UPDATE admin_users SET password = ? WHERE username = ?"
        );
        $update_stmt->bind_param("ss", $hashed_password, $username);

        if ($update_stmt->execute()) {
            // Password updated successfully
            header("Location: reset.php?success=1");
            exit();
        } else {
            // Update failed
            header("Location: reset.php?error=3");
            exit();
        }
    } catch (Exception $e) {
        error_log("Password reset error: " . $e->getMessage());
        header("Location: reset.php?error=3");
        exit();
    }
} else {
    // If someone tries to access this file directly
    header("Location: reset.php");
    exit();
}

$conn->close();
?>
