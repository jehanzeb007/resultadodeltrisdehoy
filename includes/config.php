<?php
ob_start("ob_gzhandler");
error_reporting(0);
session_start();
$site_url = 'https://resultadodeltrisdehoy.com';
// $site_url = 'http://resultadodeltrisdehoy.loc';
global $site_url;
/*Database Configurations*/
$DBhost = 'localhost';
$DBuser = 'dbAdmin';
$DBpassword = 'sfs@$5q4q0i5mngfaQ#@fsAG';
$DBname = 'resultadodeltrisdehoy';

// $DBhost = 'localhost';
// $DBuser = 'root';
// $DBpassword = '';
// $DBname = 'resultadodeltrisdehoy';

$con = mysqli_connect($DBhost,$DBuser,$DBpassword,$DBname);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to Database: " . mysqli_connect_error();
    exit();
}
$con->query("SET sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");

$query = "Select * From settings";
$results = mysqli_query($con, $query);
$settings = [];
while($row=mysqli_fetch_array($results)){
    $settings[$row['slug']] = $row['value'];
}
$query_languages = "Select * From languages";
$result_languages = mysqli_query($con, $query_languages);
$lang_arr = [];
while ($result_languages_row=mysqli_fetch_array($result_languages)){
    $lang_arr[$result_languages_row['code']] = $result_languages_row;
}

/*if(isset($_GET['lang']) && !empty($_GET['lang'])){
    $site_lang = $_GET['lang'];
    setcookie('site_lang', $site_lang, time() + (86400 * 30 * 365), "/"); // 86400 = 1 day
} elseif(isset($_COOKIE['site_lang']) && !empty($_COOKIE['site_lang'])){
    $site_lang = $_COOKIE['site_lang'];
}else{
    $site_lang = 'es';
    setcookie('site_lang', $site_lang, time() + (86400 * 30 * 365), "/"); // 86400 = 1 day
}*/

function generateLangJson($con){
    $query_keywords = "Select * From  keywords";
    $result_keywords = mysqli_query($con, $query_keywords);
    $lang_keywords = [];
    while ($row_keyword=mysqli_fetch_array($result_keywords)){
        $lang_keywords[$row_keyword['lang']][$row_keyword['slug']] = addslashes($row_keyword['translation']);
    }
    $lang_keywords = json_encode($lang_keywords);
    file_put_contents('../includes/lang.json', $lang_keywords);
}
function has_html($string) {
    // Check if the string contains any HTML tags
    return preg_match("/<[^<]+>/", $string) !== 0;
}
function fix_json_string($jsonString) {

    if(has_html($jsonString)){
        // Remove whitespace
        $jsonString = trim($jsonString);

        // Replace single quotes with double quotes
        $jsonString = str_replace("'", '"', $jsonString);

        // Escape double quotes within HTML attributes
        $jsonString = preg_replace_callback('/(="[^"]*)"/', function($matches) {
            return str_replace('"', '\\"', $matches[0]);
        }, $jsonString);

        // Escape new line characters
        $jsonString = preg_replace("/\r?\n/", "\\n", $jsonString);

        // Attempt to decode JSON string
        $decodedData = json_decode($jsonString, true);

        // If decoding is successful, return the JSON-encoded version of the decoded data
        if ($decodedData !== null && json_last_error() === JSON_ERROR_NONE) {
            return json_encode($decodedData);
        }
        // If decoding failed, return the original string
        //return $jsonString;
    }

    return $jsonString;
}
function generateCategoryLangJson($con){
    $query_keywords = "Select * From  category_keywords";
    $result_keywords = mysqli_query($con, $query_keywords);
    $lang_keywords = [];

    $result_data_merge = [];
    while($row_data = mysqli_fetch_array($result_keywords)){
        $result_data_merge[$row_data['slug']][$row_data['cat_id']] = $row_data['translation'];
    }

    foreach($result_data_merge as $slug=>$row_keyword){
        $lang_keywords[$slug] = $row_keyword;
    }
    //debug($lang_keywords,1);
    $lang_keywords = json_encode($lang_keywords);
    file_put_contents('../includes/category-lang.json', $lang_keywords);
}
$translations = json_decode(file_get_contents($site_url.'/includes/lang.json'),1);
$category_translations = json_decode(file_get_contents($site_url.'/includes/category-lang.json'),1);
global $translations;
global $category_translations;
function _translate($slug){
    global $translations;
    global $site_url;
    $site_lang = 'es';
    $trans_word = $slug;
    if(isset($translations[$site_lang][$slug]) && !empty($translations[$site_lang][$slug])){
        $trans_word = html_entity_decode($translations[$site_lang][$slug]);
        $trans_word = str_replace('../images',$site_url.'/images',$trans_word);
        $trans_word = stripslashes($trans_word);
    }
    return $trans_word;
}
function _cat_translate($slug,$cat_id){
    global $category_translations;
    global $site_url;
    $trans_word = $slug;
    if(isset($category_translations[$slug][$cat_id]) && !empty($category_translations[$slug][$cat_id])){
        $trans_word = html_entity_decode($category_translations[$slug][$cat_id]);
        $trans_word = str_replace('../images',$site_url.'/images',$trans_word);
        $trans_word = stripslashes($trans_word);
    }
    return $trans_word;
}
function getUrl(){
    global $site_url;
    $site_lang = $_COOKIE['site_lang'];
    return $site_url.'/'/*.$site_lang.'/'*/;
}
function setUrl($page){
    global $site_url;
    $site_lang = $_COOKIE['site_lang'];
    return $site_url.'/'.$page;
}
function pageUrl($slug) {
    return setUrl('').$slug.'/'; // Remove 'info' to construct a clean URL
}
function webpConvert2($file, $compression_quality = 80)
{
    // check if file exists
    if (!file_exists($file)) {
        return false;
    }
    $file_type = exif_imagetype($file);
    $output_file =  $file . '.webp';
    if (file_exists($output_file)) {
        return $output_file;
    }
    if (function_exists('imagewebp')) {
        switch ($file_type) {
            case '1': //IMAGETYPE_GIF
                $image = imagecreatefromgif($file);
                break;
            case '2': //IMAGETYPE_JPEG
                $image = imagecreatefromjpeg($file);
                break;
            case '3': //IMAGETYPE_PNG
                $image = imagecreatefrompng($file);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case '6': // IMAGETYPE_BMP
                $image = imagecreatefrombmp($file);
                break;
            case '15': //IMAGETYPE_Webp
                return false;
                break;
            case '16': //IMAGETYPE_XBM
                $image = imagecreatefromxbm($file);
                break;
            default:
                return false;
        }
        // Save the image
        $result = imagewebp($image, $output_file, $compression_quality);
        if (false === $result) {
            return false;
        }
        // Free up memory
        imagedestroy($image);
        return $output_file;
    } elseif (class_exists('Imagick')) {
        $image = new Imagick();
        $image->readImage($file);
        if ($file_type === "3") {
            $image->setImageFormat('webp');
            $image->setImageCompressionQuality($compression_quality);
            $image->setOption('webp:lossless', 'true');
        }
        $image->writeImage($output_file);
        return $output_file;
    }
    return false;
}

