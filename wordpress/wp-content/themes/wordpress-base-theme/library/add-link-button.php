<?php
/**
 * Adds an Add Link popup field similar to the one used in the WordPress TinyMCE editor
 *
 * @version  1.3.1
 *
 * @todo
 * 1. Fix get_id and get_url to work with query strings after URL
 * 2. Add ability to edit links within the popup
 *
 */
class AddLinkBtn401 {
    private static $scripts_added;
    private static $instance_id = 0;
    private $args;

    function __construct() {
        if(is_null(self::$scripts_added)):
            self::$scripts_added = true;
            add_action('admin_footer', array($this, 'footer_scripts'));
        endif;
    }

    public function footer_scripts() {
        wp_enqueue_script('wplink');
        wp_enqueue_script('wpdialogs-popup'); //also might need this
        wp_enqueue_style('wp-jquery-ui-dialog');
        wp_enqueue_style('editor', includes_url('css/editor.css' ));

        if(!class_exists('_WP_Editors'))
            require_once(ABSPATH . WPINC . '/class-wp-editor.php');
        _WP_Editors::wp_link_dialog();
    }

    public function link_fields($args = array()) {
        ++self::$instance_id;

        $defaults = array(
            'btn_text'           => 'Add/Edit Link',
            'url_field_id'       => false,   // ID for the url field
            'url_field_name'     => false,   // Name for the url field
            'url_field_value'    => '',      // Value for the url field
            'url_field_label'    => 'URL:',
            'target_field_id'    => false,   // ID for the target field (set to false for no target field)
            'target_field_name'  => false,   // Name for the target field
            'target_field_value' => '',      // Value for the target field
            'target_field_label' => 'Open link in a new window',
            'title_field_id'     => false,   // ID for the title field (set to false for no target field)
            'title_field_name'   => false,   // Name for the title field
            'title_field_value'  => '',      // Value for the title field
            'title_field_label'  => 'Title'
        );
        $this->args = wp_parse_args( $args, $defaults );
        if(!$this->args['url_field_id']) return;
        ?>
        <div class="add-link-box action-btn-wrapper" id="link-box-<?php echo self::$instance_id; ?>">
            <p>
                <label for="<?php echo $this->args['url_field_id']; ?>"><?php echo $this->args['url_field_label']; ?></label>
                <input class="widefat link-url-field" type="text" name="<?php echo $this->args['url_field_name']; ?>" id="<?php echo $this->args['url_field_id']; ?>" value="<?php echo $this->args['url_field_value']; ?>">
            </p>
            <?php if($this->args['title_field_id']): ?>
                <p>
                    <label for="<?php echo $this->args['title_field_id']; ?>"><?php echo $this->args['title_field_label']; ?></label>
                    <input class="widefat link-title-field" type="text" name="<?php echo $this->args['title_field_name']; ?>" id="<?php echo $this->args['title_field_id']; ?>" value="<?php echo $this->args['title_field_value']; ?>">
                </p>
            <?php endif; ?>
            <?php if($this->args['target_field_id']): ?>
                <p>
                    <input type="checkbox" class="link-target-field" name="<?php echo $this->args['target_field_name']; ?>" id="<?php echo $this->args['target_field_id']; ?>" value="on" <?php checked($this->args['target_field_value'], 'on'); ?>>
                    <label for="<?php echo $this->args['target_field_id']; ?>"><?php echo $this->args['target_field_label']; ?></label>
                </p>
            <?php endif; ?>
            <p><a href="#" class="link-btn button"><span class="mceIcon mce_link" style="vertical-align: text-top;"></span> <?php echo $this->args['btn_text']; ?></a></p>
        </div>
        <script type="text/javascript">


        jQuery(function($) {

            $(document).on('click', '.link-btn', function(e) {
                e.preventDefault();
                var $wrap   = $(this).closest('.add-link-box');
                var $target = $wrap.find('.link-target-field');
                var $title  = $wrap.find('.link-title-field');
                var $url    = $wrap.find('.link-url-field');
                wpActiveEditor = $url.attr('id');
                wpLink.open();
                wpLink.htmlUpdate = function() {
                    var atts = wpLink.getAttrs();
                    $url.val(atts.href);
                    $title.val(atts.title);
                    $target.prop('checked', atts.target == '_blank');
                    wpLink.close();
                    $title.focus();
                }
            });
        });
        </script>
        <?php
    }

    static public function get_id($url) {
        if(empty($url)) return false;
        $url = esc_url_raw($url);
        // Check for query string or hash and save it for later
        $query_hash = '';
        if(preg_match('/^([^#?]+)(.*)/', $url, $matches)):
            $url = $matches[1];
            $query_hash = $matches[2];
        endif;
        // Convert URL to post ID if it is found in WordPress
        if($id = url_to_postid( $url )):
            $url = $id;
        endif;

        return $url . $query_hash;
    }

    static public function get_url($id) {
        if(empty($id)) return;
        $url = $id;
        $query_hash = '';
        if(preg_match('/^([^#?]+)(.*)/', $url, $matches)):
            $id = $matches[1];
            $query_hash = $matches[2];
        endif;
        // Get the permalink for
        if(is_numeric($id)):
            $url = get_permalink($id);
            // If no post is found return the original value
            if(!$url) return $id . $query_hash;
        endif;
        return esc_url($url.$query_hash);
    }

    static public function url_to_id($url) {
        _deprecated_function( __FUNCTION__, '1.3', 'get_id' );
        self::get_id($url);
    }
    static public function id_to_url($id) {
        _deprecated_function( __FUNCTION__, '1.3', 'get_url' );
        self::get_url($id);
    }
}