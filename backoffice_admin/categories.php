<?php

include('../includes/config.php');
if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
//Do Nothing
}else{
    header("Location: login.php");
    exit;
}
if(isset($_POST) && !empty($_POST)) {
    /*echo '<pre>';
    print_r($_POST);
    print_r($_FILES);
    exit;*/
    if ($_POST['type'] == 'add_category') {
        $errors = [];
        if (empty($_POST['cat_name'])) {
            $errors[] = 'Please Enter The Name.';
        }
        if (empty($_POST['cat_slug'])) {
            $errors[] = 'Please Enter The Slug.';
        }
        if(empty($errors)){
            $query_slug_exists = "Select id From categories where slug = '".$_POST['cat_slug']."' Limit 1";
            $result_slug_exists = mysqli_query($con, $query_slug_exists);
            if(mysqli_num_rows($result_slug_exists) > '0'){
                $errors[] = 'Sorry! This Slug Already Exists.';
            }
        }
        if(!isset($_FILES['cat_image']) || empty($_FILES['cat_image']['name'])){
            $errors[] = 'Please select category image.';
        }
        $rss_image = '';
        if(isset($_FILES['rss_image']) && !empty($_FILES['rss_image']['name'])){
            $uploaddir = '../images/cat_images/';
            $uploadfileRss = 'rss-'.time().basename($_FILES['rss_image']['name']);
            move_uploaded_file($_FILES['rss_image']['tmp_name'], $uploaddir.$uploadfileRss);
            $rss_image = $uploadfileRss;
        }
        if(empty($errors)){
            $uploaddir = '../images/cat_images/';
            $uploadfile = time().basename($_FILES['cat_image']['name']);
            move_uploaded_file($_FILES['cat_image']['tmp_name'], $uploaddir.$uploadfile);
            $category_image = $uploadfile;
            $query = "INSERT INTO categories set name = '".$_POST['cat_name']."', slug = '".$_POST['cat_slug']."',image = '".$category_image."', rss_image = '".$rss_image."', prediction_text = '".$_POST['prediction_text']."',result_meta_title = '".$_POST['result_meta_title']."',result_meta_desc = '".$_POST['result_meta_desc']."',prediction_meta_title = '".$_POST['prediction_meta_title']."',prediction_meta_desc = '".$_POST['prediction_meta_desc']."',hot_meta_title = '".$_POST['hot_meta_title']."',hot_meta_desc = '".$_POST['hot_meta_desc']."',cold_meta_title = '".$_POST['cold_meta_title']."',cold_meta_desc = '".$_POST['cold_meta_desc']."',history_meta_title = '".$_POST['history_meta_title']."',history_meta_desc = '".$_POST['history_meta_desc']."',result_post_meta_title = '".$_POST['result_post_meta_title']."',result_post_meta_desc = '".$_POST['result_post_meta_desc']."',prediction_post_meta_title = '".$_POST['prediction_post_meta_title']."',prediction_post_meta_desc = '".$_POST['prediction_post_meta_desc']."'";
            mysqli_query($con,$query) or die(mysqli_error($con));
            header("Location: categories.php?success=true&msg=Category Added Successfully.");
            exit;
        }
    }
    if ($_POST['type'] == 'edit_category') {
        $errors = [];
        if (empty($_POST['cat_name'])) {
            $errors[] = 'Please Enter The Name.';
        }
        if (empty($_POST['cat_slug'])) {
            $errors[] = 'Please Enter The Slug.';
        }
        if(empty($errors)){
            $query_slug_exists = "Select id From categories where slug = '".$_POST['cat_slug']."' and id != '".$_POST['edit_category_id']."' Limit 1";
            $result_slug_exists = mysqli_query($con, $query_slug_exists);
            if(mysqli_num_rows($result_slug_exists) > '0'){
                $errors[] = 'Sorry! This Slug Already Exists.';
            }
        }
        if(isset($_FILES['cat_image']) && !empty($_FILES['cat_image']['name'])){
            $uploaddir = '../images/cat_images/';
            $uploadfile = time().basename($_FILES['cat_image']['name']);
            move_uploaded_file($_FILES['cat_image']['tmp_name'], $uploaddir.$uploadfile);
            $category_image = $uploadfile;
        }else{
            $category_image = $_POST['old_image'];
        }
        $rss_image = $_POST['old_rss_image'];
        if(isset($_FILES['rss_image']) && !empty($_FILES['rss_image']['name'])){
            $uploaddir = '../images/cat_images/';
            $uploadfileRss = 'rss-'.time().basename($_FILES['rss_image']['name']);
            move_uploaded_file($_FILES['rss_image']['tmp_name'], $uploaddir.$uploadfileRss);
            $rss_image = $uploadfileRss;
        }
        if(empty($errors)){
            $query = "UPDATE categories set name = '".$_POST['cat_name']."', slug = '".$_POST['cat_slug']."',image = '".$category_image."', rss_image = '".$rss_image."', prediction_text = '".$_POST['prediction_text']."', result_meta_title = '".$_POST['result_meta_title']."',result_meta_desc = '".$_POST['result_meta_desc']."',prediction_meta_title = '".$_POST['prediction_meta_title']."',prediction_meta_desc = '".$_POST['prediction_meta_desc']."',hot_meta_title = '".$_POST['hot_meta_title']."',hot_meta_desc = '".$_POST['hot_meta_desc']."',cold_meta_title = '".$_POST['cold_meta_title']."',cold_meta_desc = '".$_POST['cold_meta_desc']."',history_meta_title = '".$_POST['history_meta_title']."',history_meta_desc = '".$_POST['history_meta_desc']."',result_post_meta_title = '".$_POST['result_post_meta_title']."',result_post_meta_desc = '".$_POST['result_post_meta_desc']."',prediction_post_meta_title = '".$_POST['prediction_post_meta_title']."',prediction_post_meta_desc = '".$_POST['prediction_post_meta_desc']."' WHERE id = '".$_POST['edit_category_id']."'";
            mysqli_query($con,$query) or die(mysqli_error($con));
            header("Location: categories.php?success=true&msg=Category Update Successfully.");
            exit;
        }

    }
}
if(isset($_GET['del_category']) && !empty($_GET['del_category'])){
    $delete_category = "Delete From  categories where id = '".$_GET['del_category']."'";
    mysqli_query($con, $delete_category);
    generateLangJson($con);
    header("Location: categories.php?success=true&msg=Category Delete Successfully.");
    exit;
}
$query_data = "Select * From categories order by id desc";
$result_data = mysqli_query($con, $query_data);
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
    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
    <!-- Place the first <script> tag in your HTML's <head> -->
    <script src="https://cdn.tiny.cloud/1/7q7yskpxgyzsn6vokqyk3btjlthys6ham0zocxlaqirrj8br/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea.prediction_text_div',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            images_upload_url: 'post_image_upload.php',
            automatic_uploads: true,
            images_upload_base_path: '<?=$site_url?>'

        });
    </script>
