<style>
    ul.nav-submenu{display: none;}
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.php">Admin Panel</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">

        <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExampleDash" data-parent="#exampleAccordion">
                    <i class="fa fa-fw fa-dashboard"></i>
                    <span class="nav-link-text">Dashboard</span>
                </a>
                <ul class="sidenav-second-level collapse" id="collapseExampleDash">
                    <li>
                        <a href="meta-settings.php">Meta Settings</a>
                    </li>
                    <li>
                        <a href="categories.php">Categories</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                <a class="nav-link" href="predictions.php">
                    <i class="fa fa-paw"></i>
                    <span class="nav-link-text">Predictions</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExampleBlogs" data-parent="#exampleAccordion">
                    <i class="fa fa-fw fa-file"></i>
                    <span class="nav-link-text">Blogs</span>
                </a>
                <ul class="sidenav-second-level collapse" id="collapseExampleBlogs">
                    <li>
                        <a href="create_blog.php">Add New Blog</a>
                    </li>
                    <li>
                        <a href="blogs.php">Lists Blogs</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages" data-parent="#exampleAccordion">
                    <i class="fa fa-fw fa-file-archive-o"></i>
                    <span class="nav-link-text">Pages</span>
                </a>
                <ul class="sidenav-second-level collapse" id="collapseExamplePages">
                    <li>
                        <a href="create_page.php">Add New Page</a>
                    </li>
                    <li>
                        <a href="pages.php">Lists Pages</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages">
                <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExampleSlugs" data-parent="#exampleAccordion">
                    <i class="fa fas fa-language"></i>
                    <span class="nav-link-text">Keywords & Slugs</span>
                </a>
                <ul class="sidenav-second-level collapse" id="collapseExampleSlugs">
                    <li>
                        <a href="keywords_slugs.php">Site Keywords & Slugs</a>
                    </li>
                    <li>
                        <a href="category_keywords_slugs.php">Categories Keywords & Slugs</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                <a class="nav-link" href="create_faq.php">
                    <i class="fa fas fa-list"></i>
                    <span class="nav-link-text">Manage Faqs</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                <a class="nav-link" href="comments.php">
                    <i class="fa fa-comment"></i>
                    <span class="nav-link-text">Comments</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                <a class="nav-link" href="rss.php">
                    <i class="fa fa-rss"></i>
                    <span class="nav-link-text">RSS</span>
                </a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                <a class="nav-link" href="profile.php">
                    <i class="fa fas fa-user"></i>
                    <span class="nav-link-text">Profile</span>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav sidenav-toggler">
            <li class="nav-item">
                <a class="nav-link text-center" id="sidenavToggler">
                    <i class="fa fa-fw fa-angle-left"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item pull-left">
                <a class="btn btn-primary text-white" target="_blank" href="<?=$site_url?>">
                    <i class="fa fa-globe"></i> Vist Site</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-fw fa-sign-out"></i>Logout</a>
            </li>
        </ul>
    </div>
</nav>