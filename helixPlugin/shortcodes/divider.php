<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2014 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

if(!function_exists('divider_sc')){
	function divider_sc($atts, $content='') {
		extract(shortcode_atts(array(
					'margin_top'=>'18px',
					'margin_bottom'=>'18px',
					'border'=>0
				), $atts));
		return '<div class="sp-divider clearfix" style="margin-top:' . $margin_top . '; margin-bottom:' . $margin_bottom . '; border-top:' . $border . ';"></div>';
	}
	add_shortcode('divider', 'divider_sc');
}
