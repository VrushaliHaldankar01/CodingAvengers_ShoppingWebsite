<?php
require('db_conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // ClothId
    if (!empty($_POST['ClothId'])) {
        $ClothId = $_POST['ClothId'];
    } else {
        $errors[] = "<p>Error: Cloth ID is required</p>";
    }

    // ClothName
    if (!empty($_POST['ClothName'])) {
        $ClothName = $_POST['ClothName'];
    } else {
        $errors[] = "<p>Error: Cloth Name is required</p>";
    }

    // Cloth Description
    if (!empty($_POST['ClothDescription'])) {
        $ClothDescription = $_POST['ClothDescription'];
    } else {
        $errors[] = "<p>Error: Cloth Description is required</p>";
    }

    // Quantity validation
    if (!empty($_POST['Quantity'])) {
        $Quantity = $_POST['Quantity'];
        if (!is_numeric($Quantity) || $Quantity <= 0) {
            $errors[] = "<p>Error: Quantity should be a positive number</p>";
        }
    } else {
        $errors[] = "<p>Error: Quantity is required</p>";
    }

    // Price validation
    if (!empty($_POST['Price'])) {
        $Price = $_POST['Price'];
        if (!is_numeric($Price) || $Price <= 0) {
            $errors[] = "<p>Error: Price should be a positive number</p>";
        }
    } else {
        $errors[] = "<p>Error: Price is required</p>";
    }

    // Color validation
    if (!empty($_POST['Color'])) {
        $Color = $_POST['Color'];
    } else {
        $errors[] = "<p>Error: Color is required</p>";
    }

    // Check if new image is uploaded
    if (isset($_FILES['Image']) && $_FILES['Image']['error'] === UPLOAD_ERR_OK) {
        $ImageData = file_get_contents($_FILES['Image']['tmp_name']);
        if ($ImageData === false) {
            $errors[] = "<p>Error: Failed to read uploaded file</p>";
        } else {
            echo "<p>File uploaded successfully</p>";
            echo "<p>Image data size: " . strlen($ImageData) . " bytes</p>";
        }
    } else {
        // If no new image is uploaded, retain the existing image
        $ImageData = null;
    }

    if (empty($errors)) {
        $ClothName_clean = prepare_string($dbc, $ClothName);
        $ClothDescription_clean = prepare_string($dbc, $ClothDescription);
        $Quantity_clean = prepare_string($dbc, $Quantity);
        $Price_clean = prepare_string($dbc, $Price);
        $Color_clean = prepare_string($dbc, $Color);
        $ClothId_clean = prepare_string($dbc, $ClothId);

        // Update query
        $query = "UPDATE clothes SET ClothName = ?, ClothDescription = ?, Quantity = ?, Price = ?, Color = ?";
        $types = "ssdss";
        $params = [$ClothName_clean, $ClothDescription_clean, $Quantity_clean, $Price_clean, $Color_clean];

        // If new image is uploaded, update image data in the query
        if ($ImageData !== null) {
            $query .= ", ImgeData = ?";
            $types .= "b";
            $params[] = $ImageData;
        }

        $query .= " WHERE ClothId = ?";
        $types .= "i";
        $params[] = $ClothId_clean;

        // Prepare and bind parameters
        $stmt = mysqli_prepare($dbc, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$params);

        // Execute query
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            header("Location: cloth_details.php");
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
