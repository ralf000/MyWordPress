<?php
 
require_once('add-link-button.php');
require_once('cta-button.php');
require_once('add-link-button.php');
require_once('add-media-button.php');
require_once('widgets/mini_carousel.php');
require_once('widgets/tertiary.php');
require_once('adminbar.php');
require_once('galleries.php');

/****************************************************************************/
/*                          CUSTOM SETTINGS                                 */
/****************************************************************************/

$mc_settings = array(
	'img_width' => 300,			// integer
	'img_height' => 999,		// integer
	'img_crop' => false, 		// boolean
	'max_slides' => 5, 			// integer
	'has_title' => false, 		// boolean
	'has_text' => false, 		// boolean
	'has_cta' => false, 		// false/string
	'has_bclass' => false, 		// false/string
	'bullet_html' => '&bull;', 	// string
	'prev_button' => false, 	// false/string
	'next_button' => false 		// false/string
);

$cta_settings = array(
	'simple_width' => 300,		// integer
	'simple_height' => 999,		// integer
	'simple_crop' => false,		// boolean
	'complex_width' => 300,		// integer
	'complex_height' => 170,	// integer
	'complex_crop' => true		// boolean
);

$page_numbers_settings = array(
	'page_of_page' => 'on',
	'page_of_page_text' => 'Page',
	'page_of_of' => 'of',
	'next_prev_text' => 'on',
	'show_start_end_numbers' => 'on',
	'show_page_numbers' => 'on',
	'limit_pages' => 6,
	'nextpage' => '»',
	'prevpage' => '«',
	'startspace' => '',
	'endspace' => '',
	'style_theme' => 'default'
);

$category_thumb_settings = array(
	'img_width' => 140,			// integer
	'img_height' => 140,		// integer
	'img_crop' => true, 		// boolean
);


/****************************************************************************/
/*                           SIDEBAR PARAMETERS                             */
/*                                                                          */
/*                Customize the sidebar widget settings                     */
/****************************************************************************/

function sidebar_params_401($params) {

	$params[0]['before_widget'] = '<div id="'.$params[0]['widget_id'].'" class="'. str_replace("-{$params[1]['number']}", '', $params[0]['widget_id']) .' module clearfix '. sanitize_title($params[0]['widget_name']) .'">';
	$params[0]['after_widget'] = '</div>';
	$params[0]['before_title'] = '<h4>';
	$params[0]['after_title'] = '</h4>';

	return $params;
}
add_filter('dynamic_sidebar_params', 'sidebar_params_401', 1);



/****************************************************************************/
/*                          4O1 IMAGE SIZE CONTROL                          */
/*                                                                          */
/* Only allow Full Sized images if they are smaller than the "Large" size   */
/****************************************************************************/

add_filter('attachment_fields_to_edit', 'MY_image_attachment_fields_to_edit', 11, 2);

function MY_image_attachment_fields_to_edit($form_fields, $post) {
 if ( substr($post->post_mime_type, 0, 5) == 'image' ) {
  $alt = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
  if ( empty($alt) )
   $alt = '';

  $form_fields['post_title']['required'] = true;

  $form_fields['image_alt'] = array(
   'value' => $alt,
   'label' => __('Alternate text'),
   'helps' => __('Alt text for the image, e.g. &#8220;The Mona Lisa&#8221;')
  );

  $form_fields['align'] = array(
   'label' => __('Alignment'),
   'input' => 'html',
   'html'  => image_align_input_fields($post, get_option('image_default_align')),
  );

  $form_fields['image-size'] = MY_image_size_input_fields( $post, get_option('image_default_size', 'medium') );


 } else {
  unset( $form_fields['image_alt'] );
 }

 return $form_fields;
}
function MY_image_size_input_fields( $post, $check = '' ) {

	$toobig = image_get_intermediate_size($post->ID, 'large');

  $size_names = $toobig ? array('thumbnail' => __('Thumbnail'), 'medium' => __('Medium'), 'large' => __('Large')) : array('thumbnail' => __('Thumbnail'), 'medium' => __('Medium'), 'large' => __('Large'), 'full' => __('Full size'));

  if ( empty($check) )
   $check = get_user_setting('imgsize', 'medium');

  foreach ( $size_names as $size => $label ) {

   $downsize = image_downsize($post->ID, $size);
   $checked = '';

   $enabled = $toobig ? ( $downsize[3] || 'large' == $size ) : ( $downsize[3] || 'full' == $size );
   $css_id = "image-size-{$size}-{$post->ID}";
   if ( $size == $check ) {
    if ( $enabled )
     $checked = " checked='checked'";
    else
     $check = '';
   } elseif ( !$check && $enabled && 'thumbnail' != $size ) {
    $check = $size;
    $checked = " checked='checked'";
   }

   $html = "<div class='image-size-item'><input type='radio' " . ( $enabled ? '' : "disabled='disabled' " ) . "name='attachments[$post->ID][image-size]' id='{$css_id}' value='{$size}'$checked />";

   $html .= "<label for='{$css_id}'>$label</label>";
   if ( $enabled )
    $html .= " <label for='{$css_id}' class='help'>" . sprintf( __("(%d&nbsp;&times;&nbsp;%d)"), $downsize[1], $downsize[2] ). "</label>";

   $html .= '</div>';

   $out[] = $html;

  }

  return array(
   'label' => __('Size'),
   'input' => 'html',
   'html'  => join("\n", $out),
  );
}


