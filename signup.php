<?php
require('db_conn.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    // Validation function for text fields
    function is_text_only($input_value) {
        return preg_match("/^[a-zA-Z- ]*$/", $input_value);
    }

    // Validation function for email
    function is_valid_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // Validation function for phone
    function is_valid_phone($phone) {
        return preg_match("/^[0-9]{10}$/", $phone);
    }

    // Validate username
    if(!empty($_POST['username'])){
        $username = $_POST['username'];
        if (!is_text_only($username)) {
            $errors[] = "<p>Error: Username should contain only alphabets, hyphens, and spaces.</p>";
        }
    }
    else{
        $errors[] = "<p>Error: Username is required</p>";
    }

    // Validate phone
    if(!empty($_POST['phone'])){
        $phone = $_POST['phone'];
        if (!is_valid_phone($phone)) {
            $errors[] = "<p>Error: Invalid phone number format.</p>";
        }
    }
    else{
        $errors[] = "<p>Error: Phone is required</p>";
    }

    // Validate email
    if(!empty($_POST['email'])){
        $email = $_POST['email'];
        if (!is_valid_email($email)) {
            $errors[] = "<p>Error: Invalid email format.</p>";
        }
    }
    else{
        $errors[] = "<p>Error: Email is required</p>";
    }

    // Validate password
    if(!empty($_POST['password'])){
        $password = $_POST['password'];
        // You might want to add password strength validation here
    }
    else{
        $errors[] = "<p>Error: Password is required</p>";
    }

    // If there are no errors, proceed with database insertion
    if(count($errors) == 0){
        // Sanitize input data before insertion
        $username_clean = prepare_string($dbc, $username);
        $phone_clean = prepare_string($dbc, $phone);
        $email_clean = prepare_string($dbc, $email);
       
        $password_clean = prepare_string($dbc, $password);
        
        
        // Prepare and execute the SQL query
        $query = "INSERT INTO user (Username, phone,  Email,Password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, 'ssss', $username_clean, $phone_clean,$email_clean,  $password_clean);
        $result = mysqli_stmt_execute($stmt);
        
        if($result){
            header("Location: login.php");
            exit;
        } else {
            echo "<p>Some error occurred while saving the data.</p>";
        }
    } else {
        foreach($errors as $error){
            echo $error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
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
            color:black;

        }
  </style>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            Sign Up
          </div>
          <div class="card-body">
            <form action="signup.php" method="POST">
              <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
              </div>
              <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
              </div>
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
              </div>
              <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
