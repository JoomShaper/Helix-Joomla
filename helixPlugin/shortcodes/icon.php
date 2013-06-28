<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[Icon]
if(!function_exists('icon_sc')) {

	function icon_sc( $atts, $content="" ) {
	
		extract(shortcode_atts(array(
			   'name' => 'home',
			   'size' => '',
			   'color' => '',
			   'class' =>""
		 ), $atts));
		 
		 $options = 'style="';
		 $options .= ($size) ? 'font-size:'. (int) $size .'px;' : '';
		 $options .= ($color) ? 'color:'. $color . ';': '';
		 $options .='"';
		 
		return '<i ' . $options . ' class="icon-' . str_replace( 'icon-', '', $name ) . ' ' . $class . '"></i>' . $content;
	 
	}
		
	add_shortcode( 'icon', 'icon_sc' );
}