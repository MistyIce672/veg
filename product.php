<?php include "utils/connection.php";

// Assuming you're getting the product ID from URL parameter
$product_id = isset($_GET["id"]) ? $_GET["id"] : 1;

// Fetch product details from database
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $product["name"]; ?> - Fresh Produce Market</title>
    <link rel="stylesheet" href="styling/style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
</head>
<body class="bg-gray-50">
    <?php include "components/navbar.php"; ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
            <!-- Image gallery -->
            <div class="flex flex-col">
                <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                    <div class="w-full aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                        <?php
                        $imagePath = !empty($product["image"])
                            ? $product["image"]
                            : "images/default-product.jpg";
                        $imageFullPath = __DIR__ . "/" . $imagePath;

                        if (file_exists($imageFullPath)) {
                            $imageUrl = $imagePath;
                        } else {
                            $imageUrl = "images/default-product.jpg";
                        }
                        ?>
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>"
                             alt="<?php echo htmlspecialchars(
                                 $product["name"]
                             ); ?>"
                             class="w-full h-full object-center object-cover">
                    </div>
                </div>
            </div>

            <!-- Product info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                    <?php echo $product["name"]; ?>
                </h1>

                <div class="mt-3">
                    <h2 class="sr-only">Product information</h2>
                    <?php if ($product["on_sale"] && $product["sale_price"]): ?>
                        <p class="text-3xl text-gray-900">
                            <span class="text-red-600">LKR<?php echo number_format(
                                $product["sale_price"],
                                2
                            ); ?>/kg</span>
                            <span class="ml-2 text-2xl line-through text-gray-500">
                                LKR<?php echo number_format(
                                    $product["price"],
                                    2
                                ); ?>/kg
                            </span>
                        </p>
                        <p class="mt-1 text-sm text-red-500">Sale!</p>
                    <?php else: ?>
                        <p class="text-3xl text-gray-900">
                            LKR<?php echo number_format(
                                $product["price"],
                                2
                            ); ?>/kg
                        </p>
                    <?php endif; ?>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Description</h3>
                    <div class="text-base text-gray-700 space-y-6">
                        <?php echo $product["description"]; ?>
                    </div>
                </div>

                <div class="mt-6">
                    <!-- Stock Status -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <?php if ($product["in_stock"]): ?>
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            <?php else: ?>
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <p class="ml-2 text-sm text-gray-500">
                            <?php echo $product["in_stock"]
                                ? "In stock"
                                : "Out of stock"; ?>
                        </p>
                    </div>

                    <!-- Add to Cart Form -->
                    <form class="mt-6" id="add-to-cart-form">
                        <div class="flex items-center">
                            <label for="quantity" class="sr-only">Quantity</label>
                            <input type="number"
                                   id="quantity"
                                   name="quantity"
                                   min="1"
                                   value="1"
                                   required
                                   class="shadow-sm rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500 sm:text-sm p-2 w-20">
                            <span class="ml-3">kg</span>
                        </div>

                        <button type="submit"
                                <?php echo !$product["in_stock"]
                                    ? "disabled"
                                    : ""; ?>
                                class="mt-6 w-full bg-green-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 <?php echo !$product[
                                    "in_stock"
                                ]
                                    ? "opacity-50 cursor-not-allowed"
                                    : ""; ?>">
                            <?php echo $product["in_stock"]
                                ? "Add to cart"
                                : "Out of stock"; ?>
                        </button>
                    </form>
                </div>

                <!-- Product Features -->
                <div class="mt-10">
                    <h3 class="text-sm font-medium text-gray-900">Highlights</h3>
                    <div class="mt-4">
                        <ul class="pl-4 list-disc text-sm space-y-2">
                            <li>Freshly harvested</li>
                            <li>Organic certified</li>
                            <li>Locally sourced</li>
                            <li>Premium quality</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Products Section -->
        <div class="mt-24">
            <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">Similar Products</h2>
            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Similar products would be dynamically populated here -->
            </div>
        </div>
    </main>

    <?php include "components/footer.php"; ?>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartForm = document.querySelector('form');

    addToCartForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const quantity = document.getElementById('quantity').value;
        const productId = <?php echo $product_id; ?>;

        // Create form data
        const formData = new FormData();
        formData.append('product_id', productId);
        formData.append('quantity', quantity);

        // Send POST request to add_to_cart.php
        fetch('utils/add_to_cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert(data.message);
            } else {
                // Show error message
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding to cart');
        });
    });
});


</script>
