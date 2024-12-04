<?php
include('../includes/config.php');
if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
    //Do Nothing
}else{
    header("Location: login.php");
    exit;
}
$lang = 'en';
if(isset($_GET['slug']) && !empty($_GET['slug'])){
    //
    if(isset($_GET['lang']) && !empty($_GET['lang'])){
        $lang = $_GET['lang'];
    }
    $query_blog = "Select * From blog where slug = '".$_GET['slug']."' AND lang = '".$lang."' Limit 1";
    $result_blog = mysqli_query($con, $query_blog);
    if(mysqli_num_rows($result_blog) == '0'){
        header("Location: blogs.php");
        exit;
    }
    $row_blog = mysqli_fetch_array($result_blog);
}else{
    header("Location: blogs.php");
    exit;
}
if(isset($_POST) && !empty($_POST)){
    /*echo '<pre>';
    print_r($_POST);
    print_r($_FILES);
    exit;*/
    if($_POST['type'] == 'update_blog'){
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
        if(empty($_POST['short_description'])){
            $errors[] = 'Please Enter The Short Description.';
        }
        if(empty($_POST['long_description'])){
            $errors[] = 'Please Enter The Long Description.';
        }
        if(isset($_FILES['feature_image']) && !empty($_FILES['feature_image']['name'])){
            if(empty($errors)){
                $uploaddir = '../images/blog_images/';
                $uploadfile = time().basename($_FILES['feature_image']['name']);
                move_uploaded_file($_FILES['feature_image']['tmp_name'], $uploaddir.$uploadfile);
                $feature_image = $uploadfile;
                /*$feature_image = webpConvert2($uploaddir.$uploadfile, $compression_quality = 80);
                $feature_image = $uploadfile.'.webp';
                unlink($uploaddir.$uploadfile);*/
            }
        }else{
            $feature_image = $_POST['old_feature_image'];
        }
        if(empty($errors)){
            $posted_date = (isset($_POST['posted_date']) && !empty($_POST['posted_date'])) ? $_POST['posted_date'] : date('Y-m-d');

            $query_blog_exists = "Select id From blog where slug = '".$_POST['slug']."' AND lang = '".$_POST['lang']."' Limit 1";
            $result_blog_exists = mysqli_query($con, $query_blog_exists);
            if(mysqli_num_rows($result_blog_exists) == '0'){
                $query = "INSERT INTO blog set lang = '".$_POST['lang']."', posted_date = '".$posted_date."', slug = '".$_POST['slug']."', title = '".addslashes($_POST['title'])."', feature_image = '".$feature_image."', short_desc = '".addslashes($_POST['short_description'])."',long_desc = '".addslashes($_POST['long_description'])."', meta_tags = '".addslashes($_POST['meta_tags'])."', meta_description = '".addslashes($_POST['meta_description'])."'";
                mysqli_query($con, $query);
            }else{
                $row_blog_exists = mysqli_fetch_array($result_blog_exists);
                $query = "Update blog set lang = '".$_POST['lang']."', posted_date = '".$posted_date."', slug = '".$_POST['slug']."', title = '".addslashes($_POST['title'])."', feature_image = '".$feature_image."', short_desc = '".addslashes($_POST['short_description'])."',long_desc = '".addslashes($_POST['long_description'])."', meta_tags = '".addslashes($_POST['meta_tags'])."', meta_description = '".addslashes($_POST['meta_description'])."' WHERE id = '".$row_blog_exists['id']."'";
                mysqli_query($con, $query);
            }
            header("Location: blog_edit.php?slug=".$_POST['slug'].'&lang='.$_POST['lang']);
            exit;
        }
    }
}

/*Other languages contents exists*/
$query_lang_content = "Select lang From blog where slug = '".$_GET['slug']."'";
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
                <a href="blogs.php">Blogs</a>
            </li>
            <li class="breadcrumb-item active">Update Blog</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Update Blog</div>
            <div class="card-body">
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <?php include('errors.php'); ?>
                        <input type="hidden" name="type" value="update_blog">
                        <div class="row">
                            <div class="col-md-2 d-none">
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
                                    <input class="form-control" required type="text"  name="title" id="title" value="<?=$row_blog['title']?>">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Slug*</label>
                                    <input class="form-control" required type="text"  name="slug" id="slug" value="<?=$row_blog['slug']?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label>Short Description *</label>
                                <textarea class="form-control" name="short_description" required><?=$row_blog['short_desc']?></textarea>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Feature Image *</label>
                                    <input class="form-control"  type="file"  name="feature_image" id="feature_image">
                                    <i>Size 800 x 450</i>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="hidden" name="old_feature_image" value="<?=$row_blog['feature_image']?>">
                                <img class="img-thumbnail" id="feature_image_thumb" src="../images/blog_images/<?=$row_blog['feature_image']?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Long Description *</label>
                                <textarea class="form-control" name="long_description" id="long_description"><?=$row_blog['long_desc']?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Tags</label>
                                <textarea class="form-control" name="meta_tags"><?=$row_blog['meta_tags']?></textarea>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Meta Description</label>
                                <textarea class="form-control" maxlength="160" name="meta_description"><?=$row_blog['meta_description']?></textarea>
                                <i>Maximum 160 chracters</i>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label>Posted Date</label>
                                <input class="form-control"  type="date"  name="posted_date" id="posted_date" value="<?=$row_blog['posted_date']?>">
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-4">&nbsp;</div><div class="col-md-4"><button type="submit" class="btn btn-primary btn-block">Update Blog</button></div><div class="col-md-4">&nbsp;</div>
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
        /*$("#title").keyup(function() {
         var Text = $(this).val();
         Text = Text.toLowerCase();
         Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
         $("#slug").val(Text);
         });*/
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#feature_image_thumb').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#feature_image").change(function(){
            readURL(this);
        });
        function checkIsAdded(_this) {
            var is_added = $(_this).find(':selected').data('is-added');
            var selected_lang = _this.value;
            if(is_added == 'yes'){
                window.location.href="blog_edit.php?slug=<?=$_GET['slug']?>&lang="+selected_lang;
            }
        }
    </script>
</div>
</body>

</html>
