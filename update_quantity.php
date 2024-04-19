<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['key']) && isset($_POST['quantity'])) {
        $key = $_POST['key'];
        $quantity = $_POST['quantity'];

        // Update the quantity in the session cart
        if (isset($_SESSION['cart'][$key])) {
            $_SESSION['cart'][$key]['Quantity'] = $quantity;
        }

        // Calculate and return the new price based on the updated quantity
        echo calculatePrice($_SESSION['cart'][$key]['Price'], $quantity);
    }
}

// Function to calculate price based on quantity
function calculatePrice($price, $quantity) {
    return '$' . number_format($price * $quantity, 2);
}
?>
