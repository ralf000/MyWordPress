<?php

if(!function_exists('get_the_gallery401_revised')) :

    function get_the_gallery401_revised($args){
        global $_wp_additional_image_sizes;

        if ( isset( $args['orderby'] ) ) {
            $args['orderby'] = sanitize_sql_orderby( $args['orderby'] );
            if ( !$args['orderby'] )
                unset( $args['orderby'] );
        }

                extract(shortcode_atts(array(
            'ids'               => false,
            'type'              => 'slider',
            'link'              => 'none',
            'bullets'           => false,
            'arrows'            => true,
            'enlarge_text'      => false,
            'thumb_size'        => 'gallery-thumb',
            'full_size'         => 'gallery',
            'order_by'          => 'post__in',
            'order'             => 'ASC',
        ), $args, 'gallery'));

        if($ids === false) return;

        $ids = explode(',', $ids);

        $output = apply_filters( 'post_gallery', '', $attr );
        if ( $output != '' )
            return $output;

        $columns = 4;
        $maxw = '718px';
        $controls_w = ( 718 * 0.9 ) - ($columns*10);
        $mainclass = ' page-gallery';

        $thumb_w = 165;
        $thumb_h = 145;
        $thumb_ratio = $thumb_h / $thumb_w;



        $controls_width = (1/$columns)*100*count($ids);
        $image_width = 1/count($ids)*100;


        // start here //

        $gallery_before  = '<div class="hybrid-gallery'.$mainclass.'" data-columns="'.$columns.'">'."\n";
        $gallery_before .= '<div class="gallery-images">'."\n";


        $gallery_after   = '</div>'."\n";

        $gallery_after .= '<div class="gallery-controls-container">'."\n";

        $gallery_after .= '<ul class="gallery-controls" style="width:'.$controls_width.'%;">'."\n";

        $gallery_output = '<ul>';

        $current = true;

        foreach($ids as $id):
            $image_output = wp_get_attachment_image( $id, 'gallery', false );

            $image_meta  = wp_get_attachment_metadata( $id );

            $caption = '';
            $img = get_post($id);
            $desc = $img->post_excerpt ? $img->post_excerpt : '';
            if( trim($desc) ) :
                $caption = '<div class="caption">';
                $caption .= '<p>'.$desc.'</p>';
                $caption .= '</div>';
            endif;

            $gallery_output .= '<li'.($current ? ' class="current"' : '' ).'>'.$image_output.$caption.'</li>';

            $image_output = wp_get_attachment_image( $id, 'gallery-thumb', false );

            $gallery_after .= '<li'.($current ? ' class="on"' : '').' style="width:'.$image_width.'%">'.$image_output.'</li>';

            $current = false;
        endforeach;

        $gallery_after .= '</ul>'."\n";

        $gallery_after .= '</div>'."\n";

        $gallery_after .= '<div class="nav">
        <div class="next_prev prev"><span class="ico-wrap"></span></div>
        <div class="next_prev next"><span class="ico-wrap"></span></div>
        </div>';

        $gallery_after  .= '</div>'."\n";

        $gallery_output .= '</ul>';

        $output = $gallery_before . $gallery_output . $gallery_after;

        return $output;
    }

    function setup_gallery_shortcodes(){
        remove_shortcode('gallery');
        add_shortcode('gallery','get_the_gallery401_revised');
    }
    add_action('init','setup_gallery_shortcodes');

endif;