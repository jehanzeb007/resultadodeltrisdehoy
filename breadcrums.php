<?php $path=parse_url($_SERVER["REQUEST_URI"],PHP_URL_PATH);$links=explode("/",$path);if($path!="/"){?><div class="container"><div class="row"><div class="col-md-12 d-flex justify-content-between align-items-center"><ul class="custom-breadcrumb"><li><a href="/"><i class='fa fa-home'></i></a></li>&nbsp;<?php $collect="";$total=count($links);$current=0;foreach($links as $link){if(!empty($link)){$collect.=$link."/";if(($total-1)!=$current){?><li><a href="/<?php echo rtrim($collect,"/");?>"><?php echo ucwords(str_replace("-"," ",$link));?></a></li>&nbsp;<?php }else{?><li><?php echo ucwords(str_replace("-"," ",$link));?></li><?php }}$current++;}?></ul><div class="author-box"><i class="fa fa-user"></i> Autor: <a href="/author">Don Tse</a> </div></div></div></div><?php }?><style>ul.custom-breadcrumb{margin:0;padding:10px 0;list-style:none;display:inline-flex}ul.custom-breadcrumb li{display:inline;font-size:14px}ul.custom-breadcrumb li+li:before{color:#000;font-size:14px;content:">\00a0"}ul.custom-breadcrumb li a{color:#067c0a;text-decoration:none}ul.custom-breadcrumb li a:hover{color:#01447e;text-decoration:underline}.author-box{border:1px solid #ccc;padding:5px 5px;font-size:14px;border-radius:4px;display:inline-block;margin-left:auto}.author-box i{margin-right:5px;color:#067c0a}.d-flex{display:flex}.justify-content-between{justify-content:space-between}.align-items-center{align-items:center}</style><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
