<?php
$page_title=$settings['generador_de_numeros_title'];
$page_meta=$settings['generador_de_numeros_meta'];
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
    <div class="date-main"><h1><span><i class="fas fa-fire" aria-hidden="true"></i>&nbsp; Numero Ganador Del Tris De Hoy</span></h1></div>
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
        <div class="row content-block">
         <?=_translate('generator')?>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>