/****************************************************************************/
/*                             ADD TEL PROTOCOL                             */
/*                                                                          */
/* Adds tel protocol to allowed protocols for esc_url (used in wp_nav_menus)*/
/****************************************************************************/

function add_tel($protocols) {
	$protocols[] = 'tel';
	return $protocols;
}
add_action('kses_allowed_protocols', 'add_tel');

/****************************************************************************/
/*                               UWRAP IMAGES                               */
/*                                                                          */
/* Removes wrapping <p> tags from images allowing them to float properly    */
/****************************************************************************/

function unwrap_images_from_paragraph($content){
	return preg_replace('/^(<p[^>]*>)((?:<a[^>]+>)*<img[^>]+>\s*(?:<\/a>)*\s*)<\/p>/m', '$2', $content);
}
add_action('the_content','unwrap_images_from_paragraph');


/****************************************************************************/
/*                                CAPTION FIX                               */
/*                                                                          */
/* Removes excess pixel width that WordPress stupidly adds to captions      */
/****************************************************************************/

function fixed_img_caption_shortcode($html, $attr, $content = null) {
	extract(shortcode_atts(array(
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	), $attr));

	if ( 1 > (int) $width || empty($caption) ) return $content;

	if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width: ' . ((int) $width) . 'px">'
	. do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
}
add_filter('img_caption_shortcode', 'fixed_img_caption_shortcode', 10, 3);


/****************************************************************************/
/*                              SECTION BREAK                               */
/*                                                                          */
/* Addes section break to help break up content                             */
/****************************************************************************/

function sectionbreak_filter_content($content){
	return	'<div class="first-section section clearfix">'."\r\r".preg_replace('/[\s]*(<!--)(section)(-->)[\s]*/', "\r\r".'</div><div class="section clearfix">'."\r\r", $content)."\r\r".'</div>';
}
function sectionbreak_addbuttons() {
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_sectionbreak_tinymce_plugin");
     add_filter('mce_buttons', 'register_sectionbreak_button');
   }
}
function register_sectionbreak_button($buttons) {
   array_push($buttons, "|", "wp_sectionbreak");
   return $buttons;
}
function add_sectionbreak_tinymce_plugin($plugin_array) {
   $plugin_array['sectionbreak'] = get_bloginfo('template_url').'/library/401functions/editor_plugin.js';
   return $plugin_array;
}
add_action('init', 'sectionbreak_addbuttons');
add_filter('the_content', 'sectionbreak_filter_content',2);


/****************************************************************************/
/*                             CSS CACHE BUSTER                             */
/*                                                                          */
/* Appends last-modified-date to the stylesheet url to stop caching issues  */
/****************************************************************************/

function css_cache_buster() {
	$updated = filemtime(trailingslashit(get_stylesheet_directory()) . 'style.css' );
	echo add_query_arg(array('401cb' => $updated), get_bloginfo('stylesheet_url'));
}

/****************************************************************************/
/*                               WIDGET LOGIC                               */
/*                                                                          */
/* Use Wordpress Conditional Logic to only display widgets on certain pages */
/****************************************************************************/
/*
Plugin Name:    Widget Logic
Plugin URI:     http://wordpress.org/extend/plugins/widget-logic/
Description:    Control widgets with WP's conditional tags is_home etc
Version:        0.57
Author:         Alan Trewartha
Author URI:     http://freakytrigger.co.uk/author/alan/
 
Text Domain:   widget-logic
Domain Path:   /languages/
*/ 


global $wl_options;
$wl_load_points=array(  'plugins_loaded'    =>  __( 'when plugin starts (default)', 'widget-logic' ),
                        'after_setup_theme' =>  __( 'after theme loads', 'widget-logic' ),
                        'wp_loaded'         =>  __( 'when all PHP loaded', 'widget-logic' ),
                        'wp_head'           =>  __( 'during page header', 'widget-logic' )
                    );

if((!$wl_options = get_option('widget_logic')) || !is_array($wl_options) ) $wl_options = array();

if (is_admin())
{
    add_filter( 'widget_update_callback', 'widget_logic_ajax_update_callback', 10, 3);              // widget changes submitted by ajax method
    add_action( 'sidebar_admin_setup', 'widget_logic_expand_control');                              // before any HTML output save widget changes and add controls to each widget on the widget admin page
    add_action( 'sidebar_admin_page', 'widget_logic_options_control');                              // add Widget Logic specific options on the widget admin page
    add_filter( 'plugin_action_links', 'wl_charity', 10, 2);                                        // add my justgiving page link to the plugin admin page
}
else
{
    if (    isset($wl_options['widget_logic-options-load_point']) &&
            ($wl_options['widget_logic-options-load_point']!='plugins_loaded') &&
            array_key_exists($wl_options['widget_logic-options-load_point'],$wl_load_points )
        )
        add_action ($wl_options['widget_logic-options-load_point'],'widget_logic_sidebars_widgets_filter_add');
    else
        widget_logic_sidebars_widgets_filter_add();
        
    if ( isset($wl_options['widget_logic-options-filter']) && $wl_options['widget_logic-options-filter'] == 'checked' )
        add_filter( 'dynamic_sidebar_params', 'widget_logic_widget_display_callback', 10);          // redirect the widget callback so the output can be buffered and filtered
}

