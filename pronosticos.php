<?php
$REQUEST_URI = explode('/',$_SERVER['REQUEST_URI']);
//echo '<pre>';print_r($REQUEST_URI);exit;
if(isset($REQUEST_URI['3']) && !empty($REQUEST_URI['3']) && validateDateWithFormatedDate($REQUEST_URI['3'],'d-F-Y')){
    include ('prediction-detail.php');
} else {
    include ('predictions-post.php');
}