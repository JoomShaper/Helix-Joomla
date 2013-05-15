<?php
	/**
	 * @package Helix Framework
	 * @author JoomShaper http://www.joomshaper.com
	 * @copyright Copyright (c) 2010 - 2013 JoomShaper
	 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
	*/
    defined('JPATH_BASE') or die;

    jimport('joomla.form.formfield');
    class JFormFieldInclude extends JFormField {
        protected $type = 'include';
        public function getInput() {

            $tpl_path = JPATH_SITE.'/templates/'.$this->form->getValue('template').'/';
            $helix_path = JPATH_SITE.'/plugins/system/helix/';
            $text      = (string) $this->element['file'];

            if( file_exists( $helix_path.$text ) ) include $helix_path.$text;
            else include $tpl_path.$text;
        }


        public function getLabel()
        {
            return false;
        }
}