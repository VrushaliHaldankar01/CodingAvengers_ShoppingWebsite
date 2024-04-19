<?php
session_start();

// Include FPDF library
require('fpdf/fpdf.php');

// Function to generate PDF invoice
// Function to generate PDF invoice
function generateInvoice($firstname, $lastname, $postalcode, $address, $country, $email, $totalPrice, $cartItems) {
    // Create new PDF instance
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('Arial', '', 12);

    // Output invoice header
    $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
    $pdf->Cell(0, 10, '-----------------------------------------', 0, 1, 'C');
    $pdf->Ln(10);

    // Output shipping information
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Shipping Information:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Name: ' . $firstname . ' ' . $lastname, 0, 1);
    $pdf->Cell(0, 10, 'Postal Code: ' . $postalcode, 0, 1);
    $pdf->Cell(0, 10, 'Address: ' . $address, 0, 1);
    $pdf->Cell(0, 10, 'Country: ' . $country, 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . $email, 0, 1);
    $pdf->Ln(10);

    // Output order summary
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Product Name', 1);
    $pdf->Cell(30, 10, 'Quantity', 1);
    $pdf->Cell(30, 10, 'Price', 1);
    $pdf->Cell(40, 10, 'Total', 1);
    $pdf->Ln();

    // Output cart items
    foreach ($cartItems as $item) {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 10, $item['ClothName'], 1);
        $pdf->Cell(30, 10, $item['Quantity'], 1, 0, 'C');
        $pdf->Cell(30, 10, '$' . $item['Price'], 1, 0, 'C');
        $pdf->Cell(40, 10, '$' . ($item['Quantity'] * $item['Price']), 1, 0, 'C');
        $pdf->Ln();
    }
    $pdf->Ln();

    // Output total price
    $pdf->Cell(100, 10, 'Total Price:', 0, 0, 'R');
    $pdf->Cell(40, 10, '$' . number_format($totalPrice, 2), 0, 1, 'C');

    // Output PDF
    $pdf->Output('invoice.pdf', 'D');
}


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get shipping information
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $postalcode = $_POST['postalcode'];
    $address = $_POST['address'];
    $country = $_POST['country'];
    $email = $_POST['email'];

    // Get cart items from session
    $cartItems = $_SESSION['cart'];

    // Calculate total price
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $totalPrice += $item['Quantity'] * $item['Price'];
    }

    // Generate PDF invoice
    generateInvoice($firstname, $lastname, $postalcode, $address, $country, $email, $totalPrice, $cartItems);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- Bootstrap CSS -->
   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS to change logout button color */
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
        #summary{
            color:wheat;
        }
        #summaryTable{
            color:wheat;
        }
    </style>
</head>
<body>
<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">FashionFusion</a>
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
        <i class="fas fa-shopping-cart"></i> Cart
    </a>
  
    <form class="form-inline my-2 my-lg-0" method="post">
        <?php if (isset($_SESSION['username'])): ?>
            <button class="btn btn-link my-2 my-sm-0 logout-btn" type="submit" name="logout">Logout</button>
        <?php else: ?>
            <a class="nav-link login-btn" href="login.php">Login</a>
        <?php endif; ?>
    </form>
</nav> -->

    <div class="container">
        <h1 class="mt-5">Checkout Page</h1>
        <div class="row mt-3">
            <div class="col-md-8" id="summary">
                <h3>Order Summary</h3>
                <table class="table" id="summaryTable">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalPrice = 0;
                        if (!empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $key => $item) {
                                $productName = isset($item['ClothName']) ? $item['ClothName'] : 'N/A';
                                $quantity = $item['Quantity'];
                                $price = isset($item['Price']) ? $item['Price'] : 0;
                                $total = $quantity * $price;
                                $totalPrice += $total;
                                echo "<tr>";
                                echo "<td>{$productName}</td>";
                                echo "<td>{$quantity}</td>";
                                echo "<td>{$price}</td>";
                                echo "<td>{$total}</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <h3>Shipping Information</h3>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <!-- <form action="payment.php" method="POST"> -->
                <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="postalcode">Postal Code:</label>
                        <input type="text" class="form-control" id="postalcode" name="postalcode" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address Line:</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Country:</label>
                        <input type="text" class="form-control" id="country" name="country" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Proceed to Payment</button>
             
                </form>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <h3>Total Price: $<?php echo number_format($totalPrice, 2); ?></h3>
            </div>
        </div>
    </div>
</body>
</html>
