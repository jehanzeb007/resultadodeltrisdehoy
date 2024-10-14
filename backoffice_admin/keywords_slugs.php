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
            $query_slug_exists = "Select id From keywords where slug = '".$_POST['new_slug']."' Limit 1";
            $result_slug_exists = mysqli_query($con, $query_slug_exists);
            if(mysqli_num_rows($result_slug_exists) > '0'){
                $errors[] = 'Sorry! This Slug Already Exists.';
            }
        }
        if(empty($errors)){
            foreach ($_POST['translation'] as $lang_key=>$words_translations){
                $query_insert = "INSERT INTO keywords set page_name = '".$_POST['page_name']."', slug = '".$_POST['new_slug']."', lang = '".$lang_key."', translation = '".addslashes($words_translations)."'";
                mysqli_query($con, $query_insert) or die(mysqli_error($con));
            }
            generateLangJson($con);
            header("Location: keywords_slugs.php");
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
            $delete_old_translation = "Delete From keywords where slug = '".$_POST['edit_translation_old_slug']."'";
            mysqli_query($con, $delete_old_translation);
            foreach ($_POST['translation'] as $lang_key=>$words_translations){
                $query_insert = "INSERT INTO keywords set page_name = '".$_POST['page_name']."', slug = '".$_POST['updated_slug']."', lang = '".$lang_key."', translation = '".addslashes($words_translations)."'";
                mysqli_query($con, $query_insert);
            }
            generateLangJson($con);
            header("Location: keywords_slugs.php");
            exit;
        }
    }
}
if(isset($_GET['del_translation']) && !empty($_GET['del_translation'])){
    $delete_translation = "Delete From keywords where slug = '".$_GET['del_translation']."'";
    mysqli_query($con, $delete_translation);
    generateLangJson($con);
    header("Location: keywords_slugs.php");
    exit;
}
$query_data = "Select * From keywords /*where lang = 'en'*/ order by id desc";
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
            <li class="breadcrumb-item active">Keywords & Slugs</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Manage Keywords & Slugs</div>
            <div class="card-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12"><a href="javascript:void(0)" class="btn btn-primary float-right mb-3" data-toggle="modal" data-target="#create_slug">Create New Slug</a></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Page Name</th>
                                <th>Slug Edit</th>
                                <?php
                                foreach($lang_arr as $site_lang_row){?>
                                    <th><?=$site_lang_row['name']?></th>
                                <?php } ?>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while($row_data = mysqli_fetch_array($result_data)){ ?>
                                <tr>
                                    <td><?=$row_data['page_name']?></td>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_slug_<?=$row_data['id']?>"><?=$row_data['slug']?></a>
                                        <div class="modal fade editModal" id="edit_slug_<?=$row_data['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form method="post" action="" enctype="multipart/form-data">
                                                <input type="hidden" name="type" value="edit_translation">
                                                <input type="hidden" name="edit_translation_id" value="<?=$row_data['id']?>">
                                                <input type="hidden" name="edit_translation_old_slug" value="<?=$row_data['slug']?>">
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
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Page *</label>
                                                                        <select class="form-control" name="page_name">
                                                                            <option value="any" <?=$row_data['page_name']=='any'?'selected':''?>>Any Page</option>
                                                                            <option value="home" <?=$row_data['page_name']=='home'?'selected':''?>>Home</option>
                                                                            <option value="pronosticos" <?=$row_data['page_name']=='pronosticos'?'selected':''?>>Pronosticos</option>
                                                                            <option value="blog" <?=$row_data['page_name']=='blog'?'selected':''?>>Blog</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="form-group">
                                                                        <label>Slug*</label>
                                                                        <input class="form-control" required type="text"  name="updated_slug" onkeyup="slugifyit(this)" value="<?=$row_data['slug']?>">
                                                                    </div>
                                                                </div>
                                                                <?php foreach($lang_arr as $row_lang){?>
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="w-100"><?=$row_lang['name']?>* <span class="pull-right mr-2">Show Editor <input type="checkbox" onclick="editorEnableDisable(this.checked,'edit_slug_field_<?=$row_data['id']?>')"></span></label>
                                                                            <textarea class="form-control translation_box" type="text" required  name="translation[<?=$row_lang['code']?>]" id="edit_slug_field_<?=$row_data['id']?>"><?=stripslashes($translations[$row_lang['code']][$row_data['slug']])?></textarea>
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
                                    <?php
                                    foreach($lang_arr as $site_lang_row){?>
                                        <td><?=substr($translations[$site_lang_row['code']][$row_data['slug']],0,200)?></td>
                                    <?php } ?>
                                    <td class="text-center"><a href="keywords_slugs.php?del_translation=<?=$row_data['slug']?>" onclick="return confirm('are you sure you want to delete this translation?')"><i class="fa fa-trash text-danger"></i></a></td>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Page *</label>
                                    <select class="form-control" name="page_name">
                                        <option value="any" <?=isset($_POST['page_name']) && $_POST['page_name']=='any'?'selected':''?>>Any Page</option>
                                        <option value="home" <?=isset($_POST['page_name']) && $_POST['page_name']=='home'?'selected':''?>>Home</option>
                                        <option value="pronosticos" <?=isset($_POST['page_name']) && $_POST['page_name']=='pronosticos'?'selected':''?>>Pronosticos</option>
                                        <option value="blog" <?=isset($_POST['page_name']) && $_POST['page_name']=='blog'?'selected':''?>>Blog</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <?php include('errors.php'); ?>
                                <div class="form-group">
                                    <label>Slug*</label>
                                    <input class="form-control" required type="text"  name="new_slug" id="new_slug" value="<?=isset($_POST['new_slug'])?$_POST['new_slug']:''?>">
                                </div>
                            </div>
                            <?php foreach($lang_arr as $row_lang){?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="w-100"><?=$row_lang['name']?>* <span class="pull-right mr-2">Show Editor <input type="checkbox" onclick="editorEnableDisable(this.checked,'add_slug_field_<?=$row_lang['code']?>')"></span></label>
                                        <textarea class="form-control" name="translation[<?=$row_lang['code']?>]" id="add_slug_field_<?=$row_lang['code']?>"><?=isset($_POST['translation'][$row_lang['code']])?$_POST['translation'][$row_lang['code']]:''?></textarea>
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
            tinymce.remove();
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
</script>
</body>
</html>
