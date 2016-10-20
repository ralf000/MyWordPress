<?php
// Default Template for a Simple Call to action
// To overwrite, create a cta-simple.php (mc-simple.php for mini-carousel) file within a templates directory in your theme
// Child themes will inherit parent theme's templates

if($url) {

echo sprintf('<a href="%s" title="%s" class="%s %s" %s>%s</a>',
        esc_url($url),
        $link_text,
        $type_class,
        $css_class,
        $target,
        wp_get_attachment_image( $image, $img_size )
    );
} else {
    echo wp_get_attachment_image($image, $img_size);
}
?>