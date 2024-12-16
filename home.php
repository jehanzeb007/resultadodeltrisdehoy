<?php
$page_title=$settings['home_title'];
$page_meta=$settings['home_meta'];
$query_cat_data="SELECT * FROM categories ORDER BY id DESC";
$result_cat_data=mysqli_query($con,$query_cat_data);
$categories=[];
while($row_cat=mysqli_fetch_array($result_cat_data)){
    $categories[$row_cat['id']]=$row_cat;
}
$REQUEST_URI=explode('/',$_SERVER['REQUEST_URI']);
$date=date('Y-m-d');

$query_predictions="SELECT * FROM predictions WHERE date='".$date."' ORDER BY time DESC";
$result_predictions=mysqli_query($con,$query_predictions);
if(mysqli_num_rows($result_predictions)==0&&$date==date('Y-m-d')){
    $query_predictions="SELECT * FROM predictions ORDER BY time DESC LIMIT 5";
    $result_predictions=mysqli_query($con,$query_predictions);
}
$query_results="SELECT * FROM tbl_loterianacional WHERE result_date='".$date."' ORDER BY cat_id ASC";
$result_results=mysqli_query($con,$query_results);
if(mysqli_num_rows($result_results)==0&&$date==date('Y-m-d')){
    $query_results="SELECT * FROM tbl_loterianacional ORDER BY result_date DESC LIMIT 5";
    $result_results=mysqli_query($con,$query_results);
}
$check_numbers='false';
if(isset($_POST)){
    if($_POST['type']=='check-number'){
        $check_numbers='true';
        $number_arr=[$_POST['n1'],$_POST['n2'],$_POST['n3'],$_POST['n4'],$_POST['n5']];
        $number_arr=json_encode($number_arr);
        $query="SELECT * FROM tbl_loterianacional WHERE result_code='".trim($_POST['draw_number'])."' AND result_numbers='".$number_arr."'";
        $result_check=mysqli_query($con,$query);
        $isFound=mysqli_num_rows($result_check);
    }
}
$query_result_code="SELECT result_code FROM tbl_loterianacional ORDER BY result_code DESC LIMIT 50";
$result_results_code=mysqli_query($con,$query_result_code);
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php
    include 'includes/head.php';
    include ('schema/schema-h.php');
    ?>
</head>
<body>
<div class="wrap">
    <?php include 'includes/nav.php';?>
    <div class="container">
        <div class="date-main"> <h1 style="display: inline; margin: 0; font-size:15px;">Tris - <?=_date($date)?></h1> | <a style="color: white;" font-size:15px; href="#"><i class="fab fa-google-play"></i> GooglePlay</a></div>
        <div class="row content-block">
            <section class="col-content">
                <?php
                $results_array = [];
                while ($row_data=mysqli_fetch_array($result_results)){
                    $results_array[] = $row_data;
                    $result_numbers = json_decode($row_data['result_numbers'],1); ?>
                    <div class="game-block past">
                        <div class="game-info"><div class="top-section"><div class="inner-top"><div class="game-logo">
                                        <a href="<?=setUrl($categories[$row_data['cat_id']]['slug']).'/'.urlDate($row_data['result_date'])?>"><span class="session-badge">#<?=$row_data['result_code']?></span></a></div>
                                    <div class="content"><a class="game-title" href="<?=setUrl($categories[$row_data['cat_id']]['slug'])?>" style="border: 1px solid #005B8A; padding: 3px; border-radius: 10px;"> Resultado <?=$categories[$row_data['cat_id']]['name']?></a>
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
                <div class="clear mb20"></div><div class="row content-block dark"><div class="date-chooser flex-grow-1"><h2><?=_translate('first-heading')?></h2></div></div><div class="text-heading"><?=_translate('below-results')?></div></section></div>

        <?php foreach($results_array as $row_data){
            $result_numbers = json_decode($row_data['result_numbers'],1);
            $number_string = '';
            foreach ($result_numbers as $key=>$result_number){
                if($key>=5){
                    continue;
                }
                $number_string .= $result_number;
             }
            ?>
            <div class="row content-block">
                <div class="text-heading">
                    <div class="clear mb20"></div>
                    <center><?=$categories[$row_data['cat_id']]['name']?> <strong><?=$row_data['result_code']?> del <?=_date($date)?> </strong>(Apuesta de $ 1)</center>
                    <div class="clear mb20"></div>
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered">
                            <thead>
                            <tr class="danger">
                                <th class="text-center">Modalidad</th>
                                <th class="text-center">Número</th>
                                <th class="text-center">Premio</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="res text-center">Directa de 5</td>
                                <td class="res text-center">
                                        <?=$number_string?>
                                </td>
                                <td class="res">$ 50.000</td>
                            </tr>
                            <tr>
                                <td class="res text-center">Directa de 4</td>
                                <td class="res text-center"><?=convertString($number_string, 1)?></td>
                                <td class="res">$ 5.000</td>
                            </tr>
                            <tr>
                                <td class="res text-center">Directa de 3</td>
                                <td class="res text-center"><?=convertString($number_string, 2)?></td>
                                <td class="res">$ 500</td>
                            </tr>
                            <tr>
                                <td class="res text-center">Par inicial</td>
                                <td class="res text-center"><?=convertString($number_string, 3)?></td>
                                <td class="res">$ 50</td>
                            </tr>
                            <tr>
                                <td class="res text-center">Par final</td>
                                <td class="res text-center"><?=convertString($number_string, 4)?></td>
                                <td class="res">$ 50</td>
                            </tr>
                            <tr>
                                <td class="res text-center">Inicial</td>
                                <td class="res text-center"><?=convertString($number_string, 5)?></td>
                                <td class="res">$ 5</td>
                            </tr>
                            <tr>
                                <td class="res text-center">Final</td>
                                <td class="res text-center"><?=convertString($number_string, 6)?></td>
                                <td class="res">$ 5</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php $query_faq = "SELECT * FROM faqs WHERE page = 'page__Home' order by id desc";
        $result_faq = mysqli_query($con, $query_faq);
        include 'includes/faq.php';?>
        <?php include 'includes/subscribeform.php';?></div></div>
<?php include 'includes/footer.php'; ?>
</body>
</html>