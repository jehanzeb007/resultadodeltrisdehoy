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
    exit;*/
    if ($_POST['type'] == 'add_prediction') {
        $errors = [];
        if (empty($_POST['cat_id'])) {
            $errors[] = 'Please Select Category.';
        }
        if (empty($_POST['numbers_length'])) {
            $errors[] = 'Please Select Numbers Length.';
        }
        if (empty($_POST['prediction_boxes'])) {
            $errors[] = 'Please Enter Prediction Boxes.';
        }
        if(empty($errors)){

            $query = "INSERT INTO predictions set cat_id = '".$_POST['cat_id']."', predic_numbers = '".json_encode($_POST['prediction_boxes'])."',score = '".$_POST['score']."', draw_number = '".$_POST['draw_number']."', date = '".date('Y-m-d',strtotime($_POST['date']))."', time = '".$_POST['time']."'";
            mysqli_query($con,$query) or die(mysqli_error($con));
            header("Location: predictions.php?success=true&msg=Prediction Added Successfully.");
            exit;
        }
    }
    if ($_POST['type'] == 'update_prediction') {
        $errors = [];
        if (empty($_POST['cat_id'])) {
            $errors[] = 'Please Select Category.';
        }
        if (empty($_POST['numbers_length'])) {
            $errors[] = 'Please Select Numbers Length.';
        }
        if (empty($_POST['prediction_boxes'])) {
            $errors[] = 'Please Enter Prediction Boxes.';
        }
        if(empty($errors)){

            $query = "Update predictions set cat_id = '".$_POST['cat_id']."', predic_numbers = '".json_encode($_POST['prediction_boxes'])."',score = '".$_POST['score']."', draw_number = '".$_POST['draw_number']."', date = '".date('Y-m-d',strtotime($_POST['date']))."', time = '".$_POST['time']."' WHERE id = '".$_POST['edit_prediction_id']."'";
            mysqli_query($con,$query) or die(mysqli_error($con));
            header("Location: predictions.php?success=true&msg=Prediction Update Successfully.");
            exit;
        }

    }
}
if(isset($_GET['del_prediction']) && !empty($_GET['del_prediction'])){
    $delete_category = "Delete From  predictions where id = '".$_GET['del_prediction']."'";
    mysqli_query($con, $delete_category);
    generateLangJson($con);
    header("Location: predictions.php?success=true&msg=Predictions Delete Successfully.");
    exit;
}
$query_cat_data = "Select * From categories order by sort_order ASC";
$result_cat_data = mysqli_query($con, $query_cat_data);
$categories = [];
while ($row_cat=mysqli_fetch_array($result_cat_data)){
    $categories[$row_cat['id']] = $row_cat;
}

