<?php
http_response_code(404);
$page_title = '404';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/head.php';?>
    <style>
        .text-center{
            text-align: center !important;
        }
        .page_404{ padding:40px 0; background:#fff; font-family: 'Arvo', serif;
        }

        .page_404  img{ width:100%;}

        .four_zero_four_bg{

            background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
            height: 400px;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
        }


        .four_zero_four_bg h1{
            font-size:80px;
        }

        .four_zero_four_bg h3{
            font-size:80px;
        }

        .link_404{
            color: #fff!important;
            padding: 10px 20px;
            background: #39ac31;
            margin: 20px 0;
            display: inline-block;}
        .contant_box_404{ margin-top:-50px;}
    </style>
</head>
<body>
<div class="wrap">
    <?php include 'includes/nav.php';?>

    <div class="container">
        <div class="row content-block">
            <section class="page_404">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 ">
                            <div class="col-sm-10 col-sm-offset-1  text-center">
                                <div class="four_zero_four_bg">
                                    <h1 class="text-center ">404</h1>
                                </div>

                                <div class="contant_box_404">
                                    <h3 class="h2">
                                        Parece que estás perdido
                                    </h3>

                                    <p>¡La página que estás buscando no está disponible!</p>

                                    <a href="https://resultadodeltrisdehoy.com/blog" class="link_404">Información de la visita</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<?php include 'includes/footer.php';?>
</body>
</html>