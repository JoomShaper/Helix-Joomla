<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

 class HelixFeatureGoogleFont {

	private $helix;

	public function __construct($helix){
		$this->helix = $helix;
	}

	public function onHeader()
	{
		//Fonts
		$fonts = '';

		if ($this->helix->Param('body_font') and $this->helix->Param('body_selectors'))
			$fonts .= $this->helix->GoogleFont($this->helix->Param('body_font'), $this->helix->Param('body_selectors'));

		if ($this->helix->Param('header_font') and $this->helix->Param('header_selectors'))
			$fonts .=$this->helix->GoogleFont($this->helix->Param('header_font'), $this->helix->Param('header_selectors'));

		if ($this->helix->Param('other_font') and $this->helix->Param('other_selectors'))
			$fonts .=$this->helix->GoogleFont($this->helix->Param('other_font'), $this->helix->Param('other_selectors'));

		return $fonts;
	}

	public function onFooter()
	{

	}


	public function Position()
	{

	}


	public function onPosition()
	{        

	}    
}