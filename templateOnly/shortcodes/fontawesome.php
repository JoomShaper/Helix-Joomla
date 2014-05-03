<?php
if(!function_exists('fontawesome_sc')){

  function fontawesome_sc( $atts, $content = null ){
    include(dirname(__FILE__) . '/lib/fontawesome-icons.php');
    
    $output = '<p> </p><h3>Total ' . count($fontawesome_icons) . ' Awesome Icons</h3><hr>';

    $output .= '<ul style="list-style: none; padding: 0; margin: 0">';
    foreach ($fontawesome_icons as $key => $value) {
      $output .='<li style="display: block; width: 50%; padding: 5px 0; float: left;"><p><i style="display: inline-block; margin-right: 10px;" class="' . $value . '"></i> ' . $value . ' <code>[icon name="' . $value . '"]</code></p></li>';
    }
    $output .='</ul><div class="clearfix"></div>';
    return $output;
  }
  add_shortcode( 'fontawesome_icons', 'fontawesome_sc' );
}