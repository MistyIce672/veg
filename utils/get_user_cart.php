<?php
if (!isset($_SESSION)) {
    session_start();
}

$user_id = $_SESSION["user_id"];
$total_price = 0;

// Get cart items for the user including sale price information
$query = "SELECT c.*, p.name, p.price, p.image, p.on_sale, p.sale_price
          FROM cart c
          JOIN products p ON c.product_id = p.id
          WHERE c.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<div class="space-y-4">';

    while ($row = $result->fetch_assoc()) {

        // Calculate price based on whether item is on sale
        $effective_price = $row["on_sale"] ? $row["sale_price"] : $row["price"];
        $subtotal = $row["quantity"] * $effective_price;
        $total_price += $subtotal;
        ?>
        <div class="flex items-center space-x-4 py-4 border-b">
            <img src="<?php echo $row["image"]; ?>" alt="<?php echo $row[
    "name"
]; ?>"
                 class="w-24 h-24 object-cover rounded-md">

            <div class="flex-1">
                <h3 class="text-lg font-medium text-gray-900"><?php echo $row[
                    "name"
                ]; ?></h3>

                <!-- Price display with sale information -->
                <?php if ($row["on_sale"]): ?>
                    <p class="text-gray-500">
                        <span class="text-red-600">LKR<?php echo number_format(
                            $row["sale_price"],
                            2
                        ); ?>/kg</span>
                        <span class="line-through ml-2">LKR<?php echo number_format(
                            $row["price"],
                            2
                        ); ?>/kg</span>
                    </p>
                <?php else: ?>
                    <p class="text-gray-500">LKR<?php echo number_format(
                        $row["price"],
                        2
                    ); ?>/kg</p>
                <?php endif; ?>

                <div class="flex items-center mt-2">
                    <form action="utils/update_cart.php" method="POST" class="flex items-center">
                        <input type="hidden" name="product_id" value="<?php echo $row[
                            "product_id"
                        ]; ?>">
                        <button type="submit" name="decrease" class="px-2 py-1 border rounded-l">-</button>
                        <input type="number" name="quantity" value="<?php echo $row[
                            "quantity"
                        ]; ?>"
                               class="w-16 text-center border-t border-b" min="1">
                        <button type="submit" name="increase" class="px-2 py-1 border rounded-r">+</button>
                    </form>
                </div>
            </div>

            <div class="text-right">
                <p class="text-lg font-medium text-gray-900">
                    LKR<?php echo number_format($subtotal, 2); ?>
                </p>
                <form action="utils/remove_from_cart.php" method="POST" class="mt-2">
                    <input type="hidden" name="product_id" value="<?php echo $row[
                        "product_id"
                    ]; ?>">
                    <button type="submit" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </form>
            </div>
        </div>
        <?php
    }

    echo "</div>";
} else {
    echo '<div class="text-center py-8">
            <p class="text-gray-500 mb-2">Your cart is empty</p>
            <a href="index.php" class="mt-4 inline-block px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                Continue Shopping
            </a>
          </div>';
}

$stmt->close();
?>
