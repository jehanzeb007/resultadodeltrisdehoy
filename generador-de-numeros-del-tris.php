<?php
$page_title=$settings['generador_de_numeros_title'];
$page_meta=$settings['generador_de_numeros_meta'];
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
    $query_predictions="SELECT * FROM predictions ORDER BY time DESC LIMIT 20";
    $result_predictions=mysqli_query($con,$query_predictions);
}
$query_results="SELECT * FROM tbl_loterianacional WHERE result_date='".$date."' ORDER BY cat_id ASC";
$result_results=mysqli_query($con,$query_results);
if(mysqli_num_rows($result_results)==0&&$date==date('Y-m-d')){
    $query_results="SELECT * FROM tbl_loterianacional ORDER BY result_date DESC LIMIT 50";
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
<html lang="es">
<head>
    <?php
    include 'includes/head.php';
    include ('schema/schema-h.php');
    ?></head>
<body>
<div class="wrap">
    <?php include 'includes/nav.php';?>
    <div class="container">

        <div class="row content-block dark">
            <div class="date-chooser flex-grow-1">
                <h1><span><i class="fas fa-fire" aria-hidden="true"></i>&nbsp; Numero Ganador Del Tris De Hoy</span></h1>
            </div>
        </div>
        <div class="row content-block" style="background:#ffe1e0;">
            <section class="col-content">
                <div class="card hotpicks">
                    <div style="background:#ffe1e0;">
                        <?=_translate('quick-pick')?>
                    </div>
                    <div class="card-body" style="background:#ffe1e0;">
                        <form id="lucky-numbers-form" class="form-inline">
                            <div class="genrate-main">
                                <label>¿Cuántos números de la suerte quieres?</label>
                                <input type="number" id="num-of-lucky-numbers" class="form-control" name="num-of-lucky-numbers" min="1" max="5" required="">
                                <button type="submit" class="btn btn-primary">Generar Selecciones Rápidas</button>
                                <p class="text-muted">Tris Quick elige para el <?=date('d F Y')?> sorteo.</p>
                                <div class="game-block past" style="border: none">
                                    <div class="game-scores ball-mode" style="text-align: center;display: inline-flex">
                                        <div class="game-scores ball-mode" id="lucky-numbers-list" style="display: inline-flex;"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>