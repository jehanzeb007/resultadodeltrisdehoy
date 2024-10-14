<?php
include('../includes/config.php');

if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
//Do Nothing
}else{
    header("Location: login.php");
    exit;
}
if(isset($_POST) && !empty($_POST)) {
    if ($_POST['type'] == 'edit_comment') {
        $query_update_comment = "UPDATE comments SET comment='".addslashes($_POST['comment'])."',name='".addslashes($_POST['name'])."',email='".addslashes($_POST['email'])."',status='".$_POST['status']."' where id = '".$_POST['edit_comment_id']."'";
        mysqli_query($con,$query_update_comment);
        header("Location: comments.php");
        exit;
    }
}
if(isset($_GET['del_comment']) && !empty($_GET['del_comment'])){
    $delete_translation = "Delete From comments where id = '".$_GET['del_comment']."'";
    mysqli_query($con, $delete_translation);
    header("Location: comments.php");
    exit;
}
$query_data = "Select * From comments order by id desc";
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
            <li class="breadcrumb-item active">Comments</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Manage Comments</div>
            <div class="card-body">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12"><a href="javascript:void(0)" class="btn btn-primary float-right mb-3" data-toggle="modal" data-target="#create_slug">Create New Slug</a></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Name/Email</th>
                                <th>Comments</th>
                                <th>Status</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while($row_data = mysqli_fetch_array($result_data)){ ?>
                                <tr>
                                    <td><?=$row_data['name']?><br><?=$row_data['email']?></td>
                                    <td><?=$row_data['comment']?></td>
                                    <td><?=$row_data['status'] == '1'?'Approved':'Not Approve'?></td>
                                    <td class="text-center">
                                        <a href="comments.php?del_comment=<?=$row_data['id']?>" onclick="return confirm('are you sure you want to delete this comment?')"><i class="fa fa-trash text-danger"></i></a>
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_comment_<?=$row_data['id']?>"><i class="fa fa-pencil-square"></i></a>
                                        <div class="modal fade" id="edit_comment_<?=$row_data['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <form method="post" action="" enctype="multipart/form-data">
                                                <input type="hidden" name="type" value="edit_comment">
                                                <input type="hidden" name="edit_comment_id" value="<?=$row_data['id']?>">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Update Comment</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Name*</label>
                                                                        <input class="form-control" required type="text"  name="name" value="<?=$row_data['name']?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Email*</label>
                                                                        <input class="form-control" required type="text"  name="email" value="<?=$row_data['email']?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Comment*</label>
                                                                        <textarea class="form-control" required name="comment" ><?=$row_data['comment']?></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Status *</label>
                                                                        <select class="form-control" name="status">
                                                                            <option value="1" <?=$row_data['status']=='1'?'selected':''?>>Approve</option>
                                                                            <option value="home" <?=$row_data['status']=='0'?'selected':''?>>Not Approve</option>
                                                                        </select>
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
</div>
</body>
</html>
