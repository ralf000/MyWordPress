<?php

class Taxonomy{

    function __construct() {
        add_action( 'cmb2_init', array($this,'taxonomy_register_fields') );
    }

    function taxonomy_register_fields(){
        $cmb_cat_thumb = new_cmb2_box( array(
          'id'               => 'cat_edit',
          'title'            => __( 'Category Thumbnail', 'cmb2' ),
          'object_types'     => array( 'term' ),
          'taxonomies'       => array( 'service', 'category' ),
          'new_term_section' => true,
        ) );

        $cmb_cat_thumb->add_field( array(
          'name' => __( 'Category Image', 'cmb2' ),
          'id'   => 'cat_thumb',
          'type' => 'file',
        ) );
    }

}