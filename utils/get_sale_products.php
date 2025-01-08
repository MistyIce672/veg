<?php
include "connection.php";

// Fetch categories
$category_sql = "SELECT * FROM categories where on_sale = '1'";
$category_result = $conn->query($category_sql);

if ($category_result->num_rows > 0) {
    $category = $category_result->fetch_assoc();
    $products_sql =
        "SELECT * FROM products where category_id = " . $category["id"];
    $products = $conn->query($products_sql);
    if ($products->num_rows > 0) {
        while ($product = $products->fetch_assoc()) {
            echo "<div class='group bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden'>";

            // Wrapper link for the entire card except the button
            echo "<a href='product.php?id=" .
                $product["id"] .
                "' class='block'>";

            echo "<div class='aspect-w-1 aspect-h-1 w-full overflow-hidden'>";
            echo "<img
                    src='images/tomato.png'
                    alt='" .
                $product["name"] .
                "'
                    class='w-full h-48 object-cover object-center group-hover:scale-105 transition-transform duration-200'
                  />";
            echo "</div>";

            echo "<div class='p-4'>";
            echo "<h3 class='text-lg font-medium text-gray-900 group-hover:text-green-600 transition-colors duration-200'>" .
                $product["name"] .
                "</h3>";

            // Price display logic
            if (
                isset($product["on_sale"]) &&
                $product["on_sale"] &&
                isset($product["sale_price"])
            ) {
                // Show both original and sale price when on sale
                echo "<div class='mt-2'>";
                echo "<span class='text-lg font-semibold text-red-600'>LKR " .
                    number_format($product["sale_price"], 2) .
                    "</span>";
                echo "<span class='ml-2 text-sm text-gray-500 line-through'>LKR " .
                    number_format($product["price"], 2) .
                    "</span>";
                echo "</div>";
            } else {
                // Show regular price when not on sale
                echo "<p class='mt-2 text-lg font-semibold text-green-600'>LKR " .
                    number_format($product["price"], 2) .
                    "</p>";
            }

            echo "</div>";
            echo "</a>";

            // Button outside of the link to prevent nested links
            echo "<div class='px-4 pb-4'>";
            echo "<button
                    onclick='addToCart(" .
                $product["id"] .
                ")'
                    class='bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600'
                  >
                    Add to Cart
                  </button>";
            echo "</div>";

            echo "</div>";
        }
    } else {
        echo "<div class='col-span-full text-center py-8 text-gray-500'>No products available in this category</div>";
    }
} else {
    echo "<div class='col-span-full text-center py-8 text-gray-500'>Not a valid category</div>";
}

$conn->close();
?>

<script>
async function addToCart(productId) {
    try {
        const quantity = 1; // Default quantity

        const response = await fetch('utils/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`,
            credentials: 'same-origin'
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            updateCartCount();
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while adding to cart');
    }
}

async function updateCartCount() {
    try {
        const response = await fetch('utils/get_cart_count.php');
        const data = await response.json();

        if (data.success) {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = data.count;
            }
        }
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
}

document.addEventListener('DOMContentLoaded', updateCartCount);
</script>
