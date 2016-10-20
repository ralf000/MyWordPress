<!DOCTYPE html>
<!--[if gte IE 9 ]> <html <?php language_attributes(); ?> class="no-js ie ie9"> <![endif]-->
<!--[if !IE]><!-->  <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?php wp_title(''); ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico?v=1.1">

<link rel="stylesheet" type="text/css" media="all" href="<?php css_cache_buster(); ?>" />

<?php if(get_option('google_analytics_id_401')): ?>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '<?php echo get_option('google_analytics_id_401'); ?>', 'auto');
      ga('send', 'pageview');

    </script>
<?php endif; ?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="header container">

    <h1 class="logo"><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>

    <div class="m">
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="cross">
            <span></span>
            <span></span>
        </div>
    </div>
    
    <?php wp_nav_menu( array( 'container' => false, 'menu_class' => 'secondary-nav nav', 'theme_location' => 'secondary' )); 
          wp_nav_menu( array( 'container' => false, 'menu_class' => 'main-nav nav', 'theme_location' => 'primary' )); 
    ?>
    
</div><!-- header -->