</head>
<body class="fixed-nav sticky-footer bg-dark" id="page-top">
<!-- Navigation-->
<?php include "nav.php"; ?>
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.php">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Categories</li>
        </ol>
        <?php  if(isset($_GET['success']) && $_GET['success'] == 'true' && !empty($_GET['msg'])) {?>
            <div class="alert alert-success"><?php echo $_GET['msg'];?></div>
        <?php }?>

        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Manage Categories</div>
            <div class="card-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12"><a href="javascript:void(0)" class="btn btn-primary float-right mb-3" data-toggle="modal" data-target="#create_category"><i class="fa fa-plus"></i> Create New Category</a></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Image</th>
                                <th>RSS Image</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while($row_data = mysqli_fetch_array($result_data)){ ?>
                                <tr>
                                    <td><?=$row_data['name']?></td>
                                    <td><img src="<?=$site_url?>/images/cat_images/<?=$row_data['image']?>" style="width: 100px;height: 100px"></td>
                                    <td><img src="<?=$site_url?>/images/cat_images/<?=$row_data['rss_image']?>" style="width: 100px;height: 100px"></td>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_category_<?=$row_data['id']?>"><i class="fa fa-pencil-square"></i> Edit</a> | <a class="text-danger" href="?del_category=<?=$row_data['id']?>" onclick="return confirm('Are you sure you want to delete this category?')"><i class="fa fa-trash"></i> Delete</a>
                                        <div class="modal fade" id="edit_category_<?=$row_data['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form method="post" action="" enctype="multipart/form-data">
                                                <input type="hidden" name="type" value="edit_category">
                                                <input type="hidden" name="edit_category_id" value="<?=$row_data['id']?>">
                                                <input type="hidden" name="old_image" value="<?=$row_data['image']?>">
                                                <input type="hidden" name="old_rss_image" value="<?=$row_data['rss_image']?>">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Update Keyword Translations</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Name *</label>
                                                                        <input class="form-control" required type="text"  name="cat_name" value="<?=$row_data['name']?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Slug *</label>
                                                                        <input class="form-control" required type="text"  name="cat_slug" value="<?=$row_data['slug']?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Image *</label>
                                                                        <input class="form-control" type="file"  name="cat_image">
                                                                        <i class="text-danger">Size 100x100</i>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <img src="<?=$site_url?>/images/cat_images/<?=$row_data['image']?>" style="width: 100px;height: 100px">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>RSS Image *</label>
                                                                        <input class="form-control" type="file"  name="rss_image">
                                                                        <i class="text-danger">Size 100x100</i>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <img src="<?=$site_url?>/images/cat_images/<?=$row_data['rss_image']?>" style="width: 100px;height: 100px">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 d-none">
                                                                    <div class="form-group">
                                                                        <label>Prediction Page Text</label>
                                                                        <textarea class="prediction_text_div" name="prediction_text"><?=$row_data['prediction_text']?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <i class="fa fa-table"></i> Results
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Title</label>
                                                                    <textarea class="form-control" name="result_meta_title"><?=isset($row_data['result_meta_title'])?$row_data['result_meta_title']:''?></textarea>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Description</label>
                                                                    <textarea class="form-control" maxlength="500" name="result_meta_desc"><?=isset($row_data['result_meta_desc'])?$row_data['result_meta_desc']:''?></textarea>
                                                                    <i>Maximum 160 chracters</i>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <i class="fa fa-table"></i> Predictions
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Title</label>
                                                                    <textarea class="form-control" name="prediction_meta_title"><?=isset($row_data['prediction_meta_title'])?$row_data['prediction_meta_title']:''?></textarea>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Description</label>
                                                                    <textarea class="form-control" maxlength="500" name="prediction_meta_desc"><?=isset($row_data['prediction_meta_desc'])?$row_data['prediction_meta_desc']:''?></textarea>
                                                                    <i>Maximum 160 chracters</i>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <i class="fa fa-table"></i> Results Post
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Title</label>
                                                                    <textarea class="form-control" name="result_post_meta_title"><?=isset($row_data['result_post_meta_title'])?$row_data['result_post_meta_title']:''?></textarea>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Description</label>
                                                                    <textarea class="form-control" maxlength="500" name="result_post_meta_desc"><?=isset($row_data['result_post_meta_desc'])?$row_data['result_post_meta_desc']:''?></textarea>
                                                                    <i>Maximum 160 chracters</i>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <i class="fa fa-table"></i> Predictions Post
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Title</label>
                                                                    <textarea class="form-control" name="prediction_post_meta_title"><?=isset($row_data['prediction_post_meta_title'])?$row_data['prediction_post_meta_title']:''?></textarea>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Description</label>
                                                                    <textarea class="form-control" maxlength="500" name="prediction_post_meta_desc"><?=isset($row_data['prediction_post_meta_desc'])?$row_data['prediction_post_meta_desc']:''?></textarea>
                                                                    <i>Maximum 160 chracters</i>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <i class="fa fa-table"></i> Hot Numbers
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Title</label>
                                                                    <textarea class="form-control" name="hot_meta_title"><?=isset($row_data['hot_meta_title'])?$row_data['hot_meta_title']:''?></textarea>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Description</label>
                                                                    <textarea class="form-control" maxlength="500" name="hot_meta_desc"><?=isset($row_data['hot_meta_desc'])?$row_data['hot_meta_desc']:''?></textarea>
                                                                    <i>Maximum 160 chracters</i>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <i class="fa fa-table"></i> Cold Numbers
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Title</label>
                                                                    <textarea class="form-control" name="cold_meta_title"><?=isset($row_data['cold_meta_title'])?$row_data['cold_meta_title']:''?></textarea>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Description</label>
                                                                    <textarea class="form-control" maxlength="500" name="cold_meta_desc"><?=isset($row_data['cold_meta_desc'])?$row_data['cold_meta_desc']:''?></textarea>
                                                                    <i>Maximum 160 chracters</i>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="card mb-3">
                                                                        <div class="card-header">
                                                                            <i class="fa fa-table"></i>History Page
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Title</label>
                                                                    <textarea class="form-control" name="history_meta_title"><?=$row_data['history_meta_title']?></textarea>
                                                                </div>
                                                                <div class="col-md-6 mt-5">
                                                                    <label>Meta Description</label>
                                                                    <textarea class="form-control" maxlength="500" name="history_meta_desc"><?=$row_data['history_meta_desc']?></textarea>
                                                                    <i>Maximum 160 chracters</i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                            <button class="btn btn-primary" type="submit" >Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="create_category" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="type" value="add_category">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create New Category</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?php include('errors.php'); ?>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name *</label>
                                    <input class="form-control" required type="text"  name="cat_name" id="cat_name_add" value="<?=isset($_POST['cat_name'])?$_POST['cat_name']:''?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Slug *</label>
                                    <input class="form-control" required type="text"  name="cat_slug" id="cat_slug_add" value="<?=isset($_POST['cat_slug'])?$_POST['cat_slug']:''?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Image *</label>
                                    <input class="form-control" required type="file"  name="cat_image">
                                    <i class="text-danger">Size 100x100</i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>RSS Image</label>
                                    <input class="form-control" required type="file"  name="rss_image">
                                    <i class="text-danger">Size 100x100</i>
                                </div>
                            </div>
                            <div class="col-md-12 d-none">
                                <div class="form-group">
                                    <label>Prediction Page Text</label>
                                    <textarea class="prediction_text_div" name="prediction_text"><?=isset($_POST['prediction_text'])?$_POST['prediction_text']:''?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Resulta Page Meta Informations
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Title</label>
                                <textarea class="form-control" name="result_meta_title"><?=isset($_POST['result_meta_desc'])?$_POST['result_meta_desc']:''?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Description</label>
                                <textarea class="form-control" maxlength="500" name="result_meta_desc"><?=isset($_POST['result_meta_desc'])?$_POST['result_meta_desc']:''?></textarea>
                                <i>Maximum 160 chracters</i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Predictions Page Meta Informations
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Title</label>
                                <textarea class="form-control" name="prediction_meta_title"><?=isset($_POST['prediction_meta_title'])?$_POST['prediction_meta_title']:''?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Description</label>
                                <textarea class="form-control" maxlength="500" name="prediction_meta_desc"><?=isset($_POST['prediction_meta_desc'])?$_POST['prediction_meta_desc']:''?></textarea>
                                <i>Maximum 160 chracters</i>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Resulta Post Page Meta Informations
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Title</label>
                                <textarea class="form-control" name="result_post_meta_title"><?=isset($_POST['result_post_meta_title'])?$_POST['result_post_meta_title']:''?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Description</label>
                                <textarea class="form-control" maxlength="500" name="result_post_meta_desc"><?=isset($_POST['result_post_meta_desc'])?$_POST['result_post_meta_desc']:''?></textarea>
                                <i>Maximum 160 chracters</i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Predictions Post Page Meta Informations
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Title</label>
                                <textarea class="form-control" name="prediction_post_meta_title"><?=isset($_POST['prediction_post_meta_title'])?$_POST['prediction_post_meta_title']:''?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Description</label>
                                <textarea class="form-control" maxlength="500" name="prediction_post_meta_desc"><?=isset($_POST['prediction_post_meta_desc'])?$_POST['prediction_post_meta_desc']:''?></textarea>
                                <i>Maximum 160 chracters</i>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Hot Numbers Meta Informations
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Title</label>
                                <textarea class="form-control" name="hot_meta_title"><?=isset($_POST['hot_meta_title'])?$_POST['hot_meta_title']:''?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Description</label>
                                <textarea class="form-control" maxlength="500" name="hot_meta_desc"><?=isset($_POST['hot_meta_desc'])?$_POST['hot_meta_desc']:''?></textarea>
                                <i>Maximum 160 chracters</i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Cold Numbers Meta Informations
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Title</label>
                                <textarea class="form-control" name="cold_meta_title"><?=isset($_POST['cold_meta_title'])?$_POST['cold_meta_title']:''?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Description</label>
                                <textarea class="form-control" maxlength="500" name="cold_meta_desc"><?=isset($_POST['cold_meta_desc'])?$_POST['cold_meta_desc']:''?></textarea>
                                <i>Maximum 160 chracters</i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Result History Page Informations
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Title</label>
                                <textarea class="form-control" name="history_meta_title"><?=isset($_POST['history_meta_title'])?$_POST['history_meta_title']:''?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Description</label>
                                <textarea class="form-control" maxlength="500" name="history_meta_desc"><?=isset($_POST['history_meta_desc'])?$_POST['history_meta_desc']:''?></textarea>
                                <i>Maximum 160 chracters</i>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" >Add</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <?php include 'footer.php'; ?>
</div>
<script>
    $("#cat_name_add").keyup(function() {
        var Text = $(this).val();
        Text = Text.toLowerCase();
        Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
        $("#cat_slug_add").val(Text);
    });
    function slugifyit(_this) {
        var Text = $(_this).val();
        Text = Text.toLowerCase();
        Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
        $(_this).val(Text);
    }
    <?php if(!empty($errors)){?>
    $('#create_category').modal('show');
    <?php } ?>


</script>
</body>
</html>
