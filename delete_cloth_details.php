<?php
    require('db_conn.php');

    $error = null;

    if(!empty($_GET['ClothId'])){
        $ClothId = $_GET['ClothId'];
    } else {
        $ClothId = null;
        $error = "<p> Error! Cloth Id not found!</p>";
    }

    if($error == null){
        
        $query = "DELETE FROM clothes WHERE ClothId = '$ClothId' ;";
        
        $result = mysqli_query($dbc, $query);
        
        if($result){
            header("Location: cloth_details.php");
            exit;
        } else {
            echo "</br><p>Some error in Deleting the record</p>";
        }
        
    } else{
        echo "Somethinng went wrong. The error is : $error";
    }