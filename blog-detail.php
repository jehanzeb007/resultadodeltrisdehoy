<?php

setlocale(LC_TIME, 'es_MX.UTF-8'); 
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
<html lang="es-MX">
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
    border-radius: 10px; 
}

.random-posts-title {
    font-size: 20px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 30px;
    text-align: center; 
}

.random-posts-container {
    display: flex;
    justify-content: space-between; 
    gap:10px;
    flex-wrap: wrap;
}
@media (max-width: 768px) { 
    .random-posts-container {
        gap: 0;
    }
}

.random-post {
    flex: 1;
    min-width: calc(50% - 20px);
    padding: 5px;
    background-color: #ffffff; 
    border-radius: 8px;
    border: 2px solid #ffffff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center; 
}

.random-post:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); 
}

.random-post h4 {
    font-size: 17px;
    color: #79a930;
    margin-bottom: 10px;
    transition: color 0.3s ease;
}

.random-post h4 a {
    text-decoration: none;
    color: inherit;
}

.random-post h4 a:hover {
    color: #2980b9;
}

.random-post .date_line {
    font-size: 14px;
    color: #95a5a6; 
    margin-top: 0;
}

@media (max-width: 600px) {
    .random-posts-container {
        flex-direction: column; 
    }

    .random-post {
        min-width: 100%; 
    }
}
/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse; /* Merge borders for a clean look */
    text-align: left; /* Align text to the left */
    font-size: 14px; /* Adjust font size */
    background-color: rgb(191, 237, 210); /* Table background */
    border: 1px solid rgb(22, 145, 121); /* Table border color */
}

/* Table Header */
th {
    background-color: rgb(22, 145, 121); /* Header background */
    color: #fff; /* Header text color */
    padding: 10px; /* Add spacing in header cells */
    text-transform: uppercase; /* Capitalize header text */
    border: 1px solid rgb(22, 145, 121); /* Header border */
}

/* Table Rows */
td {
    padding: 8px; /* Add spacing in cells */
    border: 1px solid rgb(22, 145, 121); /* Cell border */
    color: #333; /* Cell text color */
}

/* Zebra Striping */
tr:nth-child(even) {
    background-color: #f9f9f9; /* Light gray for alternate rows */
}

/* Hover Effect */
tr:hover {
    background-color: #e0f7e9; /* Highlight row on hover */
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    table {
        font-size: 12px; /* Adjust font size for smaller screens */
    }
    th, td {
        padding: 6px; /* Reduce padding */
    }
}
/* General Heading Styles */
h1, h2, h3, h4, h5, h6 {
    font-weight: bold; /* Bold text for emphasis *
    color: #2c3e50; /* Neutral dark color for readability */
    margin: 20px 0; /* Add spacing around headings */
    line-height: 1.4; /* Improve readability for multiple lines */
    text-align: left; /* Default alignment */
}

/* Specific Heading Styles */
h1 {
    font-size: 18px; /* Largest heading size */
    color: #0275d8; /* Custom color for H1 */
    text-transform: uppercase; /* Capitalize all letters */
}

h2 {
    font-size: 16px;
    color: #2f8a11; /* Custom color for H2 */
    text-transform: capitalize; /* Capitalize the first letter of each word */
}

h3 {
    font-size: 16px;
    color: #d9534f; /* Custom color for H3 */
}

h4 {
    font-size: 16px;
    color: #5bc0de; /* Custom color for H4 */
    font-style: italic; /* Add italic for a softer look */
}

h5 {
    font-size: 16px;
    color: #f0ad4e; /* Custom color for H5 */
}

h6 {
    font-size: 16px;
    color: #6c757d; /* Subtle color for H6 */
    text-transform: lowercase; /* Lowercase text for differentiation */
}

/* Hover Effect */
h1:hover, h2:hover, h3:hover, h4:hover, h5:hover, h6:hover {
    color: #01447e; /* Change color on hover */
    cursor: pointer; /* Indicate it's clickable */
    text-decoration: underline; /* Add underline for hover */
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    h1 {
        font-size: 16px; /* Scale down for smaller screens */
    }
    h2 {
        font-size: 16px;
    }
    h3 {
        font-size: 16px;
    }
    h4 {
        font-size: 16px;
    }
    h5 {
        font-size: 16px;
    }
    h6 {
        font-size: 16px;
    }
}


    </style>
</head>
<body>
    <div class="wrap">
        <?php include 'includes/nav.php'; 
        include'breadcrums.php';?>
        <div class="container">
            <div class="row content-block">
                <div class="blog_inn">
                    <h1 class="main_head"><?=$row_blog['title']?></h1>
                   <p class="date_line" style="border: 1px solid #6c757d; padding: 5px; border-radius: 5px; display: inline-block;">
                   <?= strftime('%d de %B de %Y', strtotime($row_blog['posted_date'])) ?></p>
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
