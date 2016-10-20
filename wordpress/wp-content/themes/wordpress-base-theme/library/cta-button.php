<?php

/****************************************************************************/
/*                          CALL TO ACTION WIDGET                           */
/*                                                                          */
/*     Adds a Call to Action Widget to the available sidebar widgets        */
/*                  version 3.0 - Simple/Complex option                     */
/****************************************************************************/

function cta_image_sizes() {
    global $_wp_additional_image_sizes;
    if(!isset($_wp_additional_image_sizes) || !isset($_wp_additional_image_sizes['cta-simple'])):
        add_image_size( 'cta-home', 300, 200, true );
        add_image_size( 'cta-page', 300, 9999, true );
    endif;
    // if(!isset($_wp_additional_image_sizes) || !isset($_wp_additional_image_sizes['cta-complex'])):
    //     add_image_size( 'cta-complex', 300, 300, true );
    // endif;
}
add_action('widgets_init', 'cta_image_sizes');

class Call_To_Action_Widget extends WP_Widget {

    private static $scripts_added;

    function Call_To_Action_Widget() {
        $widget_ops = array('classname' => 'cta-widget', 'description' => __( "Add a call-to-action to the sidebar.") );
        parent::__construct('cta-widget', __('Call to Action'), $widget_ops);
    }

    function widget($args, $instance) {
        extract($args);

        $defaults = array(
            'type'      => 'simple',
            'css_class' => false,
            'target'    => false,
            'link_text' => 'Click Here',
            'url'       => false,
            'img_size'  => 'cta-simple',
            'image'     => false,
            'title'     => false,
            'text'      => false
        );
        extract(wp_parse_args( $instance, $defaults ));

        $title = apply_filters('widget_title', $title);
        $target = $target == 'on' ? 'target="_blank"' : '';
        $size = ''.$type;
        $type_class = $size;
        $url = is_int($url) ? get_permalink($url) : esc_url($url);

        echo str_replace('class="', 'class="cta-'.$type.' ', $before_widget);

        // Theme templates override defaults
        if($template = locate_template('templates/cta-'.$type.'.php')):
            include($template);
        else:
            include('templates/cta-'.$type.'.php');
        endif;

        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        //$instance = $old_instance;

        $type = in_array($new_instance['type'], array('simple', 'complex', 'button')) ? $new_instance['type'] : 'simple';
        $url = esc_url_raw($new_instance['url']);
        if($id = url_to_postid($url)) $url = $id;

        switch($type):
            case 'complex':
                $instance['text'] = esc_textarea( $new_instance['text'] );
                $instance['title'] = strip_tags($new_instance['title']);

            case 'simple':
                $sizes = get_intermediate_image_sizes();
                $instance['image'] = intval($new_instance['image']);
                $instance['img_size'] = (isset($new_instance['img_size']) && in_array($new_instance['img_size'], $sizes)) ? $new_instance['img_size'] : 'cta-'.$type;

            case 'button':
                $instance['url'] = $url;
                $instance['link_text'] = sanitize_text_field( $new_instance['link_text'] );
                $instance['target'] = $new_instance['target'] == 'on' ? 'on' : 'off';
                $instance['css_class'] = sanitize_html_class($new_instance['css_class']);
                $instance['type'] = $type;
        endswitch;

        return $instance;
    }

