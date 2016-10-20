<?php

class HomePage{

    public $p = "_401_";
    public $post_type = "home_page";

    function __construct() {
        add_action( 'cmb2_init', array($this,'homepage_register_fields') );
    }

    function homepage_register_fields(){
        // $meta = new_cmb2_box(array(
        //     'id'            => $this->p.$this->post_type.'_meta_callouts_box',
        //     'title'         => 'Callouts',
        //     'object_types'  => array( 'page' ),
        //     'show_on'       => array( 'key' => 'id', 'value' => get_option('page_on_front') ),
        //     'context'       => 'normal',
        //     'priority'      => 'high',
        //     'show_names'    => true,
        //     'closed'       => true
        // ));
        // $callouts = $meta->add_field( array(
        //     'id'          => $this->p.$this->post_type.'_meta_callouts',
        //     'type'        => 'group',
        //     'description' => __( 'Callout Images and Content', 'cmb2' ),
        //     'options'     => array(
        //         'group_title'   => __( 'Callout {#}', 'cmb2' ), // {#} gets replaced by row number
        //         'add_button'    => __( 'Add Another Callout', 'cmb2' ),
        //         'remove_button' => __( 'Remove Callout', 'cmb2' ),
        //         'sortable'      => true
        //     )
        // ));
        // $meta->add_group_field($callouts, array(
        //     'name'          => 'Image',
        //     'id'            => $this->p.$this->post_type.'_callout_image',
        //     'desc'          => 'Callout Image',
        //     'type'          => 'file'
        // ));
        // $meta->add_group_field($callouts, array(
        //     'name'          => 'Headline',
        //     'id'            => $this->p.$this->post_type.'_callout_headline',
        //     'desc'          => 'Callout Headline',
        //     'type'          => 'text'
        // ));
        // $meta->add_group_field($callouts, array(
        //     'name'          => 'Content',
        //     'id'            => $this->p.$this->post_type.'_callout_content',
        //     'desc'          => 'Callout Content',
        //     'type'          => 'wysiwyg'
        // ));
        // $meta->add_group_field($callouts, array(
        //     'name'          => 'Link URL',
        //     'id'            => $this->p.$this->post_type.'_callout_link',
        //     'desc'          => 'Callout Link URL',
        //     'type'          => 'text'
        // ));
    }

}