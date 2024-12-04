<?php
$REQUEST_URI = explode('/',$_SERVER['REQUEST_URI']);
//echo '<pre>';print_r($REQUEST_URI);exit;
if(isset($REQUEST_URI['2']) && !empty($REQUEST_URI['2']) && !validateDate($REQUEST_URI['2'])){
    include ('prediction-detail.php');
}else{
    include ('predictions.php');
}