function widget_logic_sidebars_widgets_filter_add()
{
    add_filter( 'sidebars_widgets', 'widget_logic_filter_sidebars_widgets', 10);                    // actually remove the widgets from the front end depending on widget logic provided
}
// wp-admin/widgets.php explicitly checks current_user_can('edit_theme_options')
// which is enough security, I believe. If you think otherwise please contact me


// CALLED VIA 'widget_update_callback' FILTER (ajax update of a widget)
function widget_logic_ajax_update_callback($instance, $new_instance, $this_widget)
{   global $wl_options;
    $widget_id=$this_widget->id;
    if ( isset($_POST[$widget_id.'-widget_logic']))
    {   $wl_options[$widget_id]=trim($_POST[$widget_id.'-widget_logic']);
        update_option('widget_logic', $wl_options);
    }
    return $instance;
}


// CALLED VIA 'sidebar_admin_setup' ACTION
// adds in the admin control per widget, but also processes import/export
function widget_logic_expand_control()
{   global $wp_registered_widgets, $wp_registered_widget_controls, $wl_options;


    // EXPORT ALL OPTIONS
    if (isset($_GET['wl-options-export']))
    {
        header("Content-Disposition: attachment; filename=widget_logic_options.txt");
        header('Content-Type: text/plain; charset=utf-8');
        
        echo "[START=WIDGET LOGIC OPTIONS]\n";
        foreach ($wl_options as $id => $text)
            echo "$id\t".json_encode($text)."\n";
        echo "[STOP=WIDGET LOGIC OPTIONS]";
        exit;
    }


    // IMPORT ALL OPTIONS
    if ( isset($_POST['wl-options-import']))
    {   if ($_FILES['wl-options-import-file']['tmp_name'])
        {   $import=split("\n",file_get_contents($_FILES['wl-options-import-file']['tmp_name'], false));
            if (array_shift($import)=="[START=WIDGET LOGIC OPTIONS]" && array_pop($import)=="[STOP=WIDGET LOGIC OPTIONS]")
            {   foreach ($import as $import_option)
                {   list($key, $value)=split("\t",$import_option);
                    $wl_options[$key]=json_decode($value);
                }
                $wl_options['msg']= __('Success! Options file imported','widget-logic');
            }
            else
            {   $wl_options['msg']= __('Invalid options file','widget-logic');
            }
            
        }
        else
            $wl_options['msg']= __('No options file provided','widget-logic');
        
        update_option('widget_logic', $wl_options);
        wp_redirect( admin_url('widgets.php') );
        exit;
    }


    // ADD EXTRA WIDGET LOGIC FIELD TO EACH WIDGET CONTROL
    // pop the widget id on the params array (as it's not in the main params so not provided to the callback)
    foreach ( $wp_registered_widgets as $id => $widget )
    {   // controll-less widgets need an empty function so the callback function is called.
        if (!isset($wp_registered_widget_controls[$id]))
            wp_register_widget_control($id,$widget['name'], 'widget_logic_empty_control');
        $wp_registered_widget_controls[$id]['callback_wl_redirect']=$wp_registered_widget_controls[$id]['callback'];
        $wp_registered_widget_controls[$id]['callback']='widget_logic_extra_control';
        array_push($wp_registered_widget_controls[$id]['params'],$id);  
    }


    // UPDATE WIDGET LOGIC WIDGET OPTIONS (via accessibility mode?)
    if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) )
    {   foreach ( (array) $_POST['widget-id'] as $widget_number => $widget_id )
            if (isset($_POST[$widget_id.'-widget_logic']))
                $wl_options[$widget_id]=trim($_POST[$widget_id.'-widget_logic']);
        
        // clean up empty options (in PHP5 use array_intersect_key)
        $regd_plus_new=array_merge(array_keys($wp_registered_widgets),array_values((array) $_POST['widget-id']),
            array('widget_logic-options-filter', 'widget_logic-options-wp_reset_query', 'widget_logic-options-load_point'));
        foreach (array_keys($wl_options) as $key)
            if (!in_array($key, $regd_plus_new))
                unset($wl_options[$key]);
    }

    // UPDATE OTHER WIDGET LOGIC OPTIONS
    // must update this to use http://codex.wordpress.org/Settings_API
    if ( isset($_POST['widget_logic-options-submit']) )
    {   $wl_options['widget_logic-options-filter']=$_POST['widget_logic-options-filter'];
        $wl_options['widget_logic-options-wp_reset_query']=$_POST['widget_logic-options-wp_reset_query'];
        $wl_options['widget_logic-options-load_point']=$_POST['widget_logic-options-load_point'];
    }


    update_option('widget_logic', $wl_options);

}




