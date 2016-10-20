<?php
class Tertiary_Nav_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'tertiary-nav-widget',  // ID
            'Tertiary Nav',         // Name
            array( 
                'description' => 'Adds a tertiary navigation to the sidebar on pages with sub-pages.',
                'classname' => 'tertiary-nav'
             )
        );
    }

    public function widget( $args, $instance ) {

        if ( is_404() ) return;

        extract( $args );
        extract( $instance );
        wp_reset_query();
        global $post;
        $ancestors = get_ancestors( $post->ID, 'page' );
        $parent = empty($ancestors) ? $post->ID : array_pop($ancestors);
        
        $class = ($parent == $post->ID) ? ' current_page_item' : '';

        $children = wp_list_pages('depth=1&child_of='.$parent.'&sort_column=menu_order&title_li=&echo=0');
        if(!$children) return;
        
        echo $before_widget;
        echo '<div class="tertiary-nav-wrap">';
        echo '<h3>In This Section</h3>';
        echo '<ul class="tertiary-nav">'."\n";
        echo '<li class="parent'.$class.'"><a href="'.get_permalink($parent).'">'.get_the_title($parent).'</a></li>';
        
        echo $children;
        
        echo '</ul></div>'."\n";
        echo $after_widget;
    }

    public function form( $instance ) {
        ?>
        <p>This widget has no options. <br/>Have a nice day!</p>
        <?php 
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance = $old_instance;
        return $instance;
    }
    
    public function register() {
        register_widget(__CLASS__);
    }

}
add_action("widgets_init", array('Tertiary_Nav_Widget', 'register'));