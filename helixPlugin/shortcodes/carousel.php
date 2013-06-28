<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[carousel]
if(!function_exists('carousel_sc')) {
	$carouselArray = array();
	function carousel_sc( $atts, $content="" ){
		global $carouselArray;
		$caption = array(
			'[caption]'=>'<div class="carousel-caption">',
			'[/caption]'=>'</div>'
		);
		
		$params = shortcode_atts(array(
			  'id' => 'myCarousel'
		 ), $atts);
		
		do_shortcode( $content );
		
		$html = '<div class="carousel slide" id="' . $params['id'] . '">';
		$html .= '<div class="carousel-inner">';
		
		//carousels
		foreach ($carouselArray as $key=>$carousel) {
			$html .='<div class="'. ( ($key==0) ? "active" : "").' item">' . do_shortcode( strtr($carousel['content'], $caption) ) . '</div>';
		}
		
		$html .='</div>';//end carousel-inner
		
		$html .='<a class="carousel-control left" href="#' . $params['id'] . '" data-slide="prev">&lsaquo;</a><a class="carousel-control right" href="#' . $params['id'] . '" data-slide="next">&rsaquo;</a>';
		
		$html .='</div>';
	
		$carouselArray = array();	
		return $html;
	}
	
	add_shortcode( 'carousel', 'carousel_sc' );
		
	//carousel Items
	function carousel_item_sc( $atts, $content="" ){
		global $carouselArray;
		$carouselArray[] = array('content'=>$content);
	}

	add_shortcode( 'carousel_item', 'carousel_item_sc' );			
}