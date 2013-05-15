<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[alert]
if(!function_exists('alert_sc')){
	function alert_sc($atts, $content=''){
		 extract(shortcode_atts(array(
        "type" => '',
		"style" =>'',
        "close" => true
     ), $atts));
     return '<div class="alert alert-' . $type . '" . style=' . $style . '>' . do_shortcode( $content ) . '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';
	}
	add_shortcode('alert','alert_sc');
}