<?php
session_start();
include "connection.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    $response = [
        "success" => false,
        "message" => "Please login to add items to cart",
    ];
    echo json_encode($response);
    exit();
}

// Check if required parameters are present
if (!isset($_POST["product_id"]) || !isset($_POST["quantity"])) {
    $response = [
        "success" => false,
        "message" => "Invalid request parameters",
    ];
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION["user_id"];
$product_id = $_POST["product_id"];
$quantity = (int) $_POST["quantity"];

// Validate quantity
if ($quantity <= 0) {
    $response = [
        "success" => false,
        "message" => "Quantity must be greater than 0",
    ];
    echo json_encode($response);
    exit();
}

// Check if product exists
$stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $response = [
        "success" => false,
        "message" => "Product not found",
    ];
    echo json_encode($response);
    exit();
}

// Check if item already exists in cart
$stmt = $conn->prepare(
    "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?"
);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing cart item
    $cart_item = $result->fetch_assoc();
    $new_quantity = $cart_item["quantity"] + $quantity;

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_quantity, $cart_item["id"]);
} else {
    // Add new cart item
    $stmt = $conn->prepare(
        "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
}

if ($stmt->execute()) {
    $response = [
        "success" => true,
        "message" => "Item added to cart successfully",
    ];
} else {
    $response = [
        "success" => false,
        "message" => "Error adding item to cart",
    ];
}

echo json_encode($response);
$stmt->close();
$conn->close();
?>
