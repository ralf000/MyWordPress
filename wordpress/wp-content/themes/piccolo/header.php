<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <title><?php wp_title(''); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicons
    ================================================== -->
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72"
          href="<?php echo get_template_directory_uri(); ?>/img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114"
          href="<?php echo get_template_directory_uri(); ?>/img/apple-touch-icon-114x114.png">

    <?php wp_head(); ?>
</head>

<body class="home">
<div id="background"></div>
<!-- Color Bars (above header)-->
<div class="color-bar-1"></div>
<div class="color-bar-2 color-bg"></div>
<div class="color-bar-3"></div>

<div class="container">

    <div class="row header"><!-- Begin Header -->

        <?php require_once  'inc/logo.inc.php'?>
        
        <?php require_once 'inc/main_menu.inc.php'?>

    </div><!-- End Header -->