$cat_str = '';
$limit_str = ' Limit 5';
if(isset($_GET['category']) && !empty($_GET['category'])){
    $cat_str = " WHERE cat_id = '".$_GET['category']."'";
    $limit_str = '';
}
$query_predictions = "Select * From predictions ".$cat_str." order by id desc $limit_str";
$result_predictions = mysqli_query($con, $query_predictions);

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
            <li class="breadcrumb-item active">Predictions</li>
        </ol>
        <?php  if(isset($_GET['success']) && $_GET['success'] == 'true' && !empty($_GET['msg'])) {?>
            <div class="alert alert-success"><?php echo $_GET['msg'];?></div>
        <?php }?>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Manage Predictions</div>
            <div class="card-body">
                <div class="card-body">
                    <form method="GET" action="" autocomplete="off">
                        <div class="row">

                            <div class="col-md-3">
                                <select name="category" class="form-control">
                                    <option value="">All</option>
                                    <?php foreach ($categories as $category){ ?>
                                        <option value="<?=$category['id']?>" <?=$_GET['category'] == $category['id']?'selected':''?>><?=$category['name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                            </div>

                            <div class="col-md-6">
                                <a href="javascript:void(0)" class="btn btn-primary float-right mb-3" data-toggle="modal" data-target="#create_category"><i class="fa fa-plus"></i> Create New Prediction</a>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Category</th>
                                <th>Predictions</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while($row_data = mysqli_fetch_array($result_predictions)){
                                $predic_numbers = json_decode($row_data['predic_numbers'],1);

                                $btn_class = '';
                                $text_class = 'text-dark';
                                if($row_data['score'] == 'Verde'){
                                    $btn_class = 'btn-success';
                                    $text_class = 'text-light';
                                }
                                ?>
                                <tr>
                                    <td><img src="<?=$site_url?>/images/cat_images/<?=$categories[$row_data['cat_id']]['image']?>" style="width: 100px;height: 100px"> <?=$categories[$row_data['cat_id']]['name']?></td>
                                    <td>
                                        <?php foreach ($predic_numbers as $predic_number){ ?>
                                            <button class="btn <?=$btn_class.' '.$text_class?>"><?=$predic_number?></button>
                                        <?php } ?>
                                        <?php if($row_data['score'] != ''){?>
                                            <button class="btn <?=$btn_class.' '.$text_class?>"><?=$row_data['score']?></button>
                                        <?php } ?>
                                        <p>
                                            <span class="badge badge-success"><?=$row_data['draw_number']?></span>&nbsp;&nbsp;
                                            <span class="badge badge-success"><?=date('M d Y',strtotime($row_data['date'])).' '.$row_data['time']?></span>
                                        </p>

                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_prediction_<?=$row_data['id']?>"><i class="fa fa-pencil-square"></i> Edit</a> | <a class="text-danger" href="?del_prediction=<?=$row_data['id']?>" onclick="return confirm('Are you sure you want to delete this prediction?')"><i class="fa fa-trash"></i> Delete</a>
                                        <div class="modal fade" id="edit_prediction_<?=$row_data['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form method="post" action="" enctype="multipart/form-data">
                                                <input type="hidden" name="type" value="update_prediction">
                                                <input type="hidden" name="edit_prediction_id" value="<?=$row_data['id']?>">
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
                                                                <div class="col-md-12">
                                                                    <?php include('errors.php'); ?>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Category *</label>
                                                                        <select name="cat_id" class="form-control" required>
                                                                            <?php foreach ($categories as $category){ ?>
                                                                                <option value="<?=$category['id']?>" <?=$row_data['cat_id'] == $category['id']?'selected':''?>><?=$category['name']?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Numbers  Length*</label>
                                                                        <select name="numbers_length" id="numbers_length" class="form-control" required onchange="draw_prediction_boxes_edit(this.value)">
                                                                            <option value="5" <?=count($predic_numbers) == '5'?'selected':''?>>5</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row" id="update_lenght_append">
                                                                <?php foreach($predic_numbers as $predic_number){ ?>
                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <label>Box *</label>
                                                                            <input class="form-control" required pattern="\d{1}" onkeypress="return isNumberKey(event)" type="number" name="prediction_boxes[]" min="0" max="9" oninput="enforceSingleDigit(this)" value="<?=$predic_number?>">
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3 d-none">
                                                                    <div class="form-group">
                                                                        <label>Score *</label>
                                                                        <select name="score" id="score" class="form-control">
                                                                            <option value="" <?=$row_data['score'] == ''?'selected':''?>>N/a</option>
                                                                            <option value="Blanca" <?=$row_data['score'] == 'Blanca'?'selected':''?>>Blanca</option>
                                                                            <option value="Verde" <?=$row_data['score'] == 'Verde'?'selected':''?>>Verde</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <!--<div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Draw Number *</label>
                                                                        <input class="form-control" type="text" name="draw_number" value="<?/*=$row_data['draw_number']*/?>" required placeholder="#0000">
                                                                    </div>
                                                                </div>-->
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Draw Date *</label>
                                                                        <input class="form-control" type="date" name="date" value="<?=$row_data['date']?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Draw Time *</label>
                                                                        <input class="form-control" type="time" name="time" value="<?=$row_data['time']?>" required>
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
    <div class="modal fade" id="create_category" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="type" value="add_prediction">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Create New Prediction</h5>
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
                                    <label>Category *</label>
                                    <select name="cat_id" class="form-control" required>
                                        <?php foreach ($categories as $category){ ?>
                                            <option value="<?=$category['id']?>" <?=isset($_POST['cat_id']) && $_POST['cat_id'] == $category['id']?'selected':''?>><?=$category['name']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Numbers  Length*</label>
                                    <select name="numbers_length" id="numbers_length" class="form-control" required onchange="draw_prediction_boxes(this.value)">
                                        <!--<option value="">Select Predictions Numbers Length</option>-->
                                        <option value="5" <?=isset($_POST['numbers_length']) && isset($_POST['numbers_length']) == '5'?'selected':''?>>5</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="create_lenght_append">

                        </div>
                        <div class="row">
                            <div class="col-md-3 d-none">
                                <div class="form-group">
                                    <label>Score *</label>
                                    <select name="score" id="score" class="form-control">
                                        <option value="">N/a</option>
                                        <option value="Blanca">Blanca</option>
                                        <option value="Verde">Verde</option>
                                    </select>
                                </div>
                            </div>
                            <!--<div class="col-md-4">
                                <div class="form-group">
                                    <label>Draw Number *</label>
                                    <input class="form-control" type="text" name="draw_number" value="" required placeholder="#0000">
                                </div>
                            </div>-->
                            <?php
                            $currentDateTime = new DateTime("now", new DateTimeZone("UTC"));

                            // Set the timezone to Mexico City
                            $mexicoCityTimeZone = new DateTimeZone("America/Mexico_City");
                            $currentDateTime->setTimezone($mexicoCityTimeZone);

                            // Format the date and time as needed
                            //$formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
                            ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Draw Date *</label>
                                    <input class="form-control" type="date" name="date" value="<?=$currentDateTime->format('Y-m-d');?>" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Draw Time *</label>
                                    <input class="form-control" type="time" name="time" value="<?=$currentDateTime->format('h:i');?>" required>
                                </div>
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
    <div style="display: none;" id="prediction_box_html">
        <div class="col-md-2">
            <div class="form-group">
                <label>Box *</label>
                <input class="form-control" required pattern="\d{1}" onkeypress="return isNumberKey(event)" type="number" name="prediction_boxes[]" min="0" max="9" oninput="enforceSingleDigit(this)">
            </div>
        </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <?php include 'footer.php'; ?>
</div>
<script>
    draw_prediction_boxes(5);
    <?php if(!empty($errors)){?>
    $('#create_category').modal('show');
    <?php } ?>
    function draw_prediction_boxes(_val) {
        $('#create_lenght_append').html('');
        if(_val > 0){
            for (var i = 0; i < _val; i++) {
                var append_str = $('#prediction_box_html').html();
                $('#create_lenght_append').append(append_str);
            }
        }
    }
    function draw_prediction_boxes_edit(_val) {
        $('#update_lenght_append').html('');
        if(_val > 0){
            for (var i = 0; i < _val; i++) {
                var append_str = $('#prediction_box_html').html();
                $('#update_lenght_append').append(append_str);
            }
        }
    }
    function enforceSingleDigit(element) {
        // Trim the value to the first character if it exceeds one character
        if (element.value.length > 1) {
            element.value = element.value.slice(0, 1);
        }

        // If the value length is 1, move to the next input field
        if (element.value.length === 1) {
            // Find the next input element
            let nextElement = getNextInput(element);
            if (nextElement) {
                nextElement.focus();
            }
        }
    }
    function getNextInput(element) {
        // Find the next input element within the same form
        let inputs = Array.from(document.querySelectorAll('input[type="number"]'));
        let index = inputs.indexOf(element);
        if (index !== -1 && index < inputs.length - 1) {
            return inputs[index + 1];
        }
        return null;
    }
    function isNumberKey(evt) {
        // Allow only numbers 0-9
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode < 48 || charCode > 57) {
            return false;
        }
        return true;
    }
</script>
</body>
</html>
