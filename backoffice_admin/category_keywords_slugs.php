<?php
include('../includes/config.php');

if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
//Do Nothing
}else{
    header("Location: login.php");
    exit;
}
if(isset($_POST) && !empty($_POST)) {
    if ($_POST['type'] == 'add_translation') {

        $errors = [];
        if (empty($_POST['new_slug'])) {
            $errors[] = 'Please Enter The Translation Slug.';
        }
        if(empty($errors)){
            $query_slug_exists = "Select id From category_keywords where slug = '".$_POST['new_slug']."' Limit 1";
            $result_slug_exists = mysqli_query($con, $query_slug_exists);
            if(mysqli_num_rows($result_slug_exists) > '0'){
                $errors[] = 'Sorry! This Slug Already Exists.';
            }
        }
        if(empty($errors)){
            foreach ($_POST['translation'] as $cat_id=>$translation){
                $query_insert = "INSERT INTO category_keywords SET slug = '".$_POST['new_slug']."', translation = '".addslashes($translation)."', cat_id = '".$cat_id."'";
                mysqli_query($con, $query_insert);
            }
            generateCategoryLangJson($con);
            header("Location: category_keywords_slugs.php");
            exit;
        }
    }
    if ($_POST['type'] == 'edit_translation') {
        /*echo '<pre>';
        print_r($_POST);
        exit;*/
        $errors = [];
        if (empty($_POST['updated_slug'])) {
            $errors[] = 'Please Enter The Translation Slug.';
        }
        if(empty($errors)){
            /*echo json_encode($_POST['translation']);*/
            //debug($_POST,1);exit;
            mysqli_query($con, "Delete From category_keywords where slug = '".$_POST['edit_translation_old_slug']."'") or die(mysqli_error($con));
            foreach ($_POST['translation'] as $cat_id=>$translation){
                $query_insert = "INSERT INTO category_keywords SET slug = '".$_POST['updated_slug']."', translation = '".addslashes($translation)."', cat_id = '".$cat_id."'";
                mysqli_query($con, $query_insert);
            }
            generateCategoryLangJson($con);
            header("Location: category_keywords_slugs.php");
            exit;
        }
    }
}
if(isset($_GET['del_translation']) && !empty($_GET['del_translation'])){
    $delete_translation = "Delete From category_keywords where slug = '".$_GET['del_translation']."'";
    mysqli_query($con, $delete_translation) or die(mysqli_error($con));
    generateCategoryLangJson($con);
    header("Location: category_keywords_slugs.php");
    exit;
}

$query_cat_data = "Select * From categories order by id desc";
$result_cat_data = mysqli_query($con, $query_cat_data);
$categories = [];
while ($row_cat=mysqli_fetch_array($result_cat_data)){
    $categories[$row_cat['id']] = $row_cat;
}

$query_data = "Select * From category_keywords order by id desc";
$result_data = mysqli_query($con, $query_data);