function generateSiteMap($lang_arr, $idexFollow,$con){
    global $site_url;
    $urls = [];
    /*System Urls Start*/
    //echo '<pre>';print_r($lang_arr);exit;
    foreach ($lang_arr as $lang){
        if($lang['status'] == '1'){
            $urls[] = ['url'=>$site_url,'freq'=>'monthly','priority'=>'0.5'];
            $urls[] = ['url'=>$site_url.'/blog','freq'=>'monthly','priority'=>'0.5'];
        }
    }

    /*System Urls End*/

    /*Results,Prediction,hotnumbers,cold numbers URL Start*/

    $newsxml = '<?xml version="1.0" encoding="UTF-8"?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">';

    $query_categories = "Select * From categories order by sort_order desc";
    $result_categories = mysqli_query($con, $query_categories);

    while($row_category = mysqli_fetch_array($result_categories)){

        $urls[] = ['url'=>$site_url.'/'.$row_category['slug'],'freq'=>'monthly','priority'=>'0.5'];
        $urls[] = ['url'=>$site_url.'/'.$row_category['slug'].'/predicciones','freq'=>'monthly','priority'=>'0.5'];
        $urls[] = ['url'=>$site_url.'/'.$row_category['slug'].'/numeros-calientes','freq'=>'monthly','priority'=>'0.5'];
        $urls[] = ['url'=>$site_url.'/'.$row_category['slug'].'/numeros-frios','freq'=>'monthly','priority'=>'0.5'];
        $urls[] = ['url'=>$site_url.'/'.$row_category['slug'].'/historico','freq'=>'monthly','priority'=>'0.5'];

        $query_results  = "Select result_date From tbl_loterianacional where cat_id = '".$row_category['id']."'";
        $result_results = mysqli_query($con, $query_results);
        $newsxml .= "";
        while($row_results = mysqli_fetch_array($result_results)){
            $url_loc = $site_url.'/'.$row_category['slug'].'/'.urlDate($row_results['result_date']);
            $newsxml .= '<url>
                          <loc>'.$url_loc.'</loc>
                          <news:news>
                            <news:publication>
                              <news:name>Resultados del '.$row_category['name'].' '._date($row_results['result_date']).' | Predicciones | Numeros Frios | Numeros Calientes</news:name>
                              <news:language>es</news:language>
                            </news:publication>
                            <news:publication_date>'.$row_results['result_date'].'</news:publication_date>
                            <news:title>Resultados del '.$row_category['name'].' '._date($row_results['result_date']).' | Predicciones | Numeros Frios | Numeros Calientes</news:title>
                          </news:news>
                          </url>';
            $urls[] = ['url'=>$site_url.'/'.$row_category['slug'].'/'.urlDate($row_results['result_date']),'freq'=>'monthly','priority'=>'0.5'];
        }
    }
    $newsxml .= '</urlset>';

    /*Results,Prediction,hotnumbers,cold numbers URL end*/

    /*Blogs URL Start*/
    $query_blog = "Select * From blog order by id desc";
    $result_blog = mysqli_query($con, $query_blog);
    while($row_blog = mysqli_fetch_array($result_blog)){
        $urls[] = ['url'=>$site_url.'/blog/'.$row_blog['slug'],'freq'=>'monthly','priority'=>'0.5'];
    }
    /*Blogs URL End*/

    /*Pages URL Start*/
    $query_page = "Select * From pages order by id desc";
    $result_page = mysqli_query($con, $query_page);
    while($row_pages = mysqli_fetch_array($result_page)){
        $urls[] = ['url'=>$site_url.'/'.$row_pages['slug'],'freq'=>'monthly','priority'=>'0.5'];
    }
    /*Pages URL End*/

    /*categories URL Start*/
    $query_categories = "Select * From categories order by id desc";
    $result_categories = mysqli_query($con, $query_categories);
    while($row_category = mysqli_fetch_array($result_categories)){
        $urls[] = ['url' => $site_url.'/' . $row_category['slug'] . '/predicciones', 'freq' => 'monthly', 'priority' => '0.5'];
        $urls[] = ['url' => $site_url.'/' . $row_category['slug'] . '/numeros-calientes', 'freq' => 'monthly', 'priority' => '0.5'];
        $urls[] = ['url' => $site_url.'/' . $row_category['slug'] . '/numeros-frios', 'freq' => 'monthly', 'priority' => '0.5'];
    }
    /*apks URL End*/
    /*echo '<pre>';
    print_r($urls);exit;*/
    $xml = '<?xml version="1.0" encoding="UTF-8" ?>';
    $xml .= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">';
    if($idexFollow == 'Yes'){
        $xml .= '<url>
        <loc>'.$site_url.'</loc>
        <priority>1.00</priority>
    </url>';
        foreach ($urls as $url){
            $xml .= '<url>
                <loc>'.$url['url'].'</loc>
                <changefreq>'.$url['freq'].'</changefreq>
                <priority>'.$url['priority'].'</priority>
            </url>';
        }
    }

    $xml .= '</urlset>';
    file_put_contents('/var/www/www.resultadodeltrisdehoy.com/sitemap.xml', $xml);
    file_put_contents('/var/www/www.resultadodeltrisdehoy.com/sitemap_news.xml', $newsxml);
}

