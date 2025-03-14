<?php
include "utils/connection.php";

// Check if user is logged in
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit();
}

// Get existing user details if any
$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT * FROM shipping_details WHERE account_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$shipping_result = $stmt->get_result();
$existing_shipping = $shipping_result->fetch_assoc();

// Get cart items and total
$cart_query = "SELECT c.*, p.name, p.price, p.image, p.on_sale, p.sale_price
               FROM cart c
               JOIN products p ON c.product_id = p.id
               WHERE c.user_id = ?";
$cart_stmt = $conn->prepare($cart_query);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();
$cart_items = [];
$total_price = 0;

while ($item = $cart_result->fetch_assoc()) {
    $cart_items[] = $item;
    $effective_price = $item["on_sale"] ? $item["sale_price"] : $item["price"];
    $total_price += $effective_price * $item["quantity"];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_order"])) {
    // Get form data`
    $full_name = $_POST["full_name"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $postal_code = $_POST["postal_code"];
    $phone = $_POST["phone"];

    // Save or update shipping details
    if ($existing_shipping) {
        $stmt = $conn->prepare(
            "UPDATE shipping_details SET full_name=?, address=?, city=?, postal_code=?, phone=? WHERE account_id=?"
        );
        $stmt->bind_param(
            "sssssi",
            $full_name,
            $address,
            $city,
            $postal_code,
            $phone,
            $user_id
        );
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO shipping_details (account_id, full_name, address, city, postal_code, phone) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "isssss",
            $user_id,
            $full_name,
            $address,
            $city,
            $postal_code,
            $phone
        );
    }

    if ($stmt->execute()) {
        $conn->begin_transaction();

        try {
            // Create order record
            $order_sql = "INSERT INTO orders (account_id, full_name, address, city, postal_code, phone, total_amount)
                             VALUES (?, ?, ?, ?, ?, ?, ?)";
            $order_stmt = $conn->prepare($order_sql);
            $order_stmt->bind_param(
                "isssssd",
                $user_id,
                $full_name,
                $address,
                $city,
                $postal_code,
                $phone,
                $total_price
            );
            $order_stmt->execute();
            $order_id = $conn->insert_id;

            // Insert order items
            $item_sql =
                "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $item_stmt = $conn->prepare($item_sql);

            foreach ($cart_items as $item) {
                $effective_price = $item["on_sale"]
                    ? $item["sale_price"]
                    : $item["price"];
                $item_stmt->bind_param(
                    "iiid",
                    $order_id,
                    $item["product_id"],
                    $item["quantity"],
                    $effective_price
                );
                $item_stmt->execute();
            }

            // Clear cart
            $clear_cart = "DELETE FROM cart WHERE user_id = ?";
            $clear_stmt = $conn->prepare($clear_cart);
            $clear_stmt->bind_param("i", $user_id);
            $clear_stmt->execute();

            // Commit transaction
            $conn->commit();

            // Store order ID in session for confirmation page
            $_SESSION["last_order_id"] = $order_id;

            header("Location: order_confirmation.php");
            exit();
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            // Handle error (redirect to error page or show message)
            $_SESSION["error"] = "Order processing failed. Please try again.";
            header("Location: checkout.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="styling/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-50">
    <?php include "components/navbar.php"; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Checkout
            </h1>
            <p class="mt-3 text-gray-500">
                Complete your order in two simple steps
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <form method="POST" class="flex flex-col lg:flex-row gap-8 w-full">
                <!-- Shipping Details Section -->
                <div class="flex-1">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-xl font-semibold mb-4">Step 1: Shipping Details</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="full_name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    value="<?php echo $existing_shipping[
                                        "full_name"
                                    ] ?? ""; ?>">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="address" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    value="<?php echo $existing_shipping[
                                        "address"
                                    ] ?? ""; ?>">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" name="city" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        value="<?php echo $existing_shipping[
                                            "city"
                                        ] ?? ""; ?>">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Postal Code</label>
                                    <input type="text" name="postal_code" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        value="<?php echo $existing_shipping[
                                            "postal_code"
                                        ] ?? ""; ?>">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="tel" name="phone" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    value="<?php echo $existing_shipping[
                                        "phone"
                                    ] ?? ""; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1">
                    <!-- Step 2: Card Details -->
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Step 2: Payment Details</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Card Holder Name</label>
                                <input type="text" name="card_holder" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Card Number</label>
                                <input type="text" name="card_number" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    placeholder="**** **** **** ****">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                    <input type="text" name="expiry_date" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        placeholder="MM/YY">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">CVV</label>
                                    <input type="text" name="cvv" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                        placeholder="***">
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Order Summary Section -->
                <div class="flex-1">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-xl font-semibold mb-4">Step 2: Order Summary</h2>

                        <?php if (count($cart_items) > 0) { ?>
                            <div class="space-y-4">
                                <?php foreach ($cart_items as $item) { ?>
                                    <div class="flex justify-between items-center pb-4 border-b">
                                        <div>
                                            <h3 class="text-sm font-medium"><?php echo $item[
                                                "name"
                                            ]; ?></h3>
                                            <p class="text-sm text-gray-500">Quantity: <?php echo $item[
                                                "quantity"
                                            ]; ?></p>
                                            <?php if ($item["on_sale"]): ?>
                                                <p class="text-sm text-red-600">
                                                    LKR<?php echo number_format(
                                                        $item["sale_price"],
                                                        2
                                                    ); ?>/kg
                                                    <span class="line-through text-gray-500 ml-2">
                                                        LKR<?php echo number_format(
                                                            $item["price"],
                                                            2
                                                        ); ?>/kg
                                                    </span>
                                                </p>
                                            <?php else: ?>
                                                <p class="text-sm text-gray-500">
                                                    LKR<?php echo number_format(
                                                        $item["price"],
                                                        2
                                                    ); ?>/kg
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-sm font-medium">
                                            LKR<?php
                                            $effective_price = $item["on_sale"]
                                                ? $item["sale_price"]
                                                : $item["price"];
                                            echo number_format(
                                                $effective_price *
                                                    $item["quantity"],
                                                2
                                            );
                                            ?>
                                        </p>
                                    </div>
                                <?php } ?>

                                <div class="border-t pt-4 mt-4">
                                    <div class="flex justify-between">
                                        <p class="text-base font-medium">Total</p>
                                        <p class="text-base font-medium">LKR<?php echo number_format(
                                            $total_price,
                                            2
                                        ); ?></p>
                                    </div>
                                </div>

                                <button type="submit" name="submit_order"
                                        class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700">
                                    Place Order
                                </button>
                            </div>
                        <?php } else { ?>
                            <p class="text-gray-500">Your cart is empty</p>
                            <a href="index.php" class="mt-4 inline-block text-green-600 hover:text-green-700">
                                Continue Shopping
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php include "components/footer.php"; ?>
</body>
</html>
