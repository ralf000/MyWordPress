<?php

/*
 * меню (вывод меню первый вариант)
 */
register_nav_menu('menu', 'main menu');

/*
 * функция подключения стилей и скриптов
 */
function loadStyleScript()
{
    wp_enqueue_style('google-font-css', 'http://fonts.googleapis.com/css?family=Oswald');
    wp_enqueue_style('bootstrap-css', get_template_directory_uri() . '/css/bootstrap.css');
    wp_enqueue_style('bootstrap-responsive-css', get_template_directory_uri() . '/css/bootstrap-responsive.css');
    wp_enqueue_style('prettyPhoto-css', get_template_directory_uri() . '/css/prettyPhoto.css');
    wp_enqueue_style('flexslider-css', get_template_directory_uri() . '/css/flexslider.css');
    wp_enqueue_style('style', get_template_directory_uri() . '/style.css');

    wp_enqueue_script('html5-js', 'http://html5shim.googlecode.com/svn/trunk/html5.js');
    wp_script_add_data('html5-js', 'conditional', 'lt IE 9');
    wp_enqueue_style('style-ie-css', get_template_directory_uri() . '/css/style-ie.css');
    wp_style_add_data('style-ie-css', 'conditional', 'lt IE 9');

    wp_enqueue_script('jq-js', 'http://code.jquery.com/jquery-1.8.3.min.js');
    wp_enqueue_script('jq-custom-js', get_template_directory_uri() . '/js/jquery.custom.js');
    wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js');
    wp_enqueue_script('easing-js', get_template_directory_uri() . '/js/jquery.easing.1.3.js');
    wp_enqueue_script('flexslider-js', get_template_directory_uri() . '/js/jquery.flexslider.js');
    wp_enqueue_script('prettyPhoto-js', get_template_directory_uri() . '/js/jquery.prettyPhoto.js');
    wp_enqueue_script('quicksand-js', get_template_directory_uri() . '/js/jquery.quicksand.js');
    wp_enqueue_script('index-js', get_template_directory_uri() . '/js/index.js');
}

/*
 * загружаем скрипты и стили
 */
add_action('wp_enqueue_scripts', 'loadStyleScript');

/*
 * поддержка миниатюр
 */
add_theme_support('post-thumbnails');
set_post_thumbnail_size(180, 180);

function getIndexPageText($excerpt)
{
    $list = explode('.', $excerpt, 3);
    return (count($list) < 3)
        ? implode(' ', $list)
        : '<p class="lead">' . implode('. ', array_slice($list, 0, 2)) . '</p><p>' .substr($list[2], 0, 100).'...</p>';
}