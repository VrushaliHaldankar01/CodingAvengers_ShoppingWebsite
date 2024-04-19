<?php
require('db_conn.php');

class CategoryDetails {
    private $dbc;
    private $results;

    public function __construct($dbc) {
        $this->dbc = $dbc;
        $this->results = $this->fetchCategories();
    }

    private function fetchCategories() {
        $query = 'SELECT * FROM categories;';
        return mysqli_query($this->dbc, $query);
    }

    public function displayCategories() {
        $sr_no = 0;
        while ($row = mysqli_fetch_array($this->results, MYSQLI_ASSOC)) {
            $sr_no++;
            $str_to_print = "<tr>";
            $str_to_print .= "<td>$sr_no</td>";
            $str_to_print .= "<td>{$row['cat_Name']}</td>";
           
            $str_to_print .= "<td>";
            $str_to_print .= "<a href='edit_category_details.php?cat_id={$row['cat_id']}' class='btn btn-primary mr-1'>Edit</a>";
            $str_to_print .= "<a href='delete_category_details.php?cat_id={$row['cat_id']}' class='btn btn-danger'>Delete</a>";
            $str_to_print .= "</td></tr>";

            echo $str_to_print;
        }
    }
}

$categoryDetails = new CategoryDetails($dbc);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Category Details</title>
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
        <a class="navbar-brand" href="admindashboard.php">Cloth Store</a>
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
    <br>
    <div class="container">
        <table class="table">
            <thead class="thead-dark">
                <tr align="left">
                    <th>Sr. No.</th>
                    <th>Category Name</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                $categoryDetails->displayCategories();
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
