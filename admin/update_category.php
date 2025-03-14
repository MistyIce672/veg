<?php
session_start();

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: login.php");
    exit();
}

include "../utils/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST["category_id"];
    $name = $_POST["name"];

    // Basic validation
    if (empty($category_id) || empty($name)) {
        die("Invalid input");
    }

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $category_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating category: " . $conn->error;
    }

    $stmt->close();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
