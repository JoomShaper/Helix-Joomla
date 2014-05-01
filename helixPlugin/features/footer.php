<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

class HelixFeatureFooter {

	private $helix;

	public function __construct($helix){
		$this->helix = $helix;
	}

	public function onHeader(){

	}

	public function onFooter(){

	}

	public function Position(){
		return $this->helix->Param('footer_position');
	}

	public function onPosition()
	{
		ob_start();
		
		//Helix Logo
		if ($this->helix->Param('show_helix_logo', 1)) : ?>
			<div class="helix-framework">
				<a class="helix-logo" target="_blank" title="Powered by Helix Framework" href="http://www.joomshaper.com/helix">
					Powered by Helix
				</a>
			</div>
		<?php endif;
		
		
		//Copyright
		if( $this->helix->Param('showcp', 1) ) {
			echo '<span class="copyright">' . str_ireplace('{year}',date('Y'), $this->helix->Param('copyright')) . '</span>';
		}
		
		//Brand Link
		if( $this->helix->Param('credit_link', 1) ) {
			$jscreditlink = '<a title="JoomShaper" class="sp-brand" target="_blank" href="http://www.joomshaper.com">JoomShaper</a>';
			$jscreditreplace =  str_ireplace('{JoomShaper}',$jscreditlink, $this->helix->Param('credit_text') );			
			echo '<span class="designed-by">'. $jscreditreplace .' </span> ';
		} 
		
		//Joomla Credit
		if ($this->helix->Param('jcredit', 1))
			echo '<span class="powered-by">' . JText::_('Powered by') . ' <a target="_blank" title="Joomla" href="http://www.joomla.org/">Joomla!</a></span> ';
			
		if( $this->helix->Param('validator', 0) )
			echo '<span class="validation-link">' . JText::_('Valid') . ' <a target="_blank" href="http://validator.w3.org/check/referer">XHTML</a> ' . JText::_('and') .' <a target="_blank" href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3">CSS</a></span>';
		

		//back link
		echo '<a href="http://www.joomshaper.com" title="joomshaper.com"></a>';

		return ob_get_clean();
	}    
}