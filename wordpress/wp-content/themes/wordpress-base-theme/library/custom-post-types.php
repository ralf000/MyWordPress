<?php
/* Bones Custom Post Type Example
This page walks you through creating 
a custom post type and taxonomies. You
can edit this one or copy the following code 
to create another one. 

I put this in a separate file so as to 
keep it organized. I find it easier to edit
and change things if they are concentrated
in their own file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

// == Custom Post Types =================================================================
function custom_post_types_401() {

    $menu_position_start = 6;
    
    register_post_type( 'carousel',
        array(
            'labels'            => array(
                'menu_name'     => __( 'Carousel' ),
                'name'          => __( 'Carousel' ),
                'singular_name' => __( 'Carousel' )
            ),
            'hierarchical'  => false,
            'public'        => true,
            'exclude_from_search' => true,
            'has_archive'   => false,
            'menu_icon'     => 'dashicons-slides',
            'menu_position' => ++$menu_position_start,
            'supports'      => array( 'title', 'editor', 'thumbnail', 'revisions' )
        )
    );

} 
add_action( 'init', 'custom_post_types_401');



// == Custom Taxonomies =================================================================

// testimonials type tax
function custom_taxonomies_401() {

	// register_taxonomy( 'department',
	// 	array( 'team' ),
	// 	array(
	// 		'hierarchical' 	=> true,
	// 		'labels' 		=> array(
	// 			'name' 			=> 'Departments',
	// 			'singular_name' => 'Department',
	// 			'add_new' 		=> 'Add New Department'
	// 		),
	// 		'public' 		=> false,
	// 		'show_ui' 		=> true,
	// 		'query_var' 	=> true,
	// 		'rewrite' 		=> array( 'slug' => 'department' ),
	// 	) 
	// );

}
add_action( 'init', 'custom_taxonomies_401', 0 );