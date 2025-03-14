<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: login.php");
    exit();
}

include "../utils/connection.php";

// Fetch categories for dropdown
$categories_sql = "SELECT * FROM categories";
$categories = $conn->query($categories_sql);

$upload_dir = "../uploads/";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $category_id = $_POST["category_id"];
        $price = $_POST["price"];
        $in_stock = isset($_POST["in_stock"]) ? 1 : 0;
        $on_sale = isset($_POST["on_sale"]) ? 1 : 0;
        $sale_price = $on_sale ? $_POST["sale_price"] : 0;

        // Handle image upload
        $image_path = null;
        if (isset($_FILES["image"]) && $_FILES["image"]["size"] > 0) {
            $file_extension = strtolower(
                pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
            );

            // Check if image file is valid
            $valid_types = ["jpg", "jpeg", "png", "gif"];
            if (in_array($file_extension, $valid_types)) {
                // Generate unique filename
                $new_filename = uniqid() . "." . $file_extension;
                $upload_path = $upload_dir . $new_filename;

                // Move uploaded file
                if (
                    move_uploaded_file(
                        $_FILES["image"]["tmp_name"],
                        $upload_path
                    )
                ) {
                    $image_path = "uploads/" . $new_filename;
                } else {
                    $_SESSION["error"] = "Failed to upload image.";
                }
            } else {
                $_SESSION["error"] =
                    "Invalid file type. Please upload JPG, JPEG, PNG, or GIF files only.";
            }
        }

        $sql = "INSERT INTO products (name, description, category_id, price, in_stock, image, on_sale, sale_price)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssiissis",
            $name,
            $description,
            $category_id,
            $price,
            $in_stock,
            $image_path,
            $on_sale,
            $sale_price
        );

        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
    }
}
?>
