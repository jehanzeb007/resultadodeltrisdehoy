<?php
include('../includes/config.php');
/*echo '<pre>';
print_r($_SESSION);*/
if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
    header("Location: index.php");
    exit;
}
if(isset($_POST) && !empty($_POST)){
    if($_POST['type'] == 'login'){
        $errors = [];
        if(empty($_POST['email'])){
            $errors[] = 'Please Enter The Email.';
        }else if(empty($_POST['password'])){
            $errors[] = 'Please Enter The Password.';
        }else{
            $query = "Select * From users where email = '".$_POST['email']."' AND password = '".base64_encode($_POST['password'])."' Limit 1";
            $row = mysqli_query($con, $query);
            if(mysqli_num_rows($row) > 0){
                $_SESSION['user'] = mysqli_fetch_array($row);
                header("Location: index.php");
                exit;
            }else{
                $errors[] = 'Invalid Username or Password.';
            }
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
    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
</head>

<body class="bg-dark">
<div class="container">
    <div class="card card-login mx-auto mt-5">
        <div class="card-header">Login</div>
        <div class="card-body">
            <form method="post" action="">
                <?php include('errors.php'); ?>
                <input type="hidden" name="type" value="login">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input class="form-control"  type="email"  name="email" value="<?=isset($_POST['email'])?$_POST['email']:''?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input class="form-control"  type="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="login_user">Login</button>
            </form>
        </div>
    </div>
</div>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
