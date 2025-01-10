<?php
$REQUEST_URI = explode('/',$_SERVER['REQUEST_URI']);
//echo '<pre>';print_r($REQUEST_URI);exit;
// echo $REQUEST_URI['2']."===========".validateDateWithFormatedDate($REQUEST_URI['2'],'d-F-Y');exit;
if(isset($REQUEST_URI['2']) && !empty($REQUEST_URI['2']) && validateDateWithFormatedDate($REQUEST_URI['2'],'d-F-Y')){
   include ('result-post.php');
} else {
    include ('result-listing.php');
}