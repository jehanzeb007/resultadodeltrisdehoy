<?php

$params = explode('/',$_SERVER['REQUEST_URI']);

$page_slug = $params[1];
$page_detail_query = "SELECT * FROM pages where slug = '".$page_slug."' LIMIT 1";
$row_page = mysqli_fetch_array(mysqli_query($con,$page_detail_query));
if(!empty($row_page) && isset($row_page['slug']) && !empty($row_page['slug'])){
    //
}else{
    header("Location: ".$site_url);
    exit;
}
$page_title = $row_page['title'];
$page_meta  = $row_page['meta_tags'];
$meta_description = $row_blog['meta_description'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/head.php';?>

</head>
<body>
<div class="wrap">
    <?php include 'includes/nav.php';?>
    <div class="container">
        <div class="row">
            <div class="bcca-breadcrumb">
                <div class="bcca-breadcrumb-item"><?=$page_title?></div>
                <div class="bcca-breadcrumb-item"><a href="<?=$site_url?>"><i class="fa fa-home"></i> Home</a></div>
            </div>
        </div>
        <div class="row content-block">
            <section class="flex-grow-1">
                <!--<div class="ad-container top-ad-container">
                    Ads
                </div>-->
                <div class="text-heading">
                    <h1><?=$page_title?></h1></p>
                </div>
            </section>
            <section class="col-12">
                <div class="text-heading">
                    <?=$row_page['long_desc']?>
                </div>
            </section>

        </div>
    </div>

</div>
<?php include 'includes/footer.php';?>
</body>
</html>

