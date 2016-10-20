<?php

$intro_title = get_post_meta(get_the_ID(), '_401_custom_page_custom_heading',true);

echo '<h2 class="intro-page-title">';
    if(is_page() || is_single()) {
        if($intro_title) {
            echo $intro_title;
        } else {
            the_title();
        }
    } elseif (is_search()) {
        echo '<span>Search results for </span>';
        $allsearch = new WP_Query("s=$s&showposts=-1");
        $key = wp_specialchars($s, 1);
        $count = $allsearch->post_count; _e(''); _e('<span class="search-terms">"');
        echo $key; _e('"</span>');wp_reset_query();
    } elseif (is_tax()) {
        global $wp_query;
        $term = $wp_query->get_queried_object();
        $title = $term->name;
        echo $title;
    } elseif (is_archive()) {
        single_cat_title();
        echo ' Archive';
    } elseif(is_home()) {
        $posts_page = get_option( 'page_for_posts' );
        $intro_title_posts = get_post_meta($posts_page, '_cmb_intro_title',true);
        if($intro_title_posts) {
            echo $intro_title_posts;
        } else {
            echo get_post( $posts_page )->post_title;
        }
    } elseif (is_404()) {
        echo '404 Error - Page Not Found';
    }
echo '</h2>';