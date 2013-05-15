
<?php
/**
 * @version		$Id: default.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>

<section class="featured <?php echo strtolower($this->pageclass_sfx);?>">

	<?php if ( $this->params->get('show_page_heading')!=0) : ?>
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	<?php endif; ?>
	
	<?php //Start Leading ?>
	<?php $leadingcount = 0; ?>
	<?php if (!empty($this->lead_items)) : ?>
	
		<section class="items-leading">
			<?php foreach ($this->lead_items as &$item) : ?>
				<div class="leading-<?php echo $leadingcount+1; ?>">
					<?php
						$this->item = &$item;
						echo $this->loadTemplate('item');
					?>
				</div>
				<div class="clearfix"></div>
				<?php
					$leadingcount++;
				?>
			<?php endforeach; ?>
		</section>
		
		<div class="clearfix"></div>
	<?php endif; ?>
	<?php //end Leading ?>

	
	<?php //Start Itro Item ?>
	<?php
		$introcount = (count($this->intro_items));
		$counter = 0;
	?>
	<?php if (!empty($this->intro_items)) : ?>
		<?php foreach ($this->intro_items as $key => &$item) : ?>

			<?php
			$key = ($key - $leadingcount) + 1;
			$rowcount = (((int) $key - 1) % (int) $this->columns) + 1;
			$row = $counter / $this->columns;
				if ($rowcount == 1) : 
			?>

			<section class="items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row; ?> row-fluid">
				<?php endif; ?>
					<div class="item column-<?php echo $rowcount;?> span<?php echo round((12 / $this->columns));?>">
					<?php
						$this->item = &$item;
						echo $this->loadTemplate('item');
					?>
					</div>
					<?php $counter++; ?>

				<?php if (($rowcount == $this->columns) or ($counter == $introcount)): ?>
			</section>
				<?php endif; ?>

		<?php endforeach; ?>
	<?php endif; ?>
	<?php //End Intro Items ?>

	<?php //Start Item Links ?>	
	<?php if (!empty($this->link_items)) : ?>
		<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>
	<?php //End Item Links ?>

	<?php //Start pagination ?>	
	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<section class="pagination">
			<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
				<p class="counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</p>
			<?php endif; ?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</section>
	<?php endif; ?>
	<?php //End Pagination ?>
</section>