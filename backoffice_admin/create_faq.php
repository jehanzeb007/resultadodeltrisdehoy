<?php
include('../includes/config.php');
if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
    //Do Nothing
}else{
    header("Location: login.php");
    exit;
}
if(isset($_GET['del_id']) && !empty($_GET['del_id'])){
    $query = "Delete from faqs where id  = '".$_GET['del_id']."'";
    mysqli_query($con, $query);
    header("Location: create_faq.php");
    exit;
}
if(isset($_POST) && !empty($_POST)){
    /*echo '<pre>';
    print_r($_POST);
    exit;*/
    if($_POST['type'] == 'create_faq'){
        $errors = [];
        if(empty($_POST['question'])){
            $errors[] = 'Please Enter The Question.';
        }
        if(empty($_POST['answer'])){
            $errors[] = 'Please Enter The Answer.';
        }
        if(empty($errors)){
            $query = "INSERT INTO faqs set lang = '".$_POST['lang']."', page = '".$_POST['page']."', question = '".$_POST['question']."', answer = '".addslashes($_POST['answer'])."'";
            mysqli_query($con, $query);
            header("Location: create_faq.php");
            exit;
        }
    }
    if($_POST['type'] == 'edit_faq'){
        $errors = [];
        if(empty($_POST['question'])){
            $errors[] = 'Please Enter The Question.';
        }
        if(empty($_POST['answer'])){
            $errors[] = 'Please Enter The Answer.';
        }
        if(empty($errors)){
            $query = "UPDATE faqs set lang = '".$_POST['lang']."', page = '".$_POST['page']."', question = '".$_POST['question']."', answer = '".addslashes($_POST['answer'])."' where id = '".$_POST['edit_faq_id']."'";
            mysqli_query($con, $query);
            header("Location: create_faq.php");
            exit;
        }
    }
}