    function form( $instance ) {


        $sizes = get_intermediate_image_sizes();

        $url = '';
        if(isset($instance['url'])):
            $url = $instance['url'];
            $url = is_int($url) ? get_permalink($url) : esc_url($url);
        endif;

        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $image = isset($instance['image']) ? esc_attr($instance['image']) : '';
        $text = isset($instance['text']) ? esc_textarea($instance['text']) : '';
        $link_text = isset($instance['link_text']) ? esc_attr($instance['link_text']) : '';
        $type = (isset($instance['type']) && in_array($instance['type'], array('simple', 'complex', 'button'))) ? $instance['type'] : 'simple';
        $target = (isset($instance['target']) && $instance['target'] == 'on') ? 'on' : 'off';
        $css_class = isset($instance['css_class']) ? sanitize_html_class($instance['css_class']) : '';

        $img_size = (isset($instance['img_size']) && in_array($instance['img_size'], $sizes)) ? $instance['img_size'] : 'cta-'.$type;
        ?>
        <fieldset class="type-button type-simple type-complex">
            <p>
                <strong style="margin-bottom: 5px; font-weight: normal; display:block"><?php _e('C2A Type:'); ?></strong>
                <input class="cta-type" <?php checked($type, 'button'); ?> type="radio" name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type-button'); ?>" value="button" />
                <label for="<?php echo $this->get_field_id('type-button'); ?>"><?php _e('Button'); ?></label>&nbsp;&nbsp;&nbsp;
                <input class="cta-type" <?php checked($type, 'simple'); ?> type="radio" name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type-simple'); ?>" value="simple" />
                <label for="<?php echo $this->get_field_id('type-simple'); ?>"><?php _e('Simple'); ?></label>&nbsp;&nbsp;&nbsp;
                <input class="cta-type" <?php checked($type, 'complex'); ?> type="radio" name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type-complex'); ?>" value="complex" />
                <label for="<?php echo $this->get_field_id('type-complex'); ?>"><?php _e('Complex'); ?></label>&nbsp;&nbsp;&nbsp;
            </p>
            <?php
            if(!isset($linkbtn)) $linkbtn = new AddLinkBtn401();
            $linkbtn->link_fields(array(
                'url_field_id'       => $this->get_field_id('url'),
                'url_field_name'     => $this->get_field_name('url'),
                'url_field_value'    => $url,
                'target_field_id'    => $this->get_field_id('target'),
                'target_field_name'  => $this->get_field_name('target'),
                'target_field_value' => $target,
                'title_field_id'     => $this->get_field_id('link_text'),
                'title_field_name'   => $this->get_field_name('link_text'),
                'title_field_label'  => 'Link Text:',
                'title_field_value'  => $link_text
            ));
            ?>
            <p>
                <label for="<?php echo $this->get_field_id('css_class'); ?>">CSS Class:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('css_class'); ?>" name="<?php echo $this->get_field_name('css_class'); ?>" type="text" value="<?php echo $css_class; ?>" />
            </p>
        </fieldset>
        <fieldset class="type-simple type-complex" <?php if(!in_array($type, array('simple', 'complex'))) echo ' style="display:none;"'; ?>>
            <p>
                <label for="<?php echo $this->get_field_id('img_size'); ?>">Image Size</label>
                <?php echo image_sizes_dropdown($this->get_field_id('img_size'), $this->get_field_name('img_size'), $img_size); ?>
            </p>
            <?php
            add_media_btn_401(array(
                'field_id'    => $this->get_field_id('image'),
                'field_name'  => $this->get_field_name('image'),
                'field_value' => $image,
                'link_text'   => 'Add Image',
                'img_size'    => $img_size
            ));
            ?>
        </fieldset>
        <fieldset class="type-complex" <?php if(!in_array($type, array('complex'))) echo ' style="display:none;"'; ?>>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:'); ?></label>
                <textarea rows="4" class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
            </p>
        </fieldset>


        <?php
    }

    public static function admin_scripts() {
        ?>
        <script type="text/javascript">
            jQuery(function($) {
                $(document).on('change', '.cta-type', function() {
                    if(!$(this).is(':checked')) return;

                    var type = $(this).val();
                    if(window.console !== 'undefined') window.console.log(type);

                    $(this).closest('.widget-content').find('fieldset').show().not('.type-'+type).hide();

                });
                $(document).on('change', '.img-size-dropdown', function() {
                    var s = $(this).find(':selected'),
                        w = s.data('w'),
                        h = s.data('h');
                    $(this).closest('.widget-content').find('.description.upload-size').text('('+w+'x'+h+')');
                });
            });
        </script>
        <?php
    }

    public static function register() {
        register_widget(__CLASS__);
        if(is_null(self::$scripts_added)):
            self::$scripts_added = true;
            add_action('admin_footer', array(__CLASS__, 'admin_scripts'));
        endif;
    }

}
add_action( 'widgets_init', array('Call_To_Action_Widget', 'register') );


function image_sizes_dropdown($id, $name, $selected) {
    global $_wp_additional_image_sizes;
    $output = array();
    $output[] = '<select name="'.$name.'" id="'.$id.'" class="widefat img-size-dropdown">';
    foreach (get_intermediate_image_sizes() as $s):
        if (isset($_wp_additional_image_sizes[$s])):
            $w = intval($_wp_additional_image_sizes[$s]['width']);
            $h = intval($_wp_additional_image_sizes[$s]['height']);
        else:
            $w = get_option($s.'_size_w');
            $h = get_option($s.'_size_h');
        endif;
        $output[] = "<option value='{$s}' data-w='{$w}' data-h='{$h}' ".selected($s, $selected, false).">{$s}   ({$w}x{$h})</option>";

    endforeach;
    $output[] = '</select>';
    return join("\n", $output);
}