<?php
$page_title = $settings['blog_title'];
$page_meta  = $settings['blog_meta'];
setlocale(LC_TIME, 'es_MX.UTF-8'); 
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include "includes/head.php"; ?>
    <style>
        #pagination {
            margin: 0;
            padding: 0;
            text-align: center;
        }
        #pagination li {
            display: inline;
        }
        #pagination li a {
            display: inline-block;
            text-decoration: none;
            padding: 5px 10px;
            color: #000;
            font-size: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            -webkit-transition: background-color 0.3s;
            transition: background-color 0.3s;
        }
        #pagination li a.active {
            background-color: #4caf50;
            color: #fff;
        }
        #pagination li a:hover:not(.active) {
            background-color: #ddd;
        }
        .blog__list__item__title {
            min-height: 112px;
        }
        .blog__date {
    display: inline-block;
    padding: 5px 10px;
    color: #4caf50; /* Green text color */
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
    border: 2px solid #4caf50; /* Green border around the text */
    border-radius: 5px; /* Slightly rounded corners */
    background-color: transparent; /* Transparent background */
    transition: all 0.3s ease-in-out;
}

.blog__date:hover {
    background-color: #4caf50; /* Green background on hover */
    color: #fff; /* White text color on hover */
    border-color: #4caf50; /* Keep the border green on hover */
}
.blog__list__item__title {
    font-size: 18px;
    font-weight: 400; /* Stronger emphasis on the font weight */
    color: #2c3e50; /* A deep blue-gray color for a modern look */
    text-transform: capitalize; /* Capitalizes the first letter of each word */
    margin-bottom: 20px;
    line-height: 1.3;
    letter-spacing: 1px; /* Adds some spacing between letters for readability */
    position: relative;
    transition: color 0.3s ease, transform 0.3s ease;
}

.blog__list__item__title::before {
    content: '';
    position: absolute;
    left: 0;
    bottom: -10px;
    width: 0;
    height: 4px;
    background-color: #e74c3c; /* Vibrant red underline */
    transition: width 0.4s ease;
}

.blog__list__item__title:hover::before {
    width: 100%; /* Expands the underline fully on hover */
}

.blog__list__item__title:hover {
    color: #e74c3c; /* Changes text color to match the underline on hover */
    transform: translateY(-5px); /* Slightly lifts the title on hover */
    letter-spacing: 2px; /* Expands the letter spacing for a dramatic effect */
}

.blog__list__item__title span {
    color: #4caf50; /* Optionally, add some color to specific words within the title */
}
.read-more-button {
    padding: 10px 20px;
    background-color: #55a903; /* Bright blue background */
    color: #fff; /* White text color */
    font-weight: bold;
    text-transform: uppercase; /* Uppercase text for emphasis */
    border: none; /* Remove default border */
    border-radius: 15px; /* Rounded corners */
    cursor: pointer; /* Change cursor to pointer on hover */
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
}

.read-more-button:hover {
    background-color: #2980b9; /* Darker blue on hover */
    transform: translateY(-3px); /* Slight lift on hover */
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3); /* Enhance shadow on hover */
}

.read-more-button:active {
    background-color: #1e6a8c; /* Even darker blue when clicked */
    transform: translateY(0); /* Remove lift effect on click */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Reset shadow on click */
}


    </style>
</head>
<body>
<div class="wrap">
    <?php include 'includes/nav.php'; 
    include'breadcrums.php';?>
    <div class="container">
        <div class="row">
        <div class="row content-block">
            <section class="flex-grow-1">
                <div class="ad-container top-ad-container">
                </div>
            </section>
            <section class="col-12">
                <div class="text-heading">
                    <h1><?=_translate('bg-main-heading')?></h1>
                    <p><?=_translate('bg-txt')?></p>
                </div>
            </section>
        </div>
        <div class="row content-block">
            <div class="blog_wrap">
                <?php
                if (isset($_GET['pageno'])) {
                    $pageno = $_GET['pageno'];
                } else {
                    $pageno = 1;
                }
                $no_of_records_per_page = 12;
                $offset = ($pageno-1) * $no_of_records_per_page;


                $total_pages_sql = "SELECT COUNT(*) FROM blog WHERE posted_date <= '".date('Y-m-d')."'";
                $result = mysqli_query($con,$total_pages_sql);
                $total_rows = mysqli_fetch_array($result)[0];
                $total_pages = ceil($total_rows / $no_of_records_per_page);

                $sql_blogs = "SELECT * FROM blog WHERE posted_date <= '".date('Y-m-d')."' order by posted_date desc LIMIT $offset, $no_of_records_per_page";
                $res_data = mysqli_query($con,$sql_blogs);
                $data = [];
                while($row_blog = mysqli_fetch_array($res_data)){
                    ?>
                    <div class="blog__list__item">
                        <div class="blog__list__item__media">
                            <a href="<?=setUrl('blog/'.$row_blog['slug'])?>">
                                <img src="../../images/blog_images/<?=$row_blog['feature_image']?>" alt="<?=$row_blog['title']?>">
                            </a>
                        </div>
                        <div class="blog__list__item__content">
                            <div class="blog__date"><?= strftime('%d de %B de %Y', strtotime($row_blog['posted_date'])) ?></div>
                            <h2 class="blog__list__item__title"><a href="<?=setUrl('blog/'.$row_blog['slug'])?>"><?=substr($row_blog['title'], 0, 100)?></a></h2>
                            <button onclick="window.location.href='<?=setUrl('blog/'.$row_blog['slug'])?>'" class="read-more-button"><?=_translate('more')?></button></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div style="width: 100%;display: block; clear: both;">
                <div class="ac">
                    <ul class="pagination" id="pagination">
                        <li><a href="?pageno=1">Primero</a></li>
                        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
                            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Previa</a>
                        </li>
                        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Próxima</a>
                        </li>
                        <li><a href="?pageno=<?php echo $total_pages; ?>">Última</a></li>
                    </ul>

                </div>
            </div>
        </div>
        <?php //include 'includes/subscribeform.php';?>
    </div>
</div>
<?php include 'includes/footer.php';?>
</div>
</body>
</html>
