<?php

$page_title = $settings['prediction_title'];
//$page_meta  = $settings['prediction_meta'];

//$REQUEST_URI coming from main file
//echo '<pre>';print_r($REQUEST_URI);exit;

$query_cat_data = "Select * From categories where slug = '".$REQUEST_URI['1']."'";
$result_cat_data = mysqli_query($con, $query_cat_data);
$category_info = mysqli_fetch_array($result_cat_data);

if(empty($category_info)){
    header("Location: ".$site_url.'/404');
    exit;
}

$page_title = $category_info['prediction_meta_title'];
$meta_description = $category_info['prediction_meta_desc'];
$manifest = 'manifest-'.$category_info['slug'];

$hasDate = '';
if(isset($REQUEST_URI[3]) && !empty($REQUEST_URI[3])){

    if(!preg_match("/[a-z]/i", $REQUEST_URI[3])){
        header("Location: ".$site_url.'/404');
        exit;
    }

    $date = date('Y-m-d',strtotime($REQUEST_URI[3]));
    $hasDate = " and date = '".$date."'";

    $page_title = $category_info['prediction_post_meta_title'];
    $page_title = str_replace('##date##',_date($date),$page_title);
    $meta_description = $category_info['prediction_post_meta_desc'];
    $meta_description = str_replace('##date##',_date($date),$meta_description);
}

$limit = 40;
$getQuery = "Select * From predictions where cat_id = '".$category_info['id']."' ".$hasDate;
$total_rows = mysqli_num_rows(mysqli_query($con, $getQuery));
if($total_rows == 0){
    header("Location: ".$site_url.'/404');
    exit;
}
$total_pages = ceil ($total_rows / $limit);

$page_number = 1;
if (isset($_GET['page'])) {
    $page_number = $_GET['page'];
}
$initial_page = ($page_number-1) * $limit;

$query_predictions = "Select * From predictions where cat_id = '".$category_info['id']."' ".$hasDate." order by date desc LIMIT $initial_page, $limit";
$result_predictions = mysqli_query($con, $query_predictions);



?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php 
    include 'includes/head.php';
    include 'schema/predictions.php';
    ?>
</head>
<body>
<div class="wrap">
    <?php include 'includes/nav.php'; ?>
    <div class="container">
        <div class="date-main"><?=$page_title?></div>
        <div class="row content-block">
            <!--<section class="flex-grow-1">
                <div class="ad-container top-ad-container">
                  Ads
                </div>
            </section>-->
        <div class="row content-block">
            <section class="col-content">
                <?php while ($row_data=mysqli_fetch_array($result_predictions)){
                    $predic_numbers = json_decode($row_data['predic_numbers'],1);
                    $btns_style = 'background-color:#ddd;color:#666';
                    if($row_data['score'] == 'Verde'){
                        $btns_style = '';
                    }
                    ?>
                    <div class="game-block past"><div class="game-info"><div class="top-section"><div class="inner-top">
                                    <div class="game-logo"><img src="<?=$site_url?>/images/cat_images/<?=$category_info['image']?>" alt="<?=$category_info['name']?>" loading="lazy"></div>
                                    <div class="content"><a class="game-title" style="border: 1px solid #02acff; padding: 3px; border-radius: 10px;">Predicciones <?=$category_info['name']?></a><div class="clear mb20"></div><div class="game-scores ball-mode">
                                            <?php foreach ($predic_numbers as $predic_number){ ?>
                                                <span class="score" style="<?=$btns_style?>"><?=$predic_number?></span>
                                            <?php } ?>
                                            </span></div></div></div></div>
                            <div class="clearfix"></div><div class="game-footer">
                                <!--<span class="session-badge"><?/*=$row_data['draw_number']*/?></span>-->
                                <a href="<?=setUrl($category_info['slug']).'/predicciones/'.urlDate($row_data['date'])?>"><span class="session-badge"><?=_date($row_data['date'])?></span></a>
                                <a href="<?=setUrl($category_info['slug']).'/numeros-calientes'?>"><span class="session-date session-badge">Números Calientes</span></a>
                                <a href="<?=setUrl($category_info['slug']).'/numeros-frios'?>"><span class="home-comment session-badge">Números Fríos</span></a>
                            </div></div></div>
                <?php } ?>
                <div class="pagination">
                    <?php
                    $pagLink = '';
                    if($total_pages > 1){
                        for($page_link = 1; $page_link<= $total_pages; $page_link++) {
                            $pagLink .='<a href = "?page=' . $page_link . '" class="'.($page_link == $page_number?"active":"").'">' . $page_link . ' </a>';
                        }
                    }
                    echo $pagLink;
                    ?>
                </div>
                <div class="text-heading">
                   
                     
                     <?php if(!empty($hasDate)){?>
                        <p><?=str_replace('##CATEGORY_NAME##',$category_info['name'],str_replace('##DATE##',_date($REQUEST_URI[2]),_translate('prediction-post-head-text')))?></p>
                    <?php }else {?>
                    <?=_cat_translate('header-predictions',$category_info['id'])?>
                    <?php }?>
                </div>
                <div class="text-heading">
                    <?php if(!empty($hasDate)){?>
                        <p><?=str_replace('##CATEGORY_NAME##',$category_info['name'],str_replace('##DATE##',$REQUEST_URI[3],_translate('prediction-post-below-text')))?></p>
                    <?php }else {?>
                    <?=_cat_translate('detail-information-predictions',$category_info['id'])?>
                    <?php }?>
                </div>

            </section>
        </div>
        <?php
        $query_faq = "SELECT * FROM faqs WHERE page = 'predicciones__".$category_info['id']."' order by id desc";
        $result_faq = mysqli_query($con, $query_faq);
        include 'includes/faq.php';
        ?>
        <?php include 'includes/subscribeform.php';?>
    </div>
</div>
<?php include 'includes/footer.php';?>
</body>

</html>

