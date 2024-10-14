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

$page_title = $category_info['hot_meta_title'];
$meta_description = $category_info['hot_meta_desc'];
$manifest = 'manifest-'.$category_info['slug'];

$date_30_days_ago = date('Y-m-d',strtotime('-30 days'));

$query_results = "Select * From tbl_loterianacional where cat_id = '".$category_info['id']."' and result_date >= '".$date_30_days_ago."' order by result_date asc";
$result_results = mysqli_query($con, $query_results);
//echo mysqli_num_rows($result_results);exit;
$data_numbers = [];
while ($row=mysqli_fetch_array($result_results)){
    $result_numbers = json_decode($row['result_numbers'],1);
    foreach ($result_numbers as $number){

        $data_numbers[$number]['number'] = $number;
        $data_numbers[$number]['drawn'] = $data_numbers[$number]['drawn']+1;
        $data_numbers[$number]['last_come'] = strtotime($row['result_date']);
    }
}
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
//echo '<pre>';print_r($pieces_data_numbers);echo '</pre>';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/head.php';?>
    <style>
        #view_all_balls{
            display: none;
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
                <div class="bcca-breadcrumb-item">Números Calientes</div>
                <div class="bcca-breadcrumb-item"><a href="<?=setUrl($category_info['slug'])?>"><?=$category_info['name']?></a></div>
                <div class="bcca-breadcrumb-item"><a href="<?=$site_url?>"><i class="fa fa-home"></i> Home</a></div>
            </div>
        </div>
        <div class="row content-block">
                        <section class="col-12">
                <div class="text-heading">
                    <p><?=_cat_translate('header-hot-numbers',$category_info['id'])?></p>
                </div>
            </section>
        </div>
        <div class="row content-block dark" style="margin-left: 2px;">
            <div class="date-chooser flex-grow-1">
                <h1><i class="fas fa-fire" aria-hidden="true"></i> <?=$page_title?></h1>
            </div>
        </div>
        <div class="row content-block" style="background: #f6cfcf;">
            <section class="col-content">
                <div class="game-block past" style="border-bottom: none">
                    <div class="game-info">
                        <div class="card hotColdCard" style="background: #f6cfcf;">
                            <!--<div class="card-header" style="background:#ffe1e0;">
                                <span><i class="fas fa-fire" aria-hidden="true"></i> <?/*=$category_info['name']*/?></span>
                            </div>-->
                            <div class="card-body" style="overflow-x:auto;">
                                <table class="numbers-his-table">
                                    <tr>
                                        <?php
                                        foreach ($pieces_data_numbers[0] as $key=>$number_data){
                                            if($key >= 3){
                                                continue;
                                            }
                                            ?>
                                            <td>
                                                <span class="results-number-bonus"><?=$number_data['number']?></span>
                                                <p style="color:green;font-size:15px;">Dibujada<br><strong><?=$number_data['drawn']?> veces</strong></p>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                </table>
                                <p style="text-align:center;font-size: 12px;"><i class="fas fa-info-circle" aria-hidden="true"></i> <?=_translate('hot-number-center-text')?></p>
                                <p style="text-align: center"><a href="javascript:void(0)" onclick="showMoreBalls()"><i class="fas fa-arrow-down" aria-hidden="true"></i>&nbsp;&nbsp;<?=_translate('view-hot-ball')?></a></p>
                                <div id="view_all_balls">
                                    <table class="numbers-his-table">
                                        <tr>
                                            <th>Balls</th>
                                            <th>Drawn</th>
                                            <th>Last Drawn</th>
                                        </tr>
                                        <?php foreach ($pieces_data_numbers[0] as $number_data){ ?>
                                            <tr>
                                                <td><div class="game-scores ball-mode" style="margin-top: 10px"><span class="score" style=""><?=$number_data['number']?></span></div></td>
                                                <td><?=$number_data['drawn']?> times</td>
                                                <td><?=time_elapsed_string(date('Y-m-d',$number_data['last_come']))?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <?php
        $query_faq = "SELECT * FROM faqs WHERE page = 'numeros-calientes__".$category_info['id']."' order by id desc";
        $result_faq = mysqli_query($con, $query_faq);
        include 'includes/faq.php';
        ?>
        <?php include 'includes/subscribeform.php';?>
    </div>
</div>
<?php include 'includes/footer.php';?>
</body>
<script>
    function showMoreBalls() {
        $('#view_all_balls').toggle('slow');
    }
</script>
</html>

