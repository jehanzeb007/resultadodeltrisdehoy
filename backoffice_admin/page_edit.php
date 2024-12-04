<?php
include('../includes/config.php');
if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
    //Do Nothing
}else{
    header("Location: login.php");
    exit;
}
$lang = 'es';
if(isset($_GET['slug']) && !empty($_GET['slug'])){
    //
    if(isset($_GET['lang']) && !empty($_GET['lang'])){
        $lang = $_GET['lang'];
    }
    $query_page = "Select * From pages where slug = '".$_GET['slug']."' AND lang = '".$lang."' Limit 1";
    $result_page = mysqli_query($con, $query_page);
    if(mysqli_num_rows($result_page) == '0'){
        header("Location: pages.php");
        exit;
    }
    $row_page = mysqli_fetch_array($result_page);
}else{
    header("Location: pages.php");
    exit;
}
if(isset($_POST) && !empty($_POST)){
    /*echo '<pre>';
    print_r($_POST);
    print_r($_FILES);
    exit;*/
    if($_POST['type'] == 'update_page'){
        $errors = [];
        if(empty($_POST['lang'])){
            $errors[] = 'Please Select The Language.';
        }
        if(empty($_POST['title'])){
            $errors[] = 'Please Enter The Title.';
        }
        if(empty($_POST['slug'])){
            $errors[] = 'Please Enter The Slug.';
        }
        if(empty($_POST['long_description'])){
            $errors[] = 'Please Enter The Long Description.';
        }
        if(empty($errors)){

            $query_page_exists = "Select id From pages where slug = '".$_POST['slug']."' AND lang = '".$_POST['lang']."' Limit 1";
            $result_page_exists = mysqli_query($con, $query_page_exists);
            if(mysqli_num_rows($result_page_exists) == '0'){
                $query = "INSERT INTO pages set lang = '".$_POST['lang']."', slug = '".$_POST['slug']."', title = '".addslashes($_POST['title'])."',long_desc = '".addslashes($_POST['long_description'])."', meta_tags = '".addslashes($_POST['meta_tags'])."', meta_description = '".addslashes($_POST['meta_description'])."'";
                mysqli_query($con, $query);
            }else{
                $row_page_exists = mysqli_fetch_array($result_page_exists);
                $query = "Update pages set lang = '".$_POST['lang']."', slug = '".$_POST['slug']."', title = '".addslashes($_POST['title'])."', long_desc = '".addslashes($_POST['long_description'])."', meta_tags = '".addslashes($_POST['meta_tags'])."', meta_description = '".addslashes($_POST['meta_description'])."' WHERE id = '".$row_page_exists['id']."'";
                mysqli_query($con, $query);
            }
            header("Location: page_edit.php?slug=".$_POST['slug'].'&lang='.$_POST['lang']);
            exit;
        }
    }
}

/*Other languages contents exists*/
$query_lang_content = "Select lang From pages where slug = '".$_GET['slug']."'";
$result_lang_content = mysqli_query($con, $query_lang_content);
$langs_content_exists = [];
while ($row_lang_content = mysqli_fetch_array($result_lang_content)){
    $langs_content_exists[$row_lang_content['lang']] = $row_lang_content['lang'];
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

    <!-- Place the following <script> and <textarea> tags your HTML's <body> -->
    <script>
        tinymce.init({
            selector: 'textarea#long_description',
            plugins: [
                'autolink','lists','link','image','charmap','preview','anchor','searchreplace','visualblocks','fullscreen','insertdatetime','media','table','help','wordcount'
            ],
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
            <li class="breadcrumb-item">
                <a href="pages.php">Pages</a>
            </li>
            <li class="breadcrumb-item active">Update Page</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Update Page</div>
            <div class="card-body">
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <?php include('errors.php'); ?>
                        <input type="hidden" name="type" value="update_page">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Language*</label>
                                    <select class="form-control" name="lang" required onchange="checkIsAdded(this)">
                                        <?php
                                        foreach($lang_arr as $row_lang){
                                            $tColor = 'red';
                                            $isAdded = 'no';
                                            if(isset($langs_content_exists[$row_lang['code']])){
                                                $tColor = 'green';
                                                $isAdded = 'yes';
                                            }
                                            ?>
                                            <option data-is-added="<?=$isAdded;?>" style="color: <?=$tColor?>" value="<?=$row_lang['code']?>" <?=$lang == $row_lang['code']?'selected':''?>><?=$row_lang['name']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Title*</label>
                                    <input class="form-control" required type="text"  name="title" id="title" value="<?=$row_page['title']?>">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Slug*</label>
                                    <input class="form-control" required type="text"  name="slug" id="slug" value="<?=$row_page['slug']?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Long Description *</label>
                                <textarea class="form-control" name="long_description" id="long_description"><?=$row_page['long_desc']?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Tags</label>
                                <textarea class="form-control" name="meta_tags"><?=$row_page['meta_tags']?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Description</label>
                                <textarea class="form-control" maxlength="160" name="meta_description"><?=$row_page['meta_description']?></textarea>
                                <i>Maximum 160 chracters</i>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-4">&nbsp;</div><div class="col-md-4"><button type="submit" class="btn btn-primary btn-block">Update Page</button></div><div class="col-md-4">&nbsp;</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <?php include 'footer.php'; ?>
    <script>
        function checkIsAdded(_this) {
            var is_added = $(_this).find(':selected').data('is-added');
            var selected_lang = _this.value;
            if(is_added == 'yes'){
                window.location.href="page_edit.php?slug=<?=$_GET['slug']?>&lang="+selected_lang;
            }
        }
    </script>
</div>
</body>

</html>
