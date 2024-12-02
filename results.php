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
$page_title = $category_info['result_meta_title'];
$meta_description = $category_info['result_meta_desc'];
$page_meta  = '';
$manifest = 'manifest-'.$category_info['slug'];

$date = date('Y-m-d');
$hasDate = '';
if(isset($REQUEST_URI[2]) && !empty($REQUEST_URI[2])){

    if(!preg_match("/[a-z]/i", $REQUEST_URI[2])){
        header("Location: ".$site_url.'/404');
        exit;
    }

    $date = date('Y-m-d',strtotime($REQUEST_URI[2]));
    $hasDate = " and result_date = '".$date."'";

    $page_title = $category_info['result_post_meta_title'];
    $page_title = str_replace('##date##',_date($date),$page_title);
    $meta_description = $category_info['result_post_meta_desc'];
    $meta_description = str_replace('##date##',_date($date),$meta_description);
}

$limit = 40;
$getQuery = "Select result_date From tbl_loterianacional where cat_id = '".$category_info['id']."'".$hasDate;
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

$query_results = "Select * From tbl_loterianacional where cat_id = '".$category_info['id']."' ".$hasDate." order by result_date desc LIMIT $initial_page, $limit";
$result_results = mysqli_query($con, $query_results);

$query_predictions = "Select * From predictions where cat_id = '".$category_info['id']."' order by date desc limit 5";
$result_predictions = mysqli_query($con, $query_predictions);

$date_30_days_ago = date('Y-m-d',strtotime('-30 days'));

$query_results_all = "Select * From tbl_loterianacional where cat_id = '".$category_info['id']."' and result_date >= '".$date_30_days_ago."' order by result_date asc";
$result_results_all = mysqli_query($con, $query_results_all);

$data_numbers = [];
$data_draws = [];
while ($row=mysqli_fetch_array($result_results_all)){
    $result_numbers = json_decode($row['result_numbers'],1);
    foreach ($result_numbers as $number){

        $data_numbers[$number]['number'] = $number;
        $data_numbers[$number]['drawn'] = $data_numbers[$number]['drawn']+1;
        $data_numbers[$number]['last_come'] = strtotime($row['result_date']);
    }
    $data_draws[] = ['result_code'=>$row['result_code']];
}
$data_draws = array_reverse($data_draws);
array_sort_by_column($data_numbers, 'drawn',SORT_DESC);
$data_numbers_by_number = [];
foreach ($data_numbers as $data_number_row){
    $data_numbers_by_number[$data_number_row['drawn']][] = $data_number_row;
}
$data_numbers_by_number_sort = [];
foreach ($data_numbers_by_number as $draw=>$data_numbers_by_number_arr){
    array_sort_by_column($data_numbers_by_number_arr, 'last_come',SORT_DESC);
    $data_numbers_by_number_sort[] = $data_numbers_by_number_arr;
}

$data_numbers = [];
foreach ($data_numbers_by_number_sort as $data_arr){
    foreach ($data_arr as $numbers_arr){
        $data_numbers[] = $numbers_arr;
    }
}
$pieces_data_numbers = array_chunk($data_numbers, ceil(count($data_numbers) / 2));
/*Hot Cold Numbers end*/
$check_numbers = 'false';
if(isset($_POST)){
    if($_POST['type'] == 'check-number'){
        $check_numbers = 'true';
        $number_arr = [$_POST['n1'],$_POST['n2'],$_POST['n3'],$_POST['n4'],$_POST['n5']];
        $number_arr = json_encode($number_arr);
        $query_check = "Select * From tbl_loterianacional where result_code = '".trim($_POST['draw_number'])."' AND result_numbers = '".$number_arr."'";
        $result_check = mysqli_query($con,$query_check);
        $isFound = mysqli_num_rows($result_check);
    }
}
$plain_name = trim(str_replace('Tris','',$category_info['name']));
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php 
    include 'includes/head.php';
    include 'schema/results.php';
    ?>
