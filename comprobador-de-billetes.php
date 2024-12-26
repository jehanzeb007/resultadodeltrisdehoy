<?php
$page_title=$settings['comprobador_de_billetes_title'];
$page_meta=$settings['comprobador_de_billetes_meta'];

$query_cat_data="SELECT * FROM categories ORDER BY id DESC";
$result_cat_data=mysqli_query($con,$query_cat_data);
$categories=[];
while($row_cat=mysqli_fetch_array($result_cat_data)){
    $categories[$row_cat['id']]=$row_cat;
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
$query_result_code="SELECT result_code,cat_id FROM tbl_loterianacional ORDER BY result_code DESC LIMIT 50";
$result_results_code=mysqli_query($con,$query_result_code);
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php
    include 'includes/head.php';
    include ('schema/schema-h.php');
    ?></head>
<body>
<div class="wrap">
    <?php include 'includes/nav.php';?>
    <div class="container">
        <div class="date-main"><h1 style="font-size:18px;">&#10070; Verificador de Números de Boletos Tris</h1></div>
        <div class="row content-block" id="draw-setion">
            <div class="text-heading">
                <p><?=_translate('tris-ticket')?></p>
            </div>
            <section class="col-content">
                <div class="game-block past">
                    <div class="game-info">
                        <form method="post" action="" autocomplete="off" id="category_checkbox_form">
                            <div class="combinacion juego">
                                <span>Elija sorteo:</span>
                                <?php
                                $counter = 0;
                                foreach($categories as $categoryRow){ ?>
                                    <input type="checkbox" name="tris_cat_<?=$categoryRow['id']?>" id="tris_cat_<?=$categoryRow['id']?>" value="<?=$categoryRow['id']?>" <?=$counter==0?'checked':''?>>
                                    <label for="tris_cat_<?=$categoryRow['id']?>" style="width: auto; color: #009688;"><?=$categoryRow['name']?></label>
                                    <?php $counter++; } ?>
                                <!--<input type="checkbox" name="tris-delas-3" id="tris-delas-3" value="1">
                                <label for="tris-delas-3" style="width: auto; color: #009688;">Tris Delas 3</label>

                                <input type="checkbox" name="tris-extra" id="tris-extra" value="1">
                                <label for="tris-extra" style="width: auto; color: #009688;">Tris Extra</label>

                                <input type="checkbox" name="tris-delas-7" id="tris-delas-7" value="1">
                                <label for="tris-delas-7" style="width: auto; color: #009688;">Tris Delas 7</label>

                                <input type="checkbox" name="tris-clasico" id="tris-clasico" value="1">
                                <label for="tris-clasico" style="width: auto; color: #009688;">Tris Clásico</label>-->
                            </div>
                        </form>
                        <form method="post" action="" autocomplete="off">
                            <input type="hidden" name="type" value="check-number">
                            <div class="numeros-sm-mov numeros-gr-esc">
                                <select id="draw_number_sample" style="display: none">
                                    <option value="" data-category="0">Dibujar #</option>
                                    <?php while ($row_results_code = mysqli_fetch_array($result_results_code)) { ?>
                                        <option value="<?=$row_results_code['result_code']?>" data-category="<?=$row_results_code['cat_id']?>">
                                            <?=$row_results_code['result_code']?>
                                        </option>
                                    <?php } ?>
                                </select>

                                <select name="draw_number" required id="draw_number">
                                    <option value="" data-category="0">Dibujar #</option>
                                    <?php while ($row_results_code = mysqli_fetch_array($result_results_code)) { ?>
                                        <option value="<?=$row_results_code['result_code']?>" data-category="<?=$row_results_code['cat_id']?>">
                                            <?=$row_results_code['result_code']?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <input type="tel" name="n1" placeholder="x" class="input-lg inumero input-tris" size="1" maxlength="1" value="" required />
                                <input type="tel" name="n2" placeholder="x" class="input-lg inumero input-tris" size="1" maxlength="1" value="" required />
                                <input type="tel" name="n3" placeholder="x" class="input-lg inumero input-tris" size="1" maxlength="1" value="" required />
                                <input type="tel" name="n4" placeholder="x" class="input-lg inumero input-tris" size="1" maxlength="1" value="" required />
                                <input type="tel" name="n5" placeholder="x" class="input-lg inumero input-tris" size="1" maxlength="1" value="" required />
                                <button type="submit" class="btn btn-lg btn-primary bt_comprobar_tris"><?=_translate('check-number')?></button>
                            </div>
                        </form>
                        <?php
                        if($check_numbers == 'true'){
                            if($isFound > 0){
                                $row_result_check = mysqli_fetch_array($result_check);
                                $result_numbers = json_decode($row_result_check['result_numbers'],1);
                                ?>
                                <div class="number_found">

                                    <div class="game-block past">
                                        <div class="game-info"><div class="top-section"><div class="inner-top"><div class="game-logo">
                                                        <a href="<?=setUrl($categories[$row_result_check['cat_id']]['slug']).'/'.urlDate($row_result_check['result_date'])?>"><span class="session-badge">#<?=$row_result_check['result_code']?></span></a>
                                                    </div>
                                                    <div class="content"><a class="game-title" href="<?=setUrl($categories[$row_result_check['cat_id']]['slug'])?>" style="border: 1px solid #02acff; padding: 3px; border-radius: 10px;"> Resultado <?=$categories[$row_result_check['cat_id']]['name']?></a>
                                                        <!--<span class="date"> <?/*=_date($row_result_check['result_date'])*/?></span>--></div></div>
                                                <div class="clear mb20"></div><div class="game-details"><div class="game-scores ball-mode">
                                                        <?php foreach ($result_numbers as $key=>$result_number){ if($key>=5)continue;?>
                                                            <span class="score" style="<?=$btns_style?>"><?=$result_number?></span>
                                                        <?php } ?>
                                                        </span><?php if($row_result_check['result_multiplier'] == 'SI'){?>
                                                            <div class="circle-plicador" style="background: darkblue">
                                                                <div class="plicador-text">MULTI<br>PLICADOR<br><i style="font-size: 10px;" class="fa fa-check"></i></div></div>
                                                        <?php } ?>
                                                        <?php if($row_result_check['result_multiplier'] == 'NO'){?>
                                                            <div class="circle-plicador" style="background: #dc3545;">
                                                                <div class="plicador-text">MULTI<br>PLICADOR<br><i style="font-size: 10px;" class="fa fa-times"></i></div></div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                      </div>
                            <?php } else { ?>
                                <div class="number_not_found">
                                    <?=_translate('numbers-not-found-text')?>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </section>
        </div>
         <div class="row content-block">
         <?=_translate('ticket')?>
        </div>
    </div>

</div>
<?php include 'includes/footer.php'; ?>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#draw_number').select2();

        // Event listener for checkboxes change
        $('input[type="checkbox"]').on('change', function() {
            // Get all selected categories
            var selectedCategories = [];
            $('input[type="checkbox"]:checked').each(function() {
                selectedCategories.push($(this).val());
            });

            // Collect the filtered options and temporarily remove all options
            var filteredOptions = [];
            $('#draw_number_sample option').each(function() {
                var optionCategory = $(this).data('category');
                if (typeof (optionCategory) !== undefined && selectedCategories.includes(optionCategory.toString())) {
                    filteredOptions.push($(this).clone());  // Keep the valid option
                }
            });

            // Destroy the Select2 instance and remove all options from the select element
            $('#draw_number').select2('destroy').empty();

            // Append the filtered options back to the select element
            $('#draw_number').append('<option value="" data-category="0">Dibujar #</option>'); // Add back the default option
            $.each(filteredOptions, function(i, option) {
                $('#draw_number').append(option);
            });

            // Re-initialize Select2 with the filtered options
            $('#draw_number').select2();
        });

        $('input[type="checkbox"]').trigger('change');
    });
</script>
</body>
</html>