// CALLED VIA 'sidebar_admin_page' ACTION
// output extra HTML
// to update using http://codex.wordpress.org/Settings_API asap
function widget_logic_options_control()
{   global $wp_registered_widget_controls, $wl_options, $wl_load_points;

    if ( isset($wl_options['msg']))
    {   if (substr($wl_options['msg'],0,2)=="OK")
            echo '<div id="message" class="updated">';
        else
            echo '<div id="message" class="error">';
        echo '<p>Widget Logic – '.$wl_options['msg'].'</p></div>';
        unset($wl_options['msg']);
        update_option('widget_logic', $wl_options);
    }


    ?><div class="wrap">
        
        <h2><?php _e('Widget Logic options', 'widget-logic'); ?></h2>
        <form method="POST" style="float:left; width:45%">
            <ul>
                <li><label for="widget_logic-options-filter" title="<?php _e('Adds a new WP filter you can use in your own code. Not needed for main Widget Logic functionality.', 'widget-logic'); ?>">
                    <input id="widget_logic-options-filter" name="widget_logic-options-filter" type="checkbox" value="checked" class="checkbox" <?php if (isset($wl_options['widget_logic-options-filter'])) echo "checked" ?>/>
                    <?php _e('Add \'widget_content\' filter', 'widget-logic'); ?>
                    </label>
                </li>
                <li><label for="widget_logic-options-wp_reset_query" title="<?php _e('Resets a theme\'s custom queries before your Widget Logic is checked', 'widget-logic'); ?>">
                    <input id="widget_logic-options-wp_reset_query" name="widget_logic-options-wp_reset_query" type="checkbox" value="checked" class="checkbox" <?php if (isset($wl_options['widget_logic-options-wp_reset_query'])) echo "checked" ?> />
                    <?php _e('Use \'wp_reset_query\' fix', 'widget-logic'); ?>
                    </label>
                </li>
                <li><label for="widget_logic-options-load_point" title="<?php _e('Delays widget logic code being evaluated til various points in the WP loading process', 'widget-logic'); ?>"><?php _e('Load logic', 'widget-logic'); ?>
                    <select id="widget_logic-options-load_point" name="widget_logic-options-load_point" ><?php
                        foreach($wl_load_points as $action => $action_desc)
                        {   echo "<option value='".$action."'";
                            if (isset($wl_options['widget_logic-options-load_point']) && $action==$wl_options['widget_logic-options-load_point'])
                                echo " selected ";
                            echo ">".$action_desc."</option>"; // 
                        }
                        ?>
                    </select>
                    </label>
                </li>
            </ul>
            <?php submit_button( __( 'Save WL options', 'widget-logic' ), 'button-primary', 'widget_logic-options-submit', false ); ?>

        </form>
        <form method="POST" enctype="multipart/form-data" style="float:left; width:45%">
            <a class="submit button" href="?wl-options-export" title="<?php _e('Save all WL options to a plain text config file', 'widget-logic'); ?>"><?php _e('Export options', 'widget-logic'); ?></a><p>
            <?php submit_button( __( 'Import options', 'widget-logic' ), 'button', 'wl-options-import', false, array('title'=> __( 'Load all WL options from a plain text config file', 'widget-logic' ) ) ); ?>
            <input type="file" name="wl-options-import-file" id="wl-options-import-file" title="<?php _e('Select file for importing', 'widget-logic'); ?>" /></p>
        </form>

    </div>

    <?php
}

// added to widget functionality in 'widget_logic_expand_control' (above)
function widget_logic_empty_control() {}



// added to widget functionality in 'widget_logic_expand_control' (above)
function widget_logic_extra_control()
{   global $wp_registered_widget_controls, $wl_options;

    $params=func_get_args();
    $id=array_pop($params);

    // go to the original control function
    $callback=$wp_registered_widget_controls[$id]['callback_wl_redirect'];
    if (is_callable($callback))
        call_user_func_array($callback, $params);       
    
    $value = !empty( $wl_options[$id ] ) ? htmlspecialchars( stripslashes( $wl_options[$id ] ),ENT_QUOTES ) : '';

    // dealing with multiple widgets - get the number. if -1 this is the 'template' for the admin interface
    $id_disp=$id;
    if (!empty($params) && isset($params[0]['number']))
    {   $number=$params[0]['number'];
        if ($number==-1) {$number="__i__"; $value="";}
        $id_disp=$wp_registered_widget_controls[$id]['id_base'].'-'.$number;
    }

    // output our extra widget logic field
    echo "<p><label for='".$id_disp."-widget_logic'>". __('Widget logic:','widget-logic'). " <textarea class='widefat' type='text' name='".$id_disp."-widget_logic' id='".$id_disp."-widget_logic' >".$value."</textarea></label></p>";
}