$result_data_merge = [];
while($row_data = mysqli_fetch_array($result_data)){
    $result_data_merge[$row_data['slug']][$row_data['cat_id']] = $row_data['translation'];
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
    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Place the first <script> tag in your HTML's <head> -->
    <script src="https://cdn.tiny.cloud/1/7q7yskpxgyzsn6vokqyk3btjlthys6ham0zocxlaqirrj8br/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

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
            <li class="breadcrumb-item active">Category Keywords & Slugs</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Manage Category Keywords & Slugs</div>
            <div class="card-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12"><a href="javascript:void(0)" class="btn btn-primary float-right mb-3" data-toggle="modal" data-target="#create_slug">Create New Slug</a></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Slug Edit</th>
                                <th>Translation</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($result_data_merge as $slug=>$translation_arr){ ?>
                                <tr>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_slug_<?=$slug?>"><?=$slug?></a>
                                        <div class="modal fade" id="edit_slug_<?=$slug?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form method="post" action="" enctype="multipart/form-data">
                                                <input type="hidden" name="type" value="edit_translation">
                                                <input type="hidden" name="edit_translation_old_slug" value="<?=$slug?>">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Update Category Keyword Translations</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-9">
                                                                    <div class="form-group">
                                                                        <label>Slug*</label>
                                                                        <input class="form-control" required type="text"  name="updated_slug" onkeyup="slugifyit(this)" value="<?=$slug?>">
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                foreach($categories as $category){
                                                                    ?>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="w-100"><?=$category['name']?>* <span class="pull-right mr-2">Show Editor <input type="checkbox" onclick="editorEnableDisable(this.checked,'edit_slug_field_<?=$slug.$category['id']?>')"></span></label>
                                                                            <textarea class="form-control tinyint" name="translation[<?=$category['id']?>]" id="edit_slug_field_<?=$slug.$category['id']?>"><?=isset($translation_arr[$category['id']])?$translation_arr[$category['id']]:''?></textarea>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
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
                                    <td>
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <?php
                                            foreach($translation_arr as $trans_category_id=>$trans_value){?>
                                                <tr>
                                                    <td><?=$categories[$trans_category_id]['name']?></td>
                                                    <td><?=substr(htmlentities($trans_value),0,200)?></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </td>

                                    <td class="text-center"><a href="category_keywords_slugs.php?del_translation=<?=$slug?>" onclick="return confirm('are you sure you want to delete this translation?')"><i class="fa fa-trash text-danger"></i></a></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="create_slug" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="type" value="add_translation">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create New Keyword Translations</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-9">
                                <?php include('errors.php'); ?>
                                <div class="form-group">
                                    <label>Slug*</label>
                                    <input class="form-control" required type="text"  name="new_slug" id="new_slug" value="<?=isset($_POST['new_slug'])?$_POST['new_slug']:''?>">
                                </div>
                            </div>
                            <?php foreach($categories as $category){?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="w-100"><?=$category['name']?>* <span class="pull-right mr-2">Show Editor <input type="checkbox" onclick="editorEnableDisable(this.checked,'add_slug_field_<?=$category['id']?>')"></span></label>
                                        <textarea class="form-control tinyint" name="translation[<?=$category['id']?>]" id="add_slug_field_<?=$category['id']?>"><?=isset($_POST['translation'][$category['id']])?$_POST['translation'][$category['id']]:''?></textarea>
                                    </div>
                                </div>
                            <?php } ?>
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
    $("#new_slug").keyup(function() {
        var Text = $(this).val();
        Text = Text.toLowerCase();
        Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
        $("#new_slug").val(Text);
    });
    function slugifyit(_this) {
        var Text = $(_this).val();
        Text = Text.toLowerCase();
        Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
        $(_this).val(Text);
    }
    <?php if(!empty($errors)){?>
    $('#create_slug').modal('show');
    <?php } ?>

    function editorEnableDisable(isInit,FieldId) {
        if(isInit){
            tinymce.init({
                selector: 'textarea#'+FieldId,
                plugins: [
                    'autolink','lists','link','image','charmap','preview','anchor','searchreplace','visualblocks','fullscreen','insertdatetime','media','table','help','wordcount'
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                images_upload_url: 'post_image_upload.php',
                automatic_uploads: true,
                images_upload_base_path: '<?=$site_url?>'

            });
        }else{
            tinymce.remove('textarea#'+FieldId);
        }
    }
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".tox-dialog").length) {
            e.stopImmediatePropagation();
        }
    });
    $(".modal").on('hide.bs.modal', function(){
        tinymce.remove();
    });
    $(".modal").on('show.bs.modal', function(){
        /*tinymce.init({
            selector: '.tinyint',
            plugins: [
                'autolink','lists','link','image','charmap','preview','anchor','searchreplace','visualblocks','fullscreen','insertdatetime','media','table','help','wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            images_upload_url: 'post_image_upload.php',
            automatic_uploads: true,
            images_upload_base_path: '<?=$site_url?>'

        });*/
    });
</script>
</body>
</html>
