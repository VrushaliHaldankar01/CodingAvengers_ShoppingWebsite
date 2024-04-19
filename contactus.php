<?php
// Start session
session_start();

// Check if logout is requested
if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Contact Us</title>
    <style>
        /* Custom CSS to change logout button color */
        .logout-btn ,.login-btn{
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
        #contactForm{
            height:570px;
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


<div class="container mt-5">
    <h1>Contact Us</h1>
    <form method="post" id="contactForm" action="contactus.php">
        <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<!-- Font Awesome CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-KVzmwX2iFu2xK/YzBJCFCb8g/1UzYIUxKpsL8qdMJKvZi3j+FbDqlWeCZP9dQ4Ue8de3MQu7lPuyPwULZk1+9A==" crossorigin="anonymous"></script>
</body>
</html>
