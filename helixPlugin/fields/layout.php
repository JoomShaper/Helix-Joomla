<?php
	/**
	 * @package Helix Framework
	 * @author JoomShaper http://www.joomshaper.com
	 * @copyright Copyright (c) 2010 - 2013 JoomShaper
	 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
	*/
    defined('JPATH_BASE') or die;

    jimport('joomla.form.formfield');
    class JFormFieldLayout extends JFormField {
        protected $type = 'Layout';
        public function getInput() {
            $doc = JFactory::getDocument();

            $plg_path = JURI::root(true).'/plugins/system/helix';

            $doc->addScriptDeclaration("
                var pluginPath = '{$plg_path}';
                var fieldName = 'jform[params][".$this->element['name']."]';
            ");



            $template     = $this->form->getValue('template');

            $theme_layout_path    = JPATH_SITE.'/templates/'.$template.'/layout/';
            $theme_path    = JPATH_SITE.'/templates/'.$template.'/';
            $helix_layout_path = JPATH_SITE.'/plugins/system/helix/layout/';
            $layout      = (string) $this->element['default'];


            $layoutsettings = $this->value;

            if( file_exists($theme_path.'html/modules.php') ){
                include_once( $theme_path.'html/modules.php' );

            } else {

                $modChromes=array();
            }
            if( !empty($layoutsettings) ){
                file_put_contents( $theme_layout_path.$template.'.json', json_encode($layoutsettings) );
            }

            $positions = $this->getPositions();

            if( is_array($layoutsettings) ){
                return $this->generateLayout($helix_layout_path,$layoutsettings, $positions, $modChromes);

            } else{

                if( file_exists($theme_layout_path.$template.'.json') )
                {
                    $layoutsettings = json_decode(file_get_contents($theme_layout_path.$template.'.json'));
                    return $this->generateLayout($helix_layout_path,$layoutsettings, $positions,$modChromes);
                }

                $layoutsettings = json_decode(file_get_contents($helix_layout_path.'default.json'));
                return $this->generateLayout($helix_layout_path,$layoutsettings, $positions,$modChromes);
            }

        }



        private function generateLayout($path, $layout, $positions, $modChromes)
        {

            ob_start();
            include_once( $path.'generated.php' );
            $items = ob_get_contents();
            ob_end_clean();

            return $items;

        }


        public function getLabel()
        {
            return false;
        }


        public function getPositions()
        {

            $db = JFactory::getDBO();
            $query = 'SELECT `position` FROM `#__modules` WHERE  `client_id`=0 AND ( `published` !=-2 AND `published` !=0 ) GROUP BY `position` ORDER BY `position` ASC';

            $db->setQuery($query);
            $dbpositions = (array) $db->loadAssocList();


            $template = $this->form->getValue('template');
            $templateXML = JPATH_SITE.'/templates/'.$template.'/templateDetails.xml';
            $template = simplexml_load_file( $templateXML );
            $options = array();

            foreach($dbpositions as $positions) $options[] = $positions['position'];

            foreach($template->positions[0] as $position)  $options[] =  (string) $position;

            $options = array_unique($options);

            $selectOption = array();
            sort($selectOption);

            foreach($options as $option) $selectOption[] = $option;

            return $selectOption;


        }
}