<?php
    /**
    * @package Helix Framework
    * @author JoomShaper http://www.joomshaper.com
    * @copyright Copyright (c) 2010 - 2014 JoomShaper
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
                Helix::getInstance()
                    ->loadHelixOverwrite()
                    ->importShortCodeFiles();
            }

        }

        //Added 2.1.6
        function onContentPrepare($context, &$article)
        {

            $userDef =  ( $context == 'com_content.article' ) ||
                        ( $context == 'com_content.category' ) ||
                        ( $context == 'com_content.featured' ) ||
                        ( $context == 'mod_custom.content' );

            if( $userDef ) {
                $article->text = do_shortcode($article->text);
            }
 
        }


        function onAfterRender()
        {
            $shortcodes_scripts    = Helix::getInstance()->_shortcodes_scripts;
            $shortcodes_styles     = Helix::getInstance()->_shortcodes_styles;

            $shortcodes_scripts    = array_unique($shortcodes_scripts);
            $shortcodes_styles     = array_unique($shortcodes_styles);

            $data           =  JResponse::getBody();
            $new_head_data  = '';

            foreach ($shortcodes_styles as $style)
            {
                $new_head_data .= '<link rel="stylesheet" href="' . $style . '" />'. "\n"; 
            }     

            foreach ($shortcodes_scripts as $script)
            {
                $new_head_data .= '<script type="text/javascript" src="' . $script . '"></script>'. "\n"; 
            }    

            $data = str_replace('</head>', $new_head_data . "\n</head>", $data);

            JResponse::setBody($data);
        }


        function onAfterDispatch()
        {

            if(  !JFactory::getApplication()->isAdmin() ){

                $activeMenu = JFactory::getApplication()->getMenu()->getActive();

                if(is_null($activeMenu)) $template_style_id = 0;
                else $template_style_id = (int) $activeMenu->template_style_id;
                if( $template_style_id > 0 ){

                    JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_templates/tables');
                    $style = JTable::getInstance('Style', 'TemplatesTable');
                    $style->load($template_style_id);

                    if( !empty($style->template) ) JFactory::getApplication()->setTemplate($style->template, $style->params);
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