<?php
    /**
    * @version		$Id: blog_item.php 20488 2011-01-30 18:56:00Z dextercowley $
    * @package		Joomla.Site
    * @subpackage	com_content
    * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
    * @license		GNU General Public License version 2 or later; see LICENSE.txt
    */

    // no direct access
    defined('_JEXEC') or die;

    // Create a shortcut for params.
    $params 	= &$this->item->params;
	$images 	= json_decode($this->item->images);
	$canEdit 	= $this->item->params->get('access-edit');
	JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
	$parentslug = '';
	$catslug = '';
	
?>
<article class="post-<?php echo $this->item->id ?> post hentry <?php echo ($this->item->state == 0)?'status-unpublish alert':'status-publish'; echo ' category-'.$this->escape($this->item->category_alias) ?>">

    <?php if ($params->get('show_title')) : ?>
        <header class="entry-header">
            <h2 class="entry-title">
                <?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
                    <a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>" rel="bookmark" title="<?php echo $this->escape($this->item->title); ?>">
                    <?php echo $this->escape($this->item->title); ?></a>
                    <?php else : ?>
                    <?php echo $this->escape($this->item->title); ?>
                    <?php endif; ?>
            </h2>
        </header>
    <?php endif; ?>

    <?php echo $this->item->event->beforeDisplayContent; ?>

    <?php 
        if ($params->get('show_create_date') 
            || ($params->get('show_publish_date'))
            || ($params->get('show_author') && !empty($this->item->author ))
            || ($params->get('show_category')) 
            || ($params->get('show_parent_category'))	
            || ($params->get('show_print_icon')) 
            || ($params->get('show_email_icon'))
            || ($params->get('show_hits')) 
            || $canEdit): 
        ?>
        <div class="entry-meta muted clearfix">

            <?php if ($params->get('show_create_date')) : ?>
                <time class="create-date" datetime="<?php echo $this->item->created; ?>">
                    <?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date',$this->item->created, JText::_('DATE_FORMAT_LC2'))); ?>
                </time>
                <?php endif; ?>

            <?php if ($params->get('show_publish_date')) : ?>
                <time class="publish-date" datetime="<?php echo $this->item->publish_up; ?>" pubdate="pubdate">
                    <?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date',$this->item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
                </time>
                <?php endif; ?>	

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
			
			<?php $parentslug = (JVERSION<3) ? $this->item->parent_id : $this->item->parent_slug; ?>	
			<?php if ($params->get('show_parent_category') && !empty($parentslug) && $this->item->parent_id !=1) : ?>
				<span class="parent-category-name">
					<?php $title = $this->escape($this->item->parent_title);
						$url = '<a rel="category" title="'. $title .'" href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($parentslug)).'">' . $title . '</a>'; ?>
					<?php if ($params->get('link_parent_category') and !empty($parentslug)) : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
					<?php else : ?>
						<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
					<?php endif; ?>
				</span>
			<?php endif; ?>

			<?php if ($params->get('show_category')) : ?>
				<?php $catslug = (JVERSION<3) ? $this->item->catid : $this->item->catslug;?>
				<span class="category-name">
					<?php $title = $this->escape($this->item->category_title);
						$url = '<a rel="category" title="'. $title .'" href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($catslug)) . '">' . $title . '</a>'; ?>
					<?php if ($params->get('link_category') and $catslug) : ?>
						<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
					<?php else : ?>
						<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
					<?php endif; ?>
				</span>
			<?php endif; ?>

            <?php if ($params->get('show_hits')) : ?>
                <span class="hits">
                    <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
                </span>
            <?php endif; ?>

            <?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
				<ul class="unstyled actions">
					<?php if ($params->get('show_print_icon')) : ?>
						<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
						<?php endif; ?>
					<?php if ($params->get('show_email_icon')) : ?>
						<li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
						<?php endif; ?>
					<?php if ($canEdit) : ?>
						<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
					<?php endif; ?>
				</ul>
            <?php endif; ?>

        </div>
    <?php endif; ?>
        
    <section class="entry-content">  

		<?php if (!$params->get('show_intro')) : ?>
			<?php echo $this->item->event->afterDisplayTitle; ?>
			<?php endif; ?>

		<?php // to do not that elegant would be nice to group the params ?>

		<?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
			<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
			<div class="pull-<?php echo htmlspecialchars($imgfloat); ?>">
				<img class="introtext-image"
					<?php if ($images->image_intro_caption):
							echo 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
							endif; ?>
					src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
			</div>
        <?php endif; ?>
		
		<?php echo $this->item->introtext; ?>
    </section>
    
    <footer class="entry-meta">
	
		<?php if ($params->get('show_readmore') && $this->item->readmore) :
            if ($params->get('access-view')) :
                $link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
                else :
                $menu = JFactory::getApplication()->getMenu();
                $active = $menu->getActive();
                $itemId = $active->id;
                $link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
                $returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug));
                $link = new JURI($link1);
                $link->setVar('return', base64_encode($returnURL));
                endif;
        ?>
        <a class="readmore" href="<?php echo $link; ?>">
            <?php if (!$params->get('access-view')) :
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
        <?php endif; ?>
		
		<?php echo $this->item->event->afterDisplayContent; ?>
	
		<?php if ($params->get('show_modify_date')) : ?>
			<time class="modify-date muted" datetime="<?php echo $this->item->modified; ?>">
				<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date',$this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
			</time>
		<?php endif; ?>    
    </footer>
	
</article>