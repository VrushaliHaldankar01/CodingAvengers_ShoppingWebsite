<?php
    require('db_conn.php');

    $error = null;

    if(!empty($_GET['cat_id'])){
        $cat_id = $_GET['cat_id'];
    } else {
        $cat_id = null;
        $error = "<p> Error! category Id not found!</p>";
    }

    if($error == null){
        
        $query = "DELETE FROM categories WHERE cat_id = '$cat_id' ;";
        
        $result = mysqli_query($dbc, $query);
        
        if($result){
            header("Location: category_details.php");
            exit;
        } else {
            echo "</br><p>Some error in Deleting the record</p>";
        }
        
    } else{
        echo "Somethinng went wrong. The error is : $error";
    }
?>