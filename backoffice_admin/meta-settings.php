<?php
include('../includes/config.php');
if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
    //Do Nothing
}else{
    header("Location: login.php");
    exit;
}
if(isset($_POST) && !empty($_POST)){
    /*echo '<pre>';
    print_r($_POST);exit;*/
    if($_POST['type'] == 'config'){
        $errors = [];
        /*if(empty($_POST['site_name'])){
            $errors[] = 'Please Enter The Site Name.';
        }else */if(empty($_POST['contact_us'])){
            $errors[] = 'Please Enter The Contact Email.';
        }else{
            $query_update = "Update settings set value = '".$_POST['site_name']."' WHERE slug = 'site_name'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['contact_us']."' WHERE slug = 'contact_us'";
            mysqli_query($con, $query_update);

            /*Update Site Logo*/
            if(isset($_FILES['site_logo']) && !empty($_FILES['site_logo']['name'])){
                $uploaddir = '../images/';
                //echo realpath(__DIR__);
                $uploadfile = basename($_FILES['site_logo']['name']);
                if(move_uploaded_file($_FILES['site_logo']['tmp_name'], $uploaddir.$uploadfile)){
                    $query_update = "Update settings set value = '".$uploadfile."' WHERE slug = 'site_logo'";
                    mysqli_query($con, $query_update);
                }else{
                    $errors[] = 'Sorry! Logo is not updating.';
                }
            }
            /*Update Site Favicon*/
            if(isset($_FILES['site_favicon']) && !empty($_FILES['site_favicon']['name'])){
                $uploaddir = '../images/';
                $uploadFavicon = basename($_FILES['site_favicon']['name']);
                if(move_uploaded_file($_FILES['site_favicon']['tmp_name'], $uploaddir.$uploadFavicon)){
                    $query_update = "Update settings set value = '".$uploadFavicon."' WHERE slug = 'site_favicon'";
                    mysqli_query($con, $query_update);
                }else{
                    $errors[] = 'Sorry! Logo is not updating.';
                }
            }



            $query_update = "Update settings set value = '".$_POST['home_title']."' WHERE slug = 'home_title'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['home_meta']."' WHERE slug = 'home_meta'";
            mysqli_query($con, $query_update);

            $query_update = "Update settings set value = '".$_POST['blog_title']."' WHERE slug = 'blog_title'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['blog_meta']."' WHERE slug = 'blog_meta'";
            mysqli_query($con, $query_update);

            $query_update = "Update settings set value = '".$_POST['historico_title']."' WHERE slug = 'historico_title'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['historico_meta']."' WHERE slug = 'historico_meta'";
            mysqli_query($con, $query_update);

            if(isset($_POST['site_index_follow']) && !empty($_POST['site_index_follow'])){
                $query_update = "Update settings set value = 'Yes' WHERE slug = 'site_index_follow'";
            }else{
                $query_update = "Update settings set value = 'No' WHERE slug = 'site_index_follow'";
            }


            $query_update = "Update settings set value = '".$_POST['generador_de_numeros_title']."' WHERE slug = 'generador_de_numeros_title'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['generador_de_numeros_meta']."' WHERE slug = 'generador_de_numeros_meta'";
            mysqli_query($con, $query_update);

            $query_update = "Update settings set value = '".$_POST['comprobador_de_billetes_title']."' WHERE slug = 'comprobador_de_billetes_title'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['comprobador_de_billetes_meta']."' WHERE slug = 'comprobador_de_billetes_meta'";
            mysqli_query($con, $query_update);



            mysqli_query($con, $query_update);

        }
    }
}
if(isset($_GET['generate_site_map']) && $_GET['generate_site_map'] == 'true'){
    generateSiteMap($lang_arr, $settings['site_index_follow'], $con);
    if(isset($_GET['cron']) && $_GET['cron'] == 'true'){
        exit('Done');
    }
    header("Location: meta-settings.php");
    exit;
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
            <li class="breadcrumb-item active">My Meta Settings</li>
        </ol>
        <!-- Example Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Manage Meta Settings</div>
            <div class="card-body">
                <div class="card-body">
                    <?php
                    $query = "Select * From settings";
                    $results = mysqli_query($con, $query);
                    $settings = [];
                    while($row=mysqli_fetch_array($results)){
                        $settings[$row['slug']] = $row['value'];
                    }
                    ?>
                    <form method="post" action="" enctype="multipart/form-data">
                        <?php include('errors.php'); ?>
                        <input type="hidden" name="type" value="config">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Site Name</label>
                                    <input class="form-control" type="text"  name="site_name" value="<?=isset($settings['site_name'])?$settings['site_name']:''?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact Email</label>
                                    <input class="form-control" required type="text"  name="contact_us" value="<?=isset($settings['contact_us'])?$settings['contact_us']:''?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Site Logo *</label>
                                    <input class="form-control"  type="file"  name="site_logo">
                                    <i>Size 210 x 42</i>
                                    <img style="width: 210px;" src="../images/<?=isset($settings['site_logo'])?$settings['site_logo']:''?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Site Favicon</label>
                                    <input class="form-control"  type="file"  name="site_favicon">
                                    <i>Size 20 x 20</i>
                                    <img style="width: 50px;" src="../images/<?=isset($settings['site_favicon'])?$settings['site_favicon']:''?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox"  name="site_index_follow" <?=$settings['site_index_follow']=='Yes'?'checked':''?>>Site  Index, Follow
                                        </label>
                                    </div>
                                    <br><i class="fa fa-warning text-danger"> If uncheck site will not index and not follow.</i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <a class="btn btn-primary text-white" onclick="return confirm('Are you sure you want to generate nre sitemap')"  href="?generate_site_map=true"><i class="fa fa-globe"></i> Refresh Site Map</a>
                                    <br><a href="<?=$site_url?>/sitemap.xml" target="_blank"><i class="fa fa-download text-success"> Download</i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Home Page Meta
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Home Page Title *</label>
                                                        <input class="form-control" type="text"  name="home_title" value="<?=isset($settings['home_title'])?$settings['home_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Home Meta Tags</label>
                                                        <textarea class="form-control" placeholder='<meta name="keywords" content=""/>' type="text"  name="home_meta"><?=isset($settings['home_meta'])?$settings['home_meta']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Historico Tris
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Historico Page Title *</label>
                                                        <input class="form-control" type="text"  name="historico_title" value="<?=isset($settings['historico_title'])?$settings['historico_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Historico Meta Tags</label>
                                                        <textarea class="form-control" placeholder='<meta name="keywords" content=""/>' type="text"  name="historico_meta"><?=isset($settings['historico_meta'])?$settings['historico_meta']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Blog Page Meta
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Blog Page Title *</label>
                                                        <input class="form-control" type="text"  name="blog_title" value="<?=isset($settings['blog_title'])?$settings['blog_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Blog Meta Tags</label>
                                                        <textarea class="form-control" placeholder='<meta name="keywords" content=""/>' type="text"  name="blog_meta"><?=isset($settings['blog_meta'])?$settings['blog_meta']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Generador De Numeros Page Meta
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Page Title *</label>
                                                        <input class="form-control" type="text"  name="generador_de_numeros_title" value="<?=isset($settings['generador_de_numeros_title'])?$settings['generador_de_numeros_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Meta Tags</label>
                                                        <textarea class="form-control" placeholder='<meta name="keywords" content=""/>' type="text"  name="generador_de_numeros_meta"><?=isset($settings['generador_de_numeros_meta'])?$settings['generador_de_numeros_meta']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Comprobador De Billetes Page Meta
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Page Title *</label>
                                                        <input class="form-control" type="text"  name="comprobador_de_billetes_title" value="<?=isset($settings['comprobador_de_billetes_title'])?$settings['comprobador_de_billetes_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Meta Tags</label>
                                                        <textarea class="form-control" placeholder='<meta name="keywords" content=""/>' type="text"  name="comprobador_de_billetes_meta"><?=isset($settings['comprobador_de_billetes_meta'])?$settings['comprobador_de_billetes_meta']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">&nbsp;</div><div class="col-md-4"><button type="submit" class="btn btn-primary btn-block">Update</button></div><div class="col-md-4">&nbsp;</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <?php include 'footer.php'; ?>
</div>
</body>

</html>
