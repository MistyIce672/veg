<?php
session_start();
include "connection.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Check if product_id is provided in POST request
if (!isset($_POST["product_id"])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$product_id = $_POST["product_id"];

// Prepare and execute delete query
$query = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $product_id);

if ($stmt->execute()) {
    // Set success message
    $_SESSION["message"] = "Item removed from cart successfully";
    $_SESSION["message_type"] = "success";
} else {
    // Set error message
    $_SESSION["message"] = "Error removing item from cart";
    $_SESSION["message_type"] = "error";
}

$stmt->close();
$conn->close();

// Redirect back to cart page
header("Location: ../cart.php");
exit();
?>
