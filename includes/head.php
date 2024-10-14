<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?=!empty($settings['tris_de_hoy_sitename'])?$settings['site_name'].' - ':''?><?=$page_title;?></title><meta name="robots" content="follow, index"/><meta name="googlebot-news" content="snippet"><link rel="shortcut icon" href="<?=$site_url.'/images/'.$settings['site_favicon']?>" type="image/x-icon" /><?php if(isset($meta_description) && !empty($meta_description)){ ?><meta name="description" content="<?=$meta_description?>"><?php } ?><?=$page_meta;?><?php if(isset($manifest) && !empty($manifest)){?><link rel="manifest" href="<?=$site_url?>/manifest/<?=$manifest?>.json"><?php }else{ ?><link rel="manifest" href="<?=$site_url?>/manifest/manifest.json"><?php } ?><link href="<?=$site_url?>/css/grid.css?<?=time()?>" rel="stylesheet" type="text/css"><link href="<?=$site_url?>/css/style.css?<?=time()?>" rel="stylesheet" type="text/css"><link href="<?=$site_url?>/css/theme1.css?<?=time()?>" rel="stylesheet" type="text/css"><link href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.css" rel="stylesheet" type="text/css"><link href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" type="text/css"><link href="<?php echo $site_url.$_SERVER['REQUEST_URI'];?>" rel="canonical"><link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /><!--Open Graph--><meta property="og:title" content="<?=$category_info['result_meta_title']?>"><meta property="og:type" content="website"><meta property="og:description" content="<?=$category_info['result_post_meta_desc']?>"><meta property="og:url" content="<?=$site_url?>"><meta property="og:image" content="<?=$site_url?>/images/<?=$settings['og_logo']?>"><meta property="og:image:secure_url" content="<?=$site_url?>/images/<?=$settings['og_logo']?>"><meta property="og:image:type" content="image/png"><meta property="og:site_name" content="Resultadodeltrisdehoy.com"><meta property="og:locale" content="es"><!-- Facebook Meta Tags --><meta property="og:url" content="<?=$site_url?>"><meta property="og:type" content="website"><meta property="og:title" content="<?=$category_info['result_meta_title']?>"><meta property="og:description" content="<?=$category_info['result_post_meta_desc']?>"><meta property="og:image" content="https://resultadodeltrisdehoy.com/images/tris-de-hoy.jpg"><!-- Twitter Meta Tags --><meta name="twitter:card" content="summary_large_image"><meta property="twitter:domain" content="<?=$site_url?>"><meta property="twitter:url" content="<?=$site_url?>"><meta name="twitter:title" content="<?=$category_info['result_meta_title']?>"><meta name="twitter:description" content="<?=$category_info['result_post_meta_desc']?>"><meta name="twitter:image" content="https://resultadodeltrisdehoy.com/images/tris-de-hoy.jpg">
<style>
    .table-bordered {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 20px 0;
        font-size: 16px;
        color: #333;
    }

    .table-bordered thead th {
        background-color: #4CAF50; /* Vibrant green for headers */
        color: white;
        padding: 15px;
        text-align: left;
        border: none;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .table-bordered tbody td {
        padding: 15px;
        border: none;
        background-color: #ffffff;
        border-bottom: 2px solid #eeeeee; /* Subtle bottom border */
        position: relative;
        transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition */
    }

    .table-bordered tbody tr:nth-child(even) {
        background-color: #f9f9f9; /* Light grey for alternate rows */
    }

    .table-bordered tbody tr:hover {
        background-color: #e0f7fa; /* Light blue on hover */
        transform: scale(1.02); /* Slight zoom effect on hover */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Shadow effect */
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .table-bordered tbody td:first-child {
        border-left: 4px solid #4CAF50; /* Custom left border */
        border-radius: 5px 0 0 5px;
    }

    .table-bordered tbody td:last-child {
        border-right: 4px solid #4CAF50; /* Custom right border */
        border-radius: 0 5px 5px 0;
    }

    .table-bordered thead th:first-child {
        border-top-left-radius: 8px;
    }

    .table-bordered thead th:last-child {
        border-top-right-radius: 8px;
    }

    .table-bordered tfoot td {
        padding: 12px;
        background-color: #4CAF50;
        color: white;
        text-align: right;
        border: none;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    /* Add shadow to the table for a sleek 3D effect */
    .table-bordered {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }
</style>