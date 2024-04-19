<?php
require('db_conn.php');

class Category {
    private $dbc;
    private $errors = [];

    public function __construct($dbc) {
        $this->dbc = $dbc;
    }

    

    public function addCategory($catName) {
        $stmt = $this->dbc->prepare("INSERT INTO categories (cat_Name) VALUES (?)");
        $stmt->bind_param('s',$catName);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            

            if (empty($_POST['catName'])) {
                $this->errors[] = "<p>Error: Category Name is required</p>";
            } else {
                $catName = $_POST['catName'];
            }
           

           

            // If there are no validation errors, proceed with database insertion
            if (empty($this->errors)) {
                $result = $this->addCategory($catName);
                if ($result) {
                    header("Location: category_details.php");
                    exit;
                } else {
                    $this->errors[] = "<p>Some error occurred while saving the data.</p>";
                }
            }
        }
    }
}

$category = new Category($dbc);
$category->handleFormSubmission();
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
    <title>Category Page</title>
    <style>
        .error-container {
            margin-bottom: 20px;
            color: red;
        }
        .container {
            margin-top: 50px;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: lightblue; /* Light gray background */
            margin: 0;
            padding: 0;
        }
    </style>
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a class="navbar-brand" href="admindashboard.php">FashionFusion</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="cloth_details.php">Clothes Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admindashboard.php">Add Clothes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin_category.php">Add Category</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="category_details.php">Category Details</a>
                        </li>
                    </ul>
                </div>
            </nav>
    <div class="container">
        <h1 class="text-center">FashionFusion</h1>
     
        <div class="error-container">
            <?php
                foreach($category->getErrors() as $error){
                    echo $error;
                }
            ?>
        </div>

        <form class="form" method="POST" action="" id="registration_form" >
        

            <div class="form-group">
                <label for="catName">Category Name:</label>
                <input type="text" class="form-control" name="catName" id="catName" required>
            </div>
            
            
           
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div>
</body>
</html>
