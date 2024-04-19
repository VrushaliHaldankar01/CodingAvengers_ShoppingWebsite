<?php
require('db_conn.php');

class ClothStore {
    private $dbc;
    private $errors = [];

    public function __construct($dbc) {
        $this->dbc = $dbc;
    }

    public function isValidImage($file) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        return in_array($file['type'], $allowed_types);
    }

    public function addCloth($cat_id,$ClothName, $ClothDescription, $Quantity, $Price, $Color, $image_data) {
        $stmt = $this->dbc->prepare("INSERT INTO clothes (cat_id,ClothName, ClothDescription, Quantity, Price, Color, ImgeData) VALUES (?,?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssss',$cat_id, $ClothName, $ClothDescription, $Quantity, $Price, $Color, $image_data);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_FILES['image']['name'])) {
                if (!$this->isValidImage($_FILES['image'])) {
                    $this->errors[] = "<p>Error: Invalid image format. Only JPEG, PNG, and GIF images are allowed.</p>";
                } else {
                    $image_data = file_get_contents($_FILES['image']['tmp_name']);
                }
            } else {
                $this->errors[] = "<p>Error: Image is required</p>";
            }

            if (empty($_POST['ClothName'])) {
                $this->errors[] = "<p>Error: Cloth Name is required</p>";
            } else {
                $ClothName = $_POST['ClothName'];
            }
            if (empty($_POST['cat_id'])) {
                $this->errors[] = "<p>Error: Catgrories required</p>";
            } else {
                $cat_id= $_POST['cat_id'];
            }
            if (empty($_POST['ClothDescription'])) {
                $this->errors[] = "<p>Error: Cloth description is required</p>";
            } else {
                $ClothDescription = $_POST['ClothDescription'];
            }

            if (empty($_POST['Quantity']) || !is_numeric($_POST['Quantity']) || $_POST['Quantity'] <= 0) {
                $this->errors[] = "<p>Error: Quantity should be a positive number</p>";
            } else {
                $Quantity = $_POST['Quantity'];
            }

            if (empty($_POST['Price']) || !is_numeric($_POST['Price']) || $_POST['Price'] <= 0) {
                $this->errors[] = "<p>Error: Price should be a positive number</p>";
            } else {
                $Price = $_POST['Price'];
            }

            if (empty($_POST['Color'])) {
                $this->errors[] = "<p>Error: Color is required</p>";
            } else {
                $Color = $_POST['Color'];
            }

            // If there are no validation errors, proceed with database insertion
            if (empty($this->errors)) {
                $result = $this->addCloth($cat_id,$ClothName, $ClothDescription, $Quantity, $Price, $Color, $image_data);
                if ($result) {
                    header("Location: cloth_details.php");
                    exit;
                } else {
                    $this->errors[] = "<p>Some error occurred while saving the data.</p>";
                }
            }
        }
    }
}

$clothStore = new ClothStore($dbc);
$clothStore->handleFormSubmission();
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
    <title>Product Page</title>
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
                foreach($clothStore->getErrors() as $error){
                    echo $error;
                }
            ?>
        </div>

        <form class="form" method="POST" action="" id="registration_form" enctype="multipart/form-data">
        <div class="form-group">
    <label for="category">Category:</label>
    
    <select class="form-control" id="category" name="cat_id" required>
        <option value="">Select Category</option>
        <!-- <option value="1">Men</option>
        <option value="2">Women</option> -->
       
                    
                        <?php
                
                $categoryQuery = "SELECT * FROM categories";
                $categoryResult = mysqli_query($dbc, $categoryQuery);

               
                while ($category = mysqli_fetch_assoc($categoryResult)) {
                    echo '<option value="' . $category['cat_id'] . '">' . $category['cat_Name'] . '</option>';
                }
                ?>
                    </select>
                
        

</div>

            <div class="form-group">
                <label for="ClothName">Cloth Name:</label>
                <input type="text" class="form-control" name="ClothName" id="ClothName" required>
            </div>
            <div class="form-group">
                <label for="ClothDescription">Cloth Description:</label>
                <input type="text" class="form-control" name="ClothDescription" id="ClothDescription" required>
            </div>     
            <div class="form-group">
                <label for="Quantity">Quantity:</label>
                <input type="number" class="form-control" name="Quantity" id="Quantity" required>
            </div>
            <div class="form-group">
                <label for="Price">Price:</label>
                <input type="number" step="0.01" class="form-control" id="Price" name="Price" required>
            </div>
            <div class="form-group">
                <label for="Color">Color:</label>
                <input type="text" class="form-control" id="Color" name="Color" required>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control-file" id="image" name="image" required>
            </div>
            <button class="btn btn-primary" type="submit">Save</button>
        </form>
    </div>
</body>
</html>
