<?php
//$REQUEST_URI coming from main file
//echo '<pre>';print_r($REQUEST_URI);exit;
$anno = isset($_GET['anno']) ? $_GET['anno'] : null;
$current_year = date('Y');
$yearNumber = isset($_GET['anno']) && is_numeric($_GET['anno']) && $_GET['anno'] >= 1900 && $_GET['anno'] <= $current_year ? intval($_GET['anno']) : 2024;

$page_title = $settings['historico_title'];
$page_meta = $settings['historico_meta'];
$categoryList = "Select id,name From categories order by id asc";
$categoryResult = mysqli_query($con, $categoryList);

// Query results for the selected year
$resultsQuery = "SELECT DAY(result_date) as day_number, result_date FROM tbl_loterianacional WHERE YEAR(result_date) = $yearNumber GROUP BY result_date ORDER BY result_date DESC";
$queryResponse = mysqli_query($con, $resultsQuery);
?>

<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'includes/head.php';?>
    <style>.numbers-his-table{width:100%;margin:0 auto;font-size:20px;}.numbers-his-table td{border:1px solid #ddd;text-align:center;}.top-link-white.active{color:#4CAF50;}</style>
</head>
<body>
<div class="wrap">
    <?php 
    include 'includes/nav.php';
    include 'schema/allhistory.php';
    ?>
    <div class="container">
        <div class="row content-block">
            <section class="col-12">
                <div class="text-heading">
                    <div class="clear mb20"></div>
                    <h1><?=$page_title?></h1>
                    <p><?=_translate('history')?></p>
                    <?php
                    if ($queryResponse && mysqli_num_rows($queryResponse) > 0) {
                    ?>
                        <div class="row content-block">
                            <div class="text-heading">
                                <div class="table-responsive" style="overflow-x: auto;">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr class="danger">
                                                <th class="text-center">Date</th>
                                                <?php
                                                mysqli_data_seek($categoryResult, 0);
                                                while ($category = mysqli_fetch_assoc($categoryResult)) {
                                                ?>
                                                    <th class="text-center"><?= $category['name'] ?></th>
                                                <?php
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = mysqli_fetch_assoc($queryResponse)) {
                                                $date = $row['result_date'];
                                                echo '<tr>';
                                                echo '<td class="res text-center">' . htmlspecialchars($date) . '</td>';
                                                $eachQuery = "SELECT cat_id, result_numbers,result_code FROM tbl_loterianacional WHERE result_date = '$date'";
                                                $eachResponse = mysqli_query($con, $eachQuery);
                                                $resultsByCategory = [];
                                                while ($resultRow = mysqli_fetch_assoc($eachResponse)) {
                                                    $resultNumbers = null;
                                                    if (isset($resultRow['result_numbers'])) {
                                                        $numbersArray = json_decode($resultRow['result_numbers'], true);
                                                        if (is_array($numbersArray)) {
                                                            $resultNumbers = implode('', $numbersArray);
                                                        }
                                                    }
                                                    $resultsByCategory[$resultRow['cat_id']] = $resultNumbers;
                                                }

                                                // Resetting categoryResult to iterate through categories again
                                                mysqli_data_seek($categoryResult, 0);
                                                while ($category = mysqli_fetch_assoc($categoryResult)) {
                                                    // Get the result for the current category
                                                    $categoryId = $category['id'];
                                                    $resultValue = isset($resultsByCategory[$categoryId]) ? $resultsByCategory[$categoryId] : 'No Result';

                                                    echo '<td class="res text-center">' . $resultValue . '</td>';
                                                }

                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            <!-- </div> -->
                        <!-- </div> -->
                    <?php } ?>
                </div>
            </section>
        </div> 
        <?php
        $query_faq = "SELECT * FROM faqs WHERE page = 'page__historico-tris' order by id desc";
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

