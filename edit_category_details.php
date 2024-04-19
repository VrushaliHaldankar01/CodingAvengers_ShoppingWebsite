<?php
require('db_conn.php');

class CategoryDetailsUpdater {
    private $dbc;
    private $cat_id;
    private $cat_Name;
   
    private $error;

    public function __construct($dbc) {
        $this->dbc = $dbc;
        $this->error = null;
        $this->fetchCategoryDetails();
    }

    private function fetchCategoryDetails() {
        if (!empty($_GET['cat_id'])) {
            $this->cat_id = $_GET['cat_id'];
        } else {
            $this->cat_id = null;
            $this->error = "<p>Error! Category ID not found.</p>";
            return;
        }

        $query = "SELECT * FROM categories WHERE cat_id = ?;";
        $stmt = mysqli_prepare($this->dbc, $query);
        mysqli_stmt_bind_param($stmt, 'i', $this->cat_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $this->cat_Name = $row['cat_Name'];
           
            
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
            <form class="mt-4" action="update_category_details.php" method="post" id="update_details_form" >
                <h2 class="mb-4">Update Category Details</h2>
                <?php if ($this->error != null): ?>
                    <div class="alert alert-danger"><?php echo $this->error; ?></div>
                <?php endif; ?>
                <input type="hidden" id="cat_id" name="cat_id" value="<?php echo $this->cat_id; ?>">
                <div class="form-group">
                    <label for="cat_Name">Category Name:</label>
                    <input type="text" class="form-control" name="cat_Name" id="cat_Name" value="<?php echo $this->cat_Name; ?>">
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

$categoryDetailsUpdater = new CategoryDetailsUpdater($dbc);
$categoryDetailsUpdater->displayForm();
?>
