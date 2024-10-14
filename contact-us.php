<?php
$page_title = 'Contacto US | Resultadodeltrisdehoy.com';
$page_meta  = '';

if(isset($_POST)){
    if($_POST['type'] == 'contact_us'){

        $errors = [];
        if(empty($_POST['message'])){
            $errors[] = 'Please Enter Message.';
        }
        if(empty($_POST['name'])){
            $errors[] = 'Please Enter Name.';
        }
        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $errors[] = 'Please Enter Valid Email.';
        }

        if(empty($errors)){
            /*$query_insert_comment = "INSERT INTO comments SET page_name = '".cleanSlash($_SERVER['REQUEST_URI'])."', comment='".addslashes($_POST['comment'])."',name='".addslashes($_POST['name'])."',email='".addslashes($_POST['email'])."'";
            mysqli_query($con,$query_insert_comment);*/
            $_SESSION['contact-msg-post'] = 'true';
            header("Location: ".$_SERVER['SCRIPT_URI']);
            exit;
        }


    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/head.php';?>
</head>
<body>
<div class="wrap">
    <?php include 'includes/nav.php';?>

    <div class="container">
        <div class="row content-block">
            <section class="flex-grow-1">
            </section>
            <section class="col-12">
                <div class="clear mb20"></div>
                <div class="text-heading">
                    <h1><?=$page_title?></h1>
                    <p><?=_translate('contact-us-text')?></p>
                </div>
            </section>

        </div>
    </div>
</div>

<?php include 'includes/footer.php';?>
</body>

</html>

