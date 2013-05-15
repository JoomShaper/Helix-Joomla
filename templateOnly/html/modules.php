<?php
	/*---------------------------------------------------------------
	# Package - Joomla Template based on Helix Framework   
	# ---------------------------------------------------------------
	# Author - JoomShaper http://www.joomshaper.com
	# Copyright (C) 2010 - 2012 JoomShaper.com. All Rights Reserved.
	# license - PHP files are licensed under  GNU/GPL V2
	# license - CSS  - JS - IMAGE files  are Copyrighted material 
	# Websites: http://www.joomshaper.com
	-----------------------------------------------------------------*/
	//no direct accees
	defined ('_JEXEC') or die ('resticted aceess');

	$modChromes = array('sp_xhtml', 'sp_flat', 'sp_raw', 'sp_menu', 'none'  );

	function modChrome_sp_xhtml($module, $params, $attribs)
	{ ?>
	<div class="module <?php echo $params->get('moduleclass_sfx'); ?>">	
		<div class="mod-wrapper clearfix">		
			<?php if ($module->showtitle != 0) { ?>
				<h3 class="header">			
					<?php 
						echo '<span>'.$module->title.'</span>';
					?>
				</h3>
				<?php
					$modsfx=$params->get('moduleclass_sfx');
					if ($modsfx !='') echo '<span class="sp-badge ' . $modsfx . '"></span>';
				?>
				<?php } ?>
			<div class="mod-content clearfix">	
				<div class="mod-inner clearfix">
					<?php echo $module->content; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="gap"></div>
	<?php
	}

	function modChrome_sp_flat($module, $params, $attribs)
	{ ?>
	<div class="module <?php echo $params->get('moduleclass_sfx'); ?>">	
		<div class="mod-wrapper-flat clearfix">		
			<?php if ($module->showtitle != 0) { ?>
				<h3 class="header">			
					<?php 
						echo '<span>'.$module->title.'</span>';
					?>
				</h3>
				<?php
					$modsfx=$params->get('moduleclass_sfx');
					if ($modsfx !='') echo '<span class="sp-badge ' . $modsfx . '"></span>';
				?>
				<?php } ?>
			<?php echo $module->content; ?>
		</div>
	</div>
	<div class="gap"></div>
	<?php
	}

	function modChrome_sp_raw($module, $params, $attribs)
	{ 
		echo $module->content; 
	}

	function modChrome_sp_menu($module, $params, $attribs)
	{ ?>
	<div class="module <?php echo $params->get('moduleclass_sfx'); ?>">	
		<div class="mod-wrapper-menu clearfix">
			<?php echo $module->content; ?>
		</div>
	</div>
	<?php
}