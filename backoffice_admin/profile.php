<?php
include('../includes/config.php');
if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
    //Do Nothing
}else{
    header("Location: login.php");
    exit;
}
if(isset($_POST) && !empty($_POST)){

    if($_POST['type'] == 'profile'){
        $errors = [];
        if(empty($_POST['email'])){
            $errors[] = 'Please Enter The Email.';
        }

        if(!empty(trim($_POST['password'])) && $_POST['password'] != $_POST['confirm_password']){
            $errors[] = 'Password And Confirm Password  Must Be Same.';
        }
        if(empty($errors)){
            $query_update = "Update users set email = '".$_POST['email']."' WHERE id = '".$_SESSION['user']['id']."'";
            mysqli_query($con, $query_update);
            $_SESSION['user']['email'] = $_POST['email'];

            if(!empty(trim($_POST['password'])) && $_POST['password'] != $_POST['confirm_password']){
                $query_update = "Update users set password = '".base64_encode($_POST['password'])."' WHERE id = '".$_SESSION['user']['id']."'";
                mysqli_query($con, $query_update);
                $_SESSION['user']['password'] = base64_encode($_POST['password']);
            }
            $_SESSION['success'] = 'Profile Update Successfully.';
            header("Location: profile.php");
            exit;
        }
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
            <li class="breadcrumb-item active">Profile</li>
        </ol>
        <!-- Example Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Manage Profile</div>
            <div class="card-body">
                <div class="card-body">
                    <form method="post" action="" enctype="multipart/form-data">
                        <?php
                        include('success.php');
                        include('errors.php');
                        ?>
                        <input type="hidden" name="type" value="profile">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input class="form-control" required type="text"  name="email" value="<?=$_SESSION['user']['email']?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-5">
                                    <a href="javascript:void(0)" onclick="show_old_password()"><i class="fa fa-eye"></i> Show old password</a>
                                    <i class="ml-5" id="old_password_div" style="display: none"><?=base64_decode($_SESSION['user']['password'])?></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password *</label>
                                    <input class="form-control" type="password"  name="password" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirm Password *</label>
                                    <input class="form-control" type="password"  name="confirm_password" value="">
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
        
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <?php include 'footer.php'; ?>
    <script>
        function show_old_password() {
            $('#old_password_div').toggle();
        }
    </script>
</div>
</body>

</html>
