<?php
/**
 * @version		$Id: default.php 20817 2011-02-21 21:48:16Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
$params		= $this->item->params;
$images 	= json_decode($this->item->images);
$urls 		= json_decode($this->item->urls);
$canEdit	= $this->item->params->get('access-edit');
$info    	= $params->get('info_block_position', 0);
$user		= JFactory::getUser();
$useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author'));
?>

<article class="item-page post-<?php echo $this->item->id ?> post hentry <?php echo ($this->item->state == 0)?'status-unpublish alert':'status-publish'; echo ' category-'.$this->escape($this->item->category_alias) . ' ' . strtolower($this->pageclass_sfx) ?>">
 
	<?php
		if (!empty($this->item->pagination) AND $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
		{
			echo $this->item->pagination;
		}
	 ?>
 
	<?php //Start Article Header ?> 
	<?php 
		if ($params->get('show_title')
		|| ($this->params->get('show_page_heading', 0))
		|| $params->get('access-edit')) :
	?>
	<header class="entry-header">
		<?php if($this->params->get('show_page_heading', 0) && ($params->get('show_title'))) : ?>
			<hgroup class="page-header">
				<h1 class="entry-title">
					<?php echo $this->escape($this->params->get('page_heading')); ?>
				</h1>
				<h2>
				<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
					<a href="<?php echo $this->item->readmore_link; ?>">
				<?php echo $this->escape($this->item->title); ?></a>
				<?php else : ?>
					<?php echo $this->escape($this->item->title); ?>
				<?php endif; ?>
				</h2>
			</hgroup>
		
		<?php elseif($this->params->get('show_page_heading', 0) && (!$params->get('show_title'))) : ?>
			<h1 class="entry-title page-header"><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		
		<?php elseif(!$this->params->get('show_page_heading', 0) && ($params->get('show_title'))) : ?>
			<h1 class="entry-title page-header">
				<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
					<a href="<?php echo $this->item->readmore_link; ?>">
				<?php echo $this->escape($this->item->title); ?></a>
			<?php else : ?>
				<?php echo $this->escape($this->item->title); ?>
			<?php endif; ?>
			</h1>
		<?php endif; ?>	
	</header>
	<?php endif; ?>
	<?php //End Article Header ?>
	
	<?php echo $this->item->event->beforeDisplayContent; ?>

	<?php 
	if ( $useDefList || $canEdit): 
	?>
	
	<div class="entry-meta muted clearfix">
		<?php //Start Category ?>
		<?php if ($params->get('show_parent_category') && $this->item->parent_id !=1) : ?>
			<span class="parent-category-name">
				<?php	
					$title = $this->escape($this->item->parent_title);
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';
				?>
				<?php if ($params->get('link_parent_category') and $this->item->parent_slug) : ?>
				<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
				<?php endif; ?>
			</span>
		<?php endif; ?>
		
		<?php if ($params->get('show_category')) : ?>
			<span class="category-name">
				<?php 	
					$title = $this->escape($this->item->category_title);
					$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';
				?>
				<?php if ($params->get('link_category') and $this->item->catslug) : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
				<?php endif; ?>
			</span>
		<?php endif; ?>	
		<?php //End Category ?>
		
		<?php //Start Create Date ?>
		<?php if ($params->get('show_create_date')) : ?>
			<time class="create-date" datetime="<?php echo $this->item->created; ?>">
				<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date',$this->item->created, JText::_('DATE_FORMAT_LC2'))); ?>
			</time>
		<?php endif; ?>
		<?php //End Create Date ?>
	
		<?php //Start Publish Date ?>
		<?php if ($params->get('show_publish_date')) : ?>
			<time class="publish-date" datetime="<?php echo $this->item->publish_up; ?>" pubdate="pubdate">
				<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date',$this->item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
			</time>
		<?php endif; ?>	
		<?php //End Publish Date ?>
		
		<?php //Start Author Meta ?>
		<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
			<span class="by-author"> 
				<?php $author =  $this->item->author; ?>
				<?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>
				<?php
				
					if (!empty($this->item->contactid ) &&  $params->get('link_author') == true):
						$authorurl = JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid);
				
						$written_by = '<span class="author vcard"><a class="url fn n" href="' . $authorurl . '">' . $author . '</a></span>';
					else :
						$written_by = '<span class="author vcard"><span class="fn n">' . $author . '</span></span>';
					endif;
					 
					echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $written_by);
				?>
			</span>
		<?php endif; ?>
		<?php //End Author Meta?>
		
		<?php //Start Hits ?>
		<?php if ($params->get('show_hits')) : ?>
			<span class="hits">
				<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
			</span>
		<?php endif; ?>	
		<?php //End Hits ?>
		
		<?php //Start Print and Email ?>
		<?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
			<ul class="unstyled actions">
				<?php if (!$this->print) : ?>
				<?php if ($params->get('show_print_icon')) : ?>
				<li class="print-icon"> <?php echo JHtml::_('icon.print_popup',  $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($params->get('show_email_icon')) : ?>
				<li class="email-icon"> <?php echo JHtml::_('icon.email',  $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
				<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
				<?php endif; ?>
				<?php else : ?>
				<li> <?php echo JHtml::_('icon.print_screen',  $this->item, $params); ?> </li>
				<?php endif; ?>
			</ul>
		<?php endif; ?> 		
		<?php //Start Print and Email ?>
	</div>
	<?php endif; ?>
	
	<section class="entry-content"> 
		<?php //Start Intro Text ?>
		<?php
			if (!$params->get('show_intro')) :
				echo $this->item->event->afterDisplayTitle;
			endif;
		?>
		<?php if (isset ($this->item->toc)) : ?>
			<?php echo $this->item->toc; ?>
		<?php endif; ?>
		<?php if (isset($urls) AND ((!empty($urls->urls_position) AND ($urls->urls_position=='0')) OR  ($params->get('urls_position')=='0' AND empty($urls->urls_position) ))
			OR (empty($urls->urls_position) AND (!$params->get('urls_position')))): ?>
			<?php echo $this->loadTemplate('links'); ?>
		<?php endif; ?>
		<?php if ($params->get('access-view')):?>
		<?php  if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : ?>
		<?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
		<div class="pull-<?php echo htmlspecialchars($imgfloat); ?>">
		<img class="fulltext-image"
			<?php if ($images->image_fulltext_caption):
				echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) .'"';
			endif; ?>
			src="<?php echo htmlspecialchars($images->image_fulltext); ?>" alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>"/>
		</div>
		<?php endif; ?>
		<?php
		if (!empty($this->item->pagination) AND $this->item->pagination AND !$this->item->paginationposition AND !$this->item->paginationrelative):
			echo $this->item->pagination;
		 endif;
		?>
		<?php echo $this->item->text; ?>
		<?php if (isset($urls) AND ((!empty($urls->urls_position)  AND ($urls->urls_position=='1')) OR ( $params->get('urls_position')=='1') )): ?>
		<?php echo $this->loadTemplate('links'); ?>
		<?php endif; ?>
		<?php
		if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND!$this->item->paginationrelative):
			 echo $this->item->pagination;?>
		<?php endif; ?>
		<?php //optional teaser intro text for guests ?>
		<?php elseif ($params->get('show_noauth') == true and  $user->get('guest') ) : ?>
		<?php echo $this->item->introtext; ?>
		<?php //End Intro Text ?>
	</section>
    
    <footer class="entry-meta">
		<?php //Optional link to let them register to see the whole article. ?>
			<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
				$link1 = JRoute::_('index.php?option=com_users&view=login');
				$link = new JURI($link1);?>
				<p class="readmore">
				<a href="<?php echo $link; ?>">
				<?php $attribs = json_decode($this->item->attribs);  ?>
				<?php
				if ($attribs->alternative_readmore == null) :
					echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
				elseif ($readmore = $this->item->alternative_readmore) :
					echo $readmore;
					if ($params->get('show_readmore_title', 0) != 0) :
						echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
					endif;
				elseif ($params->get('show_readmore_title', 0) == 0) :
					echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
				else :
					echo JText::_('COM_CONTENT_READ_MORE');
					echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
				endif; ?></a>
				</p>
			<?php endif; ?>
		<?php endif; ?>

		<?php //Start Item Prev-Next ?>
		<?php
		if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND $this->item->paginationrelative):
			 echo $this->item->pagination;?>
		<?php endif; ?>	
		<?php //End Item Prev-Next ?>
		
		<?php echo $this->item->event->afterDisplayContent; ?>
		
		<?php //Start Modify Date ?>
		<?php if ($params->get('show_modify_date')) : ?>
			<time class="modify-date muted" datetime="<?php echo $this->item->modified; ?>">
				<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHTML::_('date',$this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
			</time>
		<?php endif; ?>
		<?php //End Modify Date ?>
		
		
		<?php if(version_compare(JVERSION, '3.1.0', 'ge')): //Tag feature ?>
		<?php if ($params->get('show_tags', 1) && !empty($this->item->tags)) : ?>
			<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
			<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
		<?php endif; ?>
		<?php endif; ?>	
		
    </footer>
</article>