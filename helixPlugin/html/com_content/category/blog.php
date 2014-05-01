<?php
/**
 * @version		$Id: blog.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

//JHtml::_('behavior.caption');

?>

<section class="blog <?php echo $this->pageclass_sfx;?>">
	<?php if ($this->params->get('show_page_heading', 0)) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>

	<?php 
    /**
    * If show page heading we use h2 for page sub heading
    */
    if (($this->params->get('show_category_title', 1) OR $this->params->get('page_subheading')) and $this->params->get('show_page_heading', 0) ) : ?>
	<h2>
		<?php echo $this->escape($this->params->get('page_subheading')); ?>
		<?php if ($this->params->get('show_category_title')) : ?>
			<?php echo $this->category->title;?>
		<?php endif; ?>
	</h2>
	<?php endif; ?>
    
    
	<?php
	 /**
	* If hide page heading we use h1 for page sub heading
	*/
	 if (($this->params->get('show_category_title', 1) OR $this->params->get('page_subheading')) and !$this->params->get('show_page_heading', 0) ) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_subheading')); ?>
		<?php if ($this->params->get('show_category_title')) : ?>
			<?php echo $this->category->title;?>
		<?php endif; ?>
	</h1>
	<?php endif; ?>
    
	<?php //Category description ?>
	<?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
		<section class="category-desc">
		<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
			<img src="<?php echo $this->category->getParams()->get('image'); ?>" alt="<?php echo $this->category->title;?>" />
		<?php endif; ?>
		<?php if ($this->params->get('show_description') && $this->category->description) : ?>
			<?php echo JHtml::_('content.prepare', $this->category->description); ?>
		<?php endif; ?>
		</section>
	<?php endif; ?>
	<?php //End category description ?>

	<?php if (empty($this->lead_items) && empty($this->link_items) && empty($this->intro_items)) : ?>
		<?php if ($this->params->get('show_no_articles', 1)) : ?>
			<p><?php echo JText::_('COM_CONTENT_NO_ARTICLES'); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php $leadingcount = 0; ?>
	<?php if (!empty($this->lead_items)) : ?>
	<div class="items-leading clearfix">
		<?php foreach ($this->lead_items as &$item) : ?>
		<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
			<?php
				$this->item = &$item;
				echo $this->loadTemplate('item');
			?>
		</div>
		<?php $leadingcount++; ?>
		<?php endforeach; ?>
	</div><!-- end items-leading -->
	<?php endif; ?>

	<?php
	$introcount = (count($this->intro_items));
	$counter = 0;
	?>

	<?php if (!empty($this->intro_items)) : ?>
	<?php foreach ($this->intro_items as $key => &$item) : ?>

		<?php 
			if(version_compare(JVERSION, '3.2.0', 'ge')) {
				$rowcount = ((int) $key % (int) $this->columns) + 1;
			} else {			
				$key= ($key-$leadingcount)+1;
				$rowcount=( ((int)$key-1) %	(int) $this->columns) +1;
				$row = $counter / $this->columns ;
			}
		?>
		<?php if ($rowcount == 1) : ?>
		<?php 
			if(version_compare(JVERSION, '3.2.0', 'ge')) {
				$row = $counter / $this->columns;
			} 
		?>
		
		<div class="items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row; ?> row-fluid clearfix">
		<?php endif; ?>
			<div class="span<?php echo round((12 / $this->columns));?>">
				<div class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
					<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
				?>
				</div><!-- end item -->
				<?php $counter++; ?>
			</div><!-- end span -->
			<?php if (($rowcount == $this->columns) or ($counter == $introcount)) : ?>
		</div><!-- end row -->
			<?php endif; ?>
	<?php endforeach; ?>
	<?php endif; ?>

	<?php if (!empty($this->link_items)) : ?>
	<div class="items-more">
	<?php echo $this->loadTemplate('links'); ?>
	</div>
	<?php endif; ?>

	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
	<div class="cat-children">
	<?php if ($this->params->get('show_category_heading_title_text', 1) == 1) : ?>
		<h3> <?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?> </h3>
	<?php endif; ?>
		<?php echo $this->loadTemplate('children'); ?> </div>
	<?php endif; ?>
	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	<div class="pagination">
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php  endif; ?>
</section>