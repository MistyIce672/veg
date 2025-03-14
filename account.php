<?php
include "utils/connection.php";

// Check if user is logged in
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Get user details
$user_query = "SELECT * FROM accounts WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Get shipping details
$shipping_query = "SELECT * FROM shipping_details WHERE account_id = ?";
$shipping_stmt = $conn->prepare($shipping_query);
$shipping_stmt->bind_param("i", $user_id);
$shipping_stmt->execute();
$shipping_result = $shipping_stmt->get_result();
$shipping = $shipping_result->fetch_assoc();

// Get order history
$orders_query = "SELECT o.*,
                 (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
                 FROM orders o
                 WHERE account_id = ?
                 ORDER BY created_at DESC";
$orders_stmt = $conn->prepare($orders_query);
$orders_stmt->bind_param("i", $user_id);
$orders_stmt->execute();
$orders_result = $orders_stmt->get_result();
$orders = [];
while ($order = $orders_result->fetch_assoc()) {
    $orders[] = $order;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="styling/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php include "components/navbar.php"; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">My Account</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Account Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Account Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-gray-900"><?php echo htmlspecialchars(
                            $user["email"]
                        ); ?></p>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
                <?php if ($shipping) { ?>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <p class="mt-1 text-gray-900"><?php echo htmlspecialchars(
                                $shipping["full_name"]
                            ); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <p class="mt-1 text-gray-900"><?php echo htmlspecialchars(
                                $shipping["address"]
                            ); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">City</label>
                            <p class="mt-1 text-gray-900"><?php echo htmlspecialchars(
                                $shipping["city"]
                            ); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Postal Code</label>
                            <p class="mt-1 text-gray-900"><?php echo htmlspecialchars(
                                $shipping["postal_code"]
                            ); ?></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="mt-1 text-gray-900"><?php echo htmlspecialchars(
                                $shipping["phone"]
                            ); ?></p>
                        </div>
                    </div>
                <?php } else { ?>
                    <p class="text-gray-500">No shipping information saved yet.</p>
                <?php } ?>
            </div>
        </div>

        <!-- Order History -->
        <div class="mt-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Order History</h2>
                <?php if (count($orders) > 0) { ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Items
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($orders as $order) { ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            #<?php echo $order["id"]; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php echo date(
                                                "M d, Y",
                                                strtotime($order["created_at"])
                                            ); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                <?php switch (
                                                    $order["status"]
                                                ) {
                                                    case "pending":
                                                        echo "bg-yellow-100 text-yellow-800";
                                                        break;
                                                    case "processing":
                                                        echo "bg-blue-100 text-blue-800";
                                                        break;
                                                    case "shipped":
                                                        echo "bg-purple-100 text-purple-800";
                                                        break;
                                                    case "delivered":
                                                        echo "bg-green-100 text-green-800";
                                                        break;
                                                    case "cancelled":
                                                        echo "bg-red-100 text-red-800";
                                                        break;
                                                    default:
                                                        echo "bg-gray-100 text-gray-800";
                                                } ?>">
                                                <?php echo ucfirst(
                                                    $order["status"]
                                                ); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php echo $order[
                                                "item_count"
                                            ]; ?> items
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            LKR<?php echo number_format(
                                                $order["total_amount"],
                                                2
                                            ); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="order_details.php?id=<?php echo $order[
                                                "id"
                                            ]; ?>"
                                               class="text-green-600 hover:text-green-900">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <p class="text-gray-500">No orders found.</p>
                <?php } ?>
            </div>
        </div>
    </main>

    <?php include "components/footer.php"; ?>
</body>
</html>
