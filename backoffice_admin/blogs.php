<?php
include('../includes/config.php');
if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
    //Do Nothing
}else{
    header("Location: login.php");
    exit;
}
if(isset($_GET['del_slug']) && !empty($_GET['del_slug'])){
    $query_delete = "DELETE FROM blog where slug = '".$_GET['del_slug']."'";
    mysqli_query($con, $query_delete);
    header("Location: blogs.php");
    exit;
}
$lang = 'en';
$query_blog = "Select * From blog /*where lang = '".$lang."'*/ order by posted_date desc";
$result_blog = mysqli_query($con, $query_blog);
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
            <li class="breadcrumb-item active">Blogs</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> List Blog
                <a href="create_blog.php" class="btn btn-primary float-right">Create New Blog</a>
            </div>
            <div class="card-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableBlog" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Posted Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Title</th>
                                <th>Posted Date</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php while ($row=mysqli_fetch_array($result_blog)){ ?>
                            <tr>
                                <td><?=$row['title']?></td>
                                <td><?=$row['posted_date']?></td>
                                <td><a href="blog_edit.php?slug=<?=$row['slug']?>&lang=<?=$row['lang']?>"><i class="fa fa-pencil-square"></i> Edit</a>  | <a onclick="return confirm('are you sure you want to delete this blog?')" href="?del_slug=<?=$row['slug']?>" class="text-danger"><i class="fa fa-trash"></i> Delete</a></td>
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
        $('#dataTableBlog').dataTable({
            "order": [[3, 'desc']]
        });
    </script>
</div>
</body>

</html>
