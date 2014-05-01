<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[Dropcap]
if(!function_exists('dropcap_sc')) {

	function dropcap_sc( $atts, $content="" ) {
		return '<p class="sp-dropcap">' . $content .'</p>';
	}
		
	add_shortcode( 'dropcap', 'dropcap_sc' );
}

//[Block Numbers]
if(!function_exists('blocknumber_sc')) {

	function blocknumber_sc( $atts, $content="" ) {
		extract(shortcode_atts(array(
				'text' => '01',
				'background' => '#000',
				'color' => '#666',
				'type' =>''//rounded, circle
		 ), $atts));
	
		return '<p class="sp-blocknumber"><span style="background:'.$background.';color:'.$color.'" class="' .$type .'">' . $text . '</span> ' . do_shortcode( $content ) . '</p>';
	}
		
	add_shortcode( 'blocknumber', 'blocknumber_sc' );
}

//[Block]
if(!function_exists('block_sc')) {

	function block_sc( $atts, $content="" ) {
		extract(shortcode_atts(array(
				'background' => 'transparent',
			    'color' => '#666',
			    'padding' => '15px',
			    'border' => '0',
				'type' =>''//rounded, circle
		 ), $atts));
	
		return '<div class="sp-block '.$type.'" style="background:'.$background.';color:'.$color.';padding:'.$padding.';border:'.$border.'">'. do_shortcode( $content ) .'</div>';
	}
		
	add_shortcode( 'block', 'block_sc' );
}

//[Bubble]
if(!function_exists('bubble_sc')) {

	function bubble_sc( $atts, $content="" ) {
		extract(shortcode_atts(array(
				'author' => 'Ahmed',
				'background' => '#CCC',
			    'color' => '',
			    'padding' => '10px',
			    'border' => '0',
				'type' =>''//rounded, circle
		 ), $atts));
		 
		$bg			= $background;
		 
		$background = ($background !='') ? 'background:'.$background.';' : '';
		$color = ($color !='') ? 'color:'.$color.';' : '';
		
		if($border!=0) {
			$border_color = explode(' ', $border);
			$border_color = $border_color[2];
		}
		
		$cite = ($border!=0) ? $border_color : $bg;
	
		return '<div class="sp-bubble '.$type.'" style="'.$background.'padding:'.$padding.';border:'.$border.'"><div style="'.$color.'">'. do_shortcode( $content ) .'</div><cite><span style="border:15px solid '.$cite.'"></span>'.$author.'</cite></div>';
	}
		
	add_shortcode( 'bubble', 'bubble_sc' );
}