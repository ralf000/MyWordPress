<?php
// Default Template for a Complex Call to action widget
// To overwrite, create a cta-complex.php (mc-complex.php for mini-carousel) file within a templates directory in your theme
// Child themes will inherit parent theme's templates

$link = '<a href="'. $url .'" '. $target .' title="%s" class="'.$type_class.' %s">%s</a>';
$out = array();


$out[] = sprintf( $link, esc_attr($title), 'cta-image', wp_get_attachment_image( $image, $img_size ) );

if(!empty($title))
    $out[] = $before_title . sprintf( $link, esc_attr($title), 'cta-title', $title ) . $after_title;

if(!empty($text))
    $out[] = '<div class="cta-text">'.wpautop($text).'</div>';

if(!empty($link_text))
    $out[] = sprintf( $link, esc_attr($link_text), 'cta-btn', $link_text );


echo join("\n", $out);
?>