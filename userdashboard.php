<?php
// Start session
session_start();

// Initialize cart session variable if it's not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

require('db_conn.php'); // Include your database connection file

// Function to fetch current date from an API
function getCurrentDate() {
    $url = "http://worldtimeapi.org/api/ip"; // API endpoint to fetch current date
    $response = file_get_contents($url);

    if ($response) {
        $data = json_decode($response, true);
        return date('Y-m-d', strtotime($data['utc_datetime'])); // Extract the date from the API response
    } else {
        return date('Y-m-d'); // Return current date using PHP if API fails
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_to_cart'])) {
        // Retrieve product details from the database based on the submitted product ID
        $ClothId = $_POST['product_id']; // Assign the submitted product ID to $ToyId
        $query = "SELECT * FROM clothes WHERE ClothId = $ClothId"; // 
        $result = mysqli_query($dbc, $query);
        $product = mysqli_fetch_assoc($result);

        // Check if the product exists
        if ($product) {
            // Check if the product is already in the cart
            $itemExists = false;
            foreach ($_SESSION['cart'] as $item) {
                if ($item['ClothName'] === $product['ClothName']) {
                    $itemExists = true;
                    break;
                }
            }

            if ($itemExists) {
                echo '<script>alert("Item already exists in the cart.");</script>'; // Display alert
            } else {
                // Add product to the cart session variable
                $_SESSION['cart'][] = array(
                    'ClothName' => $product['ClothName'],
                    'Price' => $product['Price'],
                    'Quantity' => 1 // You can set the quantity as needed
                );
                // Store cart details in a cookie
                setcookie('cart', json_encode($_SESSION['cart']), time() + (86400 * 30), "/"); // Cookie expires in 30 days
                echo '<script>alert("Product added to cart.");</script>'; // Display alert
            }
        } else {
            echo '<script>alert("Product not found.");</script>'; // Display alert
        }
    } elseif (isset($_POST['logout'])) {
        // Destroy the session
        session_destroy();

        // Redirect to login page or any other page
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-Adq1dVjRXJ+dtMXlZ0kZuW+3S8lXoA1EEMfcq4rnOGBv3t2hdXwehdSPP5oGbZwEnMK+0AbN53/1Ow1tRUQH2w==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-Adq1dVjRXJ+dtMXlZ0kZuW+3S8lXoA1EEMfcq4rnOGBv3t2hdXwehdSPP5oGbZwEnMK+0AbN53/1Ow1tRUQH2w==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-KVzmwX2iFu2xK/YzBJCFCb8g/1UzYIUxKpsL8qdMJKvZi3j+FbDqlWeCZP9dQ4Ue8de3MQu7lPuyPwULZk1+9A==" crossorigin="anonymous"></script>

    <title>Product Page</title>
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
        .card{
            color: black;
        }
    </style>
</head>
<body>

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
<br><br>
<div class="container">
<div class="row">
        <div class="col-md-6">
            <form action="" method="post">
                <div class="form-group">
                    <label for="category">Select Category:</label>
                    <select class="form-control" id="category" name="category">
                        <option value="">All</option>
                    
                        <?php
                
                $categoryQuery = "SELECT * FROM categories";
                $categoryResult = mysqli_query($dbc, $categoryQuery);

               
                while ($category = mysqli_fetch_assoc($categoryResult)) {
                    echo '<option value="' . $category['cat_id'] . '">' . $category['cat_Name'] . '</option>';
                }
                ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
 

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Current Date</h5>
                    <p class="card-text"><?php echo getCurrentDate(); ?></p> <!-- Displaying current date fetched from API -->
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <?php
        // Fetch products from the database based on category
        if (!empty($_POST['category'])) {
            $categoryId = $_POST['category'];
            $query = "SELECT * FROM clothes WHERE cat_id = $categoryId";
        } else {
            $query = "SELECT * FROM clothes";
        }

        $results = mysqli_query($dbc, $query);

        while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
            echo '<div class="col-md-4 mb-4">';
            echo '<div class="card">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($row['ImgeData']) . '" class="card-img-top" alt="Cloth Image" style="height: 400px; object-fit: cover;">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $row['ClothName'] . '</h5>';
            echo '<p class="card-text">' . $row['ClothDescription'] . '</p>';
            echo '<p class="card-text">Quantity Available: ' . $row['Quantity'] . '</p>';
            echo '<p class="card-text">Price: ' . $row['Price'] . '</p>';
            // Add to Cart button
            echo '<form method="post">';
            echo '<input type="hidden" name="product_id" value="' . $row['ClothId'] . '">';
            echo '<button type="submit" name="add_to_cart" class="btn btn-primary btn-block">Add to Cart</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
       
    </div>
</div>


<!-- Font Awesome CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-KVzmwX2iFu2xK/YzBJCFCb8g/1UzYIUxKpsL8qdMJKvZi3j+FbDqlWeCZP9dQ4Ue8de3MQu7lPuyPwULZk1+9A==" crossorigin="anonymous"></script>
</body>
</html>
