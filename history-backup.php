<?php
//$REQUEST_URI coming from main file
//echo '<pre>';print_r($REQUEST_URI);exit;

$query_cat_data = "Select * From categories where slug = '".$REQUEST_URI['1']."'";
$result_cat_data = mysqli_query($con, $query_cat_data);
$category_info = mysqli_fetch_array($result_cat_data);

if(empty($category_info)){
    header("Location: ".$site_url);
    exit;
}

$page_title = $category_info['history_meta_title'];
$meta_description = $category_info['history_meta_desc'];
$page_meta  = '';
$manifest = 'manifest-'.$category_info['slug'];

$limit = 10;
$getQuery = "Select result_date From tbl_loterianacional where cat_id = '".$category_info['id']."'".$hasDate;
$total_rows = mysqli_num_rows(mysqli_query($con, $getQuery));

$total_pages = ceil ($total_rows / $limit);

$page_number = 1;
if (isset($_GET['page'])) {
    $page_number = $_GET['page'];
}
$initial_page = ($page_number-1) * $limit;

$query_results = "Select * From tbl_loterianacional where cat_id = '".$category_info['id']."' ".$hasDate." order by result_date desc LIMIT $initial_page, $limit";
$result_results = mysqli_query($con, $query_results);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/head.php';?>
    <style>
        .numbers-his-table {
            width: 100%;
            margin: 0 auto;
            font-size: 20px;
        }
        .numbers-his-table td{
            border: 1px solid #ddd;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="wrap">

    <?php include 'includes/nav.php'; ?>

    <div class="container">
        <div class="row">
            <div class="bcca-breadcrumb">
                <div class="bcca-breadcrumb-item">Historia</div>
                <div class="bcca-breadcrumb-item"><a href="<?=setUrl($category_info['slug'])?>"><?=$category_info['name']?></a></div>
                <div class="bcca-breadcrumb-item"><a href="<?=$site_url?>"><i class="fa fa-home"></i></a></div>
            </div>
        </div>
        <div class="row content-block">
            <section class="col-12">
                <div class="text-heading">
                    <p><?=_cat_translate('header-text-history',$category_info['id'])?></p>
                </div>
            </section>
        </div>
        <div class="row content-block dark">
            <div class="date-chooser flex-grow-1">
                <h1><?=$page_title?></h1>
            </div>
        </div>
        <div class="row content-block">
            <section class="col-content">
                <?php while ($row_data=mysqli_fetch_array($result_results)){
                    $result_numbers = json_decode($row_data['result_numbers'],1);
                    ?>
                    <div class="game-block past">
                        <div class="game-info"><div class="top-section"><div class="inner-top"><div class="game-logo">
                                        <a href="<?=setUrl($category_info['slug']).'/'.urlDate($row_data['result_date'])?>"><span class="session-badge">#<?=$row_data['result_code']?></span></a></div>
                                    <div class="content"><a class="game-title" href="<?=setUrl($category_info['slug'])?>" style="border: 1px solid #02acff; padding: 3px; border-radius: 10px;"> Resultado <?=$category_info['name']?></a>
                                        <!--<span class="date"> <?/*=_date($row_data['result_date'])*/?></span>--></div></div>
                                <div class="clear mb20"></div><div class="game-details"><div class="game-scores ball-mode">
                                        <?php foreach ($result_numbers as $key=>$result_number){ if($key>=5)continue;?>
                                            <span class="score" style="<?=$btns_style?>"><?=$result_number?></span>
                                        <?php } ?>
                                        </span><?php if($row_data['result_multiplier'] == 'SI'){?>
                                            <div class="circle-plicador" style="background: darkblue">
                                                <div class="plicador-text">MULTI<br>PLICADOR<br><i style="font-size: 10px;" class="fa fa-check"></i></div></div>
                                        <?php } ?>
                                        <?php if($row_data['result_multiplier'] == 'NO'){?>
                                            <div class="circle-plicador" style="background: #dc3545;">
                                                <div class="plicador-text">MULTI<br>PLICADOR<br><i style="font-size: 10px;" class="fa fa-times"></i></div></div>
                                        <?php } ?>
                                        <?php /*if($row_data['score'] != ''){*/?><!--
<span class="score special3"><?/*=$row_data['score']*/?></span>
                                    --><?php /*} */?>
                                    </div></div></div></div></div>
                <?php } ?>
                <div class="pagination">
                <?php
                $pagLink = '';
                if($total_pages > 1){
                    for($page_link = 1; $page_link<= $total_pages; $page_link++) {
                        if ($page_link == $page_number || $page_link == 1 || $page_link == $total_pages || ($page_link >= $page_number - 2 && $page_link <= $page_number + 2)) {
                            $pagLink .='<a href = "?page=' . $page_link . '" class="'.($page_link == $page_number?"active":"").'">' . $page_link . ' </a>';
                        } elseif ($page_link == $page_number - 3 || $page_link == $page_number + 3) {
                            $pagLink .='<span class="'.($page_link == $page_number?"active":"").'">...</span>';
                        }
                    }
                }
                echo $pagLink;
                ?>
              </div>
            </section>
        </div>
        <?php
        $query_faq = "SELECT * FROM faqs WHERE page = 'historico__".$category_info['id']."' order by id desc";
        $result_faq = mysqli_query($con, $query_faq);
        include 'includes/faq.php';
        ?>
        <?php include 'includes/subscribeform.php';?>
    </div>
</div>
<?php include 'includes/footer.php';?>
<script>

</script>
</body>

</html>

