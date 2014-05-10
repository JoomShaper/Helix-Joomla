<?php
    /**
    * @package Helix Framework
    * @author JoomShaper http://www.joomshaper.com
    * @copyright Copyright (c) 2010 - 2014 JoomShaper
    * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
    */
    //no direct accees
    defined ('_JEXEC') or die('resticted aceess');

    jimport('joomla.filesystem.file');
    jimport('joomla.filesystem.folder');

    class Helix {

        private static $_instance;
        private $document;
        private $importedFiles=array();
        private $_less;
        public $_shortcodes_scripts = array();
        public $_shortcodes_styles = array();

        //initialize 
        public function __construct(){

        }

        /**
        * making self object for singleton method
        * 
        */
        final public static function getInstance()
        {
            if( !self::$_instance ){
                self::$_instance = new self();
                self::getInstance()->getDocument();
                self::getInstance()->getDocument()->helix = self::getInstance();
            } 
            return self::$_instance;
        }


        /**
        * Get Document
        * 
        * @param string $key
        */
        public static function getDocument($key=false)
        {
            self::getInstance()->document = JFactory::getDocument();
            $doc = self::getInstance()->document;
            if( is_string($key) ) return $doc->$key;

            return $doc;
        }

        /**
        * Get Template name
        * 
        * @return string
        */
        public static function themeName()
        {
            //return self::getInstance()->getDocument()->template;
            return JFactory::getApplication()->getTemplate();
        }

        /**
        * Get Template name
        * @return string
        */
        public static function theme()
        {
            return self::getInstance()->themeName();
        }

        /**
        * Body Class
        * 
        * @param mixed $class
        * @return string
        */
        public static function bodyClass($class='')
        {
            $classes  = '';
            $classes  .= JRequest::getVar( 'view' );

            $classes .= self::getInstance()->isFrontPage() ? ' homepage ':' subpage ';

            $classes .= ' '. self::getInstance()->direction() . ' ';

            $classes .= self::getInstance()->preset();

            $app      = JFactory::getApplication();
            $menu     = $app->getMenu();
            $classes .= ' menu-' . @$menu->getActive()->alias;

            $classes .= ' ' . self::getInstance()->Param('layout_type');

            return ' class="'.$classes.' '.$class.'"';
        }


        /**
        * Get theme path
        * 
        * @param bool $base
        * @return string
        */
        public static function themePath($base=false)
        {
            if( $base==true ) return JURI::root(true).'/templates/'.self::getInstance()->themeName();

            return  JPATH_THEMES . '/' . self::getInstance()->themeName();
        }

        /**
        * Get theme path
        * @return string
        */
        public static function themeURL()
        {
            return self::getInstance()->themePath();
        }

        /**
        * Get Base Path
        * 
        */
        public static function basePath()
        {
            return JPATH_BASE;
        } 

        /**
        * Get Base URL
        * 
        */
        public static function baseURL()
        {
            return JURI::root(true);
        }

        /**
        * Get Framework HELIX path
        * 
        */
        public static function frameworkPath($base=false)
        {
            if( $base==true ) return JURI::root(true).'/plugins/system/helix';

            return JPATH_PLUGINS . '/system/helix';
        }

        public static function pluginPath($base=false){
            return self::getInstance()->frameworkPath($base);
        }

        /**
        * Save Features
        * 
        * @var array
        * @access private
        */
        private static $_features = array();

        /**
        * Get Features
        * @access private
        */
        private static function getFeatures(){

            $fromPlugin = glob( self::getInstance()->frameworkPath().'/features/*.php' );
            $fromTemplate = glob( self::getInstance()->themePath().'/features/*.php' );
            $files = array();

            foreach($fromPlugin as $file){
                $files[JFile::stripExt(basename($file))] = $file;
            } 

            if(is_array($fromTemplate)) {
                foreach($fromTemplate as $file){
                    $files[JFile::stripExt(basename($file))] = $file;
                } 
            }

            return $files;
        }


        private  $inPositions = array();
        private  $inHeader = array();
        private  $inFooter = array();

        private $beforeModule = array();
        private $afterModule = array();


        /**
        * Importing features
        * @access private
        */

        private static function importFeatures(){

            $features =(array) self::getInstance()->getFeatures();

            foreach($features as $name=>$file) {

                include_once $file;
                $class = 'HelixFeature' . ucfirst($name);
                $class = new $class( self::getInstance() );

                if( method_exists($class, 'Position') and method_exists($class, 'onPosition') ){
                    $hasposition = $class->Position();

                    if( !empty($hasposition) ){

                        //self::getInstance()->inPositions[$hasposition][] = $class->onPosition();
                        self::getInstance()->inPositions[$hasposition][] = true;

                        if( isset($class->beforeModule) and $class->beforeModule==true ) 
                            self::getInstance()->beforeModule[$hasposition][] = $class->onPosition();
                        else 
                            self::getInstance()->afterModule[$hasposition][] = $class->onPosition();
                    }
                }

                if( method_exists($class, 'onHeader') ){
                    self::getInstance()->inHeader[] = $class->onHeader();
                }

                if( method_exists($class, 'onFooter') ){
                    self::getInstance()->inFooter[] = $class->onFooter();
                }
            }
            return self::getInstance();
        }

        /**
        * Make string to slug
        * 
        * @param mixed $text
        * @return string
        */

        public static function slug($text)
        {
            return preg_replace('/[^a-z0-9_]/i','-', strtolower($text));
        }

        /**
        * Get or set Template param. If value not setted params get and return, 
        * else set params
        *
        * @param string $name
        * @param mixed $value
        */
        public static function Param($name=true, $value=NULL)
        {

            // if $name = true, this will return all param data
            if( is_bool($name) and $name==true ){
                return JFactory::getApplication()->getTemplate(true)->params;
            }
            // if $value = null, this will return specific param data
            if( is_null($value) ) return JFactory::getApplication()->getTemplate(true)->params->get($name);
            // if $value not = null, this will set a value in specific name.

            $data = JFactory::getApplication()->getTemplate(true)->params->get($name);

            if( is_null($data) or !isset($data) ){
                JFactory::getApplication()->getTemplate(true)->params->set($name, $value);
                return $value;
            } else {
                return $data;
            }
        }

        /**
        * Import required file/files
        * 
        * @param array | string $paths
        * @param object $helix
        * @return self
        */
        public static function Import($paths, $helix=false)
        {
            if( is_array($paths) ) foreach((array) $paths as $file) self::_Import( $file );
            else self::_Import( $paths, $helix );
            return self::getInstance();
        }

        /**
        * Single file import
        * 
        * @param string $path
        * @return self
        */
        private static function _Import($path, $helix)
        {
            $intheme  = self::getInstance()->themePath() . '/' . $path;
            $inplugin = self::getInstance()->frameworkPath() . '/' . $path;

            if( file_exists( $intheme ) && !is_dir( $intheme ) ){
                self::getInstance()->importedFiles[] = $intheme; 
                require_once $intheme;
            } elseif( file_exists( $inplugin ) && !is_dir( $inplugin ) ){
                self::getInstance()->importedFiles[] = $inplugin; 
                require_once $inplugin;
            }
            return self::getInstance();
        }

        /**
        * Get Imported file
        * @return array
        */
        public static function getImportedFiles()
        {
            return self::getInstance()->importedFiles;
        }

        /**
        * Saved layout
        * 
        * @var string
        * @access private
        */
        private $layout='';
        /**
        * Generating row
        * 
        * @param string $layout
        * @access private
        */
        private static function showRow($layout)
        {
            if( isset($layout->children) )
            {
                foreach( $layout->children as $i=>$v )
                {

                    if( !isset($v->type) or !isset($v->position) ) continue;
                    // hide component area
                    if( $v->type=='component' and  self::getInstance()->hideComponentArea()) continue;

                    if( $v->type=='component' or $v->type=='message' ) return true;

                    if( $v->position!='' ){
                        if( self::getInstance()->countModules( $v->position )  ) return true;
                        if( isset($v->children) ) self::getInstance()->showRow($v);
                    }
                }
            }
        }


        /**
        * Hide Component Area from frontpage
        * return bool
        */
        private static function hideComponentArea()
        {
            $hide = (bool) self::getInstance()->param('hide_component_area',0);
            if( self::getInstance()->isFrontPage() and true==$hide ) return true;
            else return false;
            //
        }


        private $inline_css = '';

        private static function get_layout_value($class, $method){
            if( isset($class->$method) and $class->$method=="" ) return false;
            return (isset( $class->$method )) ? $class->$method : FALSE;
        }

        private static function get_color_value($class, $method){
            $get = isset( $class->$method ) ? $class->$method : 'rgba(255, 255, 255, 0)';
            return ('rgba(255, 255, 255, 0)'==$get) ? FALSE : $get;
        }

        private static function get_row_class($classname){

            $replace = array( 'container'=>'', 'container-fluid'=>'' );

            if( self::getInstance()->has_container_class($classname, 'container') or self::getInstance()->has_container_class($classname, 'container-fluid') ){
                return strtr($classname, $replace);
            }
            return $classname;
        }


        private static function has_container_class($classname, $hasclass){

            $class =  explode(' ', $classname);

            if( in_array($hasclass, $class) ){
                return true;
            }        
            return false;    
        }

        private static function get_container_class($classname, $hasclass){

            $class =  explode(' ', $classname);

            if( in_array($hasclass, $class) ){
                return $hasclass;
            }        
            return '';    
        }

        private static function toObject(&$array, $class = 'stdClass')
        {
            $obj = null;

            if (is_array($array))
            {
                $obj = new $class;

                foreach ($array as $k => $v)
                {
                    if (is_array($v))
                    {
                        $obj->$k = self::toObject($v, $class);
                    }
                    else
                    {
                        $obj->$k = $v;
                    }
                }
            }
            return $obj;
        }

        /**
        * Layout generator
        * 
        * @param mixed $layout
        */
        private static function generatelayout($layout)
        {

            foreach($layout as $index=>$value)
            {
                if( is_null( self::getInstance()->showRow($value) ) ) continue;

                // set html5 stracture
                switch( self::getInstance()->slug($value->name) ){
                    case "header":
                    $sematic = 'header';
                    break;

                    case "footer":
                    $sematic = 'footer';
                    break;

                    default:
                    $sematic = 'section';
                    break;
                }

                $row_variables = (  ( self::getInstance()->get_color_value( $value, 'backgroundcolor' ) ) || 
                                    ( self::getInstance()->get_color_value( $value, 'textcolor' ) ) ||
                                    ( FALSE !== self::getInstance()->get_layout_value( $value, 'margin' ) ) ||
                                    ( FALSE !== self::getInstance()->get_layout_value( $value, 'padding' ) ) );

                $row_css = '';

                //double instance
                if ( $row_variables )
                {

                    $row_css .= "\n" . '#sp-'. self::getInstance()->slug($value->name) .'-wrapper{';

                    if( self::getInstance()->get_color_value( $value, 'backgroundcolor' ) ){
                        $row_css .= 'background: '. self::getInstance()->get_color_value( $value, 'backgroundcolor' ) .' !important; ';                   
                    }

                    if( self::getInstance()->get_color_value( $value, 'textcolor' ) ){
                        $row_css .= 'color: '. self::getInstance()->get_color_value( $value, 'textcolor' ) .' !important; ';                   
                    }  

                    if( FALSE !== self::getInstance()->get_layout_value( $value, 'margin' ) ){
                        $row_css .= 'margin: '. self::getInstance()->get_layout_value( $value, 'margin' ) .' !important; ';                   
                    }
                    if( FALSE !== self::getInstance()->get_layout_value( $value, 'padding' ) ){
                        $row_css .= 'padding: '. self::getInstance()->get_layout_value( $value, 'padding' ) .' !important; ';                   
                    }

                    $row_css .= '}' . "\n";

                }

                if( self::getInstance()->get_color_value( $value, 'linkcolor' ) ){
                    $row_css .= "\n" . '#sp-'. self::getInstance()->slug($value->name) .'-wrapper a{';
                    $row_css .= 'color: '. self::getInstance()->get_color_value( $value, 'linkcolor' ) .' !important; ';
                    $row_css .= '}' . "\n";            
                }

                if( self::getInstance()->get_color_value( $value, 'linkhovercolor' ) ){
                    $row_css .= "\n" . '#sp-'. self::getInstance()->slug($value->name) .'-wrapper a:hover{';
                    $row_css .= 'color: '. self::getInstance()->get_color_value( $value, 'linkhovercolor' ) .' !important; ';
                    $row_css .= '}' . "\n";                  
                }

                self::getInstance()->inline_css .= $row_css;

                //Layout
                self::getInstance()->layout.='<'.$sematic.' id="sp-'. self::getInstance()->slug($value->name) .'-wrapper" 
                class="'. self::getInstance()->get_row_class($value->class) . ' '.((empty($value->responsive)?'':''.$value->responsive.'')).'">';
                //

                if(self::getInstance()->has_container_class($value->class,'container') 
                    or 
                    self::getInstance()->has_container_class($value->class,'container-fluid'))
                {
                    //  start container  
                    self::getInstance()->layout.='<div class="' 
                    . self::getInstance()->get_container_class($value->class,'container-fluid') 
                    . self::getInstance()->get_container_class($value->class,'container')
                    . '">';
                }

                //   start row fluid
                self::getInstance()->layout.='<div class="row-fluid" id="'. self::getInstance()->slug($value->name) .'">';

                if( isset($value->children) )
                {
                    $absspan   = 0;    //   absence span
                    $absoffset = 0;    // absence offset
                    $i = 1;            //  span increment

                    $totalItem = count($value->children);  // total children
                    $totalPublished = count($value->children);  // total publish children

                    foreach( $value->children as $val )
                    {
                        if( !isset($val->children) )
                        {
                            if( $val->type=='modules' )
                            {
                                if( !self::getInstance()->countModules($val->position))
                                {
                                    $absspan+=$val->span;
                                    $absoffset+=$val->offset; 
                                    $totalPublished--;
                                    $totalItem--;  
                                }
                            }
                        }
                    }

                    foreach( $value->children as $v )
                    {
                        if( $v->type=='modules' )
                        {
                            if( !self::getInstance()->countModules($v->position))
                            {
                                continue;
                            }
                        }

                        // if include type message or compoennt, this span will get all absance spans
                        if($v->type=='message' or  ($v->type=='component' and !self::getInstance()->hideComponentArea() ))
                        {
                            $totalItem = $i;
                        }

                        // set absance span in last module span
                        if( $i==$totalItem){
                            if( empty($v->offset) )
                            {
                                $v->span+=$absspan+$absoffset;
                                $v->offset='';   
                            }
                        }

                        // if position name "left" or "right", this will set html5 aside tag. otherwise div
                        switch($v->position){

                            case "left":
                            case "right":
                            $sematicSpan = 'aside';
                            break;

                            default:
                            $sematicSpan = 'div';
                            break;
                        }

                        // self::getInstance()->layout.= ' <!-- Start Span --> ';
                        // start span
                        //  debugging  data-i="'.$i.'" data-total="'.$totalPublished.'" data-absspan="'.$absspan.'"  data-type="'.$v->type.'" 

                        if( $v->type=='component' and self::getInstance()->hideComponentArea() ) continue;

                        if( empty($v->position) ) $wrid = 'sp-'.$v->type.'-area';
                        else $wrid = 'sp-'.$v->position;

                        self::getInstance()->layout.="\n".'<'.$sematicSpan.' id="'.strtolower($wrid).'" class="span'.$v->span.''.(empty($v->offset)?'':' offset'.$v->offset).''.((!isset($v->responsiveclass) or  empty($v->responsiveclass))?'':' '.$v->responsiveclass).''.(empty($v->customclass)?'':' '.$v->customclass).'">';

                        $i++;

                        if( $v->type=='component' )
                        {
                            self::getInstance()->layout.='<section id="sp-component-wrapper">';
                            self::getInstance()->layout.='<div id="sp-component">';
                            self::getInstance()->layout.='<jdoc:include type="message" />';
                            self::getInstance()->layout.='<jdoc:include type="component" />';
                            self::getInstance()->layout.='</div>';
                            self::getInstance()->layout.='</section>';
                        }
                        elseif( $v->type=='modules' ){
                            if( $v->position!='')
                            {

                                self::getInstance()->layout.= self::getInstance()->getFeature($v->position, true);
                                self::getInstance()->layout.='<jdoc:include type="modules" name="'.$v->position.'"  style="'.$v->style.'" />';
                                self::getInstance()->layout.= self::getInstance()->getFeature($v->position, false);
                            }
                        }

                        if( isset($v->children) )
                        {
                            self::getInstance()->generatelayout( $v->children );
                        } 

                        // end span
                        self::getInstance()->layout.='</'.$sematicSpan.'>'."\n";

                        // self::getInstance()->layout.= ' <!-- End Span --> ';
                    }
                }

                // end row fluid
                self::getInstance()->layout.='</div>';

                if(self::getInstance()->has_container_class($value->class,'container') 
                    or 
                    self::getInstance()->has_container_class($value->class,'container-fluid'))
                {
                    //  end container  
                    self::getInstance()->layout.='</div>';
                }

                // end row
                self::getInstance()->layout.='</'.$sematic.'>';
                // self::getInstance()->layout.="\n\n".'<!-- End Row: '.$index.' -->'."\n";
            }

            $css = self::getInstance()->inline_css;
            self::getInstance()->addInlineCSS( $css );
        }



        /**
        * Get layout from saved item or template dir or plugin dir
        * 
        */
        private static function get_layout(){

            $layoutIntheme = self::getInstance()->themePath().'/layout/'.self::getInstance()->themeName().'.json';
            $layoutInplugin = self::getInstance()->pluginPath().'/layout/default.json';
            $layout = self::getInstance()->Param('layout');

            if( empty($layout) )
            {
                if( file_exists($layoutIntheme) ){
                    $layout =  json_decode( file_get_contents($layoutIntheme));
                } elseif( file_exists($layoutInplugin) ) {
                    $layout =  json_decode(file_get_contents($layoutInplugin));
                } else {
                    die('Cann\'t found '.self::getInstance()->themeName().'.json'.
                        ' file in layout directory. Please goto template manager and save.');
                }
            } else {
                return $layout;
            }
            return $layout;
        }

        /**
        * Detact External URL
        * 
        * @param string $url
        * @return boolean
        */
        public function isExternalURL($url)
        {
            $parseurl = parse_url($url);
            $urlHost = isset($parseurl['host'])?$parseurl['host']:false;
            $currentHost = $_SERVER['HTTP_HOST'];
            $currentRemoteAddr = $_SERVER['REMOTE_ADDR'];

            if(false==$urlHost) return false;

            if( $currentHost===$urlHost or $currentRemoteAddr===$urlHost ) return false;
            else return true;
        } 


        /**
        * Layout output
        * 
        */
        public static function layout()
        {
            $layout =  self::getInstance()->get_layout();
            self::getInstance()->generatelayout($layout);
            echo self::getInstance()->layout;
            return self::getInstance();
        }


        private static function getFeature($position, $beforemodule=false)
        {
            if( self::getInstance()->hasFeature($position) ){

                if( $beforemodule==true ){
                    if( !empty(self::getInstance()->beforeModule[$position]) )
                        return implode("\n", self::getInstance()->beforeModule[$position]);
                } else {
                    if( !empty(self::getInstance()->afterModule[$position]) )
                        return implode("\n", self::getInstance()->afterModule[$position]);
                }
            }
        }

        public static function countModules($position)
        {
            return (self::getInstance()->getDocument()->countModules($position) or self::getInstance()->hasFeature($position) );
        }

        /**
        * Has only module
        * 
        * @param string $position
        */
        public static function hasModule($position)
        {
            return self::getInstance()->getDocument()->countModules($position);
        }

        /**
        * Has feature
        * 
        * @param string $position
        */

        public static function hasFeature($position)
        {
            return ( isset(self::getInstance()->inPositions[$position]) ) ? true : false;
        }

        /**
        * Add stylesheet
        * 
        * @param mixed $sources. string or array
        * @param string $seperator. default is , (comma)
        * @return self
        */
        public static function addCSS($sources, $seperator=',',$checkpath=true) {

            $srcs = array();
            if( is_string($sources) ) $sources = explode($seperator,$sources);
            if(!is_array($sources)) $sources = array($sources);

            foreach( (array) $sources as $source ) $srcs[] = trim($source);

            foreach ($srcs as $src) {

                if(self::getInstance()->isExternalURL($src)) self::getInstance()->document->addStyleSheet($src);

                if( $checkpath==false ){
                    self::getInstance()->document->addStyleSheet($src);
                    continue; 
                } 

                //cheack in template path
                if( file_exists( self::getInstance()->themePath() . '/css/'. $src)) { 
                    self::getInstance()->document->addStyleSheet( self::getInstance()->themePath(true) . '/css/' . $src );
                } 
                //if not found, then check from helix path
                elseif( file_exists( self::getInstance()->frameworkPath() . '/css/' . $src ) ) { 
                    self::getInstance()->document->addStyleSheet( self::getInstance()->frameworkPath(true) . '/css/' . $src);
                }        
            }
            return self::getInstance();
        }    

        /**
        * Add javascript
        * 
        * @param mixed $sources. string or array
        * @param string $seperator. default is , (comma)
        * @return self
        */
        public static function addJS($sources, $seperator=',', $checkpath=true) {

            $srcs = array();
            if( is_string($sources) ) $sources = explode($seperator,$sources);
            if(!is_array($sources)) $sources = array($sources);

            foreach( (array) $sources as $source ) $srcs[] = trim($source);

            foreach ($srcs as $src) {

                if(self::getInstance()->isExternalURL($src)) self::getInstance()->document->addScript($src);

                if( $checkpath==false ){
                    self::getInstance()->document->addScript($src);
                    continue; 
                } 

                //cheack in template path
                if( file_exists( self::getInstance()->themePath() . '/js/'. $src)) { 
                    self::getInstance()->document->addScript( self::getInstance()->themePath(true) . '/js/' . $src );
                } 
                //if not found, then check from helix path
                elseif( file_exists( self::getInstance()->frameworkPath() . '/js/' . $src ) ) { 
                    self::getInstance()->document->addScript( self::getInstance()->frameworkPath(true) . '/js/' . $src);
                }        
            }
            return self::getInstance();
        }



        /**
        * Add stylesheet
        * 
        * @param mixed $sources. string or array
        * @param string $seperator. default is , (comma)
        * @return self
        */
        public static function addShortcodeStyle($sources, $seperator=',',$checkpath=true) {

            $srcs = array();
            if( is_string($sources) ) $sources = explode($seperator,$sources);
            if(!is_array($sources)) $sources = array($sources);

            foreach( (array) $sources as $source ) $srcs[] = trim($source);

            foreach ($srcs as $src) {

                if(self::getInstance()->isExternalURL($src)) self::getInstance()->_shortcodes_styles[] = $src;

                if( $checkpath==false ){
                    self::getInstance()->_shortcodes_styles[] = $src;
                    continue; 
                } 

                //cheack in template path
                if( file_exists( self::getInstance()->themePath() . '/css/'. $src)) { 
                    self::getInstance()->_shortcodes_styles[] = self::getInstance()->themePath(true) . '/css/' . $src;
                } 
                //if not found, then check from helix path
                elseif( file_exists( self::getInstance()->frameworkPath() . '/css/' . $src ) ) {
                    self::getInstance()->_shortcodes_styles[] = self::getInstance()->frameworkPath(true) . '/css/' . $src;
                }        
            }
            return self::getInstance();
        } 

        public static function addShortcodeScript($sources, $seperator=',', $checkpath=true) {

            $srcs = array();
            if( is_string($sources) ) $sources = explode($seperator,$sources);
            if(!is_array($sources)) $sources = array($sources);

            foreach( (array) $sources as $source ) $srcs[] = trim($source);

            foreach ($srcs as $src) {

                if(self::getInstance()->isExternalURL($src)) self::getInstance()->_shortcodes_scripts[] = $src;

                if( $checkpath==false ){
                    self::getInstance()->_shortcodes_scripts[] = $src;
                    continue; 
                } 

                //cheack in template path
                if( file_exists( self::getInstance()->themePath() . '/js/'. $src)) { 
                    self::getInstance()->_shortcodes_scripts[] = self::getInstance()->themePath(true) . '/js/' . $src;
                } 
                //if not found, then check from helix path
                elseif( file_exists( self::getInstance()->frameworkPath() . '/js/' . $src ) ) { 
                    self::getInstance()->_shortcodes_scripts[] = self::getInstance()->frameworkPath(true) . '/js/' . $src;
                }        
            }
            return self::getInstance();
        }


        /**
        * Add Inline Javascript
        * 
        * @param mixed $code
        * @return self
        */
        public function addInlineJS($code){
            self::getInstance()->document->addScriptDeclaration($code);
            return self::getInstance();
        }

        /**
        * Add Inline CSS
        * 
        * @param mixed $code
        * @return self
        */
        public function addInlineCSS($code) {
            self::getInstance()->document->addStyleDeclaration($code);
            return self::getInstance();
        }

        /**
        * Less Init
        * 
        */
        private static function lessInit() {
            //import less class file
            self::getInstance()->Import('core/classes/lessc.inc.php');
            self::getInstance()->_less = new lessc();
        }

        /**
        * Instance of Less
        */  
        public static function less() {
            return self::getInstance()->_less;
        }


        /**
        * Set Less Variables using array key and value
        * 
        * @param mixed $array
        * @return self
        */
        public static function setLessVariables($array){
            self::getInstance()->less()->setVariables( $array );
            return self::getInstance();
        }

        /**
        * Set less variable using name and value
        * 
        * @param mixed $name
        * @param mixed $value
        * @return self
        */
        public static function setLessVariable($name, $value){
            self::getInstance()->less()->setVariables( array($name=>$value) );
            return self::getInstance();
        }

        /**
        * Compile less to css when less modified or css not exist
        * 
        * @param mixed $less
        * @param mixed $css
        * @return self
        */
        private static function autoCompileLess($less, $css) {
            // load the cache
            $cachePath = JPATH_CACHE.'/com_templates/templates/'.self::getInstance()->themeName();
            $cacheFile = $cachePath.'/'.basename($css.".cache");

            if (file_exists($cacheFile)) {
                $cache = unserialize(file_get_contents($cacheFile));
            } else {
                $cache = $less;
            }

            $lessInit = self::getInstance()->less();
            $newCache = $lessInit->cachedCompile($cache);

            if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {

                if(!file_exists($cachePath)){
                    JFolder::create($cachePath);
                }

                file_put_contents($cacheFile, serialize($newCache));
                file_put_contents($css, $newCache['compiled']);
            }

            return self::getInstance();
        }

        /**
        * Add Less
        * 
        * @param mixed $less
        * @param mixed $css
        * @return self
        */
        public static function addLess($less, $css) {
            $themepath = self::getInstance()->themePath();
            $plugpath = self::getInstance()->frameworkPath();

            if( self::getInstance()->Param('lessoption') and self::getInstance()->Param('lessoption')=='1' ){

                if( file_exists( $themepath. "/less/".$less.".less" ) ){
                    //self::getInstance()->less()->compileFile($themepath. "/less/".$less.".less", $themepath ."/css/".$css.".css");
                    self::getInstance()->autoCompileLess($themepath. "/less/".$less.".less", $themepath ."/css/".$css.".css");
                } 
                elseif( file_exists( $plugpath. "/less/".$less.".less") ) {
                    //self::getInstance()->less()->compileFile($plugpath. "/less/".$less.".less", $plugpath ."/css/".$css.".css");
                    self::getInstance()->autoCompileLess($plugpath. "/less/".$less.".less", $plugpath ."/css/".$css.".css");
                } else {
                    return self::getInstance();
                }
            }
            self::getInstance()->addCSS( $css.'.css');
            return self::getInstance();
        }



        private static function addLessFiles($less, $css)
        {

            $less = self::getInstance()->file('less/'. $less .'.less');
            $css  = self::getInstance()->file('css/'. $css .'.css');
            self::getInstance()->less()->compileFile($less, $css);

            echo $less; die;

            return self::getInstance();
        }


        public static function getShortcodes()
        {
            return self::getInstance()->shortcode_tags;
        }

        private static $shortcode_tags = array();
        public static function importShortCodeFiles()
        {

            $shortcodes = array();

            $themeshortcodes = glob( self::getInstance()->themePath().'/shortcodes/*.php' );
            $pluginshortcodes = glob( self::getInstance()->pluginPath().'/shortcodes/*.php');

            foreach((array) $themeshortcodes as $value)  $shortcodes[] =  basename($value);
            foreach((array) $pluginshortcodes as $value)  $shortcodes[] =   basename($value);

            $shortcodes = array_unique($shortcodes);

            self::getInstance()->Import('core/wp_shortcodes.php', self::getInstance());

            foreach($shortcodes as $shortcode  ) self::getInstance()->Import('shortcodes/'.$shortcode);

            return self::getInstance();
        }


        private static function file($file)
        {
            // searching in template path
            if( file_exists( self::getInstance()->themePath() . '/'. $file)) { 
                return self::getInstance()->themePath() . '/'. $file;
            } 
            //if not found, then check from helix path
            elseif( file_exists( self::getInstance()->frameworkPath() . '/'. $file ) ) { 
                return self::getInstance()->frameworkPath() . '/'. $file;
            }
            return false;
        }

        private static function resetCookie($name)
        {
            if( JRequest::getVar('reset',  ''  , 'get')==1 )
                setcookie( $name, '', time() - 3600, '/');
        }

        /**
        * Set Presets
        * 
        */
        public static function Preset() {
            $name = self::getInstance()->theme() . '_preset';
            self::getInstance()->resetCookie($name);

            $require = JRequest::getVar('preset',  ''  , 'get');
            if( !empty( $require ) ){
                setcookie( $name, $require, time() + 3600, '/');
                $current = $require;
            } 
            elseif( empty( $require ) and  isset( $_COOKIE[$name] )) {
                $current = $_COOKIE[$name];
            } else {
                $current = self::getInstance()->Param('preset');
            }

            return $current;
        }

        public static function PresetParam($name) {
            return self::getInstance()->param( self::getInstance()->Preset().$name );
        }



        /**
        * Set Direction
        * 
        */
        public static function direction() {

            $name = self::getInstance()->theme() . '_direction';
            self::getInstance()->resetCookie($name);

            $require = JRequest::getVar('direction',  ''  , 'get');
            if( !empty( $require ) ){
                setcookie( $name, $require, time() + 3600, '/');
                $current = $require;
            } 
            elseif( empty( $require ) and  isset( $_COOKIE[$name] )) {
                $current = $_COOKIE[$name];
            } else {
                $current = self::getInstance()->getDocument()->direction;
            }

            self::getInstance()->getDocument()->direction = $current;

            return $current;
        }		

        /**
        * Load Head
        *
        * @since    1.0
        *
        * @update    2.0
        */    
        public static function Header() {
            //layout option
            if (self::getInstance()->Param('layout_type')=='responsive') {
                self::getInstance()->document->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
                self::getInstance()->addInlineCSS('.container{max-width:' . self::getInstance()->Param('layout_width') . 'px}');
            } else {
                self::getInstance()->addInlineCSS('.container{width:' . self::getInstance()->Param('layout_width') . 'px}');
            }

            // add jQuery
            if((bool) self::getInstance()->Param('loadjquery', 0)==true ){
                self::getInstance()->addJQuery((bool)self::getInstance()->Param('loadfromcdn', 0), true);
            }

            self::getInstance()->addBootstrap();

            self::getInstance()->addJS('modernizr-2.6.2.min.js');
            //CSS3 pseudo-class and attribute selectors for IE 6-8
            self::getInstance()->selectivizr();
            self::getInstance()->respondJS();


            self::getInstance()->addJS('helix.core.js');

            //Initiate less
            self::getInstance()->lessInit();

            //Import all features			
            self::getInstance()->importFeatures();

            foreach(self::getInstance()->inHeader as $load) echo $load;

            return self::getInstance();
        }

        public static function Footer() {
            foreach(self::getInstance()->inFooter as $load) echo $load;			

            self::getInstance()->Compression();

            self::getInstance()->addCSS('custom.css');

            return self::getInstance();
        }


        /**
        * Detect frontpage
        *
        * @since    1.0
        */
        public static function isFrontPage(){
            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            $lang = JFactory::getLanguage();
            if ($menu->getActive() == $menu->getDefault($lang->getTag())) {
                return true;
            }
            else {
                return false;
            } 
        }


        /**
        * Set Menus
        * 
        */
        public static function megaMenuType() {
            $name = self::getInstance()->theme() . '_menu';
            self::getInstance()->resetCookie($name);

            $require = JRequest::getVar('menu',  ''  , 'get');
            if( !empty( $require ) ){
                setcookie( $name, $require, time() + 3600, '/');
                $current = $require;
            } 
            elseif( empty( $require ) and  isset( $_COOKIE[$name] )) {
                $current = $_COOKIE[$name];
            } else {
                $current = self::getInstance()->Param('menutype');
            }

            return $current;
        }

        /**
        * Load Menu
        *
        * @since    1.0
        */
        public static function loadMegaMenu() {
            self::getInstance()->import('core/classes/menu.php');
            return new HelixMenu(self::getInstance(), self::getInstance()->Param(), self::getInstance()->megaMenuType());
        }


        /**
        * Load Menu
        *
        * @since    1.0
        */
        public static function loadMobileMenu() {
            self::getInstance()->import('core/classes/menu.php');
            return new HelixMenu(self::getInstance(), self::getInstance()->Param(), 'mobile');
        }

        /**
        * Add jQuery
        * 
        * @since    1.9.5
        * @param mixed $usecdn
        * @param mixed $forceLoad
        * @return self
        */
        public function addJQuery($usecdn=false, $forceLoad=false) {
            if (JVERSION>=3) {
                JHtml::_('jquery.framework');
                
            } else {
                $scripts = (array) array_keys( self::getInstance()->getDocument()->_scripts );
                $hasjquery=false;
                foreach($scripts as $script) {
                    if (preg_match("/\b(jquery|jquery-latest).([0-9\.min|max]+).(.js)\b/i", $script)) {
                        $hasjquery = true;
                    }  
                }

                if( $forceLoad ) $hasjquery=false;

                if( !$hasjquery ) {
                    if( $usecdn ) self::getInstance()->addJS( 'http://code.jquery.com/jquery-latest.min.js' );
                    else self::getInstance()->addJS( 'jquery.min.js' );                    
                }
            }
            self::getInstance()->addJS( 'jquery-noconflict.js' );
            return self::getInstance();
        }


        /**
        * Remove CSS
        * 
        * @param mixed $sources
        * @param mixed $seperator
        */
        public function removeJS($sources, $seperator=',') {

            $srcs = array();
            if( is_string($sources) ) $sources = explode($seperator,$sources);
            if(!is_array($sources)) $sources = array($sources);

            foreach( (array) $sources as $source ) $srcs[] = trim($source);

            $scripts = (array) array_keys( self::getInstance()->getDocument()->_scripts );
            $removedJS = array();
            foreach ($srcs as $src) {
                foreach($scripts as $script) {
                    if (preg_match("/\b".$src."\b/i", $script)) {
                        $removedJS[] = $script;
                        unset( self::getInstance()->getDocument()->_scripts[$script] );
                    }  
                }
            }
            return $removedJS;
        }


        public function removeCSS($sources, $seperator=',') {

            $srcs = array();
            if( is_string($sources) ) $sources = explode($seperator,$sources);
            if(!is_array($sources)) $sources = array($sources);

            foreach( (array) $sources as $source ) $srcs[] = trim($source);

            $scripts = (array) array_keys( self::getInstance()->getDocument()->_styleSheets );
            $removedCSS = array();
            foreach ($srcs as $src) {
                foreach($scripts as $script) {
                    if (preg_match("/\b".$src."\b/i", $script)) {
                        $removedCSS[] = $script;
                        unset( self::getInstance()->getDocument()->_styleSheets[$script] );
                    }  
                }
            }
            return $removedCSS;
        }





        // Add bootstrap	
        public function addBootstrap($responsive=true, $rtl=false) {


			// RTL enable
            if( self::getInstance()->direction()=='rtl' ) {
                self::getInstance()->addCSS('bootstrap.min.rtl.css');
				// BootStrap responsive layout rtl
                if (self::getInstance()->Param('layout_type')=='responsive') self::getInstance()->addCSS('bootstrap-responsive.min.rtl.css');
            } else {
                self::getInstance()->addCSS('bootstrap.min.css');
				// BootStrap responsive layout normal
                if (self::getInstance()->Param('layout_type')=='responsive') self::getInstance()->addCSS('bootstrap-responsive.min.css');
            }

            self::getInstance()->addCSS('font-awesome.css');
            self::getInstance()->addJQuery();
            
            if(JVERSION < 3)
            {
                self::getInstance()->addJS('bootstrap.min.js');
            }
            else
            {
                JHtml::_('bootstrap.framework');
            }

            return self::getInstance();
        }

        /**
        * 
        *
        */
        public static function selectivizr(){
            if(self::getInstance()->isIE(8))
                self::getInstance()->addJS('selectivizr-min.js');    
            return self::getInstance();    
        }

        public static function respondJS(){
            if(self::getInstance()->isIE(8))
                self::getInstance()->addJS('respond.min.js');	
            return self::getInstance();	
        }

        /**
        * Add Google Fonts
        * 
        * @param string $name. Name of font. Ex: Yanone+Kaffeesatz:400,700,300,200 or Yanone+Kaffeesatz  or Yanone Kaffeesatz
        * @param string $field. Applied selector. Ex: h1, h2, #id, .classname
        */
        public static function GoogleFont($name, $field) {

            $name = str_replace(' ', '+', $name ); 

            $font_name = explode(':', $name);
            if( is_array($font_name) ) $font_name = str_replace('+', ' ', $font_name[0] );
            else $font_name = str_replace('+', ' ', $name );

            self::getInstance()->document->addStyleSheet("http://fonts.googleapis.com/css?family=" . $name);
            $styleDeclaration = "$field{font-family:'" . $font_name . "';}";
            self::getInstance()->document->addStyleDeclaration($styleDeclaration);
        }    

        //////////
        public static function Compression() {//compress css and js files
            if (self::getInstance()->Param('compress_css')) self::getInstance()->compressCSS();
            if (self::getInstance()->Param('compress_js')) self::getInstance()->compressJS(); 
            return self::getInstance();       
        }

        private static function compressJS() {//function to compress js files

            self::getInstance()->Import('core/classes/jsmin.php');

            $js_files = array();
            $cache_time = self::getInstance()->Param('cache_time');//Cache time in minute
            $diff=false;
            $helix_folder='helix_assets';//path of cache where to save
            $output = array();
            $md5sum = null;

            $scripts = self::getInstance()->getDocument('_scripts');//get all scripts

            foreach ($scripts as $fileSrc => $fileAttr) {//separate path from attribute
                $md5sum .= md5($fileSrc);
                $js_files[] = $fileSrc;
            }

            if (!is_writable(JPATH_CACHE)) {//check for cache path writable, if not return
                return;
            } 

            if (is_writable(JPATH_CACHE)) {//add helix_assets folder under cache directory
                if (!file_exists(JPATH_CACHE.DIRECTORY_SEPARATOR.$helix_folder)) mkdir (JPATH_CACHE.DIRECTORY_SEPARATOR.$helix_folder);
            }

            if (count($js_files) > 0) {//if any js file available
                $cache_name = md5($md5sum) . ".js";
                $cache_path = JPATH_CACHE . DIRECTORY_SEPARATOR . $helix_folder . DIRECTORY_SEPARATOR . $cache_name;


                //see if file is stale
                if (!file_exists($cache_path)) {
                    $diff=true;   
                } elseif(filesize($cache_path) == 0 || ((filemtime($cache_path) + $cache_time * 60) < time())) {
                    $diff=true; 
                }

                foreach ($js_files as $files) {
                    $external = self::getInstance()->isExternalURL($files);
                    if( $external ) continue;
                    unset(self::getInstance()->getDocument()->_scripts[$files]); //Remove js files from the header
                }

                if ($diff) {
                    $output = '';
                    foreach ($js_files as $files) {

                        $external = self::getInstance()->isExternalURL($files);
                        if( $external ) continue;

                        $filepath = self::getInstance()->realPath($files);
                        if (JFile::exists($filepath)) {
                            $js = JSMin::minify(JFile::read($filepath));//read and compress js files
                            $output .= "/*------ " . $files . " ------*/\n" . $js . "\n\n";//add file name to compressed JS
                        }
                    }
                    JFile::write($cache_path, $output);//write cache to the joomla cache directory
                }

                $cache_url =  self::getInstance()->baseURL() . "/cache/" . $helix_folder . '/' . $cache_name;//path of css cache to add as script

                self::getInstance()->document->addScript( $cache_url ); //add script to the header
            }
        }

        private static function compressCSS() {//function to compress css files

            self::getInstance()->Import('core/classes/cssmin.php');

            $css_files = array();
            $cache_time = self::getInstance()->Param('cache_time');//Cache time in minute
            $helix_folder='helix_assets';//path of cache where to save
            $output = array();
            $md5sum = null;

            $csss = self::getInstance()->getDocument('_styleSheets');//get all css

            foreach ($csss as $fileSrc => $fileAttr) {//separate path from attribute
                $md5sum .= md5($fileSrc);
                $css_files[] = $fileSrc;
            }

            if (!is_writable(JPATH_CACHE)) {//check for cache path writable, if not return
                return;
            } 

            if (is_writable(JPATH_CACHE)) {//add helix_assets folder under cache directory
                if (!file_exists(JPATH_CACHE.DIRECTORY_SEPARATOR.$helix_folder)) mkdir (JPATH_CACHE.DIRECTORY_SEPARATOR.$helix_folder);
            }

            if (count($css_files) > 0) {//if any css file available
                $cache_name = md5($md5sum) . ".css";
                $cache_path = JPATH_CACHE . DIRECTORY_SEPARATOR . $helix_folder . DIRECTORY_SEPARATOR . $cache_name;
                $diff=false;

                //see if file is stale
                if (!file_exists($cache_path)) {
                    $diff=true;   
                } elseif(filesize($cache_path) == 0 || ((filemtime($cache_path) + $cache_time * 60) < time())) {
                    $diff=true; 
                }

                foreach ($css_files as $files) {

                    $external = self::getInstance()->isExternalURL($files);
                    if( $external ) continue;

                    unset(self::getInstance()->getDocument()->_styleSheets[$files]); //Remove all css files from the header
                }

                if ($diff) {
                    $output = '';
                    foreach ($css_files as $files) {

                        $external = self::getInstance()->isExternalURL($files);
                        if( $external ) continue;

                        $filepath = self::getInstance()->realPath($files);//convert to real url

                        global $absolute_url;
                        $absolute_url = $files;//absoulte path of each css file
                        if (JFile::exists($filepath)) {
                            $css = CSSMinify::process(JFile::read($filepath));//read and compress css files

                            $css=preg_replace_callback('/url\(([^\)]*)\)/', array(self::getInstance(), 'replaceUrl'), $css);//call replaceUrl function to set absolute value to the urls

                            $output .= "/*------ " . $files . " ------*/\n" . $css . "\n\n";//add file name to compressed css
                        }
                    }
                    JFile::write($cache_path, $output);//write cache to the joomla cache directory
                }

                $cache_url = self::getInstance()->baseURL() . "/cache/" . $helix_folder . '/' . $cache_name;//path of css cache to add as stylesheet

                self::getInstance()->document->addStyleSheet( $cache_url ); //add stylesheet to the header
            }
        }

        private static function replaceUrl($matches) {//replace url with absolute path
            $url = str_replace(array('"', '\''), '', $matches[1]);
            $url = self::getInstance()->fixUrl($url);
            return "url('$url')";
        }

        private static function fixUrl($url) {
            global $absolute_url;
            $base = dirname($absolute_url);
            if (preg_match('/^(\/|http)/', $url))
                return $url;
            /*absolute or root*/
            while (preg_match('/^\.\.\//', $url)) {
                $base = dirname($base);
                $url = substr($url, 3);
            }

            $url = $base . '/' . $url;
            return $url;
        }    

        private static function realPath($strSrc) { //Real path of css or js file
            if (preg_match('/^https?\:/', $strSrc)) {
                if (!preg_match('#^' . preg_quote(JURI::base()) . '#', $strSrc)) return false; //external css
                $strSrc = str_replace(JURI::base(), '', $strSrc);
            } else {
                if (preg_match('/^\//', $strSrc)) {
                    if (!preg_match('#^' . preg_quote(JURI::base(true)) . '#', $strSrc)) return false; //same server, but outsite website
                    $strSrc = preg_replace('#^' . preg_quote(JURI::base(true)) . '#', '', $strSrc);
                }
            }
            $strSrc = str_replace('//', '/', $strSrc);
            $strSrc = preg_replace('/^\//', '', $strSrc);
            return $strSrc;
        }    

        public static function loadHelixOverwrite(){

             if (!JFactory::getApplication()->isAdmin()) {

                if( JVERSION >= 3 ){
                    
                    // override core joomla 3 class
                    if (!class_exists('JViewLegacy', false))  self::getInstance()->Import('core/classes/joomla30/viewlegacy.php');
                    if (!class_exists('JModuleHelper', false)) self::getInstance()->Import('core/classes/joomla30/helper.php'); 

                } else {
                    // override core joomla 2.5 class
                    if (!class_exists('JHtmlBehavior', false)) self::getInstance()->Import('core/classes/joomla25/behavior.php'); 
                    if (!class_exists('JViewLegacy', false)) self::getInstance()->Import('core/classes/joomla25/view.php'); 
                    if (!class_exists('JDocumentRendererMessage', false)) self::getInstance()->Import('core/classes/joomla25/message.php'); 
                    if (!class_exists('JModuleHelper', false)) self::getInstance()->Import('core/classes/joomla25/helper.php');
                    if (!class_exists('JHtmlBootstrap', false)) Helix::Import('core/classes/joomla30/bootstrap.php');
                } 
            }

            return self::getInstance();

        }

        /**
        * Detect IE Version
        *
        * @since	1.0
        */
        public static function isIE($version = false) {  
            $agent=$_SERVER['HTTP_USER_AGENT'];  
            $found = strpos($agent,'MSIE ');  
            if ($found) { 
                if ($version) {
                    $ieversion = substr(substr($agent,$found+5),0,1);   
                    if ($ieversion == $version) return true;
                    else return false;
                } else {
                    return true;
                }

            } else {
                return false;
            }
            if (stristr($agent, 'msie'.$ieversion)) return true;
            return false;        
        }
    }