</head>
<body>
<div class="wrap">
    <?php include 'includes/nav.php'; ?>
        <div class="container">
        <div class="date-main"><h1><?=_cat_translate('results',$category_info['id'])?></h1></div>
        <div class="row content-block">
            <section class="col-content">
                <?php while ($row_data=mysqli_fetch_array($result_results)){
                    $result_numbers = json_decode($row_data['result_numbers'],1);

                    /*$btns_style = 'background-color:#ddd;color:#666';
                    if($row_data['score'] == 'Verde'){
                        $btns_style = '';
                    }*/
                    ?>
                    <div class="game-block past">
                    <div class="game-info"><div class="top-section"><div class="inner-top"><div class="game-logo">
                     <a href="<?=setUrl($category_info['slug']).'/'.urlDate($row_data['result_date'])?>"><span class="session-badge">#<?=$row_data['result_code']?></span></a></div>
                     <div class="content"><a class="game-title" href="<?=setUrl($category_info['slug'])?>" style="border: 1px solid #02acff; padding: 3px; border-radius: 10px;"> Resultado <?=$category_info['name']?></a></div></div>
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
                            <div class="game-footer">
                            <span class="date session-badge" style="display: inline;"> <?= date('M j', strtotime($row_data['result_date'])) ?> </span></div></div></div></div></div></div>
                <?php } ?>
                <div class="pagination">
                <?php
                $pagLink = '';
                if($total_pages > 1){
                    for($page_link = 1; $page_link<= $total_pages; $page_link++) {
                        $pagLink .='<a href = "?page=' . $page_link . '" class="'.($page_link == $page_number?"active":"").'">' . $page_link . ' </a>';
                    }
                }
                //echo $pagLink;
                ?>
              </div>
              <div class="button-row" style="display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
                    <!-- Button -->
                    <a href="<?=setUrl($category_info['slug']).'/historico'?>" style="flex: 1; text-align: center; padding: 10px; background-color: #02acff; color: white; border: none; border-radius: 5px; text-decoration: none; font-size: 16px;">
                        Sorteos Anteriores</a>
                    <a href="https://resultadodeltrisdehoy.com/generador-de-numeros-del-tris" style="flex: 1; text-align: center; padding: 10px; background-color: #dc3545; color: white; border: none; border-radius: 5px; text-decoration: none; font-size: 14px;">
                        Generar número</a></div>
              <?php if(!empty($hasDate)){?>
                        <p><?=str_replace('##CATEGORY_NAME##',$category_info['name'],str_replace('##DATE##',$REQUEST_URI[2],_translate('results-post-below-text')))?></p>
                    <?php }else {?>
                    <?=_cat_translate('results-below-text',$category_info['id'])?>
                    <?php }?>
            </section>
        </div>
         <div class="row content-block dark">
            <div class="date-chooser flex-grow-1">
               <h2><?=_cat_translate('schedule',$category_info['id'])?></h2>
            </div> 
        </div>
        <div class="row content-block">
            <p><?=_cat_translate('schedule-table',$category_info['id'])?></p>
        </div>
        <div class="row content-block dark">
            <div class="date-chooser flex-grow-1">
               <h2><?=_cat_translate('hot-and-cold-number',$category_info['id'])?></h2>
            </div> 
        </div>
        <div class="row content-block">
            <?php if(!empty($hasDate)){?>
                        <p><?=str_replace('##CATEGORY_NAME##',$category_info['name'],str_replace('##DATE##',$REQUEST_URI[2],_translate('result-post-hot-and-cold-ball')))?></p>
                    <?php }else {?>
                    <?=_cat_translate('results-hot-and-cold-ball-text',$category_info['id'])?>
                    <?php }?>
            <div class="row w-50-col bg-white">
                <div class="row content-block" style="background: #f6cfcf;">
                    <section class="col-content">
                        <div class="game-block past" style="border: none">
                            <div class="game-info">
                                <div class="card hotColdCard" style="background: #f6cfcf;">
                                    <div class="card-header">
                                        <h3 style="display: inline; font-weight: bold;"><i class="fas fa-fire" aria-hidden="true"></i> Caliente Tris Mediodía</h3>
                                        <a style="float: right" href="<?=setUrl($category_info['slug'].'/numeros-calientes')?>">Más números</a>
                                    </div>
                                    <div class="card-body" style="overflow-x:auto;">
                                        <table class="numbers-his-table">
                                            <tr>
                                                <?php
                                                foreach ($pieces_data_numbers[0] as $key=>$number_data){
                                                    if ($key > 2){
                                                        break;
                                                    }
                                                    ?>
                                                    <td>
                                                        <span class="results-number-bonus"><?=$number_data['number']?></span>
                                                        <p style="color:green;font-size:15px;">Dibujado<br><strong><?=$number_data['drawn']?> veces</strong></p>
                                                        <!--<p><?/*=time_elapsed_string(date('Y-m-d',$number_data['last_come']))*/?></p>-->
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        </table>
                                        <p style="font-size: 12px; padding: 10px;"><i class="fas fa-info-circle" aria-hidden="true"></i> Las bolas calientes son bolas que se han extraído con mayor frecuencia según los últimos veinte sorteos.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <div class="row w-50-col bg-white">
                <div class="row content-block" style="background: #dae6ec;">
                    <section class="col-content">
                        <div class="game-block past" style="border: none">
                            <div class="game-info">
                                <div class="card hotColdCard" style="background: #dae6ec;">
                                    <div class="card-header" >
                                        <h3 style="display: inline; font-weight: bold;"><i class="fa fa-thermometer-empty" aria-hidden="true"></i> Frios <?=$category_info['name']?></h3>

                                        <a style="float: right" href="<?=setUrl($category_info['slug'].'/numeros-frios')?>">Más números</a>
                                    </div>
                                    <div class="card-body" style="overflow-x:auto;">
                                        <table class="numbers-his-table">
                                            <tr>
                                                <?php
                                                foreach ($pieces_data_numbers[1] as $key=>$number_data){
                                                    if ($key > 2){
                                                        break;
                                                    }
                                                    ?>
                                                    <td>
                                                        <span class="results-number-bonus"><?=$number_data['number']?></span>
                                                        <p style="color:green;font-size:15px;">Dibujado<br><strong><?=$number_data['drawn']?> veces</strong></p>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        </table>
                                        <p style="font-size: 12px; padding: 10px;"><i class="fas fa-info-circle" aria-hidden="true"></i> Las bolas frías son bolas que se han extraído con menor frecuencia según los últimos veinte sorteos.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row content-block">
         <p><?=_cat_translate('how-to-play',$category_info['id'])?></p>
        </div>

        <?php
        $query_faq = "SELECT * FROM faqs WHERE page = 'results__".$category_info['id']."' order by id desc";
        $result_faq = mysqli_query($con, $query_faq);
        include 'includes/faq.php';
        ?>
        <?php include 'includes/subscribeform.php';?>
    </div>
</div>
<?php include 'includes/footer.php';?>
</body>
</html>

