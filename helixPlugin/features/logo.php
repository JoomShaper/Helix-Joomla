<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

class HelixFeatureLogo {

	private $helix;

	public function __construct($helix){
		$this->helix = $helix;
	}

	public function onHeader()
	{

	}

	public function onFooter()
	{

	}


	public function Position()
	{

		if( $this->helix->Param('logo_type', 'image')!='no' )
			return $this->helix->Param('logo_position');
	}


	public function onPosition()
	{        
		$html = '';
		$wrapper = $this->helix->Param('logo_css_wrapper');

		$logoimage = $this->helix->Param('logo_type_image');
		$logowidth = $this->helix->Param('logo_width');
		$logowidth = !empty($logowidth)?"width:".$logowidth.'px;':'';

		$logoheight = $this->helix->Param('logo_height');
		$logoheight = !empty($logoheight)?"height:".$logoheight.'px;':'';


		$html .= "<div class=\"logo-wrapper\">";

		if( $this->helix->Param('logo_type')=='image' ){


			$html .= "<a href=\"".JURI::root(true)."\">";
			if( !empty($logoimage) )
				$html .= "<img alt=\"\" class=\"image-logo\" src=\"".JURI::root(true).'/'.$this->helix->Param('logo_type_image')."\" />";
			else
				$html .= "<div style=\"{$logowidth} {$logoheight}\" class=\"logo\"></div>";
			$html .= "</a>";



		} elseif( $this->helix->Param('logo_type')=='text' ) {

			$html .= "<div class=\"logo-text\">";
			$html .= "<a href=\"".JURI::root(true)."\">";
			$html .= $this->helix->Param('logo_type_text');
			$html .= "</a>";
			$html .="</div>
			<div class=\"logo-slogan\">".$this->helix->Param('logo_type_slogan')."</div>";
		}

		$html .= "</div>";

		return $html;
	}    
}