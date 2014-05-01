<?php
	/**
	 * @package Helix Framework
	 * @author JoomShaper http://www.joomshaper.com
	 * @copyright Copyright (c) 2010 - 2013 JoomShaper
	 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
	*/

    defined('JPATH_BASE') or die;
    jimport('joomla.form.formfield');

    class JFormFieldAsset extends JFormField
    {
        protected	$type = 'Asset';

        protected function getInput() {

            $plg_path = JURI::root(true).'/plugins/system/helix';
            $tpl_path = JURI::root(true).'/templates/'.$this->form->getValue('template').'/admin';

            if( !defined('THEME_URL') ) define('THEME_URL', JURI::root(true).'/templates/'.$this->form->getValue('template'));
            if( !defined('HELIX_URL') ) define('HELIX_URL', $plg_path);

            $doc = JFactory::getDocument();

            $doc->addScriptDeclaration("var basepath = '{$plg_path}';");
            $doc->addScriptDeclaration("var spjversion = '" . JVERSION . "';");

            if( JVERSION < 3 ){
                $templateCss= "templates/".JFactory::getApplication()->getTemplate().'/css/template.css';    
                $doc->addStyleSheet($templateCss);
            }
         
           // 2.5
            if( JVERSION < 3 ){
                $doc->addScript($plg_path.'/js/jquery.min.js');
                $doc->addScript($plg_path.'/js/admin/jquery-ui.min.js');
                $doc->addScript($plg_path.'/js/bootstrap.min.js');
                $doc->addStyleSheet($plg_path.'/css/bootstrap.min.css');
                $doc->addScript($plg_path.'/js/admin/helix.admin.25.js');
            } else {
                $doc->addScript($plg_path.'/js/admin/jquery-ui.min.js');
                $doc->addScript($plg_path.'/js/admin/helix.admin.30.js');
            }

            $doc->addScript($plg_path.'/js/admin/layout.admin.js');

            if( JVERSION < 3 ){
                $doc->addStyleSheet($plg_path.'/css/admin/helix.admin.css');
            } else {
                $doc->addStyleSheet($plg_path.'/css/admin/helix.admin3.css');
            }

            $doc->addStyleSheet($plg_path.'/css/admin/font-awesome.min.css');


            if(isset($this->element['loadbefore']) and (int) $this->element['loadbefore'] > JVERSION )
            {
                if($this->element['assettype'] == 'css')  $doc->addStyleSheet($tpl_path.'/'.$this->element['filename']);            
                if($this->element['assettype'] == 'js') $doc->addScript($tpl_path.'/'.$this->element['filename']);    
            }

            if(isset($this->element['loadafter']) and (int) $this->element['loadafter'] <= JVERSION )
            {
                if($this->element['assettype'] == 'css')  $doc->addStyleSheet($tpl_path.'/'.$this->element['filename']);            
                if($this->element['assettype'] == 'js') $doc->addScript($tpl_path.'/'.$this->element['filename']);    
            }

            if(!isset($this->element['loadbefore']) or !isset($this->element['loadafter']) )
            {
                if($this->element['assettype'] == 'css')  $doc->addStyleSheet($tpl_path.'/'.$this->element['filename']);            
                if($this->element['assettype'] == 'js') $doc->addScript($tpl_path.'/'.$this->element['filename']);    
            }

        }
    }