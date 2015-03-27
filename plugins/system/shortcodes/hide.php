<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2015 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
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