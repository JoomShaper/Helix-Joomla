<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

class HelixFeatureMenu {

	private $helix;

	public function __construct($helix){
		$this->helix = $helix;
	}

	public function onHeader()
	{
		$this->helix->addCSS('menu.css');
		//if ($this->helix->megaMenuType()=='drop') {
			//$this->helix->addInlineCSS('#sublevel ul.level-1 {display:none}');
		   // $this->helix->addJS('dropline.js');
		//}
		$this->helix->addJS('menu.js');
	}

	public function onFooter()
	{

	}


	public function Position()
	{
		return 'menu';
	}


	public function onPosition()
	{        

		$menu = $this->helix->loadMegaMenu();

		if ($menu) {

			ob_start();
				?>	
					<div class="mobile-menu pull-right btn hidden-desktop" id="sp-moble-menu">
						<i class="icon-align-justify"></i>
					</div>
				
					<div id="sp-main-menu" class="visible-desktop">
						<?php echo $menu->showMenu(); ?>        
					</div>  				
				<?php

			if (($this->helix->megaMenuType()=='split') && $menu->hasSub() || $this->helix->megaMenuType()=='drop') {    
				echo '<div id="split" class="visible-desktop">';
					$menu->showMenu(1);
				echo '</div>';            
			}

			if (($this->helix->megaMenuType()=='split' && $menu->hasSub()) || $this->helix->megaMenuType()=='drop') {
				$sublevel=1;
			} else {
				$sublevel=0;
			}
                        
            $this->helix->addInlineJS("spnoConflict(function($){
            	
                        function mainmenu() {
                            $('.sp-menu').spmenu({
                                startLevel: 0,
                                direction: '" . $this->helix->direction() . "',
                                initOffset: {
                                    x: ".$this->helix->Param('init_x',0).",
                                    y: ".$this->helix->Param('init_y',0)."
                                },
                                subOffset: {
                                    x: ".$this->helix->Param('sub_x',0).",
                                    y: ".$this->helix->Param('sub_y',0)."
                                },
                                center: ".$this->helix->Param('submenu_position',0)."
                            });
                        }
						
						mainmenu();
                        
                        $(window).on('resize',function(){
								mainmenu();
                        });
                        
                        //Mobile Menu
                        $('#sp-main-menu > ul').mobileMenu({
                            defaultText:'".JText::_('NAVIGATE')."',
                            appendTo: '#sp-moble-menu'
                        });
                        
                    });");
            

			return ob_get_clean();
		}
	}    
}