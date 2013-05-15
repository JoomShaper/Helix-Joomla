<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (count($this->children[$this->category->id]) > 0 && $this->maxLevel != 0) : ?>

	<section class="cat-children">
		<h3 class="item-title"><?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?></h3>
		
		<ul>
			<?php foreach($this->children[$this->category->id] as $id => $child) : ?>
				<?php
				if ($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) :
				?>
				<li>
					<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id));?>">
						<?php echo $this->escape($child->title); ?>
					</a>
					<?php if ( $this->params->get('show_cat_num_articles', 1)) : ?>
						<span class="muted small" title="<?php echo JText::_('COM_CONTENT_NUM_ITEMS'); ?>">
							(<?php echo $child->getNumItems(true); ?>)
						</span>
					<?php endif; ?>
				</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		
	</section>
	
<?php endif;
