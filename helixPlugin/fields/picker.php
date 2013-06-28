<?php
    /**
    * @package Helix Framework
    * @author JoomShaper http://www.joomshaper.com
    * @copyright Copyright (c) 2010 - 2013 JoomShaper
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */
    // no direct access
    defined( '_JEXEC' ) or die( 'Restricted access' );

    jimport('joomla.form.formfield');

    class JFormFieldPicker extends JFormField
    {
        protected	$type = 'Picker';
        protected function getInput() {
            $plg_path = JURI::root(true).'/plugins/system/helix';
            $doc = JFactory::getDocument();
            $doc->addScript($plg_path.'/js/admin/spectrum.js');
            $doc->addStylesheet($plg_path.'/css/admin/spectrum.css');

            $defaultColor = $this->element['default']; 
            $className = $this->element['class']; 


            $doc->addScriptDeclaration('jQuery(function($){
                $("#' . $this->id . '_picker").spectrum({
                flat:false,
                showInput:true,
                showButtons:true,
                showAlpha:false,
                showPalette:true,
                clickoutFiresChange:true,
                cancelText:"cancel",  
                chooseText:"Choose", 
                palette: [ ["'.$defaultColor.'", "'.$this->value.'" ]],
                }); 
                });'
            );
            return '<input type="text" name="' . $this->name . '" id="' . $this->id . '_picker" class="presetcolors '.$className.'" value="' . $this->value . '" size="10" />
            <span id="' . $this->id . '_picker" class="picker-box"></span>';
        }
    } 
