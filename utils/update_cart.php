<?php
session_start();
include "connection.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $product_id = $_POST["product_id"];

    // Handle increase/decrease buttons
    if (isset($_POST["increase"])) {
        $query = "UPDATE cart SET quantity = quantity + 1
                 WHERE user_id = ? AND product_id = ?";
    } elseif (isset($_POST["decrease"])) {
        $query = "UPDATE cart SET quantity = GREATEST(quantity - 1, 1)
                 WHERE user_id = ? AND product_id = ?";
    }
    // Handle direct quantity input
    elseif (isset($_POST["quantity"])) {
        $quantity = max(1, intval($_POST["quantity"])); // Ensure quantity is at least 1
        $query = "UPDATE cart SET quantity = ?
                 WHERE user_id = ? AND product_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();
        $stmt->close();

        header("Location: cart.php");
        exit();
    }

    if (isset($query)) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: ../cart.php");
exit();
?>
