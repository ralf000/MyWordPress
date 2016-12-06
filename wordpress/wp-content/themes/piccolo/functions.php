<?php

/*
 * меню (вывод меню первый вариант)
 */
register_nav_menus([
    'menu' => 'main menu',
    'footer-menu' => 'footer-menu'
]);

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

function add_quotes_title_in_opts()
{
    add_settings_field(
        'quotes_title', // $id - Название опции (идентификатор)
        'Заголовок для цитат на главной', // $title - Заголовок поля
        'display_quotes_title_in_opts', // $callback - callback function
        'general' // $page - Страница меню в которую будет добавлено поле
    );
    register_setting(
        'general', // $option_group - Название группы, к которой будет принадлежать опция.
        // Это название должно совпадать с названием группы в функции settings_fields()
        'quotes_title' // $option_name - Название опции, которая будет сохраняться в БД
    );
}

add_action('admin_init', 'add_phone_in_opts');
add_action('admin_init', 'add_logo_text_in_opts');
add_action('admin_init', 'add_quotes_title_in_opts');

function display_phone_in_opts()
{
    echo "<input type='text' class='regular-text' name='my_phone' value='" . esc_attr(get_option('my_phone')) . "'>";
}

function display_logo_text_in_opts()
{
    echo "<input type='text' class='regular-text' name='logo_text' value='" . esc_attr(get_option('logo_text')) . "'>";
}

function display_quotes_title_in_opts()
{
    echo "<input type='text' class='regular-text' name='quotes_title' value='" . esc_attr(get_option('quotes_title')) . "'>";
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


/*
 * Добавляем новый тип записей цитаты
 */
function quote_post()
{
    $labels = array(
        'name' => 'Цитаты', // Основное название типа записи
        'singular_name' => 'Цитата', // отдельное название записи типа Book
        'add_new' => 'Добавить новую',
        'add_new_item' => 'Добавить новую Цитату',
        'edit_item' => 'Редактировать Цитату',
        'new_item' => 'Новая Цитата',
        'view_item' => 'Посмотреть Цитату',
        'search_items' => 'Найти Цитату',
        'not_found' => 'Цитат не найдено',
        'not_found_in_trash' => 'В корзине Цитат не найдено',
        'parent_item_colon' => '',
        'menu_name' => 'Цитаты'
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
        'supports' => ['title', 'excerpt', 'editor']
    );
    register_post_type('quote', $args);
}

//добавляем функцию в экшн init
add_action('init', 'quote_post');

function titleGenerator(string $string) :string
{
    list($l, $r) = explode('~', strip_tags($string));
    return ($l && $r) ? $l . " <small>$r</small>" : null;
}

//область для виджетов
$opts = [
    'name' => 'Клиенты компании',//название области для админки
    'id' => 'clients',
    'before_widget' => '<div class="clients-widget" id="%1$s">',
    'after_widget' => '</div>',
    'before_title' => '<h3>',
    'after_title' => '</h3>'
];
register_sidebar($opts);

//область для виджетов
$opts = [
    'name' => 'Подвал сайта',//название области для админки
    'id' => 'footer',
    'before_widget' => '<div class="span3 footer-col" id="%1$s">',
    'after_widget' => '</div>',
    'before_title' => '<h5>',
    'after_title' => '</h5>'
];
register_sidebar($opts);

//область для виджетов
$opts = [
    'name' => 'Правая колонка категории записей',//название области для админки
    'id' => 'category_sidebar',
    'before_widget' => '<div id="%1$s">',
    'after_widget' => '</div>',
    'before_title' => '<h5 class="title-bg">',
    'after_title' => '</h5>'
];
register_sidebar($opts);

//область для виджетов
$opts = [
    'name' => 'Правая колонка категории записей (переключающаяся панель)',//название области для админки
    'id' => 'category_sidebar_bottom',
    'before_widget' => '<div class="tab-pane" id="%1$s">',
    'after_widget' => '</div>',
];
register_sidebar($opts);

/**
 * Выводит отформатированный список тегов
 * @param string $tags результат работы функции get_the_tag_list()
 * @return string отформатированный список тегов
 */
function getTags(string $tags) : string
{
    return trim(str_replace('</a>', '</a> ', $tags));
}


/**
 * пагинация
 */
function sitePagination() {
    global $wp_query, $wp_rewrite;
    $output       = $pages        = '';
    $max          = $wp_query->max_num_pages;
    if (!$current      = get_query_var('paged'))//если текущая страница = 0 (если мы на первой странице)
        $current      = 1;
    $total          = 0; //1 - выводить текст "Страница N из N", 0 - не выводить
    $opts = array(
        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'total' => $max,
        'type' => 'list',
        'current' => $current,
        'prev_text' => __('Previous'),
        'next_text' => __('Next'),
        'end_size' => 1,
        'mid_size' => 2,
        'add_args' => array(), // array of query args to add
        'add_fragment' => '',
        'before_page_number' => '',
        'after_page_number' => ''
    );
    if ($max > 1) {
        echo '<div class="pagination">';
        if ($total == 1)
            $pages = '<span class="pages">Страница ' . $current . ' из ' . $max . '</span>' . "\r\n";
        echo $pages . paginate_links($opts);
        echo '</div>';
    }
}

/**
 * редактируем форму комментариев (порядок полей)
 */
add_filter('comment_form_fields', 'reorder_comment_fields');
function reorder_comment_fields($fields)
{
    $new_fields = array(); // сюда соберем поля в новом порядке
    $order = array('author', 'email', 'url', 'comment'); // нужный порядок
    foreach ($order as $key) {
        $new_fields[$key] = $fields[$key];
        unset($fields[$key]);
    }
    // если остались еще какие-то поля добавим их в конец
    if ($fields)
        foreach ($fields as $key => $val)
            $new_fields[$key] = $val;
    return $new_fields;
}


/**
 * Вовзращает массив меток
 * @return array массив меток
 */
function getTagsInArray($id = NULL) {
    $arr    = ($id !== NULL) ? wp_get_post_tags($id) : get_the_tags();
    $output = [];
    if (empty($arr))
        return NULL;
    foreach ($arr as $tag) {
        $id                 = $tag->term_id;
        $output[$tag->name] = get_tag_link($id);
    }
    return $output;
}

/*
  * возвращает список меток всех постов в категории
  */
function getAllTagsFromCategory($catID) {
    $posts = get_posts([
        'category'    => $catID,
        'numberposts' => -1
    ]);
    $tags = [];
    foreach ($posts as $post) {
        if (!empty($postTags = getTagsInArray($post->ID))) {
            foreach ($postTags as $tag => $link) {
                $tags[$tag] = $link;
            }
        }
    }
    return $tags;
}

/**
 * Получает список имен тегов
 * Использовать в цикле
 * @return null|string
 */
function getTagsAsString(){
    $tags = get_the_tags();
    $output = '';
    if (!$tags) return null;
    foreach ($tags as $tag){
        $output .= ($tag->name) . ' ';
    }
    return trim($output);
}

