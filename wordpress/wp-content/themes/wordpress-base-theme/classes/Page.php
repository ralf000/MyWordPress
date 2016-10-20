<?php

class Page{

    public $p = "_401_";
    public $post_type = "custom_page";

    function __construct() {
        add_filter( 'cmb2_show_on', array($this,'be_metabox_show_on_template'), 10, 2 );
        add_action( 'cmb2_init', array($this,'page_register_fields') );
    }

    function page_register_fields(){
        // $meta = new_cmb2_box(array(
        //     'id'            => $this->p.$this->post_type.'_meta_extra_blocks',
        //     'title'         => 'Meta Content',
        //     'object_types'  => array( 'page' ),
        //     'show_on'       => array( 'key' => 'template', 'value' => array('')),
        //     'context'       => 'normal',
        //     'priority'      => 'high',
        //     'show_names'    => true,
        //     'closed'       => true
        // ));

        // $meta->add_field(array(
        //     'name'          => 'Custom Heading',
        //     'id'            => $this->p.$this->post_type.'_custom_heading',
        //     'desc'          => 'Custom Heading (optional).',
        //     'type'          => 'text'
        // ));
        // $meta->add_field(array(
        //     'name'          => 'Sub Heading',
        //     'id'            => $this->p.$this->post_type.'_sub_heading',
        //     'desc'          => 'Sub Heading (optional).',
        //     'type'          => 'text'
        // ));
        // $meta->add_field(array(
        //     'name'          => 'Book Now',
        //     'id'            => $this->p.$this->post_type.'_book_now_link_url',
        //     'desc'          => 'Book Now Link URL (optional).',
        //     'type'          => 'text'
        // ));
    }

    /**
     * Metabox for Page Template
     * @author Kenneth White
     * @link https://github.com/WebDevStudios/CMB2/wiki/Adding-your-own-show_on-filters
     *
     * @param bool $display
     * @param array $meta_box
     * @return bool display metabox
     */
    function be_metabox_show_on_template( $display, $meta_box ) {
        if ( ! isset( $meta_box['show_on']['key'], $meta_box['show_on']['value'] ) ) {
            return $display;
        }

        if ( 'template' !== $meta_box['show_on']['key'] ) {
            return $display;
        }

        $post_id = 0;

        // If we're showing it based on ID, get the current ID
        if ( isset( $_GET['post'] ) ) {
            $post_id = $_GET['post'];
        } elseif ( isset( $_POST['post_ID'] ) ) {
            $post_id = $_POST['post_ID'];
        }

        if ( ! $post_id ) {
            return false;
        }

        $template_name = get_page_template_slug( $post_id );
        $template_name = ! empty( $template_name ) ? substr( $template_name, 0, -4 ) : '';

        // See if there's a match
        return in_array( $template_name, (array) $meta_box['show_on']['value'] );
    }

}