<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 20010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct accees
defined ('_JEXEC') or die ('resticted aceess');  
$app = JFactory::getApplication();
$this->helix = Helix::getInstance();
$this->helix->header()->addLess('offline', 'offline');

?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"  lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"  lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"  lang="<?php echo $this->language; ?>"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<?php echo $this->language; ?>"> <!--<![endif]-->
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<jdoc:include type="head" />
</head>
<body<?php echo $this->helix->bodyClass('clearfix'); ?>>
	<div id="offline-page" class="container">
	
		<jdoc:include type="message" />
		
		<form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login" class="form-signin">
			
			<?php if ($app->getCfg('offline_image')) : ?>
			<img class="offline-image" src="<?php echo $app->getCfg('offline_image'); ?>" alt="<?php echo htmlspecialchars($app->getCfg('sitename')); ?>" />
			<?php endif; ?>
			<h2 class="form-signin-heading">
				<?php echo htmlspecialchars($app->getCfg('sitename')); ?>
			</h2>

			<?php if ($app->getCfg('display_offline_message', 1) == 1 && str_replace(' ', '', $app->getCfg('offline_message')) != '') : ?>
				<p class="alert alert-danger">
					<?php echo $app->getCfg('offline_message'); ?>
				</p>
			<?php elseif ($app->getCfg('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != '') : ?>
				<p class="alert alert-danger">
					<?php echo JText::_('JOFFLINE_MESSAGE'); ?>
				</p>
			<?php  endif; ?>

			<input name="username" id="username" type="text" class="input-block-level" placeholder="<?php echo JText::_('JGLOBAL_USERNAME') ?>" />		
			<input type="password" name="password" class="input-block-level" placeholder="<?php echo JText::_('JGLOBAL_PASSWORD') ?>">
			
			<label class="checkbox">
				<input type="checkbox" name="remember" value="yes" id="remember"> <?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>
			</label>
			
			<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGIN') ?>" />
			<input type="hidden" name="option" value="com_users" />
			<input type="hidden" name="task" value="user.login" />
			<input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
			<?php echo JHtml::_('form.token'); ?>
			
		</form>
	</div>
</body>
</html>