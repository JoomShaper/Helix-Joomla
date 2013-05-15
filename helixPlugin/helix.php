<?php
    /**
    * @package Helix Framework
    * @author JoomShaper http://www.joomshaper.com
    * @copyright Copyright (c) 2010 - 2013 JoomShaper
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */	

    //no direct accees
    defined ('_JEXEC') or die ('resticted aceess');

    jimport( 'joomla.event.plugin' );

    class  plgSystemHelix extends JPlugin
    {
        function onAfterInitialise()
        {
            $helix_path = JPATH_PLUGINS.'/system/helix/core/helix.php';
            if (file_exists($helix_path)) {
                require_once($helix_path);
                Helix::getInstance();
                Helix::getInstance()->loadHelixOverwrite();

            } else {
                echo JText::_('Helix framework not found.');
                die;
            }
        }

        function onAfterDispatch()
        {
            if(  !JFactory::getApplication()->isAdmin() ){

                $template_style_id = (int) JFactory::getApplication()
                ->getMenu()
                ->getActive()
                ->template_style_id;



                if( $template_style_id > 0 ){

                    JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_templates/tables');
                    $style = JTable::getInstance('Style', 'TemplatesTable');
                    $style->load($template_style_id);

                    if( !empty($style->template) ) JFactory::getApplication()->setTemplate($style->template, $style->params);
                }
            }
        }


        function onAfterRender()
        {
            if(  !JFactory::getApplication()->isAdmin() ){

                $document = JFactory::getDocument();
                $type = $document->getType();

                if($type=='html') {

                    $oldhead = $document->getHeadData();  // old head

                    $data =  JResponse::getBody();
                    Helix::getInstance()->importShortCodeFiles();

                    $data = do_shortcode($data); 
                    $newhead = $document->getHeadData();  // new head

                    $scripts =  (array)  array_diff_key($newhead['scripts'], $oldhead['scripts']);
                    $styles  =  (array) array_diff_key($newhead['styleSheets'], $oldhead['styleSheets']);

                    $new_head_data = '';

                    foreach ($scripts as $key => $type)
                        $new_head_data .= '<script type="' . $type['mime'] . '" src="' . $key . '"></script>';

                    foreach ($styles as $key => $type)
                        $new_head_data .=  '<link rel="stylesheet" href="' . $key . '" />';

                    $data = str_replace('</head>', $new_head_data . "\n</head>", $data);

                    JResponse::setBody($data);
                }
            }
        }

        // Updated 1.9.5
        function onContentPrepareForm($form, $data)
        {
            if ($form->getName()=='com_menus.item') //Add Helix menu params to the menu item
            {
                JHtml::_('behavior.framework');
                $doc = JFactory::getDocument();

                JForm::addFormPath(JPATH_PLUGINS.'/system/helix/fields');
                $form->loadFile('params', false);


                // 2.5
                if (JVERSION < 3) {
                    $plg_path = JURI::root(true).'/plugins/system/helix/js/admin/menuscript.25.js';
                } else {
                    $plg_path = JURI::root(true).'/plugins/system/helix/js/admin/menuscript.30.js';	//for joomla 3.0		
                }
                $doc->addScript($plg_path);
            }
        }
}