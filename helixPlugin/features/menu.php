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
		
		if ($this->helix->megaMenuType()=='drop') {
			$this->helix->addCSS('dropline.css');
			$this->helix->addJS('dropline.js');
		} elseif($this->helix->megaMenuType()=='split') {
			$this->helix->addCSS('dropline.css');
		} else {
			$this->helix->addJS('menu.js');
		}
		
		$this->helix->addCSS('mobile-menu.css');
		
	}

	public function onFooter()
	{

		ob_start();
		?>	

		<a class="hidden-desktop btn btn-inverse sp-main-menu-toggler" href="#" data-toggle="collapse" data-target=".nav-collapse">
			<i class="icon-align-justify"></i>
		</a>

		<div class="hidden-desktop sp-mobile-menu nav-collapse collapse">
			<?php
			$mobilemenu = $this->helix->loadMobileMenu();
			echo $mobilemenu->showMenu(); 
			?>   
		</div>
		<?php
		return ob_get_clean();
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


			<div id="sp-main-menu" class="visible-desktop">
				<?php echo $menu->showMenu(); ?>        
			</div>  				
			<?php

			if (($this->helix->megaMenuType()=='split') && $menu->hasSub() || $this->helix->megaMenuType()=='drop') {
				if($this->helix->megaMenuType()=='drop'){
					$newclass = 'dropline empty ';
				} else{
					$newclass = 'split ';
				}

				echo '<div id="sublevel" class="' . $newclass . 'visible-desktop"><div class="container">';
				$menu->showMenu(1);
				echo '</div></div>';            
			}

			if (($this->helix->megaMenuType()=='split' && $menu->hasSub()) || $this->helix->megaMenuType()=='drop') {
				$sublevel=1;
			} else {
				$sublevel=0;
			}

			if (($this->helix->megaMenuType()=='split') && $menu->hasSub() || $this->helix->megaMenuType()=='drop') {  

				if($this->helix->megaMenuType()=='drop') {

					$this->helix->addInlineJS("spnoConflict(function($){

						function mainmenu() {
							$('#sp-main-menu').droplinemenu({
								sublevelContainer:$('#sublevel > div')
							});
				}

				mainmenu();

				$(window).on('resize',function(){
					mainmenu();
				});



				});");
				}



			} else {

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


			});");


			}




			return ob_get_clean();
		}
	}    
}