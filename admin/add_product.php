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
    try {
        $name = $_POST["name"];
        $category_id = $_POST["category_id"];
        $price = $_POST["price"];
        $in_stock = true; // Adding in_stock value

        $sql =
            "INSERT INTO products (name, category_id, price, in_stock) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sidi", $name, $category_id, $price, $in_stock);
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
    }

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
