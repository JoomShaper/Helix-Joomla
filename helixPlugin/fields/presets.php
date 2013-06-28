<?php
    /**
    * @package Helix Framework
    * @author JoomShaper http://www.joomshaper.com
    * @copyright Copyright (c) 2010 - 2013 JoomShaper
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */

    defined('JPATH_BASE') or die;

    jimport('joomla.form.formfield');
    jimport('joomla.filesystem.folder');
    jimport('joomla.filesystem.file');
    /**
    * Supports a modal article picker.
    *
    * @package		Joomla.Administrator
    * @since		1.6
    */
    class JFormFieldPresets extends JFormField
    {
        /**
        * The form field type.
        *
        * @var		string
        * @since	1.6
        */
        protected $type = 'Presets';

        /**
        * Method to get the field input markup.
        *
        * @return	string	The field input markup.
        * @since	1.6
        */
        protected function getInput()
        {
            $template = $this->form->getValue('template');
            $templatePresetsDir = JPATH_SITE.'/templates/'.$template.'/images/presets/*';
            $base_url = JURI::root(true).'/templates/'.$template.'/images/presets/';
            $root_path = JPATH_SITE.'/templates/'.$template.'/images/presets/';
            $doc = JFactory::getDocument();
            $helix_url = JURI::root(true).'/plugins/system/helix/';

            $folders = glob($templatePresetsDir, GLOB_ONLYDIR);
            if( !defined('CURRENT_PRESET') ){
                define('CURRENT_PRESET', $this->value);
                $doc->addScriptDeclaration('var $current_preset = "'.$this->value.'"');
            } 

            $html = '';
            $options = array();

            natsort($folders );
            //print_r($folders);

            foreach($folders as $folder)
            {

                if( file_exists($root_path.basename($folder).'/thumbnail.png') ) $image = $base_url.basename($folder).'/thumbnail.png';
                else $image = $helix_url.'img/no-preview.jpg';

                $html .='<div class="presets'.(($this->value == basename($folder))?' active':'').'">';
                $html .='<div class="preset-title">';
                $html .= basename($folder);
                $html .='</div>';

                $html .='<div data-preset="'. basename($folder) .'" class="preset-contents">';
                $html .='<label>';
                $html .='<input style="display:none" '.(($this->value == basename($folder))?'checked':'').' value="'.basename($folder).'" type="radio" name="jform[params]['.$this->element['name'].']" />';
                $html .='<img  src="'.$image.'" alt="'.basename($folder).'" />';
                $html .='</div>';

                $html .='</label>';
                $html .='</div>';
            }


            $html .= '';

            return $html; 

        }

        public function getLabel()
        {
            return false;
        }

    }
