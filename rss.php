<?php
header("Content-Type: application/xml;charset=UTF-8");

$page_title = $settings['home_title'];

$xml = "<?xml version='1.0' encoding='UTF-8' ?>".PHP_EOL;
$xml .= '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">'.PHP_EOL;
//$xml .= '<atom:link href="http://dallas.example.com/rss.xml" rel="self" type="application/rss+xml" />'.PHP_EOL;

$xml .= "<channel>".PHP_EOL;
$xml .= "<title>".$page_title." | RSS</title>".PHP_EOL;
$xml .= "<link>".$site_url."/rss</link>".PHP_EOL;
$xml .= "<description></description>".PHP_EOL;
$xml .= "<language>es</language>".PHP_EOL;

$query_cat_data = "Select * From categories order by id desc";
$result_cat_data = mysqli_query($con, $query_cat_data);
$categories = [];
while ($row_cat=mysqli_fetch_array($result_cat_data)){
    $categories[$row_cat['id']] = $row_cat;
}
/*Results Start*/
$query_results = "Select * From tbl_loterianacional order by result_code desc";
$result_results = mysqli_query($con, $query_results);

while ($row_data=mysqli_fetch_array($result_results)){
    $result_numbers = json_decode($row_data['result_numbers'],1);


    $img_url = $site_url.'/images/cat_images/'.$categories[$row_data['cat_id']]['rss_image'];
    $image_size_array = get_headers($img_url, 1);
    $image_size = $image_size_array["Content-Length"];
    $image_mime_array = getimagesize($img_url);
    $image_mime = $image_mime_array["mime"];

    $results_title      = $settings['rss_results_title'];
    $results_desc       = $settings['rss_results_desc'];

    $results_title = str_replace('##category-name##',$categories[$row_data['cat_id']]['name'],$results_title);
    $results_title = str_replace('##result-date##',date('d F Y',strtotime($row_data['result_date'])),$results_title);

    $results_desc = str_replace('##category-name##',$categories[$row_data['cat_id']]['name'],$results_desc);
    $results_desc = str_replace('##result-date##',date('d F Y',strtotime($row_data['result_date'])),$results_desc);

    $xml .= "<item>".PHP_EOL;
    $xml .= "<title>".$results_title."</title>".PHP_EOL;
    $xml .= "<link>".setUrl($categories[$row_data['cat_id']]['slug']).'/'.date('d-m-Y',strtotime($row_data['result_date']))."</link>".PHP_EOL;
    $xml .= "<url>".setUrl($categories[$row_data['cat_id']]['slug']).'/'.date('d-m-Y',strtotime($row_data['result_date']))."</url>".PHP_EOL;
    $xml .= "<pubDate>".date(DATE_RSS, strtotime($row_data["result_date"]))."</pubDate>".PHP_EOL;
    //$xml .= "<dc:creator>".$row["author"]."</dc:creator>".PHP_EOL;
    $xml .= "<description>".$results_desc."</description>".PHP_EOL;
    $xml .= "<enclosure url='".$img_url."' length='".$image_size."' type='".$image_mime."' />".PHP_EOL;
    $xml .= "<category>".$categories[$row_data['cat_id']]['name']."</category>".PHP_EOL;
    $xml .= "</item>".PHP_EOL;
}
/*Results End*/

/*Predictions Start*/
$query_predictions = "Select * From predictions order by date desc";
$result_predictions = mysqli_query($con, $query_predictions);

while ($row_data=mysqli_fetch_array($result_predictions)){
    $result_numbers = json_decode($row_data['predic_numbers'],1);


    $img_url = $site_url.'/images/cat_images/'.$categories[$row_data['cat_id']]['rss_image'];
    $image_size_array = get_headers($img_url, 1);
    $image_size = $image_size_array["Content-Length"];
    $image_mime_array = getimagesize($img_url);
    $image_mime = $image_mime_array["mime"];

    $predictions_title  = $settings['rss_predictions_title'];
    $predictions_desc   = $settings['rss_predictions_desc'];

    $predictions_title = str_replace('##category-name##',$categories[$row_data['cat_id']]['name'],$predictions_title);
    $predictions_title = str_replace('##prediction-date##',date('d F Y',strtotime($row_data['date'])),$predictions_title);

    $predictions_desc = str_replace('##category-name##',$categories[$row_data['cat_id']]['name'],$predictions_desc);
    $predictions_desc = str_replace('##prediction-date##',date('d F Y',strtotime($row_data['date'])),$predictions_desc);

    $xml .= "<item xmlns:dc='http://purl.org/dc/elements/1.1/'>".PHP_EOL;
    $xml .= "<title>".$predictions_title."</title>".PHP_EOL;
    $xml .= "<link>".setUrl($categories[$row_data['cat_id']]['slug']).'/predicciones/'.date('d-m-Y',strtotime($row_data['date']))."</link>".PHP_EOL;
    $xml .= "<pubDate>".date(DATE_RSS, strtotime($row_data["date"]))."</pubDate>".PHP_EOL;
    //$xml .= "<dc:creator>".$row["author"]."</dc:creator>".PHP_EOL;
    $xml .= "<description>".$predictions_desc."</description>".PHP_EOL;
    $xml .= "<enclosure url='".$img_url."' length='".$image_size."' type='".$image_mime."' />".PHP_EOL;
    $xml .= "<category>".$categories[$row_data['cat_id']]['name']."</category>".PHP_EOL;
    $xml .= "</item>".PHP_EOL;
}
/*Predictions Start*/

$xml .= '</channel>'.PHP_EOL;
$xml .= '</rss>'.PHP_EOL;
echo $xml;
?>
