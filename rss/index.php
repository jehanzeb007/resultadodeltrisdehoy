<?php
header("Content-Type: text/xml;charset=iso-8859-1");
include('../includes/config.php');
$page_title = $settings['home_title'];
$page_meta  = $settings['home_meta'];

$xml = "<?xml version='1.0' encoding='UTF-8' ?>".PHP_EOL;
$xml .= "<rss version='2.0'>".PHP_EOL;
$xml .= "<channel>".PHP_EOL;
$xml .= "<title>".$page_title." | RSS</title>".PHP_EOL;
$xml .= "<link>".$site_url."/rss.xml</link>".PHP_EOL;
$xml .= "<description>".$page_meta."</description>".PHP_EOL;
$xml .= "<language>es</language>".PHP_EOL;

$query_cat_data = "Select * From categories order by id desc";
$result_cat_data = mysqli_query($con, $query_cat_data);
$categories = [];
while ($row_cat=mysqli_fetch_array($result_cat_data)){
    $categories[$row_cat['id']] = $row_cat;
}

$query_results = "Select * From tbl_loterianacional order by result_code desc";
$result_results = mysqli_query($con, $query_results);

while ($row_data=mysqli_fetch_array($result_results)){
    $result_numbers = json_decode($row_data['result_numbers'],1);

    $publish_Date = date("D, d M Y H:i:s T", strtotime($result_numbers["result_date"].' '.$result_numbers["result_time"]));
    $img_url = $site_url.'/images/cat_images/'.$categories[$row_data['cat_id']]['image'];
    $image_size_array = get_headers($img_url, 1);
    $image_size = $image_size_array["Content-Length"];
    $image_mime_array = getimagesize($img_url);
    $image_mime = $image_mime_array["mime"];

    $xml .= "<item xmlns:dc='ns:1'>".PHP_EOL;
    $xml .= "<title>".$categories[$row_data['cat_id']]['name'].' '.date('M d Y',strtotime($row_data['result_date']))."</title>".PHP_EOL;
    $xml .= "<link>".setUrl($categories[$row_data['cat_id']]['slug']).'/'.date('d-m-Y',strtotime($row_data['result_date']))."</link>".PHP_EOL;
    //$xml .= "<guid>".md5($row["id"])."</guid>".PHP_EOL;
    $xml .= "<pubDate>".$row_data["result_date"]."</pubDate>".PHP_EOL;
    //$xml .= "<dc:creator>".$row["author"]."</dc:creator>".PHP_EOL;
    $xml .= "<description></description>".PHP_EOL;
    $xml .= "<enclosure url='".$img_url."' length='".$image_size."' type='".$image_mime."' />".PHP_EOL;
    $xml .= "<category>".$categories[$row_data['cat_id']]['name']."</category>".PHP_EOL;
    $xml .= "</item>".PHP_EOL;
}
$xml .= '</channel>'.PHP_EOL;
$xml .= '</rss>'.PHP_EOL;
echo $xml;
?>
