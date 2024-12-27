<?php

$params = explode('/',$_SERVER['REQUEST_URI']);

$page_slug = $params[1];
$page_detail_query = "SELECT * FROM pages where slug = '".$page_slug."' LIMIT 1";
$row_page = mysqli_fetch_array(mysqli_query($con,$page_detail_query));
if(!empty($row_page) && isset($row_page['slug']) && !empty($row_page['slug'])){
    //
}else{
    header("Location: ".$site_url);
    exit;
}
$page_title = $row_page['title'];
$page_meta  = $row_page['meta_tags'];
$meta_description = $row_page['meta_description'];
$htmlContent = $row_page['long_desc'];
$firstImgSrc = '';
if (!empty($htmlContent)) {
    $dom = new DOMDocument();
    @$dom->loadHTML($htmlContent);
    $firstImgSrc = $dom->getElementsByTagName('img')->item(0)?->getAttribute('src') ?? '';
}
?>
<!DOCTYPE html>
<html lang="es-MX">
<head>
    <?php include 'includes/head.php';?>
    <style>
        /* Parent container styles for Flexbox centering */
.text-heading {
    display: flex; /* Enables flexbox */
    /* justify-content: center;  */
    align-items: center; /* Vertically centers the child (if needed) */
    flex-wrap: wrap; /* Ensures content wraps if necessary */
}

.text-heading h1{
font-size: 18px;
color: #2c3e50; /* Neutral color for readability */
padding: 10px 15px; /* Adds space inside the border */
border: 1px solid #2f8a11; /* Adds a dynamic border */
border-radius: 12px; /* Smooth corners */
background-color: #eef5ff; /* Light background for contrast */
display: block; /* Ensures consistent block behavior */
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Adds depth */
text-align: center; /* Centers the text within the heading */
line-height: 1.5; /* Improves readability for multiline headings */
}
/* Heading styles */
.text-heading h2,
.text-heading h3,
.text-heading h4,
.text-heading h5,
.text-heading h6 {
    font-size: 16px;
    color: #2c3e50; /* Neutral color for readability */
    padding: 15px 20px; /* Adds space inside the border */
    border-radius: 12px; /* Smooth corners */
    background-color: #eef5ff; /* Light background for contrast */
    display: block; /* Ensures consistent block behavior */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Adds depth */
    text-align: center; /* Centers the text within the heading */
    line-height: 1.5; /* Improves readability for multiline headings */
}
/* Styles for Screen Widths 1024px and Below */
@media (max-width: 1024px) {
    .text-heading h1,
    .text-heading h2,
    .text-heading h3,
    .text-heading h4,
    .text-heading h5,
    .text-heading h6 {
        padding: 4px 8px; /* Adjust padding for smaller screens */
        border-width: 1px; /* Keep border lightweight */
        line-height: 1.4; /* Slightly tighten line spacing */
    }
}

/* Styles for Screen Widths 768px and Below */
@media (max-width: 768px) {
    .text-heading h1,
    .text-heading h2,
    .text-heading h3,
    .text-heading h4,
    .text-heading h5,
    .text-heading h6 {
        padding: 3px 6px; /* Reduce padding to save space */
        border-radius: 10px; /* Maintain smaller, smooth corners */
        line-height: 1.3; /* Tighter line spacing for smaller text */
    }
}

/* Ordered List Styling */
ol {
    counter-reset: list-counter; /* Reset custom counter for ordered list */
    margin: 10px 0 20px 40px; /* Add consistent spacing around the list */
    padding-left: 0; /* Remove default padding */
    list-style: none; /* Remove default numbering */
    width: 100%;
}

/* List Item Styling */
ol li {
    font-family: 'Poppins', sans-serif; /* Modern font */
    font-size: 16px; /* Adjust font size for readability */
    margin-bottom: 15px; /* Add spacing between items */
    line-height: 1.6; /* Improve readability for multiline items */
    padding-left: 35px; /* Indent text */
    position: relative; /* Position for custom numbering */
}

/* Custom Number Styling */
ol li::before {
    content: counter(list-counter) "."; /* Custom number format */
    counter-increment: list-counter; /* Increment the counter */
    position: absolute; /* Position relative to the list item */
    left: 0; /* Align to the left */
    top: 0; /* Align with the text */
    font-weight: bold; /* Make the number bold */
    font-size: 18px; /* Adjust size of the number */
    color: #0275d8; /* Blue color for the number */
}

/* Styling Nested Ordered Lists */
ol ol {
    counter-reset: list-counter; /* Reset counter for nested lists */
    margin-left: 20px; /* Indent nested list */
}

ol ol li::before {
    content: counter(list-counter, lower-alpha) "."; /* Use letters for nested lists */
    color: #2f8a11; /* Different color for nested numbers */
}

/* Strong Tag Styling */
ol li strong {
    color: #2f8a11; /* Highlighted color for strong text */
    font-weight: 700; /* Ensure bold text */
}

/* Fix for Heading and Ordered List Alignment */
.text-heading + ol {
    margin-top: 10px; /* Reduce unnecessary space above the list */
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    ol li {
        font-size: 14px; /* Reduce font size for smaller screens */
    }
    ol li::before {
        font-size: 16px; /* Adjust bullet size */
    }
}/* Styles for Screen Widths 1024px and Below */
@media (max-width: 1024px) {
    ol {
        margin: 15px 20px; /* Adjust margin for smaller screens */
        padding-left: 0; /* Remove unnecessary padding */
    }

    ol li {
        font-size: 15px; /* Slightly smaller font for readability */
        padding-left: 25px; /* Adjust padding for smaller devices */
        margin-bottom: 12px; /* Reduce spacing between items */
    }

    ol li::before {
        font-size: 16px; /* Scale down custom number size */
        left: 0; /* Ensure proper alignment of numbering */
    }

    ol ol {
        margin-left: 15px; /* Reduce indentation for nested lists */
    }

    ol ol li::before {
        font-size: 14px; /* Adjust size for nested numbers */
    }
}

/* Styles for Screen Widths 768px and Below */
@media (max-width: 768px) {
    ol {
        margin: 10px 15px; /* Further reduce margin for smaller devices */
    }

    ol li {
        font-size: 14px; /* Smaller font for compact devices */
        padding-left: 20px; /* Reduce padding to maintain alignment */
    }

    ol li::before {
        font-size: 14px; /* Scale down number size for compact screens */
    }

    ol ol {
        margin-left: 10px; /* Minimize nesting indentation */
    }

    ol ol li::before {
        font-size: 12px; /* Reduce size for nested list numbering */
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
p {
    width: 100%;
}
.text-heading.topheading {
    text-align: center;
    justify-content: center;
}


    </style>
</head>
<body>

<div class="wrap">
    <?php include 'breadcrums.php';?> 
    <?php include 'includes/nav.php';
    include 'schema/page.php';?>
    <div class="container">

        <div class="row content-block">
            <section class="flex-grow-1">
                <!--<div class="ad-container top-ad-container">
                    Ads
                </div>-->
                <div class="clear mb20"></div>
                <div class="text-heading topheading">
                    <h1><?=$page_title?></h1>
                </div>
            </section>
            <section class="col-12">
                <div class="text-heading">
                    <?=$row_page['long_desc']?>
                </div>
            </section>

        </div>
    </div>

</div>
<?php include 'includes/footer.php';?>
</body>
</html>

