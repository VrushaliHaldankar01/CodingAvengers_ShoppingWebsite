<?php
require('db_conn.php');

class ClothDetailsUpdater {
    private $dbc;
    private $ClothId;
    private $ClothName;
    private $ClothDescription;
    private $Price;
    private $Quantity;
    private $Color;
    private $ImageData;
    private $error;

    public function __construct($dbc) {
        $this->dbc = $dbc;
        $this->error = null;
        $this->fetchClothDetails();
    }

    private function fetchClothDetails() {
        if (!empty($_GET['ClothId'])) {
            $this->ClothId = $_GET['ClothId'];
        } else {
            $this->ClothId = null;
            $this->error = "<p>Error! Cloth ID not found.</p>";
            return;
        }

        $query = "SELECT * FROM clothes WHERE ClothId = ?;";
        $stmt = mysqli_prepare($this->dbc, $query);
        mysqli_stmt_bind_param($stmt, 'i', $this->ClothId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $this->ClothName = $row['ClothName'];
            $this->ClothDescription = $row['ClothDescription'];
            $this->Price = $row['Price'];
            $this->Quantity = $row['Quantity'];
            $this->Color = $row['Color'];
            // For Image data retrieval, you may need to change the column name to match your database structure
           // $this->ImageData = $row['ImgeData'];
        } else {
            $this->error = "<p>Error! Incorrect entry in the database.</p>";
        }
    }

    public function displayForm() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="stylesheet" href="styles.css">
            <title>Updation Form</title>
            <style>
        
        body {
                font-family: Arial, sans-serif;
                background-color: lightblue; /* Light gray background */
                margin: 0;
                padding: 0;
            }
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
            <form class="mt-4" action="update_cloth_details.php" method="post" id="update_details_form" enctype="multipart/form-data">
                <h2 class="mb-4">Update Cloth Details</h2>
                <?php if ($this->error != null): ?>
                    <div class="alert alert-danger"><?php echo $this->error; ?></div>
                <?php endif; ?>
                <input type="hidden" id="ClothId" name="ClothId" value="<?php echo $this->ClothId; ?>">
                <div class="form-group">
                    <label for="ClothName">Cloth Name:</label>
                    <input type="text" class="form-control" name="ClothName" id="ClothName" value="<?php echo $this->ClothName; ?>">
                </div>
                <div class="form-group">
                    <label for="ClothDescription">Cloth Description:</label>
                    <input type="text" class="form-control" name="ClothDescription" id="ClothDescription" value="<?php echo $this->ClothDescription; ?>">
                </div>
                <div class="form-group">
                    <label for="Quantity">Quantity:</label>
                    <input type="number" class="form-control" name="Quantity" id="Quantity" value="<?php echo $this->Quantity; ?>">
                </div>
                <div class="form-group">
                    <label for="Price">Price:</label>
                    <input type="number" step="0.01" class="form-control" id="Price" name="Price" value="<?php echo $this->Price; ?>">
                </div>
                <div class="form-group">
                    <label for="Color">Color:</label>
                    <input type="text" class="form-control" id="Color" name="Color" value="<?php echo $this->Color; ?>">
                </div>
                <button class="btn btn-primary" type="submit">Update</button>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        </body>
        </html>
        <?php
    }
}

$clothDetailsUpdater = new ClothDetailsUpdater($dbc);
$clothDetailsUpdater->displayForm();
?>
