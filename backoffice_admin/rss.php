<?php
include('../includes/config.php');
if(isset($_SESSION['user']) && !empty($_SESSION['user']) && isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])){
    //Do Nothing
}else{
    header("Location: login.php");
    exit;
}
if(isset($_POST) && !empty($_POST)){
    /*echo '<pre>';
    print_r($_POST);exit;*/
    if($_POST['type'] == 'config'){
        $errors = [];

        $query_update = "Update settings set value = '".$_POST['rss_results_title']."' WHERE slug = 'rss_results_title'";
        mysqli_query($con, $query_update);
        $query_update = "Update settings set value = '".$_POST['rss_results_desc']."' WHERE slug = 'rss_results_desc'";
        mysqli_query($con, $query_update);

        $query_update = "Update settings set value = '".$_POST['rss_predictions_title']."' WHERE slug = 'rss_predictions_title'";
        mysqli_query($con, $query_update);
        $query_update = "Update settings set value = '".$_POST['rss_predictions_desc']."' WHERE slug = 'rss_predictions_desc'";
        mysqli_query($con, $query_update);

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
            <li class="breadcrumb-item active">Manage RSS Settings</li>
        </ol>
        <!-- Example Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Manage RSS Settings</div>
            <div class="card-body">
                <div class="card-body">
                    <?php
                    $query = "Select * From settings";
                    $results = mysqli_query($con, $query);
                    $settings = [];
                    while($row=mysqli_fetch_array($results)){
                        $settings[$row['slug']] = $row['value'];
                    }
                    ?>
                    <form method="post" action="" enctype="multipart/form-data">
                        <?php include('errors.php'); ?>
                        <input type="hidden" name="type" value="config">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> RSS Variables
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    ##category-name##<br>
                                                    This is category name variable
                                                </div>
                                                <div class="col-md-4">
                                                    ##result-date##<br>
                                                    This is result date variable
                                                </div>
                                                <div class="col-md-4">
                                                    ##prediction-date##<br>
                                                    This is prediction date variable
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Results RSS Settings
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Results Title *</label>
                                                        <input class="form-control" type="text"  name="rss_results_title" value="<?=isset($settings['rss_results_title'])?$settings['rss_results_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Results Descriptions *</label>
                                                        <textarea class="form-control" placeholder='' type="text"  name="rss_results_desc"><?=isset($settings['rss_results_desc'])?$settings['rss_results_desc']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Predictions RSS Settings
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Predictions Title *</label>
                                                        <input class="form-control" type="text"  name="rss_predictions_title" value="<?=isset($settings['rss_predictions_title'])?$settings['rss_predictions_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Predictions Descriptions *</label>
                                                        <textarea class="form-control" placeholder='' type="text"  name="rss_predictions_desc"><?=isset($settings['rss_predictions_desc'])?$settings['rss_predictions_desc']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Generated RSS
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php
                                                        $xml = file_get_contents($site_url.'/rss');
                                                        $dom = new \DOMDocument('1.0');
                                                        $dom->preserveWhiteSpace = false;
                                                        $dom->formatOutput = true;
                                                        $dom->loadXML($xml);
                                                        $xml_pretty = $dom->saveXML();
                                                        echo nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($xml_pretty)));
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <?php include 'footer.php'; ?>
</div>
</body>

</html>
