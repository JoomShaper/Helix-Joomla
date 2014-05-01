<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[Row]
if(!function_exists('row_sc')) {
	$columnArray = array();

	function row_sc( $atts, $content="" ){
		global $columnArray;
		$id='';
		
		$params = shortcode_atts(array(
			  'id' => '',
			  'class' => ''
		 ), $atts);
		
		 if ($params['id']) 
			$id = 'id="' . $params['id'] . '"'; 
		
		do_shortcode( $content );
		
		//Row
		$html = '<div class="row-fluid ' . $params['class'] . '" ' . $id . '>';
		//Columns
		foreach ($columnArray as $key=>$value) $html .='<div class="' . $value['class'] . '">' . do_shortcode($value['content']) . '</div>';
		$html .='</div>';
	
		$columnArray = array();	
		return $html;
	}
	
	add_shortcode( 'row', 'row_sc' );
		
	//Row Items
	function span_sc( $atts, $content="" ){
		global $columnArray;
		$columnArray[] = array('class'=>$atts['class'], 'content'=>$content);
	}

	add_shortcode( 'col', 'span_sc' );			
}