<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

if(!function_exists('button_sc')){
	function button_sc($atts, $content='') {
		extract(shortcode_atts(array(
					"type" => '',
					"size" => '',
					"link" => '',
					"target"=>''
				), $atts));
		return '<a href="' . $link . '" target=" '.$target.' "  class="btn btn-' . $type . ' btn-' . $size . '" >' .  do_shortcode($content)  . '</a>';
	}
	add_shortcode('button', 'button_sc');
}