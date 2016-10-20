<?php
require_once('library/401functions.php');
require_once('library/cmb-field-gallery-master/cmb-field-gallery.php');
require_once('library/custom-post-types.php');
require_once('library/custom-cmb2-fields.php');

$class_path = get_template_directory() . "/classes";
foreach (glob($class_path . "/*.php") as $filename)
{
    include $filename;
    preg_match('/(\w+)\.php/',$filename,$class_name);
    if($class_name = $class_name[1])$$class_name = new $class_name;
}

// Debugging function for displaying the current page template
function debug_template() {
    global $template;
    echo basename($template);
}
//add_action('wp_head', 'debug_template');

// This theme uses post thumbnails
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 505,267, true );
add_image_size( 'gallery', 808, 454, true );
add_image_size( 'gallery-thumb', 190, 107, true );

// This theme uses wp_nav_menu() in one location.
register_nav_menus( array(
    'primary'   => 'Primary Navigation',
    'secondary' => 'Secondary Nav',
    'social'    => 'Social Nav'
));

// Enqueue Scripts
function enqueue_401_scripts() {
	wp_enqueue_script( 'jquery' );
	if ( !is_admin() ) {
		wp_enqueue_script( 'fourohone-header', get_bloginfo( 'template_url' ).'/js/header-scripts.min.js' );
		wp_enqueue_script( 'fourohone-footer', get_bloginfo( 'template_url' ).'/js/scripts.min.js', 'jquery', null, true );
    wp_localize_script( 'fourohone-header', 'bloginfo', array(
        'url'           => get_bloginfo('url'),
        'template_url'  => get_bloginfo('stylesheet_directory'),
        'ajax_url'      => admin_url('admin-ajax.php')
    ));
	}
	if ( is_admin() ) {
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
	}

}
add_action( 'init', 'enqueue_401_scripts' );

// Favicon
function favicon() {
	echo '<link rel="shortcut icon" type="image/x-icon" href="'.get_bloginfo('url').'/favicon.ico">';
}
add_action( 'wp_head', 'favicon' );

// ajax spinner
add_filter("gform_ajax_spinner_url", "spinner_url", 10, 2);
function spinner_url($image_src, $form){
    return get_bloginfo('template_url').'/images/ajax-loader.gif';
}

// Uncomment this if your site 500's on image upload since WP 4.5.x
// It reverts the image library back to the GD Library.
/*
function change_graphic_lib($array) {
    return array( 'WP_Image_Editor_GD', 'WP_Image_Editor_Imagick' );
}
add_filter( 'wp_image_editors', 'change_graphic_lib' );
*/

// Register sidebars
if ( function_exists('register_sidebar') ) {

   register_sidebar(array(
       'name' => 'Home',
       'id' => 'home',
       'before_widget' => '<div id="%1$s" class="widget %2$s">',
       'after_widget' => '</div>',
       'before_title' => '<h3>',
       'after_title' => '</h3>'
   ));

    register_sidebar(array(
       'name' => 'Primary',
       'id' => 'primary',
       'before_widget' => '<div id="%1$s" class="widget %2$s">',
       'after_widget' => '</div>',
       'before_title' => '<h3>',
       'after_title' => '</h3>'
   ));

}

// adding search html5 formatting
add_theme_support( 'html5', array( 'search-form' ) );

// == RANDOM HEADER IMAGE
add_theme_support( 'custom-header', array(
    'width'          => 1900,
    'height'         => 208,
    'header-text'    => false,
    'random-default' => true
));

function get_headline_image() {
    if (is_front_page())return;
    global $post;
    echo 'style="background-image:url('.get_header_image().');"';
}

// == DYNAMIC NAV
// function get_dynamic_nav($label=false){
//      $menu_name = 'primary';
//      $label = $label ? $label : $menu_name;
//      $menu_list = '';

//      $menu_list .= '<nav class="desktop">';

//     if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
//         $menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
//         $menu_items = wp_get_nav_menu_items($menu->term_id);
//         $menu_list .= '<ul id="menu-' . $label . '-navigation" class="menu">';
//         foreach ( $menu_items as $key => $menu_item ) {
//             $show_posts = false;
//             $children = array();
//             $classes = '';
//             $child_html = false;
//             foreach($menu_item->classes as $class){
//                 if($class == 'dynamic')$show_posts = true;
//                 $classes .= $class.' ';
//             }
//             if($show_posts){
//                 $children = get_posts(array('posts_per_page'=>-1,'orderby'=>'menu_order', 'order'=>'ASC', 'post_type'=>$menu_item->object, 'post_parent'=>$menu_item->object_id));
//                 if(count($children))$classes .= ' menu-item-has-children';
//                 foreach($children as $child){
//                     $child_html .= '<li><a href="'.get_the_permalink($child->ID).'">'.get_the_title($child->ID).'</a></li>';
//                 }
//             }
//             $href = get_page_template_slug($menu_item->object_id) == 'page-heading.php' ? 'href="javascript:;"' : 'href="' . $menu_item->url . '"';
//             $menu_list .= '<li class="'.$classes.'"><a '.$href.'>' . $menu_item->title . '</a>';
//             if($child_html)$menu_list .= '<ul class="sub-menu">'.$child_html.'</ul>';

//             $menu_list .= '</li>';
//         }
//         $menu_list .= '</ul>';
//     } else {
//         $menu_list = '<ul><li>Menu "' . $menu_name . '" not defined.</li></ul>';
//     }

//     $menu_list .= '</nav>';

//     echo $menu_list;
// }


// WIDGET BASEPLACE

// class Class_Name extends WP_Widget {
//     function Class_Name() {
//         $widget_ops = array( 'classname' => 'classname', 'description' => 'description goes here' );
//         $control_ops = array( 'id_base' => 'id_here' );
//         parent::__construct( 'id_here', 'Title Here', $widget_ops, $control_ops );
//     }

//     function widget( $args, $instance ) {
//         extract( $args );
//         global $post;
//         echo $before_widget;
        
        

//         echo $after_widget;
//     }
// }


// SHORTCODE BASEPLATE

// function shortcode_shortcode($atts, $content = null) {
//     extract(shortcode_atts(array(
//             "id" => ''
//     ), $atts));
        
//     ob_start();

    

//     $shortcode = ob_get_contents();
//     ob_end_clean();
        
//     return $shortcode;
// } 
// add_shortcode('shortcode', 'shortcode_shortcode'); 