// CALLED ON 'plugin_action_links' ACTION
function wl_charity($links, $file)
{   if ($file == plugin_basename(__FILE__))
        array_push($links, '<a href="http://www.justgiving.com/widgetlogic_cancerresearchuk/">Charity Donation</a>');
    return $links;
}



// FRONT END FUNCTIONS...



// CALLED ON 'sidebars_widgets' FILTER
function widget_logic_filter_sidebars_widgets($sidebars_widgets)
{   global $wp_reset_query_is_done, $wl_options;

    // reset any database queries done now that we're about to make decisions based on the context given in the WP query for the page
    if ( !empty( $wl_options['widget_logic-options-wp_reset_query'] ) && ( $wl_options['widget_logic-options-wp_reset_query'] == 'checked' ) && empty( $wp_reset_query_is_done ) )
    {   wp_reset_query(); $wp_reset_query_is_done=true; }

    // loop through every widget in every sidebar (barring 'wp_inactive_widgets') checking WL for each one
    foreach($sidebars_widgets as $widget_area => $widget_list)
    {   if ($widget_area=='wp_inactive_widgets' || empty($widget_list)) continue;

        foreach($widget_list as $pos => $widget_id)
        {   if (empty($wl_options[$widget_id]))  continue;
            $wl_value=stripslashes(trim($wl_options[$widget_id]));
            if (empty($wl_value))  continue;

            $wl_value=apply_filters( "widget_logic_eval_override", $wl_value );
            if ($wl_value===false)
            {   unset($sidebars_widgets[$widget_area][$pos]);
                continue;
            }
            if ($wl_value===true) continue;

            if (stristr($wl_value,"return")===false)
                $wl_value="return (" . $wl_value . ");";

            if (!eval($wl_value))
                unset($sidebars_widgets[$widget_area][$pos]);
        }
    }
    return $sidebars_widgets;
}



// If 'widget_logic-options-filter' is selected the widget_content filter is implemented...



// CALLED ON 'dynamic_sidebar_params' FILTER - this is called during 'dynamic_sidebar' just before each callback is run
// swap out the original call back and replace it with our own
function widget_logic_widget_display_callback($params)
{   global $wp_registered_widgets;
    $id=$params[0]['widget_id'];
    $wp_registered_widgets[$id]['callback_wl_redirect']=$wp_registered_widgets[$id]['callback'];
    $wp_registered_widgets[$id]['callback']='widget_logic_redirected_callback';
    return $params;
}


// the redirection comes here
function widget_logic_redirected_callback()
{   global $wp_registered_widgets, $wp_reset_query_is_done;

    // replace the original callback data
    $params=func_get_args();
    $id=$params[0]['widget_id'];
    $callback=$wp_registered_widgets[$id]['callback_wl_redirect'];
    $wp_registered_widgets[$id]['callback']=$callback;

    // run the callback but capture and filter the output using PHP output buffering
    if ( is_callable($callback) ) 
    {   ob_start();
        call_user_func_array($callback, $params);
        $widget_content = ob_get_contents();
        ob_end_clean();
        echo apply_filters( 'widget_content', $widget_content, $id);
    }
}




/****************************************************************************/
/*                               PAGE NUMBERS                               */
/*                                                                          */
/* Page Numbers modified - returns instead of echos                         */
/****************************************************************************/

function wp_page_numbers_check_num($num) {
  return ($num%2) ? true : false;
}
function wp_page_numbers_page_of_page($max_page, $paged, $page_of_page_text, $page_of_of) {
	$pagingString = "";
	if ( $max_page > 1)
	{
		$pagingString .= '<li class="page_info">';
		if($page_of_page_text == "")
			$pagingString .= 'Page ';
		else
			$pagingString .= $page_of_page_text . ' ';

		if ( $paged != "" )
			$pagingString .= $paged;
		else
			$pagingString .= 1;

		if($page_of_of == "")
			$pagingString .= ' of ';
		else
			$pagingString .= ' ' . $page_of_of . ' ';
		$pagingString .= floor($max_page).'</li>';
	}
	return $pagingString;
}
function wp_page_numbers_prevpage($paged, $max_page, $prevpage) {
	global $filter_pagenum;
	if( $max_page > 1 && $paged > 1 ) {
		$filter_pagenum = $paged-1;
		$pagingString = '<li class="prev-page_btn"><a href="'.get_pagenum_link($paged-1). '">'.$prevpage.'</a></li>';
	} else {
		$pagingString = '<li class="prev-page_btn"><a class="null">'.$prevpage.'</a></li>';
	}
	return $pagingString;
}
function wp_page_numbers_left_side($max_page, $limit_pages, $paged, $pagingString) {
	global $filter_pagenum;
	$pagingString = "";
	$page_check_max = false;
	$page_check_min = false;
	if($max_page > 1)
	{
		for($i=1; $i<($max_page+1); $i++)
		{
			if( $i <= $limit_pages )
			{
				if ($paged == $i || ($paged == "" && $i == 1)){
					$filter_pagenum = $i;
					$pagingString .= '<li class="active_page"><a href="'.get_pagenum_link($i). '">'.$i.'</a></li>'."\n";
				} else {
					$filter_pagenum = $i;
					$pagingString .= '<li><a href="'.get_pagenum_link($i). '">'.$i.'</a></li>'."\n";
				}
				if ($i == 1)
					$page_check_min = true;
				if ($max_page == $i)
					$page_check_max = true;
			}
		}
		return array($pagingString, $page_check_max, $page_check_min);
	}
}
function wp_page_numbers_middle_side($max_page, $paged, $limit_pages_left, $limit_pages_right) {
	global $filter_pagenum;
	$pagingString = "";
	$page_check_max = false;
	$page_check_min = false;
	for($i=1; $i<($max_page+1); $i++)
	{
		if($paged-$i <= $limit_pages_left && $paged+$limit_pages_right >= $i)
		{
			if ($paged == $i) {
				$filter_pagenum = $i;
				$pagingString .= '<li class="active_page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>'."\n";
			} else {
				$filter_pagenum = $i;
				$pagingString .= '<li><a href="'.get_pagenum_link($i). '">'.$i.'</a></li>'."\n";
			}
			if ($i == 1)
				$page_check_min = true;
			if ($max_page == $i)
				$page_check_max = true;
		}
	}
	return array($pagingString, $page_check_max, $page_check_min);
}

