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
        : '<p class="lead">' . implode('. ', array_slice($list, 0, 2)) . '</p><p>' . substr($list[2], 0, 100) . '...</p>';
}

/**
 * опции
 **/
function add_phone_in_opts()
{
    // создаем поле опции
    // $id, $title, $callback, $page, $section, $args
    add_settings_field(
        'phone', // $id - Название опции (идентификатор)
        'Телефон', // $title - Заголовок поля
        'display_phone_in_opts', // $callback - callback function
        'general' // $page - Страница меню в которую будет добавлено поле
    );

    // Регистрирует новую опцию и callback функцию (функцию обратного вызова)
    // для обработки значения опции при её сохранении в БД.
    // $option_group, $option_name, $sanitize_callback
    register_setting(
        'general', // $option_group - Название группы, к которой будет принадлежать опция.
        // Это название должно совпадать с названием группы в функции settings_fields()
        'my_phone' // $option_name - Название опции, которая будет сохраняться в БД
    );

}

function add_logo_text_in_opts()
{
    add_settings_field(
        'logo_text', // $id - Название опции (идентификатор)
        'Текст для логотипа', // $title - Заголовок поля
        'display_logo_text_in_opts', // $callback - callback function
        'general' // $page - Страница меню в которую будет добавлено поле
    );
    register_setting(
        'general', // $option_group - Название группы, к которой будет принадлежать опция.
        // Это название должно совпадать с названием группы в функции settings_fields()
        'logo_text' // $option_name - Название опции, которая будет сохраняться в БД
    );
}

add_action('admin_init', 'add_phone_in_opts');
add_action('admin_init', 'add_logo_text_in_opts');

function display_phone_in_opts()
{
    echo "<input type='text' class='regular-text' name='my_phone' value='" . esc_attr(get_option('my_phone')) . "'>";
}

function display_logo_text_in_opts()
{
    echo "<input type='text' class='regular-text' name='logo_text' value='" . esc_attr(get_option('logo_text')) . "'>";
}

add_filter('nav_menu_css_class', 'special_nav_class', 10, 2);

//добавляем класс active для активного пункта меню и другие css классы
function special_nav_class($classes, $item)
{
    if (in_array('current-menu-item', $classes)) {
        $classes[] = 'active';
    }
    if (in_array('menu-item-has-children', $classes)) {
        $classes[] = 'dropdown';
    }
    return $classes;
}


function getMainMenu()
{
    $menu = wp_nav_menu([
        'theme_location' => 'main_menu',
        'menu' => 'Top menu',
        'container' => '',
        'menu_class' => 'nav',
        'echo' => false,
        'fallback_cb' => 'wp_page_menu',
        'depth' => 0,
//        'walker' => new My_Walker_Nav_Menu(),
    ]);

    $search = [
        '~sub-menu~',
        '~(<li.*dropdown.*>)<a.* (href=".*")>(.*)</a>~Uuim'
    ];
    $replacements = [
        'dropdown-menu',
        '$1<a $2 class="dropdown-toggle" data-toggle="dropdown">$3 <b class="caret"></b></a>',
    ];
    $menu = preg_replace($search, $replacements, $menu);
    return $menu;
}


/*
 * Добавляем новый тип записей слайдер
 */
function slider_post()
{
    $labels = array(
        'name' => 'Слайды', // Основное название типа записи
        'singular_name' => 'Слайд', // отдельное название записи типа Book
        'add_new' => 'Добавить новый',
        'add_new_item' => 'Добавить новый слайд',
        'edit_item' => 'Редактировать слайд',
        'new_item' => 'Новый слайд',
        'view_item' => 'Посмотреть слайд',
        'search_items' => 'Найти слайд',
        'not_found' => 'Слайдов не найдено',
        'not_found_in_trash' => 'В корзине слайдов не найдено',
        'parent_item_colon' => '',
        'menu_name' => 'Слайды'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'thumbnail', 'custom-fields')
    );
    register_post_type('slide', $args);
}

//добавляем функцию в экшн init
add_action('init', 'slider_post');

function titleGenerator(string $string) :string
{
    list($l, $r) = explode('~', strip_tags($string));
    return ($l && $r) ? $l . " <small>$r</small>" : null;
}