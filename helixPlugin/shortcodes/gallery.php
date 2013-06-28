<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[Gallery]
if(!function_exists('gallery_sc')) {
	$galleryArray = array();
	function gallery_sc( $atts, $content="" ){
		global $galleryArray;
		
		$tags = array();
		
		extract(shortcode_atts(array(
			'columns' => 3,
			'modal' => 'yes',
			'filter' => 'no'
		), $atts));
		 
		do_shortcode( $content );
		
		//Add gallery.css file
		Helix::addCSS('gallery.css');
		//isotope
		if($filter=='yes')
			Helix::addJS('jquery.isotope.min.js');
		
		$tags = '';
		
		foreach ($galleryArray as $key=>$item) $tags .= ',' . $item['tag'];

		$tags = ltrim($tags, ',');
		$tags = explode(',', $tags);
		$newtags = array();
		foreach($tags as $tag) $newtags[] = trim($tag);
		$tags = array_unique($newtags);
		
		ob_start();
		if($filter=='yes') {
		?>
		
		<div class="gallery-filters btn-group">
			<a class="btn active" href="#" data-filter="*"><?php echo JText::_('Show All'); ?></a>
			<?php foreach ($tags as $tag) { ?>		  
				<a class="btn" href="#" data-filter=".<?php echo trim($tag) ?>"><?php echo ucfirst(trim($tag)) ?></a>
			<?php } ?>
		</div>
		<?php } ?>
		
		<ul class="gallery">
			<?php foreach ($galleryArray as $key=>$item) { ?>	
				<li style="width:<?php echo round(100/$columns); ?>%" class="<?php echo str_replace(',', ' ', $item['tag']) ?>">
					<a class="img-polaroid" data-toggle="modal" href="<?php echo ($modal=='yes')? '#modal-' . $key . '':'#' ?>">
						<?php
							echo '<img alt=" " src="' . $item['src'] . '" />';
						?>
						<?php if($item['content'] !='') { ?>
							<div>
								<div>
									<?php echo do_shortcode($item['content']); ?>
								</div>
							</div>
						<?php } ?>
					</a>
				</li>
				
				<?php if($modal=='yes') { ?>
				<div id="modal-<?php echo $key; ?>" class="modal hide fade" tabindex="-1">
					<a class="close-modal" href="javascript:;" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></a>
					<div class="modal-body">
						<?php echo '<img src="' . $item['src'] . '" alt=" " width="100%" style="max-height:400px" />';?>
					</div>
				</div>
				<?php } ?>
				
			<?php } ?>
		</ul>
		
		<?php if($filter=='yes') { ?>
			<script type="text/javascript">
				window.addEvent('load', function(){
					spnoConflict(function($){
					$gallery = $('.gallery');
					$gallery.isotope({
					  // options
					  itemSelector : 'li',
					  layoutMode : 'fitRows'
					});
					
					$filter = $('.gallery-filters');
					$selectors = $filter.find('>a');
					
					$filter.find('>a').click(function(){
						var selector = $(this).attr('data-filter');
						
							$selectors.removeClass('active');
							$(this).addClass('active');
							
							$gallery.isotope({ filter: selector });
						  return false;
						});
					});
				});
			</script>
		<?php } ?>
		  
		<?php
		$galleryArray = array();	
		//return $html;
		return ob_get_clean();
	}
	add_shortcode( 'gallery', 'gallery_sc' );
	
	//Accordion Items
	function gallery_item_sc( $atts, $content="" ){
		global $galleryArray;
		$galleryArray[] = array(
			'src'=>(isset($atts['src'])?$atts['src']:''),
			'tag'=>(isset($atts['tag']) && $atts['tag'] !='')?$atts['tag']:'',
			'content'=>$content
		);
	}

	add_shortcode( 'gallery_item', 'gallery_item_sc' );
}