function wp_page_numbers_right_side($max_page, $limit_pages, $paged, $pagingString) {
	global $filter_pagenum;
	$pagingString = "";
	$page_check_max = false;
	$page_check_min = false;
	for($i=1; $i<($max_page+1); $i++)
	{
		if( ($max_page + 1 - $i) <= $limit_pages )
		{
			if ($paged == $i) {
				$filter_pagenum = $i;
				$pagingString .= '<li class="active_page"><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>'."\n";
			} else {
				$filter_pagenum = $i;
				$pagingString .= '<li><a href="'.get_pagenum_link($i). '">'.$i.'</a></li>'."\n";
			}
			if ($i == 1)
			$page_check_min = true;
		}
		if ($max_page == $i)
			$page_check_max = true;

	}
	return array($pagingString, $page_check_max, $page_check_min);
}

function wp_page_numbers_nextpage($paged, $max_page, $nextpage) {
	global $filter_pagenum;
	if( $paged != "" && $paged < $max_page) {
		$filter_pagenum = $paged+1;
		$pagingString = '<li class="next-page_btn"><a href="'.get_pagenum_link($paged+1).'">'.$nextpage.'</a></li>'."\n";
	} else {
		$pagingString = '<li class="next-page_btn"><a class="null">'.$nextpage.'</a></li>'."\n";
	}
	return $pagingString;
}
function wp_page_numbers($start = "", $end = "", $query = null) {
  if( $query === null ) {
    global $wp_query;
  } else {
    $wp_query = $query;
  }
	global $max_page;
	global $paged;
	global $page_numbers_settings;
	if ( !$max_page ) { $max_page = $wp_query->max_num_pages; }
	if ( !$paged ) { $paged = 1; }

	//$settings = get_option('wp_page_numbers_array');
	$settings = $page_numbers_settings;
	$page_of_page = $settings["page_of_page"];
	$page_of_page_text = $settings["page_of_page_text"];
	$page_of_of = $settings["page_of_of"];

	$next_prev_text = $settings["next_prev_text"];
	$show_start_end_numbers = $settings["show_start_end_numbers"];
	$show_page_numbers = $settings["show_page_numbers"];

	$limit_pages = $settings["limit_pages"];
	$nextpage = $settings["nextpage"];
	$prevpage = $settings["prevpage"];
	$startspace = $settings["startspace"];
	$endspace = $settings["endspace"];

	if( $nextpage == "" ) { $nextpage = "&gt;"; }
	if( $prevpage == "" ) { $prevpage = "&lt;"; }
	if( $startspace == "" ) { $startspace = "..."; }
	if( $endspace == "" ) { $endspace = "..."; }

	if($limit_pages == "") { $limit_pages = "10"; }
	elseif ( $limit_pages == "0" ) { $limit_pages = $max_page; }

	if(wp_page_numbers_check_num($limit_pages) == true)
	{
		$limit_pages_left = ($limit_pages-1)/2;
		$limit_pages_right = ($limit_pages-1)/2;
	}
	else
	{
		$limit_pages_left = $limit_pages/2;
		$limit_pages_right = ($limit_pages/2)-1;
	}

	if( $max_page <= $limit_pages ) { $limit_pages = $max_page; }

	$pagingString = "<div class='wp_page_numbers'>\n";
	$pagingString .= '<ul>';

	if($page_of_page != "no")
		$pagingString .= wp_page_numbers_page_of_page($max_page, $paged, $page_of_page_text, $page_of_of);

	if( ($paged) <= $limit_pages_left )
	{
		list ($value1, $value2, $page_check_min) = wp_page_numbers_left_side($max_page, $limit_pages, $paged, $pagingString);
		$pagingMiddleString .= $value1;
	}
	elseif( ($max_page+1 - $paged) <= $limit_pages_right )
	{
		list ($value1, $value2, $page_check_min) = wp_page_numbers_right_side($max_page, $limit_pages, $paged, $pagingString);
		$pagingMiddleString .= $value1;
	}
	else
	{
		list ($value1, $value2, $page_check_min) = wp_page_numbers_middle_side($max_page, $paged, $limit_pages_left, $limit_pages_right);
		$pagingMiddleString .= $value1;
	}
	if($next_prev_text != "no")

		if ($page_check_min == false && $show_start_end_numbers != "no")
		{
			$pagingString .= "<li class=\"first_last_page\">";
			$pagingString .= "<a href=\"" . get_pagenum_link(1) . "\">1</a>";
			$pagingString .= "</li>\n<li  class=\"space\">".$startspace."</li>\n";
		}

	if($show_page_numbers != "no")
		$pagingString .= $pagingMiddleString;

		if ($value2 == false && $show_start_end_numbers != "no")
		{
			$pagingString .= "<li class=\"space\">".$endspace."</li>\n";
			$pagingString .= "<li class=\"first_last_page\">";
			$pagingString .= "<a href=\"" . get_pagenum_link($max_page) . "\">" . $max_page . "</a>";
			$pagingString .= "</li>\n";
		}

	if($next_prev_text != "no"){
		$pagingString .= wp_page_numbers_nextpage($paged, $max_page, $nextpage, false);
	} else {
		$pagingString .= wp_page_numbers_nextpage($paged, $max_page, $nextpage, true);
	}

	if($next_prev_text != "no"){
		$pagingString .= wp_page_numbers_prevpage($paged, $max_page, $prevpage, false);
	} else {
		$pagingString .= wp_page_numbers_prevpage($paged, $max_page, $prevpage, true);
	}

	$pagingString .= "</ul>\n";
	$pagingString .= "</div>\n";

	if($max_page > 1)
		return $start . $pagingString . $end;
}


