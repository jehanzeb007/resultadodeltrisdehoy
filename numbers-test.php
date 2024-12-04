<?php
$page_title = 'Hot Numbers';
$page_meta  = '';

//$REQUEST_URI coming from main file
//echo '<pre>';print_r($REQUEST_URI);exit;

$query_cat_data = "Select * From categories where slug = '".$REQUEST_URI['1']."'";
$result_cat_data = mysqli_query($con, $query_cat_data);
$category_info = mysqli_fetch_array($result_cat_data);

if(empty($category_info)){
    header("Location: ".$site_url);
    exit;
}
$date = date('Y-m-d');
if(isset($REQUEST_URI[3]) && !empty($REQUEST_URI[3])){
    $date = date('Y-m-d',strtotime($REQUEST_URI[3]));
}
$check_numbers = 'false';
if(isset($_POST)){
    if($_POST['type'] == 'check-number'){
        $check_numbers = 'true';
        $number_arr = [$_POST['n1'],$_POST['n2'],$_POST['n3'],$_POST['n4'],$_POST['n5']];
        $number_arr = json_encode($number_arr);
        $query = "Select * From tbl_loterianacional where result_date = '".$date."' AND result_numbers = '".$number_arr."'";
        $isFound = mysqli_num_rows(mysqli_query($con,$query));
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/head.php';?>
    <style>

    </style>
</head>
<body>
<div class="wrap">

    <?php include 'includes/nav.php'; ?>

    <div class="container">
        <div class="row content-block">
            <section class="flex-grow-1">
                <div class="ad-container top-ad-container">
                    Ads
                </div>
            </section>
            <section class="col-12">
                <div class="text-heading">
                    <h1>Resultados de Hoy de la Lotería Nacional de México</h1><p>Te ofrecemos al instante todos los números ganadores de los sorteos electrónicos y tradicionales de la Lotería Nacional, entre ellos:&nbsp; Melate, Chispazo, Tris, Mayor, Superior, Zodiaco, Especial y Magno.</p>
                </div>
            </section>

        </div>
        <div class="row content-block dark">
            <div class="date-chooser flex-grow-1">
                <span>Resultados Anteriores</span>
                <input id="datepicker" value="<?=date('d-m-Y',strtotime($date))?>" readonly autocomplete="off">
            </div>
        </div>
        <div class="row content-block">

            <section class="col-content">

                <div class="company-block">
                    <div class="company-title">
                        <?=$category_info['name']?>
                    </div>
                </div>
                <div class="game-block past">
                    <div class="game-info">
                        <form method="post" action="" autocomplete="off">
                            <input type="hidden" name="type" value="check-number">
                            <div class="numeros-sm-mov numeros-gr-esc">
                                <input type="tel" name="n1" placeholder="x" class="input-lg inumero input-tris" size="1" maxlength="1" value="<?=isset($_POST['n1'])?$_POST['n1']:''?>" required />
                                <input type="tel" name="n2" placeholder="x" class="input-lg inumero input-tris" size="1" maxlength="1" value="<?=isset($_POST['n1'])?$_POST['n2']:''?>" required />
                                <input type="tel" name="n3" placeholder="x" class="input-lg inumero input-tris" size="1" maxlength="1" value="<?=isset($_POST['n1'])?$_POST['n3']:''?>" required />
                                <input type="tel" name="n4" placeholder="x" class="input-lg inumero input-tris" size="1" maxlength="1" value="<?=isset($_POST['n1'])?$_POST['n4']:''?>" required />
                                <input type="tel" name="n5" placeholder="x" class="input-lg inumero input-tris" size="1" maxlength="1" value="<?=isset($_POST['n1'])?$_POST['n5']:''?>" required />
                                <button type="submit" class="btn btn-lg btn-primary bt_comprobar_tris"><?=_translate('check-number')?></button>
                            </div>
                        </form>
                        <?php
                        if($check_numbers == 'true'){
                            if($isFound > 0){
                                ?>
                                <div class="number_found">
                                    <?=_translate('numbers-found-text')?>
                                </div>
                            <?php }else{?>
                                <div class="number_not_found">
                                    <?=_translate('numbers-not-found-text')?>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
                <div class="ad-container">Ads</div>

                <div class="text-heading">
                    <?=_translate('hot-numbers-below-text')?>
                </div>

            </section>
        </div>
    </div>
</div>
<?php include 'includes/footer.php';?>
<script type="text/javascript">
    $(document).ready(function () {

        $('input[type="tel"]').on('focus',function () {
            this.selectionStart=0;
            this.selectionEnd=this.value.length;
            return false;
        });

        $('input[type="tel"]').on('click',function () {
            $(this).val('');
            return false;
        });

        $('input[type="tel"]').on('input', function () {
            if($(this).val().length == $(this).attr('maxlength')) {
                $(this).next().focus();
            };
        });

    });
</script>
</body>

</html>

