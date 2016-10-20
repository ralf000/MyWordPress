<?php
// Default Template for a Button Call to action
// To overwrite, create a cta-button.php file within a templates directory in your theme
// Child themes will inherit parent theme's templates


echo sprintf('<a href="%s" title="%s" class="%s %s">%s</a>',
        esc_url($url),
        $link_text,
        $type_class,
        $css_class,
        $link_text
    );
?>