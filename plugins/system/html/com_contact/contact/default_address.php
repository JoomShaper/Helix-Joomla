<?php

/**
 * @package		Joomla.Site
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/* marker_class: Class based on the selection of text, none, or icons
 * jicon-text, jicon-none, jicon-icon
 */
?>
<?php if (($this->params->get('address_check') > 0) && 
	($this->contact->address 
	|| $this->contact->suburb  
	|| $this->contact->state 
	|| $this->contact->country 
	|| $this->contact->postcode) 
	|| $this->params->get('show_email')
	|| $this->params->get('show_telephone')
	|| $this->params->get('show_fax')
	|| $this->params->get('show_mobile')
	|| $this->params->get('show_webpage')
	|| ($this->contact->con_position && $this->params->get('show_position'))):?>

	<div class="contact-address-block">
	<?php if ($this->contact->con_position && $this->params->get('show_position')) : ?>
		<h4><?php echo $this->contact->con_position; ?></h4>
	<?php endif; ?>
	<?php if ($this->contact->image && $this->params->get('show_image')) : ?>
		<div class="pull-right">
			<?php echo JHtml::_('image', $this->contact->image, JText::_('COM_CONTACT_IMAGE_DETAILS'), array('align' => 'middle')); ?>
		</div>
	<?php endif; ?>
	<?php if (($this->params->get('address_check') > 0) &&  ($this->contact->address || $this->contact->suburb  || $this->contact->state || $this->contact->country || $this->contact->postcode)) : ?>
		<div class="contact-address">
			<div class="media">
				<div class="pull-left">
					<i class="icon-home"></i>
				</div>
				<div class="media-body">
					<?php if ($this->contact->address && $this->params->get('show_street_address')) : ?>
						<span class="contact-street">
							<?php echo nl2br($this->contact->address); ?>
						</span>
					<?php endif; ?>
					
					<?php if ($this->contact->suburb && $this->params->get('show_suburb')) : ?>
						<span class="contact-suburb">
							<?php echo $this->contact->suburb; ?>
						</span>
					<?php endif; ?>
					
					<?php if ($this->contact->state && $this->params->get('show_state')) : ?>
						<span class="contact-state">
							<?php echo $this->contact->state; ?>
						</span>
					<?php endif; ?>
					
					<?php if ($this->contact->postcode && $this->params->get('show_postcode')) : ?>
						<span class="contact-postcode">
							<?php echo $this->contact->postcode; ?>
						</span>
					<?php endif; ?>
					
					<?php if ($this->contact->country && $this->params->get('show_country')) : ?>
						<span class="contact-country">
							<?php echo $this->contact->country; ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if($this->params->get('show_email') || $this->params->get('show_telephone')||$this->params->get('show_fax')||$this->params->get('show_mobile')|| $this->params->get('show_webpage') ) : ?>
		<div class="contact-contactinfo">
	<?php endif; ?>
	<?php if ($this->contact->email_to && $this->params->get('show_email')) : ?>
		<div class="media">
			<div class="pull-left">
				<i class="icon-envelope"></i>
			</div>
			<div class="media-body contact-emailto">
				<?php echo $this->contact->email_to; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ($this->contact->telephone && $this->params->get('show_telephone')) : ?>
		<div class="media">
			<div class="pull-left">
				<i class="icon-phone"></i>
			</div>
			<div class="media-body contact-telephone">
				<?php echo nl2br($this->contact->telephone); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($this->contact->fax && $this->params->get('show_fax')) : ?>
		<div class="media">
			<div class="pull-left">
				<i class="icon-print"></i>
			</div>
			<div class="media-body contact-fax">
				<?php echo nl2br($this->contact->fax); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($this->contact->mobile && $this->params->get('show_mobile')) :?>
		<div class="media">
			<div class="pull-left">
				<i class="icon-mobile-phone"></i>
			</div>
			<div class="media-body contact-mobile">
				<?php echo nl2br($this->contact->mobile); ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($this->contact->webpage && $this->params->get('show_webpage')) : ?>
		<div class="media">
			<div class="pull-left">
				<i class="icon-globe"></i>
			</div>
			<div class="media-body contact-webpage">
				<a href="<?php echo $this->contact->webpage; ?>" target="_blank">
				<?php echo $this->contact->webpage; ?></a>
			</div>
		</div>
	<?php endif; ?>
	<?php if($this->params->get('show_email') || $this->params->get('show_telephone')||$this->params->get('show_fax')||$this->params->get('show_mobile')|| $this->params->get('show_webpage') ) : ?>
		</div>
	<?php endif; ?>
	
	<?php if ($this->params->get('allow_vcard')) :	?>
		<?php echo JText::_('COM_CONTACT_DOWNLOAD_INFORMATION_AS');?>
			<a href="<?php echo JRoute::_('index.php?option=com_contact&amp;view=contact&amp;id='.$this->contact->id . '&amp;format=vcf'); ?>">
				<?php echo JText::_('COM_CONTACT_VCARD');?></a>
	<?php endif; ?>	
	</div>
<?php endif; ?>