/****************************************************************************/
/*                       POST TYPE ARCHIVE MENU ITEMS                       */
/*                                                                          */
/* Adds archive pages for custom post types to Menus admin page             */
/****************************************************************************/


function inject_cpt_archives_menu_meta_box() {
	/* isn't this much better? */
	add_meta_box( 'add-cpt', __( 'Archive Pages' ), 'wp_nav_menu_cpt_archives_meta_box', 'nav-menus', 'side', 'default' );
}

add_action( 'admin_head-nav-menus.php', 'inject_cpt_archives_menu_meta_box' );

/* render custom post type archives meta box */
function wp_nav_menu_cpt_archives_meta_box() {

	/* get custom post types with archive support */
	$post_types = get_post_types( array( 'has_archive' => true ), 'object' );

	/* hydrate the necessary properties for identification in the walker */
	foreach ( $post_types as &$post_type ) {
		$post_type->classes = array();
		$post_type->type = $post_type->name;
		$post_type->object_id = $post_type->name;
		$post_type->title = $post_type->labels->name;
		$post_type->object = 'cpt-archive';
	}

	/* the native menu checklist */
	$walker = new Walker_Nav_Menu_Checklist( array() );

	?>
	<div id="cpt-archive" class="posttypediv">
		<div id="tabs-panel-cpt-archive" class="tabs-panel tabs-panel-active">
			<ul id="ctp-archive-checklist" class="categorychecklist form-no-clear">
				<?php echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $post_types), 0, (object) array( 'walker' => $walker) ); ?>
			</ul>
		</div><!-- /.tabs-panel -->
	</div>
	<p class="button-controls">
	  <span class="add-to-menu">
	    <input type="submit"<?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary submit-add-to-menu" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-ctp-archive-menu-item" id="submit-cpt-archive" />
	    <span class="spinner"></span>
	  </span>
	</p>
	<?php
}

function cpt_archive_menu_filter( $items, $menu, $args ) {

  /* alter the URL for cpt-archive objects */
  foreach ( $items as &$item ) {
    if ( $item->object != 'cpt-archive' ) continue;

    /* we stored the post type in the type property of the menu item */
    $item->url = get_post_type_archive_link( $item->type );

    if ( get_query_var( 'post_type' ) == $item->type ) {
      $item->classes[] = 'current-menu-item';
      $item->current = true;
    }
  }

  return $items;
}

add_filter( 'wp_get_nav_menu_items', 'cpt_archive_menu_filter', 10, 3 );

/****************************************************************************/
/*                             MODAL TEMPLATE                               */
/*                                                                          */
/*               Switches template for modal (popup) windows                */
/****************************************************************************/

function modal_template($template) {
	if( isset($_REQUEST['modal']) && $_REQUEST['modal'] === true ) {
		if(get_query_template('modal') != '') $template = get_query_template('modal');
	}
	return $template;
}

add_filter( 'template_include', 'modal_template' );

/****************************************************************************/
/*                                 IS 4O1!                                  */
/*                                                                          */
/*        Checks if user is logged in or IP address matches ours            */
/****************************************************************************/

