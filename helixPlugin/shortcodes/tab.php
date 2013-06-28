<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');
	
//[Tab]
if(!function_exists('tab_sc')) {
	$tabArray = array();
	function tab_sc( $atts, $content="" ){
		global $tabArray;
		
		$params = shortcode_atts(array(
			  'button' => 'nav-tabs',
			  'id' => 'tab',
			  'class'=>''
		 ), $atts);
		
		do_shortcode( $content );
		
		$html = '<div class="tab">';
		
		$html .= '<div class="' . $params['class'] . '">';
		
		//Tab Title
		$html .='<ul class="nav ' . $params['button'] . '" id="' . $params['id'] . '">';
		foreach ($tabArray as $key=>$tab) {
			$html .='<li class="'. ( ($key==0) ? "active" : "").'"><a href="#'. Helix::slug( $params['id'] ) . '-' . Helix::slug($tab['title']).'" data-toggle="tab">'. $tab['title'] .'</a></li>';
		}
		$html .='</ul>';
		
		//Tab Content
		$html .='<div class="tab-content">';
		foreach ($tabArray as $key=>$tab) {
			$html .='<div class="tab-pane fade'. ( ($key==0) ? " active in" : "").'" id="'. Helix::slug( $params['id'] ) . '-' . Helix::slug($tab['title']).'">' . do_shortcode($tab['content']) .'</div>';
		}
		$html .='</div>';
		
		$html .='</div>';
		
		$html .='</div>';
		
		$tabArray = array();
		
		return $html;
	}
	add_shortcode( 'tab', 'tab_sc' );
	
	//Tab Items
	function tab_item_sc( $atts, $content="" ){
		global $tabArray;
		$tabArray[] = array('title'=>$atts['title'], 'content'=>$content);
	}

	add_shortcode( 'tab_item', 'tab_item_sc' );	
}