<?php

// no direct access
defined('_JEXEC') or die;
?>

<section class="items-more">
	<h3 class="item-title"><?php echo JText::_('COM_CONTENT_MORE_ARTICLES'); ?></h3>
	<ul>
	<?php foreach ($this->link_items as &$item): ?>
		<li>
			<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)); ?>">
				<?php echo $item->title; ?>
			</a>
		</li>
	<?php endforeach; ?>
	</ul>
</section>