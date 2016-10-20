<?php

/****************************************************************************/
/*                            ADD MEDIA BUTTON                              */
/*                                                                          */
/*   Adds a button for uploading or inserting images into widgets, etc.     */
/****************************************************************************/

function add_media_btn_401($args = array()) {
    global $_wp_additional_image_sizes;
    $defaults = array(
        'field_id'    => false,       // ID for the hidden field
        'field_name'  => false,       // Name for the hidden field
        'field_value' => '',          // Value for the hidden field (attachment ID)
        'btn_text'    => 'Add Image', // The text displayed on the button
        'file_type'   => 'image',     // File type (image, application/pdf, any)
        'img_size'    => 'cta',       // The image size to display the image once loaded
        'multiple'    => false
    );
    extract(array_merge($defaults, $args));
    $image = '';

    if($file_type === 'image' && !isset($_wp_additional_image_sizes[$img_size])) $img_size = 'full';


    // If we don't have a Name and ID for the hidden input let's quit now
    if(!$field_id || !$field_name) return;

    if(isset($field_value)):
        $fv_array = is_serialized($field_value) ? unserialize($field_value) : (array) $field_value;
        $image = '';
        foreach($fv_array as $k => $fv):
            $src = false;
            $out = '';
            if($file_type === 'image'):
                $src = wp_get_attachment_image_src( $fv, $img_size );
                $out = '<li><img src="'.$src[0].'" /></li>';
            else:
                $src = wp_get_attachment_link( $fv, null, true );
                $out = '<li>'.$src.'</li>';
            endif;
            if($src && $src !== 'Missing Attachment'):
                $image .= $out;
            else:
                unset($fv_array[$k]);
            endif;
        endforeach;
        $field_value = join(',', $fv_array);
    endif;

    // Enqueue the scripts for the uploader
    wp_enqueue_media();
    $out = array();
    $out[] = '<div class="hide-if-no-js action-btn-wrapper wp-media-buttons '.$file_type.'-type" style="margin-bottom:10px">';
    $out[] = '<div class="'.$field_id.' display-media-img"><ul>'.$image.'</ul></div>';
    $out[] = '<input type="hidden" class="media-img-ids" id="'.$field_id.'" name="'.$field_name.'" value="'.$field_value.'" />';
    $out[] = '<p class="up-btn"><a href="#" class="button upload-image-btn" data-field-id="'.$field_id.'" data-multiple="'.($multiple ? 'true' : 'false').'" data-size="'.$img_size.'" title="'.$btn_text.'" data-type="'.$file_type.'">';
    $out[] = '<span class="mceIcon mce_image" style="vertical-align: text-top;"></span> '.$btn_text;
    $out[] = '</a>';
    if($img_size !== 'full' && $file_type === 'image') $out[] = ' <span class="description upload-size">('.$_wp_additional_image_sizes[$img_size]['width'].'x'.$_wp_additional_image_sizes[$img_size]['height'].')</span>';
    $out[] = '</p></div>';
    echo join("\n", $out);
}

function custom_image_sizes_for_js( $response, $attachment, $meta ) {

    global $_wp_additional_image_sizes;

    foreach ( $_wp_additional_image_sizes as $size => $value ):

        if ( isset($meta['sizes'][$size]) ) {
            $attachment_url = wp_get_attachment_url( $attachment->ID );
            $base_url = str_replace( wp_basename( $attachment_url ), '', $attachment_url );
            $size_meta = $meta['sizes'][ $size ];

            $response['sizes'][ $size ] = array(
                'height'        => $size_meta['height'],
                'width'         => $size_meta['width'],
                'url'           => $base_url . $size_meta['file'],
                'orientation'   => $size_meta['height'] > $size_meta['width'] ? 'portrait' : 'landscape',
            );
        }
    endforeach;

    return $response;
}
add_filter ( 'wp_prepare_attachment_for_js', 'custom_image_sizes_for_js', 10, 3  );

