<?php

/**
 * "Icons" field 
 * Examples of use:
 * 
 $cmb_fields->add_field( array(
    'name'      => 'Icon',
    'id'        => $prefix . 'icon',
    'type'      => 'icons',
 ));

 In CSS:
 [start comment] Icons [end comment]
    @font-face {
      font-family: 'ch';
      src: url('fonts/ch.eot?93597903');
      src: url('fonts/ch.eot?93597903#iefix') format('embedded-opentype'),
           url('fonts/ch.woff?93597903') format('woff'),
           url('fonts/ch.ttf?93597903') format('truetype'),
           url('fonts/ch.svg?93597903#ch') format('svg');
      font-weight: normal;
      font-style: normal;
    }

     [class^="icon-"]:before, [class*=" icon-"]:before {
      font-family: "ch";
      font-style: normal;
      font-weight: normal;
      speak: none;

      display: inline-block;
      text-decoration: inherit;
      width: 1em;
      margin-right: .2em;
      text-align: center;
      font-variant: normal;
      text-transform: none;
      line-height: 1em;
      margin-left: .2em;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
     
    .icon-ankle:before { content: '\e800'; }
    .icon-facebook:before { content: '\46'; }
  [start comment] End Icons [end comment]
 */
function cmb2_render_callback_for_icons( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
    $icons = array();
    $start_comment = "/* Icons */";
    $end_comment = "/* End Icons */";
    $css = file_get_contents( get_template_directory().'/style.css' );
    $start_point = strpos($css, $start_comment);
    $end_point = strpos($css, $end_comment);
    if( $start_point && $end_point ) {
        $start_point = intval($start_point)+(strlen($start_comment));
        $css_substr = substr($css, $start_point, intval($end_point)-intval($start_point));
        $icon_matched = preg_match_all('/\.icon-([\w-_]*)/', $css_substr, $icon_matches);
        if( $icon_matched && count($icon_matches)==2 ) {
            $icons = array_combine( $icon_matches[0], $icon_matches[1]);
            if( !empty($icons) ) {
                $icons = array_filter($icons);
                asort($icons);
                $options = array();
                $options[] = $field_type_object->select_option(array('label'=>'--- None ---','value'=>''));
                foreach( $icons as $value=>$label ) {
                    $label = str_replace(array('-','_'), ' ', $label);
                    $value = str_replace('.', '', $value);
                    $options[] = $field_type_object->select_option(array(
                        'label'     => ucwords($label),
                        'value'     => $value,
                        'checked'   => $value==$escaped_value ? true : false
                    ));
                }
                ?>    
                <span class="icon-preview" id="icon-preview-<?php echo $field->object_id; ?>"><i class=""></i></span>
                <?php
                echo $field_type_object->select(array(
                    'options' => implode("\r", $options)
                ));                            
                ?>
                <style>
                .icon-preview {
                    display:inline-block;
                    vertical-align: middle;
                    font-size:3em;
                }
                <?php 
                $css_substr = preg_replace('/(url\(\s*)([\'\"])/','$1$2'.get_template_directory_uri().'/',$css_substr); 
                $css_substr = preg_replace('/icon-/',"__$0",$css_substr); 
                echo $css_substr;
                ?>
                </style>
                <script type="text/javascript">
                ;(function($) {
                    var $iconDropdown<?php echo $field->object_id; ?> = $("#<?php echo $field->args['id']; ?>");
                    $iconDropdown<?php echo $field->object_id; ?>.on('change',function(e){
                        var $iconPreview = $('#icon-preview-<?php echo $field->object_id; ?>');
                        $val = $iconDropdown<?php echo $field->object_id; ?>.val();
                        if( $.trim($val) ) {
                           $iconPreview.show();
                           $iconPreview.find('i').removeAttr('class').addClass('__'+$val);
                           $iconPreview.find('strong').html( $iconDropdown<?php echo $field->object_id; ?>.find('option[value="'+$val+'"]').text() );
                        } else {
                           $iconPreview.hide();
                        }
                    }).trigger('change');
                })(jQuery);               
                </script>
                <?php
                // echo '<pre>'.print_r( $field, true ).'</pre>';
            } else {
                echo '<strong>No icons found. Make sure you have your stylesheet set up like so:</strong><br/>';
                echo '<code>.icon-name:before { content: \'\2b\'; }</code>';
            }
        } else {
            echo '<strong>No icons found. Make sure you have your stylesheet set up like so:</strong><br/>';
            echo '<code>.icon-name:before { content: \'\2b\'; }</code>';            
        }
    } else {
        echo '<strong>No icons section found. Make sure you have your icons wrapped in the following comments in the stylesheet:</strong><br/>';
        echo '<code>'.$start_comment.'</code><br/>';
        echo '<code>@font-face...</code><br/>';
        echo '<code>.icon-name:before { content: \'\2b\'; }...</code><br/>';
        echo '<code>'.$end_comment.'</code>';
    }
}
add_action( 'cmb2_render_icons', 'cmb2_render_callback_for_icons', 10, 5 );