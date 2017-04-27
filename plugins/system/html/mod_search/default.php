<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_search
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div class="search pull-right<?php echo $moduleclass_sfx; if ($button) echo ' input-append' ?> ">
    <form action="<?php echo JRoute::_('index.php');?>" method="post" class="form-inline">
    		<?php
				$output = '<input name="searchword" id="mod-search-searchword" type="text" value="' . $text . '"  onblur="if (this.value==\'\') this.value=\'' . $text . '\';" onfocus="if (this.value==\'' . $text . '\') this.value=\'\';" />';

				$button_text = $params->get('button_text');
				
				if ($button) :
					if ($imagebutton) :
						$button = '<input type="image" value="' . $button_text . '" class="button' . $moduleclass_sfx.'" src="' . $img . '" onclick="this.form.searchword.focus();"/>';
					else :
						$button = '<button class="button btn btn-primary" onclick="this.form.searchword.focus();"><i class="icon-search"></i>' . $button_text . '</button>';
					endif;
				endif;

				echo $output;
			?>
    	<input type="hidden" name="task" value="search" />
    	<input type="hidden" name="option" value="com_search" />
    	<input type="hidden" name="Itemid" value="0" />
		<?php echo $button; ?>
    </form>
</div>
