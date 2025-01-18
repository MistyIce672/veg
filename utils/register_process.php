<?php
session_start();
require_once "connection.php";

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $firstName = trim(htmlspecialchars($_POST["firstName"]));
    $lastName = trim(htmlspecialchars($_POST["lastName"]));
    $email = trim(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL));
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Validation
    $errors = [];

    // Check if fields are empty
    if (empty($firstName)) {
        $errors[] = "First name is required";
    }
    if (empty($lastName)) {
        $errors[] = "Last name is required";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }

    // Check password strength (minimum 8 characters)
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Email already exists";
    }
    $stmt->close();

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the INSERT statement
        $stmt = $conn->prepare(
            "INSERT INTO accounts (first_name, last_name, email, password) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "ssss",
            $firstName,
            $lastName,
            $email,
            $hashedPassword
        );

        if ($stmt->execute()) {
            // Registration successful
            $_SESSION["success_message"] =
                "Registration successful! You can now login.";
            header("Location: ../signin.php");
            exit();
        } else {
            // Registration failed
            $errors[] = "Registration failed. Please try again.";
        }
        $stmt->close();
    }

    // If there are errors, store them in session and redirect back to registration form
    if (!empty($errors)) {
        $_SESSION["register_errors"] = $errors;
        $_SESSION["form_data"] = [
            "firstName" => $firstName,
            "lastName" => $lastName,
            "email" => $email,
        ];
        header("Location: ../register.php");
        exit();
    }
} else {
    // If someone tries to access this file directly without POST request
    header("Location: ../register.php");
    exit();
}

$conn->close();
?>
