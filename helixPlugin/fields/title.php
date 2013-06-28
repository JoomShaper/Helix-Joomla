<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
class JFormFieldTitle extends JFormField
{
	protected $type = 'Title';	
	protected function getInput() {
		$html = '<span class="tab-text">' . $this->element['label'] . '</span>';
		return $html;	
	}	
}