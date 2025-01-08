<?php
session_start();
mkdir("new_folder");
if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: login.php");
    exit();
}

include "../utils/connection.php";

// Get product ID from URL
$product_id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

// Fetch product details
$product_sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($product_sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: dashboard.php");
    exit();
}

// Fetch categories for dropdown
$categories_sql = "SELECT * FROM categories";
$categories = $conn->query($categories_sql);

// Handle form submission
$upload_dir = "../uploads/";

if (!file_exists($upload_dir)) {
    try {
        if (!mkdir($upload_dir, 0777, true)) {
            throw new Exception("Failed to create upload directory");
        }
        chmod($upload_dir, 0777); // Ensure directory is writable
    } catch (Exception $e) {
        error_log("Failed to create directory: " . $e->getMessage());
        $_SESSION["error"] =
            "Upload directory creation failed. Please contact administrator.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $category_id = $_POST["category_id"];
    $price = $_POST["price"];
    $in_stock = isset($_POST["in_stock"]) ? 1 : 0;
    $on_sale = isset($_POST["on_sale"]) ? 1 : 0;
    $sale_price = $on_sale ? $_POST["sale_price"] : null;

    // Handle image upload
    $image_path = $product["image"]; // Keep existing image path by default
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

            // Delete old image if exists
            if ($product["image"] && file_exists("../" . $product["image"])) {
                unlink("../" . $product["image"]);
            }

            // Move uploaded file
            if (
                move_uploaded_file($_FILES["image"]["tmp_name"], $upload_path)
            ) {
                $image_path = "uploads/products/" . $new_filename;
            } else {
                $_SESSION["error"] = "Failed to upload image.";
            }
        } else {
            $_SESSION["error"] =
                "Invalid file type. Please upload JPG, JPEG, PNG, or GIF files only.";
        }
    }

    // Update product in database
    $update_sql = "UPDATE products SET
                      name = ?,
                      description = ?,
                      category_id = ?,
                      price = ?,
                      in_stock = ?,
                      image = ?,
                      on_sale = ?,
                      sale_price = ?
                      WHERE id = ?";

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param(
        "ssiissiid",
        $name,
        $description,
        $category_id,
        $price,
        $in_stock,
        $image_path,
        $on_sale,
        $sale_price,
        $product_id
    );

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <nav class="bg-green-600 text-white p-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">Edit Product</h1>
                <a href="dashboard.php" class="hover:text-gray-200">Back to Dashboard</a>
            </div>
        </nav>

        <div class="max-w-2xl mx-auto p-6">
            <div class="bg-white rounded-lg shadow p-6">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                        <input type="text"
                               name="name"
                               value="<?php echo htmlspecialchars(
                                   $product["name"]
                               ); ?>"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea name="description"
                                  rows="4"
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars(
                                      $product["description"]
                                  ); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                        <select name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <?php while (
                                $category = $categories->fetch_assoc()
                            ): ?>
                                <option value="<?php echo $category["id"]; ?>"
                                        <?php echo $category["id"] ==
                                        $product["category_id"]
                                            ? "selected"
                                            : ""; ?>>
                                    <?php echo htmlspecialchars(
                                        $category["name"]
                                    ); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                        <input type="number"
                               name="price"
                               step="0.01"
                               value="<?php echo $product["price"]; ?>"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Sale Options</label>
                        <div class="flex items-center mb-2">
                            <input type="checkbox"
                                   name="on_sale"
                                   id="on_sale"
                                   <?php echo isset($product["on_sale"]) &&
                                   $product["on_sale"]
                                       ? "checked"
                                       : ""; ?>
                                   class="mr-2">
                            <label for="on_sale">Put on Sale</label>
                        </div>

                        <div id="sale_price_container" class="<?php echo isset(
                            $product["on_sale"]
                        ) && $product["on_sale"]
                            ? ""
                            : "hidden"; ?>">
                            <label class="block text-gray-700 text-sm mb-2">Sale Price</label>
                            <input type="number"
                                   name="sale_price"
                                   step="0.01"
                                   value="<?php echo isset(
                                       $product["sale_price"]
                                   )
                                       ? $product["sale_price"]
                                       : ""; ?>"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Stock Status</label>
                        <input type="checkbox"
                               name="in_stock"
                               <?php echo $product["in_stock"]
                                   ? "checked"
                                   : ""; ?>>
                        <span class="ml-2">In Stock</span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Product Image</label>
                        <?php if ($product["image"]): ?>
                            <div class="mb-2">
                                <img src="../<?php echo htmlspecialchars(
                                    $product["image"]
                                ); ?>"
                                     alt="Current product image"
                                     class="w-32 h-32 object-cover">
                            </div>
                        <?php endif; ?>
                        <input type="file"
                               name="image"
                               accept="image/*"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="flex justify-end">
                        <a href="dashboard.php"
                           class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</a>
                        <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const onSaleCheckbox = document.getElementById('on_sale');
        const salePriceContainer = document.getElementById('sale_price_container');

        onSaleCheckbox.addEventListener('change', function() {
            if(this.checked) {
                salePriceContainer.classList.remove('hidden');
            } else {
                salePriceContainer.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