function is_401( $strict = false ) {
	$IP = $_SERVER['REMOTE_ADDR'];

	if( $IP == '216.15.65.131' ) {
		return true;
	} elseif( !$strict && current_user_can('create_users') ) {
		return true;
	} else {
		return false;
	}
}

/****************************************************************************/
/*                      Google Analytics Field                              */
/*                                                                          */
/*    Adds a field on the dashboard to add your Google Analytics ID         */
/****************************************************************************/

function google_analytics_dashboard_widget() {
	global $wp_meta_boxes;
	add_meta_box( 'google_analytics', ' Google Analytics', 'google_analytics_field_display', 'dashboard', 'normal', 'high' );
}
function google_analytics_field_display() {
	if( isset($_POST['_wpnonce']) && wp_verify_nonce( $_POST['_wpnonce'], 'google_analytics_widget') ) :
		if( isset($_POST['google_analytics_id_401']) ) {
			update_option('google_analytics_id_401', $_POST['google_analytics_id_401']);
		} else {
			delete_option('google_analytics_id_401');
		}
	endif;
	?>

	<form name="google_analytics_widget" method="POST" action="" />
	<?php wp_nonce_field('google_analytics_widget'); ?>
	<p>
		<label for="google_analytics_id_401">Analytics ID:</label> <input type="text" name="google_analytics_id_401" id="google_analytics_id_401" value="<?php echo get_option('google_analytics_id_401'); ?>" />
		<input type="submit" class="button-primary" value="Submit" />
	</p>
	</form>
	<?php
}
add_action('admin_init','google_analytics_dashboard_widget');


/****************************************************************************/
/*                       Custom Gfield Classes                              */
/*                                                                          */
/*   Adds custom classes based on field type to the top level list items    */
/****************************************************************************/

function custom_gfield_classes($classes, $field, $form){
    if ( $field->type != 'hidden' ) {
        $classes .= ' ' . $field['type'];
    }
    if((isset($field['inputs']) && is_array($field['inputs'])) || ( is_array($field['type']) && in_array($field['type'], array('select', 'radio', 'fileupload')) )) {
        $classes .= ' multi';
    }
    return $classes;
}
add_action("gform_field_css_class", "custom_gfield_classes", 10, 3);


/***********************************************************************************************/
/*                                     Root Shortcode                                          */
/*                                                                                             */
/*   Adds shortcode [root] for text editor to maintain absolute path from dev to production    */
/***********************************************************************************************/
function shortcoderoot() {
	return get_option('home');
}
add_shortcode('root', 'shortcoderoot');


/****************************************************************************/
/*                            IMAGE SIZES TABLE                             */
/*                                                                          */
/*   Adds a table of all available image sizes to the Media settings page   */
/****************************************************************************/

function add_image_sizes_table401() {
	add_settings_section('image_sizes_table401', 'Image Sizes', 'image_sizes_table401', 'media');
}
add_action('admin_init', 'add_image_sizes_table401');

function image_sizes_table401() {
	global $_wp_additional_image_sizes;
	$sizes = array();
	foreach( get_intermediate_image_sizes() as $s ):
		$sizes[ $s ] = array( 0, 0 );
		if( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ){
			$sizes[ $s ][0] = get_option( $s . '_size_w' );
			$sizes[ $s ][1] = get_option( $s . '_size_h' );
			$sizes[ $s ][2] = get_option( $s . '_crop' );
		}else{
			if( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
				$sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'], $_wp_additional_image_sizes[ $s ]['crop']);
		}
	endforeach;
	$i = 0;
	?>
	<table width="400" cellpadding="5" cellspacing="0" style="background: #f9f9f9; border: 1px solid #DFDFDF; border-collapse: separate; border-bottom: 0;">
		<thead>
			<tr>
				<th scope="col" style="border-top: 1px solid #fff; border-bottom: 1px solid #dfdfdf; text-align:left;">Size</th>
				<th scope="col" style="border-top: 1px solid #fff; border-bottom: 1px solid #dfdfdf; text-align:left;">Width</th>
				<th scope="col" style="border-top: 1px solid #fff; border-bottom: 1px solid #dfdfdf; text-align:left;">Height</th>
				<th scope="col" style="border-top: 1px solid #fff; border-bottom: 1px solid #dfdfdf; text-align:left;">Crop</th>
			</tr>
		</thead>
		<?php foreach( $sizes as $size => $atts ): ?>
			<tr <?php if(++$i%2) echo 'class="alt"'; ?>>
				<th  style="border-top: 1px solid #fff; border-bottom: 1px solid #dfdfdf; text-align: left;" scope="row"><?php echo $size; ?></th>
				<td  style="border-top: 1px solid #fff; border-bottom: 1px solid #dfdfdf"><?php echo $atts[0]; ?></td>
				<td  style="border-top: 1px solid #fff; border-bottom: 1px solid #dfdfdf"><?php echo $atts[1]; ?></td>
				<td  style="border-top: 1px solid #fff; border-bottom: 1px solid #dfdfdf"><?php echo $atts[2] ? 'true' : 'false'; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php
}

?>