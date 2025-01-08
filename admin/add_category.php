<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    // If not admin, redirect to login page
    header("Location: login.php");
    exit();
}
include "../utils/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];

    $sql = "INSERT INTO categories (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
