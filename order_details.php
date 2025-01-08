<?php
include "utils/connection.php";

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: account.php");
    exit();
}

$order_id = $_GET["id"];
$user_id = $_SESSION["user_id"];

// Get order details
$order_query = "SELECT * FROM orders WHERE id = ? AND account_id = ?";
$order_stmt = $conn->prepare($order_query);
$order_stmt->bind_param("ii", $order_id, $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    header("Location: account.php");
    exit();
}

// Get order items
$items_query = "SELECT oi.*, p.name, p.image
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = ?";
$items_stmt = $conn->prepare($items_query);
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
$items = [];
while ($item = $items_result->fetch_assoc()) {
    $items[] = $item;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="styling/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
</head>
<body class="bg-gray-50">
    <?php include "components/navbar.php"; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Order #<?php echo $order_id; ?></h1>
                <p class="text-gray-600">
                    <?php echo date(
                        "M d, Y",
                        strtotime($order["created_at"])
                    ); ?>
                </p>
            </div>

            <div class="space-y-6">
                <!-- Order Items -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Order Items</h2>
                    <div class="space-y-4">
                        <?php foreach ($items as $item) { ?>
                            <div class="flex items-center justify-between border-b pb-4">
                                <div class="flex items-center">
                                    <img src="<?php echo $item[
                                        "image"
                                    ]; ?>" alt="<?php echo $item[
    "name"
]; ?>" class="w-16 h-16 object-cover rounded">
                                    <div class="ml-4">
                                        <h3 class="font-medium"><?php echo $item[
                                            "name"
                                        ]; ?></h3>
                                        <p class="text-gray-600">Quantity: <?php echo $item[
                                            "quantity"
                                        ]; ?></p>
                                    </div>
                                </div>
                                <p class="font-medium">$<?php echo number_format(
                                    $item["price"],
                                    2
                                ); ?></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    <div class="border-t pt-4">
                        <div class="flex justify-between">
                            <p class="font-medium">Total</p>
                            <p class="font-medium">$<?php echo number_format(
                                $order["total_amount"],
                                2
                            ); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Details -->
                <div>
                    <h2 class="text-xl font-semibold mb-4">Shipping Details</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Name:</span> <?php echo $order[
                            "full_name"
                        ]; ?></p>
                        <p><span class="font-medium">Address:</span> <?php echo $order[
                            "address"
                        ]; ?></p>
                        <p><span class="font-medium">City:</span> <?php echo $order[
                            "city"
                        ]; ?></p>
                        <p><span class="font-medium">Postal Code:</span> <?php echo $order[
                            "postal_code"
                        ]; ?></p>
                        <p><span class="font-medium">Phone:</span> <?php echo $order[
                            "phone"
                        ]; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include "components/footer.php"; ?>
</body>
</html>
