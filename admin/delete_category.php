<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    // If not admin, redirect to login page
    header("Location: login.php");
    exit();
}
include "../utils/connection.php";

// Check if category ID is provided in URL
if (isset($_GET["id"])) {
    try {
        $category_id = $_GET["id"];

        // Start transaction
        $conn->begin_transaction();

        // First delete all products in this category
        $sql_products = "DELETE FROM products WHERE category_id = ?";
        $stmt_products = $conn->prepare($sql_products);
        $stmt_products->bind_param("i", $category_id);

        // Execute products deletion
        if (!$stmt_products->execute()) {
            throw new Exception("Error deleting products: " . $conn->error);
        }
        $stmt_products->close();

        // Then delete the category
        $sql_category = "DELETE FROM categories WHERE id = ?";
        $stmt_category = $conn->prepare($sql_category);
        $stmt_category->bind_param("i", $category_id);

        // Execute category deletion
        if (!$stmt_category->execute()) {
            throw new Exception("Error deleting category: " . $conn->error);
        }
        $stmt_category->close();

        // If everything is successful, commit the transaction
        $conn->commit();

        // Redirect back to dashboard on successful deletion
        header("Location: dashboard.php");
        exit();
    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        $conn->rollback();
        error_log("Delete error: " . $e->getMessage());
        echo "Error occurred while deleting the category and its products";
    }
} else {
    echo "No category ID provided";
}

// Close connection
$conn->close();
?>
