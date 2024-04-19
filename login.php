<?php
// Start session
session_start();

// Include database connection
require('db_conn.php');

$errors = [];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to fetch user details from database
    $query = "SELECT * FROM user WHERE Username = ? AND Password = ?";
    $stmt = mysqli_prepare($dbc, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if user exists
    if (mysqli_num_rows($result) == 1) {
        // Fetch the row
        $row = mysqli_fetch_assoc($result);

        // Store user details in session variables
        $_SESSION['username'] = $row['Username'];
        $_SESSION['userType'] = $row['UserType'];

        // Set cookie if "Remember Me" is checked
        if (isset($_POST['remember_me'])) {
            $cookie_value = base64_encode($username . '|' . md5($password)); // You might want to use a more secure way to generate the token
            setcookie('remember_me_cookie', $cookie_value, time() + (86400 * 30), '/'); // 30 days
        }

        // Redirect based on UserType
        if ($_SESSION['userType'] == 'admin') {
            header("Location: admindashboard.php");
            exit;
        } elseif ($_SESSION['userType'] == 'user') {
            header("Location: userdashboard.php");
            exit;
        }
    } else {
        // User does not exist, display error message
        $errors[] = "Invalid username or password";
    }
}

// Check if logout is requested
if (isset($_POST['logout'])) {
    // Destroy the session
    session_destroy();

    // Delete the remember me cookie
    setcookie('remember_me_cookie', '', time() - 3600, '/');

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
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       
        body {
            font-family: Arial, sans-serif;
            background-color: black;
  background-image: linear-gradient(#C33764, #1D2671);
  color: wheat;
            margin: 0;
            padding: 0;
        }
        .container{
            height:730px;
        }
        .card {
            color:black;
            margin-top: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Add shadow effect */
        }

        .card-header {
            background-color: #007bff; /* Set header background color */
            color: white; /* Set header text color */
        }

        .btn-primary {
            background-color: #007bff; /* Set button background color */
            border-color: #007bff; /* Set button border color */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Set button hover background color */
            border-color: #0056b3; /* Set button hover border color */
        }

        .btn-link {
            color: #007bff; /* Set link color */
        }

        .btn-link:hover {
            color: #0056b3; /* Set link hover color */
            text-decoration: none; /* Remove underline on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Login
                    </div>
                    <div class="card-body">
                        <?php foreach($errors as $error) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php } ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                                <label class="form-check-label" for="remember_me">Remember Me</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                        <p>Don't have an account? <a href="signup.php" class="btn-link">Sign up here</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