$query_categories = "Select * From categories order by id desc";
$result_categories = mysqli_query($con, $query_categories);
$array_categories = [];
while($row_category = mysqli_fetch_array($result_categories)){
    $array_categories[$row_category['id']] = $row_category;
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
                plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
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
                <a href="create_faq.php">Faqs</a>
            </li>
            <li class="breadcrumb-item active">Create Faq</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Create New Faq
                <button class="btn btn-primary float-right" onclick="toggle_new_faq_div()">+ Add New Faq</button>
            </div>
            <div class="card-body" id="add_new_faq_div" style="display: none;">
                <form method="post" action="" enctype="multipart/form-data">
                    <?php include('errors.php'); ?>
                    <input type="hidden" name="type" value="create_faq">
                    <div class="row">
                        <div class="col-md-2 d-none">
                            <div class="form-group">
                                <label>Language*</label>
                                <select class="form-control" name="lang" required>
                                    <?php
                                    foreach($lang_arr as $row_lang){
                                        ?>
                                        <option value="<?=$row_lang['code']?>" <?=$lang == $row_lang['code']?'selected':''?>><?=$row_lang['name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Page*</label>
                                <select class="form-control" name="page" required>
                                    <optgroup label="Site Pages">
                                        <option value="page__Home">Home</option>
                                        <option value="page__historico-tris">historico-tris</option>
                                    </optgroup>
                                    <?php
                                    foreach($array_categories as $row_category){
                                        ?>
                                        <optgroup label="<?=$row_category['name']?>">
                                            <option value="results__<?=$row_category['id']?>">Results</option>
                                            <option value="predicciones__<?=$row_category['id']?>">Pronósticos</option>
                                            <option value="numeros-calientes__<?=$row_category['id']?>">Números Calientes</option>
                                            <option value="numeros-frios__<?=$row_category['id']?>">Números Fríos</option>
                                            <option value="historico__<?=$row_category['id']?>">Historia</option>
                                        </optgroup>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Question*</label>
                                <input class="form-control" required type="text"  name="question" value="<?=isset($_POST['question'])?$_POST['question']:''?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Answer*</label>
                                <textarea class="form-control" name="answer" required><?=isset($_POST['answer'])?$_POST['answer']:''?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-4">&nbsp;</div><div class="col-md-4"><button type="submit" class="btn btn-primary btn-block">Create Faq</button></div><div class="col-md-4">&nbsp;</div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> List Faqs
                <select name="page" onchange="setPage(this.value)">
                    <optgroup label="Site Pages">
                        <option value="" <?=$_GET['page'] == ''?'selected':''?>>All Pages</option>
                        <option value="page__Home" <?=$_GET['page'] == 'page__Home'?'selected':''?>>Home</option>
                        <option value="page__historico-tris" <?=$_GET['page'] == 'page__historico-tris'?'selected':''?>>historico-tris</option>
                    </optgroup>
                    <?php
                    foreach($array_categories as $row_category){
                        ?>
                        <optgroup label="<?=$row_category['name']?>">
                            <option value="results__<?=$row_category['id']?>" <?=$_GET['page'] == 'results__'.$row_category['id']?'selected':''?>>Results</option>
                            <option value="predicciones__<?=$row_category['id']?>" <?=$_GET['page'] == 'predicciones__'.$row_category['id']?'selected':''?>>Pronósticos</option>
                            <option value="numeros-calientes__<?=$row_category['id']?>" <?=$_GET['page'] == 'numeros-calientes__'.$row_category['id']?'selected':''?>>Números Calientes</option>
                            <option value="numeros-frios__<?=$row_category['id']?>" <?=$_GET['page'] == 'numeros-frios__'.$row_category['id']?'selected':''?>>Números Fríos</option>
                            <option value="historico__<?=$row_category['id']?>" <?=$_GET['page'] == 'historico__'.$row_category['id']?'selected':''?>>Historia</option>
                        </optgroup>
                    <?php } ?>
                </select>
            </div>
            <div class="card-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Page</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $where = '';
                            if(isset($_GET['page']) && !empty($_GET['page'])){
                                $where = "WHERE page='".$_GET['page']."'";
                            }
                            $query_faq = "SELECT * FROM faqs ".$where." order by id desc";
                            $result_faq = mysqli_query($con, $query_faq);
                            while ($row=mysqli_fetch_array($result_faq)){
                                $row_page = explode('__',$row['page']);
                                if(!empty((int)$row_page[1]) && is_int((int)$row_page[1])){
                                    $show_page = $array_categories[$row_page[1]]['name'].' ('.ucfirst($row_page[0]).')';
                                }else{
                                    $show_page = $row_page[1];
                                }
                                ?>
                                <tr>
                                    <!--<td width="5"><?/*=$lang_arr[$row['lang']]['name']*/?> (<?/*=$row['lang']*/?>)</td>-->
                                    <td width="5"><?=$show_page?></td>
                                    <td width="40"><?=$row['question']?></td>
                                    <td width="40"><?=substr($row['answer'],0,150)?></td>
                                    <td width="5"><?=date('F d, Y',strtotime($row['created_at']))?></td>
                                    <td width="10">
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_faq_<?=$row['id']?>"><i class="fa fa-pencil"></i> Edit</a> | <a class="text-danger" href="create_faq.php?del_id=<?=$row['id']?>" onclick="return confirm('Are you sure you want to delete this Faq?')"><i class="fa fa-trash"></i> Delete</a>
                                        <div class="modal fade" id="edit_faq_<?=$row['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form method="post" action="" enctype="multipart/form-data">
                                                <input type="hidden" name="type" value="edit_faq">
                                                <input type="hidden" name="edit_faq_id" value="<?=$row['id']?>">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Update Faq</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-2 d-none">
                                                                    <div class="form-group">
                                                                        <label>Language*</label>
                                                                        <select class="form-control" name="lang" required>
                                                                            <?php
                                                                            foreach($lang_arr as $row_lang){
                                                                                ?>
                                                                                <option value="<?=$row_lang['code']?>" <?=$row['lang'] == $row_lang['code']?'selected':''?>><?=$row_lang['name']?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Page*</label>
                                                                        <select class="form-control" name="page" required>
                                                                            <optgroup label="Site Pages">
                                                                                <option value="page__Home" <?=$row['page'] == 'page__Home'?'selected':''?>>Home</option>
                                                                                <option value="page__historico-tris" <?=$row['page'] == 'page__historico-tris'?'selected':''?>>historico-tris</option>
                                                                            </optgroup>
                                                                            <?php
                                                                            foreach($array_categories as $row_category){
                                                                                ?>
                                                                                <optgroup label="<?=$row_category['name']?>">
                                                                                    <option value="results__<?=$row_category['id']?>" <?=$row['page'] == 'results__'.$row_category['id']?'selected':''?>>Results</option>
                                                                                    <option value="predicciones__<?=$row_category['id']?>" <?=$row['page'] == 'predicciones__'.$row_category['id']?'selected':''?>>Pronósticos</option>
                                                                                    <option value="numeros-calientes__<?=$row_category['id']?>" <?=$row['page'] == 'numeros-calientes__'.$row_category['id']?'selected':''?>>Números Calientes</option>
                                                                                    <option value="numeros-frios__<?=$row_category['id']?>" <?=$row['page'] == 'numeros-frios__'.$row_category['id']?'selected':''?>>Números Fríos</option>
                                                                                    <option value="historico__<?=$row_category['id']?>" <?=$row['page'] == 'historico__'.$row_category['id']?'selected':''?>>Historia</option>
                                                                                </optgroup>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Question*</label>
                                                                        <input class="form-control" required type="text"  name="question" value="<?=$row['question']?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Answer*</label>
                                                                        <textarea class="form-control" name="answer" required><?=$row['answer']?></textarea>
                                                                    </div>
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
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <?php include 'footer.php'; ?>
    <script>
        $("#title").keyup(function() {
            var Text = $(this).val();
            Text = Text.toLowerCase();
            Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
            $("#slug").val(Text);
        });
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
        function toggle_new_faq_div() {
            $('#add_new_faq_div').toggle('slow');
        }
        function setPage(_value) {
            window.location.href = '?page='+_value;
        }
    </script>
</div>
</body>

</html>
