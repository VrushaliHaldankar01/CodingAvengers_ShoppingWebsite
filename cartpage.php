<?php
session_start();

// Check if logout is requested
if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: login.php");
    exit;
}
// Check if the cart is not empty and the product key to delete is provided
if (!empty($_SESSION['cart']) && isset($_POST['delete_key'])) {
    $deleteKey = $_POST['delete_key'];

    // Remove the product with the specified key from the cart
    if (isset($_SESSION['cart'][$deleteKey])) {
        unset($_SESSION['cart'][$deleteKey]);
    }
}

// Function to calculate total price of items in the cart
function calculateTotalPrice($cart) {
    $totalPrice = 0;
        if($cart!=null ){
            foreach ($cart as $item) {
                if (isset($item['Price']) && isset($item['Quantity'])) {
                    $totalPrice += $item['Price'] * $item['Quantity'];
                }
                
               
            }
        }
    
    
    return $totalPrice;
}

// Function to update quantity in session
function updateQuantity($key, $quantity) {
    if (isset($_SESSION['cart'][$key])) {
        $_SESSION['cart'][$key]['Quantity'] = $quantity;
    }
}

// Handle quantity update
if (isset($_POST['update_quantity'])) {
    $key = $_POST['key'];
    $quantity = $_POST['quantity'];
    updateQuantity($key, $quantity);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-Adq1dVjRXJ+dtMXlZ0kZuW+3S8lXoA1EEMfcq4rnOGBv3t2hdXwehdSPP5oGbZwEnMK+0AbN53/1Ow1tRUQH2w==" crossorigin="anonymous" />
    <title>Cart</title>
    <style>
          .logout-btn, .login-btn {
            color: white !important;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: black;
  background-image: linear-gradient(#C33764, #1D2671);
  color: wheat;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #343a40; /* Dark gray navbar */
        }
        .navbar-brand {
            color: #ffffff; /* White text */
            font-weight: bold;
            text-decoration: none;
        }
        .container {
            margin-top: 20px;
            height: 650px;
        }
        h1 {
            margin-bottom: 30px;
        }
        table {
            background-color: #ffffff; /* White table background */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .cart-table {
            background-color: #f2f2f2; /* Light gray background for cart table */
        }
        th, td {
            padding: 15px;
            text-align: center;
        }
        th {
            background-color: #343a40; /* Dark gray header */
            color: #ffffff; /* White text */
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa; /* Alternate rows background color */
        }
        .btn {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff; /* Blue button */
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker blue on hover */
            border-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545; /* Red button */
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333; /* Darker red on hover */
            border-color: #c82333;
        }
    </style>
</head>
<body>

<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="userdashboard.php">FashionFusion</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="aboutus.php">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contactus.php">Contact Us</a>
            </li>
        </ul>
     
    </div>


   
</nav> -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="userdashboard.php">FashionFusion</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="aboutus.php">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contactus.php">Contact Us</a>
            </li>
        </ul>
     
    </div>
    <a class="navbar-brand" href="cartpage.php">
    <i class="fas fa-shopping-cart" style="color: white !important;"></i> 
</a>

    <a class="navbar-brand" href="cartpage.php">
        <i class="fas fa-shopping-cart"></i> Cart
    </a>
  
    <form class="form-inline my-2 my-lg-0" method="post">
        <?php if (isset($_SESSION['username'])): ?>
            <button class="btn btn-link my-2 my-sm-0 logout-btn" type="submit" name="logout">Logout</button>
        <?php else: ?>
            <a class="nav-link login-btn" href="login.php">Login</a>
        <?php endif; ?>
    </form>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Shopping Cart</h1>
            <table class="table cart-table"> <!-- Added cart-table class -->
                <thead>
                    <tr>
                        <th scope="col">Item</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if the cart is not empty
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $key => $item) {
                            echo "<tr>";
                            echo "<td>" . (isset($item['ClothName']) ? $item['ClothName'] : 'N/A') . "</td>";
                            echo "<td id='price_$key'>" . (isset($item['Price']) ? $item['Price'] : 'N/A') . "</td>";
                            echo "<td>";
                            echo "<form method='post'>";
                            echo "<input type='hidden' name='key' value='$key'>";
                            echo "<input type='number' name='quantity' value='" . $item['Quantity'] . "' min='1'>";
                            echo "<button type='submit' name='update_quantity' class='btn btn-primary btn-sm'>Update</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "<td>";
                            echo "<form method='post'>";
                            echo "<input type='hidden' name='delete_key' value='$key'>";
                            echo "<button type='submit' class='btn btn-danger btn-sm'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Display a message if the cart is empty
                        echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <!-- Display total price -->
            <table class="table">
                <tbody>
                    <tr>
                        <td colspan="4"><strong>Total Price: <?php echo '$' . number_format(calculateTotalPrice($_SESSION['cart']), 2); ?></strong></td>
                        <?php if (isset($_SESSION['username'])) { ?>
                            <td><a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a></td>
                        <?php } else { ?>
                            <td><a href="login.php" class="btn btn-primary">Login to Checkout</a></td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
