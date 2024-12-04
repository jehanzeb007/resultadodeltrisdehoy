<?php
include('../includes/config.php');
/*echo '<pre>';
print_r($_FILES);exit;*/
if(isset($_FILES['file']) && !empty($_FILES['file']['name'])){
    $uploaddir = '../images/site_uploads/';
    $uploadfile = time().basename($_FILES['file']['name']);
    if(move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.$uploadfile)){
        echo json_encode(['location'=>'/images/site_uploads/'.$uploadfile]);exit;
    }
}

mysqli_close($con);
?>