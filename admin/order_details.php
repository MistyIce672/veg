<?php
session_start();

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true) {
    header("Location: login.php");
    exit();
}

include "../utils/connection.php";

// Get order ID from URL parameter
$order_id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if (!$order_id) {
    header("Location: dashboard.php");
    exit();
}

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    $new_status = $_POST["status"];
    $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
}

// Fetch order details
$order_sql = "SELECT orders.*, accounts.email
              FROM orders
              LEFT JOIN accounts ON orders.account_id = accounts.id
              WHERE orders.id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Fetch order items
$items_sql = "SELECT order_items.*, products.name as product_name
              FROM order_items
              LEFT JOIN products ON order_items.product_id = products.id
              WHERE order_items.order_id = ?";
$stmt = $conn->prepare($items_sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-green-600 text-white p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Order Details</h1>
            <div>
                <a href="dashboard.php" class="hover:text-gray-200 mr-4">Back to Dashboard</a>
                <a href="logout.php" class="hover:text-gray-200">Logout</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Order #<?php echo $order_id; ?></h2>
                    <p class="text-gray-600">
                        Placed on <?php echo date(
                            "F j, Y g:i A",
                            strtotime($order["created_at"])
                        ); ?>
                    </p>
                </div>

                <!-- Status Update Form -->
                <form method="POST" class="flex items-center">
                    <select name="status" class="rounded-l border p-2">
                        <option value="pending" <?php echo $order["status"] ==
                        "pending"
                            ? "selected"
                            : ""; ?>>Pending</option>
                        <option value="processing" <?php echo $order[
                            "status"
                        ] == "processing"
                            ? "selected"
                            : ""; ?>>Processing</option>
                        <option value="shipped" <?php echo $order["status"] ==
                        "shipped"
                            ? "selected"
                            : ""; ?>>Shipped</option>
                        <option value="delivered" <?php echo $order["status"] ==
                        "delivered"
                            ? "selected"
                            : ""; ?>>Delivered</option>
                        <option value="cancelled" <?php echo $order["status"] ==
                        "cancelled"
                            ? "selected"
                            : ""; ?>>Cancelled</option>
                    </select>
                    <button type="submit" name="update_status" class="bg-green-600 text-white px-4 py-2 rounded-r hover:bg-green-700">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Customer Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Customer Information</h3>
                    <div class="bg-gray-50 p-4 rounded">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars(
                            $order["full_name"]
                        ); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars(
                            $order["email"]
                        ); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars(
                            $order["phone"]
                        ); ?></p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-2">Shipping Address</h3>
                    <div class="bg-gray-50 p-4 rounded">
                        <p><?php echo htmlspecialchars(
                            $order["address"]
                        ); ?></p>
                        <p><?php echo htmlspecialchars($order["city"]); ?></p>
                        <p><?php echo htmlspecialchars(
                            $order["postal_code"]
                        ); ?></p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <h3 class="text-lg font-semibold mb-2">Order Items</h3>
            <div class="bg-gray-50 rounded overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php while ($item = $items->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4"><?php echo htmlspecialchars(
                                $item["product_name"]
                            ); ?></td>
                            <td class="px-6 py-4"><?php echo $item[
                                "quantity"
                            ]; ?></td>
                            <td class="px-6 py-4">LKR <?php echo number_format(
                                $item["price"],
                                2
                            ); ?></td>
                            <td class="px-6 py-4">LKR <?php echo number_format(
                                $item["price"] * $item["quantity"],
                                2
                            ); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-semibold">Total Amount:</td>
                            <td class="px-6 py-4 font-semibold">LKR <?php echo number_format(
                                $order["total_amount"],
                                2
                            ); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="dashboard.php" class="inline-flex items-center text-green-600 hover:text-green-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
