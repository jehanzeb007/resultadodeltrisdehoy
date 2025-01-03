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
        /*if(empty($_POST['site_name'])){
            $errors[] = 'Please Enter The Site Name.';
        }else */if(empty($_POST['contact_us'])){
            $errors[] = 'Please Enter The Contact Email.';
        }else{
            $query_update = "Update settings set value = '".$_POST['site_name']."' WHERE slug = 'site_name'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['contact_us']."' WHERE slug = 'contact_us'";
            mysqli_query($con, $query_update);

            /*Update Site Logo*/
            if(isset($_FILES['site_logo']) && !empty($_FILES['site_logo']['name'])){
                $uploaddir = '../images/';
                //echo realpath(__DIR__);
                $uploadfile = basename($_FILES['site_logo']['name']);
                if(move_uploaded_file($_FILES['site_logo']['tmp_name'], $uploaddir.$uploadfile)){
                    $query_update = "Update settings set value = '".$uploadfile."' WHERE slug = 'site_logo'";
                    mysqli_query($con, $query_update);
                }else{
                    $errors[] = 'Sorry! Logo is not updating.';
                }
            } elseif (isset($_POST['remove_site_logo']) && !empty($_POST['remove_site_logo'])) {
                $fileName = $_POST['remove_site_logo']; // Get the old file name
            
                // Remove site logo from database
                $query_update = "UPDATE settings SET value = '' WHERE slug = 'site_logo'";
                if (mysqli_query($con, $query_update)) {
                    // Delete the file from the server
                    $filePath = '../images/' . $fileName;
                    if (file_exists($filePath)) {
                        unlink($filePath); // Delete the old file
                    }
                    echo "Site Logo removed successfully.";
                } else {
                    echo "Error: Unable to update the database.";
                }
            }
            /*Update Site Favicon*/
            if(isset($_FILES['site_favicon']) && !empty($_FILES['site_favicon']['name'])){
                $uploaddir = '../images/';
                $uploadFavicon = basename($_FILES['site_favicon']['name']);
                if(move_uploaded_file($_FILES['site_favicon']['tmp_name'], $uploaddir.$uploadFavicon)){
                    $query_update = "Update settings set value = '".$uploadFavicon."' WHERE slug = 'site_favicon'";
                    mysqli_query($con, $query_update);
                }else{
                    $errors[] = 'Sorry! Logo is not updating.';
                }
            } elseif (isset($_POST['remove_fav_icon']) && !empty($_POST['remove_fav_icon'])) {
                $fileName = $_POST['remove_fav_icon'];
                $query_update = "UPDATE settings SET value = '' WHERE slug = 'site_favicon'";
                if (mysqli_query($con, $query_update)) {
                    $filePath = '../images/' . $fileName;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    echo "Fav Icon removed successfully.";
                } else {
                    echo "Error: Unable to update the database.";
                }
            }

            $query_update = "Update settings set value = '".$_POST['home_title']."' WHERE slug = 'home_title'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['home_meta']."' WHERE slug = 'home_meta'";
            mysqli_query($con, $query_update);

            $query_update = "Update settings set value = '".$_POST['blog_title']."' WHERE slug = 'blog_title'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['blog_meta']."' WHERE slug = 'blog_meta'";
            mysqli_query($con, $query_update);

            $query_update = "Update settings set value = '".$_POST['historico_title']."' WHERE slug = 'historico_title'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['historico_meta']."' WHERE slug = 'historico_meta'";
            mysqli_query($con, $query_update);

            if(isset($_POST['site_index_follow']) && !empty($_POST['site_index_follow'])){
                $query_update = "Update settings set value = 'Yes' WHERE slug = 'site_index_follow'";
            }else{
                $query_update = "Update settings set value = 'No' WHERE slug = 'site_index_follow'";
            }


            $query_update = "Update settings set value = '".$_POST['generador_de_numeros_title']."' WHERE slug = 'generador_de_numeros_title'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['generador_de_numeros_meta']."' WHERE slug = 'generador_de_numeros_meta'";
            mysqli_query($con, $query_update);

            $query_update = "Update settings set value = '".$_POST['comprobador_de_billetes_title']."' WHERE slug = 'comprobador_de_billetes_title'";
            mysqli_query($con, $query_update);
            $query_update = "Update settings set value = '".$_POST['comprobador_de_billetes_meta']."' WHERE slug = 'comprobador_de_billetes_meta'";
            mysqli_query($con, $query_update);



            mysqli_query($con, $query_update);

        }
    }
}
if(isset($_GET['generate_site_map']) && $_GET['generate_site_map'] == 'true'){
    generateSiteMap($lang_arr, $settings['site_index_follow'], $con);
    if(isset($_GET['cron']) && $_GET['cron'] == 'true'){
        exit('Done');
    }
    header("Location: meta-settings.php");
    exit;
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
            <li class="breadcrumb-item active">My Meta Settings</li>
        </ol>
        <!-- Example Card-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Manage Meta Settings</div>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Site Name</label>
                                    <input class="form-control" type="text"  name="site_name" value="<?=isset($settings['site_name'])?$settings['site_name']:''?>">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Contact Email</label>
                                    <input class="form-control" required type="text"  name="contact_us" value="<?=isset($settings['contact_us'])?$settings['contact_us']:''?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox"  name="site_index_follow" <?=$settings['site_index_follow']=='Yes'?'checked':''?>>Site  Index, Follow
                                        </label>
                                    </div>
                                    <br><i class="fa fa-warning text-danger"> If uncheck site will not index and not follow.</i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <a class="btn btn-primary text-white" onclick="return confirm('Are you sure you want to generate nre sitemap')"  href="?generate_site_map=true"><i class="fa fa-globe"></i> Refresh Site Map</a>
                                    <br><a href="<?=$site_url?>/sitemap.xml" target="_blank"><i class="fa fa-download text-success"> Download</i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Database Setting
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Database Link</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                    <a href="http://212.28.177.188/adminDbMgmt/" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Go to Admin DB Management">
                                                        <i class="fa fa-link" style="cursor: pointer;"></i>&nbsp;
                                                    </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>User Name</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                    <i class="fa fa-user" data-bs-toggle="tooltip" data-bs-placement="top" title="User Name"  credentials="dbAdmin" id="username-icon" style="cursor: pointer;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                    <i class="fa fa-lock" data-bs-toggle="tooltip" data-bs-placement="top" title="password"  credentials="sfs@$5q4q0i5mngfaQ#@fsAG" id="password-icon" style="cursor: pointer;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Home Page Meta
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Home Page Title *</label>
                                                        <input class="form-control" type="text"  name="home_title" value="<?=isset($settings['home_title'])?$settings['home_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Home Meta Tags</label>
                                                        <textarea class="form-control" placeholder='<meta name="keywords" content=""/>' type="text"  name="home_meta"><?=isset($settings['home_meta'])?$settings['home_meta']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Historico Tris
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Historico Page Title *</label>
                                                        <input class="form-control" type="text"  name="historico_title" value="<?=isset($settings['historico_title'])?$settings['historico_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Historico Meta Tags</label>
                                                        <textarea class="form-control" placeholder='<meta name="keywords" content=""/>' type="text"  name="historico_meta"><?=isset($settings['historico_meta'])?$settings['historico_meta']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Blog Page Meta
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Blog Page Title *</label>
                                                        <input class="form-control" type="text"  name="blog_title" value="<?=isset($settings['blog_title'])?$settings['blog_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Blog Meta Tags</label>
                                                        <textarea class="form-control" placeholder='<meta name="keywords" content=""/>' type="text"  name="blog_meta"><?=isset($settings['blog_meta'])?$settings['blog_meta']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Generador De Numeros Page Meta
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Page Title *</label>
                                                        <input class="form-control" type="text"  name="generador_de_numeros_title" value="<?=isset($settings['generador_de_numeros_title'])?$settings['generador_de_numeros_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Meta Tags</label>
                                                        <textarea class="form-control" placeholder='<meta name="keywords" content=""/>' type="text"  name="generador_de_numeros_meta"><?=isset($settings['generador_de_numeros_meta'])?$settings['generador_de_numeros_meta']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Comprobador De Billetes Page Meta
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Page Title *</label>
                                                        <input class="form-control" type="text"  name="comprobador_de_billetes_title" value="<?=isset($settings['comprobador_de_billetes_title'])?$settings['comprobador_de_billetes_title']:''?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Meta Tags</label>
                                                        <textarea class="form-control" placeholder='<meta name="keywords" content=""/>' type="text"  name="comprobador_de_billetes_meta"><?=isset($settings['comprobador_de_billetes_meta'])?$settings['comprobador_de_billetes_meta']:''?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header">
                                        <i class="fa fa-table"></i> Images
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body">
                                            <div class="row">  
                                                <div class="col-md-6">
                                                    <label>Site Logo</label>
                                                    <div class="form-group">
                                                        <input id="site_logo" name="site_logo" type="file" class="file" placeholder="select site logo">
                                                        <input id="remove_site_logo" name="remove_site_logo" type="hidden" >
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Fav Icon</label>
                                                    <div class="form-group">
                                                        <input id="site_favicon" name="site_favicon" type="file" class="file">
                                                        <input id="remove_fav_icon" name="remove_fav_icon" type="hidden" >
                                                    </div>
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
<script>
        var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        var tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        // Copy to clipboard function using Clipboard API
        function copyToClipboard(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() {
                    console.log('Text copied to clipboard');
                }).catch(function(err) {
                    console.error('Unable to copy text: ', err);
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                console.log('Text copied to clipboard (fallback)');
            }
        }

        // Add click event listener to username icon
        document.getElementById("username-icon").addEventListener("click", function() {
            const tooltipText = this.getAttribute("credentials");
            copyToClipboard(tooltipText);
            const icon = this;
            const originalText = icon.innerHTML; // Save original icon content
            // Set "Copied" text inside <i></i>
            icon.innerHTML = "Copy";
            // Reset to original icon content after 2 seconds
            setTimeout(function() {
                icon.innerHTML = originalText;
            }, 1000);
        });

        // Add click event listener to password icon
        document.getElementById("password-icon").addEventListener("click", function() {
            const tooltipText = this.getAttribute("credentials"); // Get the tooltip text
            copyToClipboard(tooltipText);
            const icon = this;
            const originalText = icon.innerHTML; // Save original icon content
            // Set "Copied" text inside <i></i>
            icon.innerHTML = "Copy!";
            // Reset to original icon content after 2 seconds
            setTimeout(function() {
                icon.innerHTML = originalText;
            }, 1000);
        });
        function initializeFileInput(selector, filePath, caption, removeInputName, buttonText) {
            console.log(buttonText)
            let oldFileName = '';
            const options = {
                showUpload: false,
                showRemove: true,
                browseClass: 'btn btn-primary',
                previewFileType: 'image',
                allowedFileExtensions: ['jpg', 'png'],
                dropZoneEnabled: false,
                showCaption: true,
                overwriteInitial: true,
                maxFileCount: 1,
                initialPreviewAsData: true,
            };
            if (filePath) {
                options.initialPreview = [`../images/${filePath}`];
                options.initialPreviewConfig = [
                    {
                        caption: caption,
                        key: 1
                    }
                ];
                oldFileName = filePath;
            }

            $(selector).fileinput(options);
            $(selector).on('fileclear', function(event) {
                if (oldFileName) {
                    const input = $('#' + removeInputName);
                    input.val(oldFileName);
                    console.log('Hidden input value set to: ', oldFileName);
                }
            });
        }
        const siteLogo = "<?= isset($settings['site_logo']) && !empty($settings['site_logo']) ? $settings['site_logo'] : '' ?>";
        initializeFileInput("#site_logo", siteLogo, "Site Logo", "remove_site_logo", "Select Site Logo");

        const siteFavIcon = "<?= isset($settings['site_favicon']) && !empty($settings['site_favicon']) ? $settings['site_favicon'] : '' ?>";
        initializeFileInput("#site_favicon", siteFavIcon, "Fav Icon", "remove_fav_icon", "Select Fav Icon");






    
</script>
</body>

</html>
