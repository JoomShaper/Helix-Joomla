<?php
	/**
	 * @package Helix Framework
	 * @author JoomShaper http://www.joomshaper.com
	 * @copyright Copyright (c) 2010 - 2013 JoomShaper
	 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
	*/
    defined('JPATH_BASE') or die;

    jimport('joomla.form.formfield');
    class JFormFieldGroup extends JFormField {
        protected $type = 'Group';
        public function getInput() {
            $text  	= (string) $this->element['title'];
            $group = ($this->element['group']=='no')?'no_group':'in_group';
            return '<div class="group_separator '.$group.'" title="'. JText::_($this->element['desc']) .'">' . JText::_($text) . '</div>';
        }

        public function getLabel(){
            return false;
        }
}