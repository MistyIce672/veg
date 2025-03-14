<?php
include "utils/connection.php";

session_start();

// Check if user is logged in and has a last order ID
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["last_order_id"])) {
    header("Location: index.php");
    exit();
}

$order_id = $_SESSION["last_order_id"];
$user_id = $_SESSION["user_id"];

// Fetch order details
$order_query = "SELECT * FROM orders WHERE id = ? AND account_id = ?";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("ii", $order_id, $user_id);
$order_stmt->execute();
$order = $order_stmt->get_result()->fetch_assoc();

// Fetch order items
$items_query = "SELECT oi.*, p.name, p.image
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = ?";
$items_stmt = $conn->prepare($items_query);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$order_items = $items_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Clear the last order ID from session
unset($_SESSION["last_order_id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styling/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php include "components/navbar.php"; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="mb-4">
                <i class="fas fa-check-circle text-green-500 text-5xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Thank You for Your Order!
            </h1>
            <p class="mt-3 text-gray-500">
                Order #<?php echo str_pad($order_id, 8, "0", STR_PAD_LEFT); ?>
            </p>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Order Details -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold mb-4">Order Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Shipping Address</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-900"><?php echo htmlspecialchars(
                                $order["full_name"]
                            ); ?></p>
                            <p class="text-sm text-gray-900"><?php echo htmlspecialchars(
                                $order["address"]
                            ); ?></p>
                            <p class="text-sm text-gray-900">
                                <?php echo htmlspecialchars(
                                    $order["city"]
                                ); ?>, <?php echo htmlspecialchars(
    $order["postal_code"]
); ?>
                            </p>
                            <p class="text-sm text-gray-900"><?php echo htmlspecialchars(
                                $order["phone"]
                            ); ?></p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Order Summary</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-900">
                                Total Amount: LKR<?php echo number_format(
                                    $order["total_amount"],
                                    2
                                ); ?>
                            </p>
                            <p class="text-sm text-gray-900">
                                Order Date: <?php echo date(
                                    "F j, Y",
                                    strtotime($order["created_at"])
                                ); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Ordered Items</h2>
                <div class="space-y-4">
                    <?php foreach ($order_items as $item) { ?>
                        <div class="flex items-center justify-between py-4 border-b last:border-0">
                            <div class="flex items-center">
                                <img src="<?php echo htmlspecialchars(
                                    $item["image"]
                                ); ?>"
                                     alt="<?php echo htmlspecialchars(
                                         $item["name"]
                                     ); ?>"
                                     class="w-16 h-16 object-cover rounded">
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium"><?php echo htmlspecialchars(
                                        $item["name"]
                                    ); ?></h3>
                                    <p class="text-sm text-gray-500">Quantity: <?php echo $item[
                                        "quantity"
                                    ]; ?></p>
                                </div>
                            </div>
                            <p class="text-sm font-medium">
                                $<?php echo number_format(
                                    $item["price"] * $item["quantity"],
                                    2
                                ); ?>
                            </p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 text-center">
            <a href="index.php" class="inline-block bg-green-600 text-white py-2 px-6 rounded-md hover:bg-green-700">
                Continue Shopping
            </a>
        </div>
    </main>

    <?php include "components/footer.php"; ?>
</body>
</html>