function debug($arr,$exit=0){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
    if($exit){
        exit;
    }
}
function GenerateRandomColor(){
    $color = '#';
    $colorHexLighter = array("9","A","B","C","D","E","F" );
    for($x=0; $x < 6; $x++):
        $color .= $colorHexLighter[array_rand($colorHexLighter, 1)]  ;
    endfor;
    return substr($color, 0, 7);
}
if(isset($_POST) && !empty($_POST)){
    if($_POST['type'] == 'post-comment'){
        $_SESSION['comment-post-check'] = 'true';
        $errors = [];
        if(empty($_POST['comment'])){
            $errors[] = 'Please Enter Comment.';
        }
        if(empty($_POST['name'])){
            $errors[] = 'Please Enter Name.';
        }
        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors[] = 'Please Enter Valid Email.';
        }
        $reg_exUrl = "/(http|https|ftp|ftps|.com)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        if(preg_match($reg_exUrl, $_POST['comment'], $url)) {
            $errors[] = 'Sorry! You cannot post any URL in comment.';
        }
        //echo '<pre>';print_r($errors);exit;
        if(empty($errors)){
            $query_insert_comment = "INSERT INTO comments SET page_name = '".cleanSlash($_SERVER['REQUEST_URI'])."', comment='".addslashes($_POST['comment'])."',name='".addslashes($_POST['name'])."',email='".addslashes($_POST['email'])."'";
            mysqli_query($con,$query_insert_comment);
            $_SESSION['comment-post'] = 'true';
            echo '<script>window.location.href = "'.$_SERVER['SCRIPT_URI'].'";</script>';
            //header("Location: ".$_SERVER['SCRIPT_URI']);
            exit;
        }

    }
}
function cleanSlash($string){
    $string = trim($string,'/');
    $string = rtrim($string,'/');
    $string = ltrim($string,'/');
    $string = explode('?',$string);
    return $string[0];
}
function urlDate($date){
    return strtolower(date('d-F-Y',strtotime($date)));
}
function _date($dateStemp=''){
    if(empty($dateStemp)){
        $dateStemp = date('Y-m-d H:i:s');
    }
    $month = _translate(strtolower(date('M',strtotime($dateStemp))));
    $date = date('d',strtotime($dateStemp));
    $year = date('Y',strtotime($dateStemp));
    return $month.' '.$date.' '.$year;
}
function convertString($input,$pattern) {
    if($pattern == 1)
        return 'x' . substr($input, 1);

    if($pattern == 2)
        return 'xx' . substr($input, 2);

    if($pattern == 3)
        return substr($input, 0, 2) . 'xxx';

    if($pattern == 4)
        return 'xxx' . substr($input, 3);

    if($pattern == 5)
        return substr($input, 0, 1) . 'xxxx';

    if($pattern == 6)
        return 'xxxx' . substr($input, -1);
}
?>