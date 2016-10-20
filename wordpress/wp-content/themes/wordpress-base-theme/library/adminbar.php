<?php
/**
 * Admin bar styling
 */
function admin_bar_css() {
    ?>
    <style type="text/css" media="screen">
        #wpadminbar.mini {
            overflow: hidden;
        }
        #wpadminbar #wp-admin-bar-my-sites-list {
            overflow-y: scroll;
            max-height: 500px;
            overflow-x: visible;
            padding-right: 160px;
            padding-bottom: 80px;
        }
        #wpadminbar.mini:hover {
            max-height: 60px;
        }
        #wpadminbar.open {
            overflow: visible;
        }
        #wpadminbar #wp-admin-bar-template-file strong,
        #wpadminbar #wp-admin-bar-hook-suffix strong {
            color: #fff;
        }
        #wpadminbar #wp-admin-bar-template-file em,
        #wpadminbar #wp-admin-bar-hook-suffix em {
            font-style: italic;
            font-size: .9em;
            margin-left: 5px;
        }
        #wpadminbar #wp-admin-bar-template-file .ab-icon {
            line-height: 24px !important;
        }
    </style>  
    <?php 
}
add_action('admin_head', 'admin_bar_css');

/**
 * Minimize Admin Bar
 */
function tiny_admin_bar(){
    admin_bar_css();
    ?>
    <script>
        jQuery(function($) {
            var $ab = $('#wpadminbar');
            $ab.addClass('mini').css({maxHeight: 2});
            $ab.on('mouseenter', function() {
                $ab.stop().animate({maxHeight: 60}, 400, function() {
                    $ab.addClass('open');
                });
            }).on('mouseleave', function() {
                $ab.removeClass('open');
                $ab.stop().animate({maxHeight: 2}, 400);
            });
        });
    </script>
    <?php
}
add_theme_support( 'admin-bar', array( 'callback' => 'tiny_admin_bar') );

/**
 * Sort My Sites dropdown
 */

function admin_bar_menu_reorder_sites(){
    global $wp_admin_bar;
    
    $blog_names = array();
    $sites = $wp_admin_bar->user->blogs;
    foreach($sites as $site_id=>$site) {
        $blog_names[$site_id] = strtoupper($site->blogname);
    }
    
    // Remove main blog from list...we want that to show at the top
    unset($blog_names[1]);
    
    // Order by name
    asort($blog_names);
    
    // Create new array
    $wp_admin_bar->user->blogs = array();
    
    // Add main blog back in to list
    $wp_admin_bar->user->blogs{1} = $sites[1];
    

    // Add others back in alphabetically
    foreach($blog_names as $site_id=>$name) {
        $wp_admin_bar->user->blogs{$site_id} = $sites[$site_id];
    }
}
add_action('admin_bar_menu', 'admin_bar_menu_reorder_sites');


/**
 * Displays the current template file within the admin bar
 */
function template_file_admin_bar($wp_admin_bar) {
    if(is_admin()):
        global $hook_suffix;
         $wp_admin_bar->add_node(array(
            'id'    => 'hook-suffix',
            'title' => '<strong>Hook Suffix:</strong> <em>'.$hook_suffix.'</em><div class="ab-icon dashicons-editor-code" style="line-height:24px;;"></div>',
            'meta'  => array(
                'class' => 'ab-item',
            )
        ));   
    else:
        global $template;
        $wp_admin_bar->add_node(array(
            'id'    => 'template-file',
            'title' => '<strong>Template File:</strong> <em>'.basename($template).'</em><div class="ab-icon dashicons-media-code"></div>',
            'meta'  => array(
                'class' => 'ab-item',
            )
        ));
    endif;
    
    global $wp;
    // $current_url = home_url( add_query_arg( NULL, NULL ) );
    // if(strpos(home_url(), 'dlv.dev')):
    //     $href = str_replace('dlv.dev', 'discoverlehighvalley.com', $current_url);
    //     $wp_admin_bar->add_node(array(
    //         'id' => 'view-on-production',
    //         'title' => 'View Live',
    //         'href' => $href,
    //         'meta' => array('class'=>'dashicons dashicons-admin-site', 'target' => '_blank')
    //     ));
    // elseif(get_current_user_id() === 1):
    //     $href = str_replace('discoverlehighvalley.com', 'dlv.dev', $current_url);
    //     $wp_admin_bar->add_node(array(
    //         'id' => 'view-on-dev',
    //         'title' => 'View Dev',
    //         'href' => $href,
    //         'meta' => array('class'=>'dashicons dashicons-admin-site', 'target' => '_blank')
    //     ));
    // endif;
}
add_action( 'admin_bar_menu', 'template_file_admin_bar', 999 );