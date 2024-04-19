
<?php
session_start(); // Start the session

// Include database connection or other required files here

$errors = [];

// Your existing PHP code for signup validation and insertion goes here

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
    <title>About Us - FusionFashion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Custom styles -->
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
        .navbar {
            margin-bottom: 20px; /* Add some space below the navbar */
        }
        .container {
            background-color: white;
            padding: 20px;
            color:black;
            border-radius: 10px;
            height:400px;
        }
        .outercontainer{
            height:650px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="userdashboard.php">FusionFashion</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="aboutus.php">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contactus.php">Contact Us</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" method="post">
            <?php if (isset($_SESSION['username'])): ?>
                <button class="btn btn-link my-2 my-sm-0 logout-btn" type="submit" name="logout">Logout</button>
            <?php else: ?>
                <a class="nav-link login-btn" href="login.php">Login</a>
            <?php endif; ?>
        </form>
    </div>
</nav>
<div class="outercontainer">
<div class="container">
    <h1>Welcome to FusionFashion</h1>
    <p>At FusionFashion, we believe that style is more than just what you wear - it's a reflection of your personality, your lifestyle, and your unique essence. With a passion for fashion and a commitment to quality, we bring you the latest trends, timeless classics, and everything in between.</p>
    <h2>Our Story</h2>
    <p>FusionFashion started as a dream shared by a group of fashion enthusiasts who wanted to create a space where style knows no bounds. Established in [year], we've since grown from a small boutique to a leading online destination for fashion-forward individuals around the globe.</p>
    <h2>Our Mission</h2>
    <p>Our mission at FusionFashion is simple: to inspire confidence, empower self-expression, and celebrate individuality through fashion. We believe that everyone deserves to feel beautiful, confident, and comfortable in what they wear, and we strive to make that a reality for our customers every day.</p>
</div>
            </div>
<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
