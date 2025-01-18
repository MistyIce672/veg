<!-- admin/dashboard.php -->
<?php
session_start();

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

include "../utils/connection.php";

include "../utils/connection.php";
// Add authentication check here to ensure only admins can access

// Fetch all products
$products_sql = "SELECT products.*, categories.name as category_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id";
$products = $conn->query($products_sql);

// Fetch all categories
$categories_sql = "SELECT * FROM categories";
$categories = $conn->query($categories_sql);

$orders_sql = "SELECT orders.*,
               COUNT(order_items.id) as total_items,
               accounts.email
               FROM orders
               LEFT JOIN order_items ON orders.id = order_items.order_id
               LEFT JOIN accounts ON orders.account_id = accounts.id
               GROUP BY orders.id
               ORDER BY orders.created_at DESC";
$orders = $conn->query($orders_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-green-600 text-white p-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                    <div>
                        <a href="../index.php" class="hover:text-gray-200 mr-4">Back to Site</a>
                        <a href="logout.php" class="hover:text-gray-200">Logout</a>
                    </div>
            </div>
        </nav>


        <div class="max-w-7xl mx-auto p-6">
            <!-- Products Section -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold">Products</h2>
                    <button onclick="openProductModal()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Add New Product
                    </button>
                </div>

                <!-- Products Table -->
                <div class="bg-white rounded-lg shadow overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while (
                                $product = $products->fetch_assoc()
                            ): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars(
                                    $product["name"]
                                ); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars(
                                    $product["category_name"]
                                ); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">LKR <?php echo number_format(
                                    $product["price"],
                                    2
                                ); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php echo htmlspecialchars(
                                        $product["in_stock"] == 0
                                            ? "Out of Stock"
                                            : "In Stock"
                                    ); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="edit_product.php?id=<?php echo $product[
                                        "id"
                                    ]; ?>"
                                       class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <a href="delete_product.php?id=<?php echo $product[
                                        "id"
                                    ]; ?>" class="text-red-600 hover:text-red-900">
                                        <button type="button">Delete</button>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Categories Section -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold">Categories</h2>
                    <button onclick="openCategoryModal()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Add New Category
                    </button>
                </div>

                <!-- Categories Table -->
                <div class="bg-white rounded-lg shadow overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while (
                                $category = $categories->fetch_assoc()
                            ): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars(
                                    $category["name"]
                                ); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="text-blue-600 hover:text-blue-900 mr-2">Edit</button>
                                    <a href="delete_category.php?id=<?php echo $category[
                                        "id"
                                    ]; ?>" class="text-red-600 hover:text-red-900">
                                        <button type="button">Delete</button>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">Orders Summary</h2>
                    </div>

                    <!-- Orders Table -->
                    <div class="bg-white rounded-lg shadow overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php while (
                                    $order = $orders->fetch_assoc()
                                ): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">#<?php echo htmlspecialchars(
                                        $order["id"]
                                    ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div><?php echo htmlspecialchars(
                                            $order["full_name"]
                                        ); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars(
                                            $order["email"]
                                        ); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars(
                                        $order["total_items"]
                                    ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">LKR <?php echo number_format(
                                        $order["total_amount"],
                                        2
                                    ); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo date(
                                            "Y-m-d H:i",
                                            strtotime($order["created_at"])
                                        ); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        // Define status colors
                                        $statusColors = [
                                            "pending" =>
                                                "bg-yellow-100 text-yellow-800",
                                            "processing" =>
                                                "bg-blue-100 text-blue-800",
                                            "completed" =>
                                                "bg-green-100 text-green-800",
                                            "cancelled" =>
                                                "bg-red-100 text-red-800",
                                        ];

                                        // Get status from order array
                                        $status = strtolower($order["status"]);

                                        // Get color classes based on status
                                        $colorClass = isset(
                                            $statusColors[$status]
                                        )
                                            ? $statusColors[$status]
                                            : "bg-gray-100 text-gray-800";
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $colorClass; ?>">
                                            <?php echo ucfirst(
                                                $order["status"]
                                            ); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="order_details.php?id=<?php echo $order[
                                            "id"
                                        ]; ?>"
                                           class="text-blue-600 hover:text-blue-900">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="productModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Add New Product</h3>
            <form action="add_product.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <textarea
                        name="description"
                        rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Enter product description"
                    ></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                    <select name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <?php
                        $categories->data_seek(0); // Reset category result pointer
                        while ($category = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $category[
                                "id"
                            ]; ?>"><?php echo htmlspecialchars(
    $category["name"]
); ?></option>
                        <?php endwhile;
                        ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Price</label>
                    <input type="number" name="price" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeProductModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="categoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Add New Category</h3>
            <form action="add_category.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeCategoryModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openProductModal() {
            document.getElementById('productModal').classList.remove('hidden');
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        function openCategoryModal() {
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }
    </script>
</body>
</html>
