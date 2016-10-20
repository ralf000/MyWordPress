<?php
class Mini_Carousel_Widget extends WP_Widget {

    protected static $scripts_added;
    protected static $linkbtn;

    function Mini_Carousel_Widget() {
        $widget_ops = array('classname' => 'widget_mini_carousel', 'description' => __( "Add a mini-carousel to the sidebar.") );
        parent::__construct('mini-carousel', __('Mini Carousel'), $widget_ops);

        if(is_null(self::$linkbtn)):
            self::$linkbtn = new AddLinkBtn401();
        endif;
    }

    public function widget($args, $instance) {
        extract($args);
        $global_defaults = array(
            'type'      => 'simple',
            'img_size'  => 'cta'
        );
        $settings = array();
        if(isset($instance['settings'])):
            $settings = $instance['settings'];
            unset($instance['settings']);
        endif;
        extract(wp_parse_args( $settings, $global_defaults ));

        echo $before_widget;

        if ( ! empty( $settings['mini_title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $settings['mini_title'] ) . $args['after_title'];
        }

        echo '<ul class="mini-controls">' . str_repeat('<li><span>B</span></li>', count($instance)) . '</ul>';

        echo '<div class="mini-items"><ul>';

        foreach($instance as $k => $item):
            $defaults = array(
                'css_class' => '',
                'target'    => false,
                'link_text' => '',
                'url'       => false,
                'image'     => false,
                'title'     => '',
                'text'      => ''
            );
            extract(wp_parse_args( $item, $defaults ));
            $target = $target == 'on' ? 'target="_blank"' : '';

            $url = self::$linkbtn->get_url($url);

            echo '<li class="mini-item'.(++$k === 1 ? ' current' : '').' ' .$css_class.'">';

            // Theme templates override defaults
            if($template = locate_template('templates/mini-'.$type.'.php')):
                include($template);
            elseif($template = locate_template('templates/cta-'.$type.'.php')):
                include($template);
            else:
                include('templates/cta-'.$type.'.php');
            endif;

        endforeach;

        echo '</li>';

        echo '</ul></div>';

        echo $after_widget;

        if( $args['widget_id'] ) :
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#<?php echo $args['widget_id']; ?>').imagegallery({
                effect:     'fxSnapIn',
                effectlist: '.mini-items',
                items:      '.mini-item',
                nav:        '.mini-controls',
                autoPlay:   true
            });            
        });
        </script>
        <?php
        endif;
    }


    public function update( $new_instance, $old_instance ) {

        $items = $this->regroup_array($new_instance);

        $instance['settings']['mini_title'] = (isset($new_instance['mini_title'])) ? $new_instance['mini_title'] : '';
        unset($new_instance['mini_title']);

        $type = in_array($new_instance['type'], array('simple', 'complex')) ? $new_instance['type'] : 'simple';
        $instance['settings']['type'] = $type;
        unset($new_instance['type']);

        $sizes = get_intermediate_image_sizes();
        $instance['settings']['img_size'] = (isset($new_instance['img_size']) && in_array($new_instance['img_size'], $sizes)) ? $new_instance['img_size'] : 'cta';
        unset($new_instance['img_size']);

        foreach($items as $k => $v):
            $item = array();
            $url = self::$linkbtn->get_id($v['url']);
            // echo '<pre>'.print_r($url, true).'</pre>';die();

            switch($type):
                case 'complex':
                    $item['text'] = esc_textarea( $v['text'] );
                    $item['title'] = strip_tags($v['title']);

                default:
                    if(!empty($v['image'])) $item['image'] = intval($v['image']);
                    $item['url'] = $url;
                    $item['link_text'] = sanitize_text_field( $v['link_text'] );
                    $item['target'] = $v['target'] == 'on' ? 'on' : '';
                    $item['css_class'] = sanitize_html_class($v['css_class']);
            endswitch;

            $item_string = join('', $item);
            if(!empty($item_string))
                $instance[] = $item;

        endforeach;

        return $instance;
    }

    public function form( $instance ) {
        $global_defaults = array(
            'type'      => 'simple',
            'img_size'  => 'cta'
        );
        $settings = array();
        if(isset($instance['settings'])):
            $settings = $instance['settings'];
            unset($instance['settings']);
        endif;

        extract(wp_parse_args( $settings, $global_defaults ));

        $defaults = array(
            'css_class' => '',
            'target'    => false,
            'link_text' => '',
            'url'       => '',
            'image'     => false,
            'title'     => '',
            'text'      => ''
        );

        $mini_title = (isset($settings['mini_title'])) ? $settings['mini_title'] : '';

        // $minitit = ( ! empty( $new_instance['mini_title'] ) ) ? strip_tags( $new_instance['mini_title'] ) : '';
        // $instance['settings']['mini_title'] = $minitit;

        ?>

        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'mini_title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'mini_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'mini_title' ) ); ?>" type="text" value="<?php echo $mini_title; ?>">
        </p>

        <p>
            <strong style="margin-bottom: 5px; font-weight: normal; display:block">Mini Carousel Type</strong>
            <input class="mc-type" <?php checked($type, 'simple'); ?> type="radio" name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type-simple'); ?>" value="simple" />
            <label for="<?php echo $this->get_field_id('type-simple'); ?>"><?php _e('Simple'); ?></label>&nbsp;&nbsp;&nbsp;
            <input class="mc-type" <?php checked($type, 'complex'); ?> type="radio" name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type-complex'); ?>" value="complex" />
            <label for="<?php echo $this->get_field_id('type-complex'); ?>"><?php _e('Complex'); ?></label>&nbsp;&nbsp;&nbsp;
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('img_size'); ?>">Image Size</label>
            <?php echo image_sizes_dropdown($this->get_field_id('img_size'), $this->get_field_name('img_size'), $img_size); ?>
        </p>

        <div class="mini-car-form">
            <div class="minis">
                <?php $count = max(1, count($instance));
                for($i = 0; $i < $count; $i++):
                    $item = isset($instance[$i]) ? $instance[$i] : array();
                    extract(wp_parse_args( $item, $defaults ));
                    $url = self::$linkbtn->get_url($url);

                    $item_title = empty($link_text) ? 'Mini Item' : $link_text;

                    ?>
                    <div class="mini-item">
                        <a href="#" class="remove-mini">Remove</a> <h4 class="mini-title"><span class="dashicons dashicons-plus"></span> <span class="title-text"><?php echo $item_title; ?></span></h4>
                        <div class="mini-item-content" style="display:none;">
                            <?php
                            self::$linkbtn->link_fields(array(
                                'url_field_id'       => $this->mini_field_id('url', $i),
                                'url_field_name'     => $this->mini_field_name('url'),
                                'url_field_value'    => esc_url($url),
                                'target_field_id'    => $this->mini_field_id('target', $i),
                                'target_field_name'  => $this->mini_field_name('target'),
                                'target_field_value' => $target,
                                'title_field_id'     => $this->mini_field_id('link_text', $i),
                                'title_field_name'   => $this->mini_field_name('link_text'),
                                'title_field_label'  => 'Link Text:',
                                'title_field_value'  => $link_text
                            ));

                            add_media_btn_401(array(
                                'field_id'    => $this->mini_field_id('image', $i),
                                'field_name'  => $this->mini_field_name('image'),
                                'field_value' => $image,
                                'link_text'   => 'Add Image',
                                'img_size'    => $img_size,
                                'multiple'    => false
                            ));
                            ?>
                            <p>
                                <label for="<?php echo $this->mini_field_id('css_class', $i); ?>">CSS Class:</label>
                                <input class="widefat" id="<?php echo $this->mini_field_id('css_class', $i); ?>" name="<?php echo $this->mini_field_name('css_class'); ?>" type="text" value="<?php echo sanitize_html_class($css_class); ?>" />
                            </p>
                            <fieldset class="type-complex" <?php if(!in_array($type, array('complex'))) echo ' style="display:none;"'; ?>>
                                <p>
                                    <label for="<?php echo $this->mini_field_id('title', $i); ?>"><?php _e('Title:'); ?></label>
                                    <input class="widefat" id="<?php echo $this->mini_field_id('title', $i); ?>" name="<?php echo $this->mini_field_name('title'); ?>" type="text" value="<?php echo sanitize_text_field($title); ?>" />
                                </p>
                                <p>
                                    <label for="<?php echo $this->mini_field_id('text', $i); ?>"><?php _e('Text:'); ?></label>
                                    <textarea rows="4" class="widefat" id="<?php echo $this->mini_field_id('text', $i); ?>" name="<?php echo $this->mini_field_name('text'); ?>"><?php echo sanitize_text_field($text); ?></textarea>
                                </p>
                            </fieldset>
                        </div>
                    </div>

                <?php endfor; ?>
            </div>
            <a href="#" class="button add-mini-item"><span class="dashicons dashicons-plus"></span> Add Item</a>
        </div>
    <?php

    }
    public static function admin_scripts() {
        ?>
        <script type="text/javascript">
            jQuery(function($){
                // Sort Items

                $('.minis').sortable();
                $('.minis').each(function() {
                    if($(this).find('.mini-item').length > 1) {
                        $(this).find('.remove-mini').show();
                    }
                });

                $(document).ajaxSuccess(function(e, xhr, settings) {
                    var widget_id_base = 'mywidget';

                    if(settings.data.search('action=save-widget') != -1) {
                        $('.minis').not('.ui-sortable').sortable();
                        $('.minis').each(function() {
                            if($(this).find('.mini-item').length > 1) {
                                $(this).find('.remove-mini').show();
                            }
                        });
                    }
                });

                // Show hide complex fields
                $(document).on('change', '.mc-type', function() {
                    if(!$(this).is(':checked')) return;

                    if($(this).val() === 'complex') {
                        if(window.console !== 'undefined') window.console.log($(this).val());
                        $(this).closest('.widget-content').find('fieldset.type-complex').show();
                    } else {
                        $(this).closest('.widget-content').find('fieldset.type-complex').hide();
                    }
                });

                // Add Items
                $(document).on('click', '.add-mini-item', function(e) {
                    if(window.console !== 'undefined') window.console.log('click');
                    e.preventDefault();
                    var $wrap = $(this).closest('.widget-content').find('.minis'),
                        $items = $wrap.find('.mini-item')
                        $clone = $items.first().clone();

                    $clone.find(':input').prop('checked', false).not(':checkbox, :radio').val('');
                    $clone.find('.display-media-img ul').empty();
                    $clone.find('.mini-title .title-text').text('Mini Item');

                    $clone.hide().appendTo($wrap[0]).slideDown(180);
                    $('.remove-mini').show();
                });
                // Change Title
                $(document).on('input focus', '.link-title-field', function() {
                    var title = $(this).val();
                    $(this).closest('.mini-item-content').siblings('.mini-title').find('.title-text').text(title);
                });

                // Expand / Collapse
                $(document).on('click', '.mini-title', function() {
                    $(this).find('.dashicons').toggleClass('dashicons-minus dashicons-plus');
                    $(this).siblings('.mini-item-content').slideToggle(300);
                });

                // Remove Item
                $(document).on('click', '.remove-mini', function(e) {
                    var $wrap = $(this).closest('.widget-content').find('.minis');
                    e.preventDefault();
                    $(this).closest('.mini-item').slideUp(180, function() {
                        $(this).remove();
                        if($wrap.find('.mini-item').length <= 1) {
                            $('.remove-mini').hide();
                        }
                    });
                });
            });
        </script>
        <?php
    }
    public static function admin_styles() {
        ?>
        <style>
            .mini-car-form {
                border: 1px solid #ccc;
                position: relative;
                padding: 10px;
                background: #f7f7f7;
                margin-bottom: 40px;
            }
            .mini-car-form .mini-item {
                background: #fff;
                padding: 10px 10px 0;
                border: 1px solid #ddd;
                border-left-width: 5px;
                margin: 10px 0;
                cursor: move;
            }
            .remove-mini {
                display: none;
                float: right;
                color: red;
                font-size: 12px;
                text-decoration: none;
            }
            .mini-car-form h4 {
                margin: 0;
                padding: 0 0 10px;
                cursor: pointer;
            }
            .mini-car-form .dashicons {
                vertical-align: text-top;
            }
            .mini-car-form .mini-item-content {
                cursor: default;
                padding: 0 0 10px;
                border-top: 1px solid #eee;
            }
            .mini-car-form fieldset p:nth-child(1) {
                margin-top: 0;
            }
            .mini-car-form .add-mini-item {
                border-color: #ccc !important;
                border-top: 0;
                box-shadow: 0 1px 0 rgba(0,0,0,.08);
                border-radius: 0 0 3px 3px;
                position: absolute;
                bottom: -28px;
                left: 50%;
                margin-left: -47px;
                width: 94px;
            }
            .mini-car-form .button .dashicons {
                margin-left: -7px;
            }
        </style>
        <?php

    }
    private function regroup_array($array) {
        $return = array();
        foreach($array as $field => $items):
            if($field == 'settings') $return['settings'] = $items;
            foreach($items as $k => $value):
                $return[$k][$field] = $value;
            endforeach;
        endforeach;
        return $return;
    }
    private function mini_field_id($field, $i) {
        return $this->get_field_id($field) .'-'.$i;
    }
    private function mini_field_name($field) {
        return $this->get_field_name($field).'[]';
    }
    public static function register() {
        register_widget(__CLASS__);
        if(is_null(self::$scripts_added)):
            self::$scripts_added = true;
            add_action('admin_footer-widgets.php', array(__CLASS__, 'admin_scripts'));
            add_action('admin_head', array(__CLASS__, 'admin_styles'));
        endif;
        // Register cta image size if not already set
        global $_wp_additional_image_sizes;
        if(!isset($_wp_additional_image_sizes) || !isset($_wp_additional_image_sizes['cta'])):
            add_image_size( 'cta', 380, 230, true );
        endif;
    }

}
add_action("widgets_init", array('Mini_Carousel_Widget', 'register'));