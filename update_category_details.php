<?php
require('db_conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // cat_id
    if (!empty($_POST['cat_id'])) {
        $cat_id = $_POST['cat_id'];
    } else {
        $errors[] = "<p>Error: Category ID is required</p>";
    }

    // cat_Name
    if (!empty($_POST['cat_Name'])) {
        $cat_Name = $_POST['cat_Name'];
    } else {
        $errors[] = "<p>Error: category Name is required</p>";
    }

   


    if (empty($errors)) {
        $cat_Name_clean = prepare_string($dbc, $cat_Name);
        $cat_id_clean = prepare_string($dbc, $cat_id);

        // Update query
        $query = "UPDATE categories SET cat_Name = ?";
        $types = "s";
        $params = [$cat_Name_clean];

        $query .= " WHERE cat_id = ?";
        $types .= "i";
        $params[] = $cat_id_clean;

        // Prepare and bind parameters
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$params);

        // Execute query
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            header("Location: category_details.php");
            exit;
        } else {
            echo "<p>Some error occurred while saving the data.</p>";
        }
    } else {
        foreach ($errors as $error) {
            echo $error;
        }
    }
}
?>