function add_media_btn_scripts_401(){
    ?>
    <script type="text/javascript">
    jQuery(function($) {
        // Uploading files
        var file_frame;

        $('.upload-image-btn').live('click', function(e){
            e.preventDefault();
            var $field = $(this).closest('.action-btn-wrapper').find('.media-img-ids');
            var $imgs = $(this).closest('.action-btn-wrapper').find('.display-media-img');
            var multi = $(this).data('multiple');
            var size = $(this).data('size');
            var ftype = $(this).data('type');
            var btntext = $(this).attr('title');
            // If the media frame already exists, reopen it.
            if ( file_frame ) {
                file_frame.open();
            } else {
                var args = {
                    button: {
                        text: btntext
                    },
                    multiple: multi
                }
                if(['image', 'pdf'].indexOf(ftype) > -1) {
                    args.library = {type: ftype};
                }
                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media(args);
                file_frame.open();
            }
            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {

                attachments = file_frame.state().get('selection').toJSON();
                if(attachments.length <= 0) return;
                if(window.console !== 'undefined') window.console.log(attachments);
                var src;
                var id_array = $field.val() === '' ? [] : $field.val().split(',');
                if(!multi) {
                    id_array = [];
                    $imgs.find('> ul').empty();
                }
                for(var i = 0, n = attachments.length; i<n; i++ ) {
                    if(ftype === 'image') {
                        src = typeof attachments[i]['sizes'][size] === 'undefined' ? attachments[i].url : attachments[i]['sizes'][size].url;
                        $imgs.find('> ul').append('<li><img src="'+ src +'" /></li>');
                    } else {
                        $imgs.find('> ul').append('<li><a href="#" class="file-btn">'+ attachments[i].title +'</a></li>');
                    }
                    id_array.push(attachments[i].id);
                }
                $imgs.trigger("close_buttons");
                $field.val( id_array.join(',') ).trigger('change');

                file_frame.off( 'select');
            });

            // Finally, open the modal
            file_frame.open();
        });
        var cb = $("<button>", {
            'class': 'close-btn',
            type: 'button',
            html: '&times;',
            click: function(e) {
                e.preventDefault();
                var i = $(this).closest('ul').find('li').index($(this).closest('li'));
                var container = $(this).closest('.wp-media-buttons');

                container.find('.display-media-img').find('li').eq(i).remove();
                var str = container.find('.media-img-ids').val().split(',');
                if(window.console !== 'undefined') window.console.log(str);
                str[i] = '';
                str = str.filter(Number);
                container.find('.media-img-ids').val( str.join(',') );
            }
        });
        $('.display-media-img').on('close_buttons', function() {
            $(this).find('close-btn').remove();
            $(this).find('li').append(cb.clone(true));
        }).trigger('close_buttons');
        $('.display-media-img img, .display-media-img a').live('click', function(e) {
            e.preventDefault();
            $(this).closest('.display-media-img').siblings('p').find('.upload-image-btn').click();
        });
    });
    </script>
    <?php
}
add_action('in_admin_footer','add_media_btn_scripts_401');


function add_media_btn_styles_401() {
    ?>
    <style>
    .ui-widget-overlay {
        background: #000;
    }
    .action-btn-wrapper {
        clear:both;
        background: #f0f0f0;
        padding: 0 10px;
        border: 2px dashed #aaa;
        margin-bottom: 10px;
    }
    .display-media-img li {
        position: relative;
        max-width: 100%;
    }
    .display-media-img .close-btn {
        opacity: .5;
        color: red;
        cursor: pointer;
    }
    .image-type .display-media-img .close-btn {
        position: absolute;
        left: 10px;
        top: 10px;
    }
    .image-type .display-media-img img {
        padding: 0;
        outline: 0;
    }
    .display-media-img .close-btn:hover {
        opacity: 1;
    }
    .display-media-img ul {
        max-width: 500px;
    }
    .display-media-img img {
        max-width: 100%;
        height: auto !important;
        outline: 1px solid #eee;
    }
    .action-btn-wrapper.wp-media-buttons .up-btn {
        margin: 1em 0;
    }

    </style>
    <?php
}
add_action('in_admin_header', 'add_media_btn_styles_401' );