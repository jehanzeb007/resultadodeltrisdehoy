<?php
// Fetch the current blog post based on slug
$params = explode('/', $_SERVER['REQUEST_URI']);
$blog_slug = $params[2];
$blog_detail_query = "SELECT * FROM blog WHERE slug = '".$blog_slug."' AND posted_date <= '".date('Y-m-d')."' LIMIT 1";
$row_blog = mysqli_fetch_array(mysqli_query($con, $blog_detail_query));

// If the blog post doesn't exist, redirect to the homepage
if (empty($row_blog) || !isset($row_blog['slug']) || empty($row_blog['slug'])) {
    header("Location: ".$site_url);
    exit;
}

// Fetch two random previous blog posts
$random_posts_query = "SELECT * FROM blog WHERE slug != '".$blog_slug."' AND posted_date <= '".date('Y-m-d')."' ORDER BY RAND() LIMIT 2";
$random_posts_result = mysqli_query($con, $random_posts_query);

// Page meta data
$page_title = $row_blog['title'];
$page_meta  = $row_blog['meta_tags'];
$meta_description = $row_blog['meta_description'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "includes/head.php"; ?>
    <style>
        .primaryimage { margin-top: 0; margin-bottom: 30px; }
        .primaryimage img { aspect-ratio: 2/1; width: 100%; height: 100%; object-fit: cover; }
        .full_gray_box .app.clickable:hover { background: #fff; }
        .blog_head { text-align: center; font-weight: bold; }
        figure { text-align: center; }
        .main_head { font-size: 34px; line-height: 42px; font-weight: bold; }
        .date_line { color: #6c757d; font-size: 13px; line-height: 22px; margin-bottom: 15px; }
        .blog_inn { width: 800px; margin: 0 auto; }
        @media (max-width: 800px) { .blog_inn { width: 100%; } }
         }
        .random-posts h3 { font-size: 24px; margin-bottom: 20px; }
        .random-post { margin-bottom: 30px; }
        .random-post h4 { font-size: 20px; margin-bottom: 10px; }
        .random-post a { text-decoration: none; color: #3498db; }
        .random-post a:hover { color: #2980b9; }
.random-posts {
    padding: 3px;
    border-radius: 10px; /* Rounded corners */
}

.random-posts-title {
    font-size: 20px;
    font-weight: bold;
    color: #2c3e50; /* Dark blue-gray color */
    margin-bottom: 30px;
    text-align: center; /* Center the heading */
}

.random-posts-container {
    display: flex;
    justify-content: space-between; /* Space out the posts evenly */
    gap:10px;
    flex-wrap: wrap; /* Allow wrapping of items */
}
@media (max-width: 768px) { /* Adjust the max-width as needed for your mobile breakpoint */
    .random-posts-container {
        gap: 0; /* Set gap to 0 for mobile devices */
    }
}

.random-post {
    flex: 1; /* Make posts flexible to fit in a line */
    min-width: calc(50% - 20px); /* Ensure each post takes up half the width minus the gap on larger screens */
    padding: 5px;
    background-color: #ffffff; /* White background for each post */
    border-radius: 8px; /* Rounded corners for each post */
    border: 2px solid #ffffff; /* White border around each post */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    text-align: center; /* Center align the content inside each post */
}

.random-post:hover {
    transform: translateY(-5px); /* Slight lift on hover */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* More prominent shadow on hover */
}

.random-post h4 {
    font-size: 17px;
    color: #79a930; /* Blue color for the title */
    margin-bottom: 10px;
    transition: color 0.3s ease;
}

.random-post h4 a {
    text-decoration: none;
    color: inherit; /* Inherits the color from the h4 element */
}

.random-post h4 a:hover {
    color: #2980b9; /* Darker blue on hover */
}

.random-post .date_line {
    font-size: 14px;
    color: #95a5a6; /* Light gray color for the date */
    margin-top: 0;
}

/* Media query for mobile devices */
@media (max-width: 600px) {
    .random-posts-container {
        flex-direction: column; /* Stack posts in a column on small screens */
    }

    .random-post {
        min-width: 100%; /* Full width on small screens */
    }
}


    </style>
</head>
<body>
    <div class="wrap">
        <?php include 'includes/nav.php'; ?>
        <div class="container">
            <div class="row">
                <div class="bcca-breadcrumb">
                    <div class="bcca-breadcrumb-item"><?=$row_blog['title']?></div>
                    <div class="bcca-breadcrumb-item"><a href="<?=setUrl('blog')?>"><?=_translate('blog')?></a></div>
                    <div class="bcca-breadcrumb-item"><a href="<?=getUrl()?>"><i class="fa fa-home"></i></a></div>
                </div>
            </div>
            <div class="row content-block">
                <div class="blog_inn">
                    <h1 class="main_head"><?=$row_blog['title']?></h1>
                    <p class="date_line"><?=date('F d, Y', strtotime($row_blog['posted_date']))?></p>
                    <figure id="primaryimage" class="primaryimage">
                        <img width="360" height="180" src="../../../images/blog_images/<?=$row_blog['feature_image']?>" alt="" importance="high">
                    </figure>
                    <div class="cnt_box">
                        <?=str_replace('../images/', '../../../images/', $row_blog['long_desc'])?>
                        <div class="clear mb15"></div>
                    </div>
                    
                    <!-- Display Random Previous Posts -->
                    <div class="random-posts">
    <h3 class="random-posts-title"><?=_translate('blog-detail-previous')?></h3>
    <div class="random-posts-container">
        <?php while ($random_post = mysqli_fetch_array($random_posts_result)) { ?>
            <div class="random-post">
                <h4><a href="<?=setUrl('blog/'.$random_post['slug'])?>"><?=$random_post['title']?></a></h4>
                <p class="date_line"><?=date('F d, Y', strtotime($random_post['posted_date']))?></p>
            </div>
        <?php } ?>
    </div>
</div>
                <div class="clear mb50"></div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
