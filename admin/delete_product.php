<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    // If not admin, redirect to login page
    header("Location: login.php");
    exit();
}
include "../utils/connection.php";

// Check if product ID is provided in URL
if (isset($_GET["id"])) {
    try {
        $product_id = $_GET["id"];

        // Prepare delete statement
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);

        // Execute delete statement
        if ($stmt->execute()) {
            // Redirect back to dashboard on successful deletion
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error deleting product: " . $conn->error;
        }
    } catch (PDOException $e) {
        error_log("Delete error: " . $e->getMessage());
        echo "Error occurred while deleting the product";
    }

    // Close statement
    $stmt->close();
} else {
    echo "No product ID provided";
}

// Close connection
$conn->close();
?>
