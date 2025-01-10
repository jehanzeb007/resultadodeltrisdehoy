<?php

include('includes/config.php');
$REQUEST_URI = cleanParams(explode('/',$_SERVER['REQUEST_URI']));
// echo '<pre>';print_r($REQUEST_URI);exit;

if(isset($REQUEST_URI['1']) && !empty($REQUEST_URI['1']) && !validateDate($REQUEST_URI['1'])){
    if(isset($REQUEST_URI['2']) && $REQUEST_URI['2'] == 'scripts.php'){
        include ('scripts.php');
        exit;
    }elseif(isset($REQUEST_URI['1']) && $REQUEST_URI['1'] == 'rss'){
        include ('rss.php');
        exit;
    }elseif(isset($REQUEST_URI['1']) && $REQUEST_URI['1'] == 'historico-tris'){
        include ('all-category-history.php');
        exit;
    }elseif (isset($REQUEST_URI['1']) && $REQUEST_URI['1'] == "comprobador-de-billetes"){
        include ('comprobador-de-billetes.php');
        exit;
    }elseif (isset($REQUEST_URI['1']) && $REQUEST_URI['1'] ==  "generador-de-numeros-del-tris"){
        include ('generador-de-numeros-del-tris.php');
        exit;
    }elseif(isset($REQUEST_URI['1']) && $REQUEST_URI['1'] == 'contact-us'){
        include ('contact-us.php');
        exit;
        }elseif(isset($REQUEST_URI['1']) && $REQUEST_URI['1'] == 'author'){
        include ('author.php');
        exit;
    }elseif(isset($REQUEST_URI['1']) && $REQUEST_URI['1'] == 'history-new'){
        include ('history_new.php');
        exit;
    }elseif(isset($REQUEST_URI['1']) && $REQUEST_URI['1'] == 'blog'){
        if(isset($REQUEST_URI['2']) && !empty($REQUEST_URI['2'])){
            include ('blog-detail.php');
            exit;
        }else{
            include ('blog.php');
            exit;
        }
    }else{
        $isPage = mysqli_num_rows(mysqli_query($con,"Select id from pages where slug = '".$REQUEST_URI['1']."'"));
        if($isPage){
            include ('pagina.php');
            exit;
        }
        $isCategory = mysqli_num_rows(mysqli_query($con,"Select id from categories where slug = '".$REQUEST_URI['1']."'"));
        if($isCategory){
            if (strpos($_SERVER['REQUEST_URI'], "/predicciones") !== false){
                //This is predictions page
                include ('pronosticos.php');
                exit;
            }elseif (strpos($_SERVER['REQUEST_URI'], "/numeros-calientes") !== false){
                include ('hot-numbers.php');
                exit;
            }elseif (strpos($_SERVER['REQUEST_URI'], "/numeros-frios") !== false){
                include ('cold-numbers.php');
                exit;
            }elseif (strpos($_SERVER['REQUEST_URI'], "/check-tickets") !== false){
                include ('numbers-test.php');
                exit;
            }elseif (strpos($_SERVER['REQUEST_URI'], "/historico") !== false){
                include ('history.php');
                exit;
            } else{
                //This is results page
                include ('results.php');
                exit;
            }
        }else{
            include ('404.php');
            exit;
        }
    }
}else{
    include ('home.php');
    exit;
}
function validateDate($date, $format = 'd-m-Y')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}
function validateDateWithFormatedDate($date, $format = 'd-m-Y')
{
    $date = ucwords(strtolower($date));
    $d = DateTime::createFromFormat($format, $date);
    if ($d === false) {
        return false;
    }
    return strtolower($d->format($format)) === $date;
}
function cleanParams($REQUEST_URI){
    foreach ($REQUEST_URI as $key=>$value){
        $value = explode('?',$value);
        $REQUEST_URI[$key] = $value[0];
    }
    return $REQUEST_URI;
}
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key => $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}