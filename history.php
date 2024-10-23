<?php
//$REQUEST_URI coming from main file
//echo '<pre>';print_r($REQUEST_URI);exit;
$categoryList = "Select id,name,history_meta_title,history_meta_desc,slug   From categories where slug = '".$REQUEST_URI['1']."' order by id asc";
$categoryResult = mysqli_query($con, $categoryList);
$categoryInfo = mysqli_fetch_array($categoryResult);
if(empty($categoryInfo)){
    header("Location: ".$site_url);
    exit;
}
$anno = isset($_GET['anno']) ? $_GET['anno'] : null;
$current_year = date('Y');
$yearNumber = isset($_GET['anno']) && is_numeric($_GET['anno']) && $_GET['anno'] >= 1900 && $_GET['anno'] <= $current_year ? intval($_GET['anno']) : 2024;
$page_title = $categoryInfo['history_meta_title'];
$page_title = str_replace('2024 | 2023 | 2022', '', $page_title);
$page_title = trim($page_title).' ';
$yearLinks = '';
for ($i = 0; $i < 3; $i++) {
    $year = $current_year - $i;
    $isActive = ($year == $yearNumber) ? 'active' : '';
    $yearLinks .= "<a href='?anno={$year}' class='top-link-white btn btn-sm btn-outline-primary mr-2 {$isActive}'>{$year}</a>";
    if ($i < 2) {
        $yearLinks .= " | ";
    }
}
$page_title .= $yearLinks;
$meta_description = $categoryInfo['history_meta_desc'];
$page_meta  = '';
$manifest = 'manifest-'.$categoryInfo['slug'];


$resultsQuery = "SELECT DAY(result_date) as day_number, result_date, result_numbers,result_code FROM tbl_loterianacional WHERE YEAR(result_date) = $yearNumber AND cat_id = '".$categoryInfo['id']."'  GROUP BY result_date ORDER BY result_date DESC";
$queryResponse = mysqli_query($con, $resultsQuery);
$numRows = mysqli_num_rows($queryResponse);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/head.php';?>
    <style>
        .numbers-his-table {
            width: 100%;
            margin: 0 auto;
            font-size: 20px;
        }
        .numbers-his-table td{
            border: 1px solid #ddd;
            text-align: center;
        }
        .top-link-white {
            color: white;
        }
        .top-link-white.active {
            color: #4CAF50;
        }
    </style>
</head>
<body>
<div class="wrap">

    <?php include 'includes/nav.php'; ?>

    <div class="container">
        <div class="row">
            <div class="bcca-breadcrumb">
                <div class="bcca-breadcrumb-item">Historia</div>
                <div class="bcca-breadcrumb-item"><a href="<?=setUrl($categoryInfo['slug'])?>"><?=$categoryInfo['name']?></a></div>
                <div class="bcca-breadcrumb-item"><a href="<?=$site_url?>"><i class="fa fa-home"></i></a></div>
            </div>
        </div>
        <div class="row content-block">
            <section class="col-12">
                <div class="text-heading">
                    <p><?=_cat_translate('header-text-history',$categoryInfo['id'])?></p>
                </div>
            </section>
        </div>
        <div class="row content-block dark">
            <div class="date-chooser flex-grow-1">
                <h1><?=$page_title?></h1>
            </div>
        </div>
        <div class="row content-block">
        <section class="col-content">
                    <?php
                    if ($queryResponse && mysqli_num_rows($queryResponse) > 0) {
                    ?>
                        <div class="row content-block">
                            <div class="text-heading">
                                <div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="danger">
                                                <th class="text-center">Date</th>
                                                <th class="text-center"><?= $categoryInfo['name'] ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = mysqli_fetch_assoc($queryResponse)) {
                                                $date = $row['result_date'];
                                                echo '<tr>';
                                                echo '<td class="res text-center">' . htmlspecialchars($date) . '</td>';
                                                $resultNumbers = '';
                                                if (isset($row['result_numbers'])) {
                                                    $numbersArray = json_decode($row['result_numbers'], true);
                                                    if (is_array($numbersArray)) {
                                                        $resultNumbers = implode('', $numbersArray);
                                                    }
                                                }
                                                echo '<td class="res text-center">' . $resultNumbers . '</td>';
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </section>
        </div>
        <?php
        $query_faq = "SELECT * FROM faqs WHERE page = 'historico__".$categoryInfo['id']."' order by id desc";
        $result_faq = mysqli_query($con, $query_faq);
        include 'includes/faq.php';
        ?>
        <?php include 'includes/subscribeform.php';?>
    </div>
</div>
<?php include 'includes/footer.php';?>
<script>

</script>
</body>

</html>

