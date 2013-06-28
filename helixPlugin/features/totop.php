<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');
 
class HelixFeatureTotop {

	private $helix;

	public function __construct($helix){
		$this->helix = $helix;
	}

	public function onHeader()
	{

	}

	public function onFooter()
	{
		
	ob_start();

	$data = ob_get_contents();
	
	ob_end_clean();
	if( $this->helix->Param('showtop')  ) return $data;

	}

	public function Position()
	{
		if( $this->helix->Param('showtop') )
			return $this->helix->Param('totop_position');
	}


	public function onPosition()
	{        
		 return '<a class="sp-totop" href="javascript:;" title="' . JText::_('GOTO_TOP') . '" rel="nofollow"><small>'. JText::_('GOTO_TOP') .' </small><i class="icon-caret-up"></i></a>';
	}    
}