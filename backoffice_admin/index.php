<?php
include('../includes/config.php');
if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
    //Do Nothing
}else{
    header("Location: login.php");
    exit;
}

if(isset($_GET['del_lang_id']) && !empty($_GET['del_lang_id'])){
    $query = "Delete from languages where id  = '".$_GET['del_lang_id']."'";
    mysqli_query($con, $query);
    header("Location: index.php");
    exit;
}
if(isset($_POST) && !empty($_POST)){
    /*echo '<pre>';
    print_r($_POST);exit;*/
    if($_POST['type'] == 'config'){

        /*Update Languages*/
        $htacces_lr_301 = "";
        $htacces_lang_param = "";
        $_POST['languages']['es'] = '1';
        foreach ($_POST['languages'] as $lang_code=>$lang_status){
            $query_update = "Update languages set status = '".$lang_status."' WHERE code = '".$lang_code."'";
            mysqli_query($con, $query_update);

            $htacces_lr_301 .= "RewriteCond %{HTTP:Accept-Language} ^".$lang_code." [NC]\nRewriteRule ^$ ".$site_url."/".$lang_code."/ [L,R=301]\n\n";
            $htacces_lang_param .= $lang_code.'|';
        }
        $htacces_lang_param = rtrim($htacces_lang_param,'|');
        $htacces_lang_param = "RewriteRule ^(".$htacces_lang_param.")/?$ index.php?lang=$1 [QSA,NC,L]";
        $htacces_lang_content = "RewriteEngine On\n\n".$htacces_lr_301."RewriteCond %{REQUEST_FILENAME} !-d\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-l\n\n".$htacces_lang_param;
        $htacces_lang_content .= "\n\nRewriteCond %{REQUEST_FILENAME}.php -f\nRewriteRule !.*\.php$ %{REQUEST_FILENAME}.php [QSA,L]";
        file_put_contents('../.htaccess', $htacces_lang_content);
    }
    if($_POST['type'] == 'create_lang'){
        $query = "INSERT INTO languages set name = '".$_POST['lang_name']."', code = '".$_POST['lang_code']."'";
        mysqli_query($con, $query);
        header("Location: index.php");
        exit;
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin</title>
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
<!-- Navigation-->

<?php include "nav.php"; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">My Dashboard</li>
        </ol>
        <!-- Example Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Dashboard
                <button style="display: none" class="btn btn-primary float-right" onclick="toggle_new_lang_div()">+ Add New Language</button>
            </div>
            <div class="card-body">
                <form method="post" id="add_new_lang_form" style="display: none;" class="mb-5" action="" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="create_lang">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Language Code* (2 Digit Only)</label>
                                <input maxlength="2" class="form-control" required type="text"  name="lang_code" value="<?=isset($_POST['lang_code'])?$_POST['lang_code']:''?>">
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label>Language Name*</label>
                                <input class="form-control" required type="text"  name="lang_name" value="<?=isset($_POST['lang_name'])?$_POST['lang_name']:''?>">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-4">&nbsp;</div><div class="col-md-4"><button type="submit" class="btn btn-primary btn-block">Create Language</button></div><div class="col-md-4">&nbsp;</div>
                    </div>
                </form>

                <?php
                $query_languages = "Select * From languages";
                $result_languages = mysqli_query($con, $query_languages);
                ?>
                <form method="post" action="" enctype="multipart/form-data" onsubmit="return false;" style="display: none;">
                    <?php include('errors.php'); ?>
                    <input type="hidden" name="type" value="config">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-3">
                                <div class="card-header"><i class="fa fa-language"></i> Manage Languages</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Lang Name</th>
                                    <th>Lang Code</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                <?php while($row_lang=mysqli_fetch_array($result_languages)){ ?>
                                    <tr>
                                        <td><?=$row_lang['name']?></td>
                                        <td><?=$row_lang['code']?></td>
                                        <td>
                                            <?php if($row_lang['id']=='1'){ ?>
                                                Default
                                            <?php }else{ ?>
                                                Show <input type="radio" name="languages[<?=$row_lang['code']?>]" value="1" <?=$row_lang['status'] == '1'?'checked':''?>>
                                                Hide <input type="radio" name="languages[<?=$row_lang['code']?>]" value="0" <?=$row_lang['status'] == '0'?'checked':''?>>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if($row_lang['id']=='1'){ ?>
                                                Default
                                            <?php }else{ ?>
                                                <a onclick="return confirm('Are you sure you want to delete this Language?')" href="?del_lang_id=<?=$row_lang['id']?>"><i class="fa fa-trash"></i> Delete</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">&nbsp;</div><div class="col-md-4"><button type="submit" class="btn btn-primary btn-block">Update & Refresh .htaccess</button></div><div class="col-md-4">&nbsp;</div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        Welcome to dashboard
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <?php include 'footer.php'; ?>
    <script>
        function toggle_new_lang_div() {
            $('#add_new_lang_form').toggle('slow');
        }
    </script>
</div>
</body>

</html>
