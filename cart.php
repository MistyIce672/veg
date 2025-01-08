<?php
include "utils/connection.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shopping Cart</title>
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
                Your Shopping Cart
            </h1>
            <p class="mt-3 text-gray-500">
                Review and manage your selected items
            </p>
        </div>

        <!-- Cart Contents -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <?php if (isset($_SESSION["user_id"])) {
                    include "utils/get_user_cart.php";
                } else {
                    echo '<div class="text-center py-8">
                            <p class="text-gray-500">Please login to view your cart</p>
                            <a href="login.php" class="mt-4 inline-block px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                Login
                            </a>
                          </div>';
                } ?>
            </div>
        </div>

        <!-- Cart Summary -->
        <?php if (isset($_SESSION["user_id"]) && isset($total_price)): ?>
        <div class="mt-8 bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Cart Summary</h2>
                        <p class="mt-1 text-sm text-gray-500">Shipping and taxes calculated at checkout</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">Total: $<?php echo number_format(
                            $total_price,
                            2
                        ); ?></p>
                    </div>
                </div>
                <div class="mt-6 mb-4">
                    <a href="checkout.php" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </main>

    <?php include "components/footer.php"; ?>
</body>
</html>
