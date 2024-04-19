<?php
require('db_conn.php');

class ClothDetails {
    private $dbc;
    private $results;

    public function __construct($dbc) {
        $this->dbc = $dbc;
        $this->results = $this->fetchClothes();
    }

    private function fetchClothes() {
        $query = 'SELECT * FROM clothes;';
        return mysqli_query($this->dbc, $query);
    }

    public function displayClothes() {
        $sr_no = 0;
        while ($row = mysqli_fetch_array($this->results, MYSQLI_ASSOC)) {
            $sr_no++;
            $str_to_print = "<tr>";
            $str_to_print .= "<td>$sr_no</td>";
            $str_to_print .= "<td>{$row['ClothName']}</td>";
            $str_to_print .= "<td>{$row['ClothDescription']}</td>";
            // Display image
            $image_data = base64_encode($row['ImgeData']);
            $str_to_print .= "<td><img src='data:image/jpeg;base64,$image_data' alt='Mobile Image' style='width: 100px; height: auto;'></td>";
            $str_to_print .= "<td>{$row['Quantity']}</td>";
            $str_to_print .= "<td>{$row['Price']}</td>";
            $str_to_print .= "<td>{$row['Color']}</td>";
            $str_to_print .= "<td>";
            $str_to_print .= "<a href='edit_cloth_details.php?ClothId={$row['ClothId']}' class='btn btn-primary mr-1'>Edit</a>";
            $str_to_print .= "<a href='delete_cloth_details.php?ClothId={$row['ClothId']}' class='btn btn-danger'>Delete</a>";
            $str_to_print .= "</td></tr>";

            echo $str_to_print;
        }
    }
}

$clothDetails = new ClothDetails($dbc);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <title>Clothes Details</title>
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
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Quantity Available</th>
                    <th>Price</th>
                    <th>Color</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $clothDetails->displayClothes();
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
