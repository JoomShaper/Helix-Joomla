<?php
//Hide component area
if(!function_exists('hide_sc')) {
    function hide_sc($atts, $content="") {
        ob_start();
        ?>
            <script type="text/javascript">
                spnoConflict(function($){
                    $('#sp-main-body-wrapper').remove();
                });
            </script>
        <?php
        return ob_get_clean();
    }
    add_shortcode( 'sp_hide', 'hide_sc' );
}