<?php
session_start();
include "connection.php";

// Check if the form is submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input data
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Validate input
    if (empty($email) || empty($password)) {
        $_SESSION["error"] = "All fields are required";
        header("Location: ../signin.php");
        exit();
    }

    // Query to check if user exists
    $sql = "SELECT * FROM accounts WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user["password"])) {
            // Password is correct, create session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["first_name"] = $user["first_name"];
            $_SESSION["last_name"] = $user["last_name"];

            // Check if "Remember me" is checked
            if (isset($_POST["remember"]) && $_POST["remember"] == "on") {
                // Set cookies for 30 days
                setcookie("user_email", $email, time() + 86400 * 30, "/");
            }

            // Redirect to dashboard or home page
            header("Location: ../index.php");
            exit();
        } else {
            // Invalid password
            $_SESSION["error"] = "Invalid email or password";
            header("Location: ../signin.php");
            exit();
        }
    } else {
        // User not found
        $_SESSION["error"] = "Invalid email or password";
        header("Location: ../signin.php");
        exit();
    }
} else {
    // If someone tries to access this file directly
    header("Location: ../signin.php");
    exit();
}

$conn->close();
?>
