<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[video]
if(!function_exists('video_sc')) {
	function video_sc( $atts, $content="" ){
	
		extract(shortcode_atts(array(
			  'height' => 281
		), $atts));
	
		Helix::addJS('fitvids.js');
		ob_start();

		$video = parse_url($content);
		
		switch($video['host']) {
			case 'youtu.be':
				$id = trim($video['path'],'/');
				$src = 'https://www.youtube.com/embed/' . $id;
			break;
			
			case 'www.youtube.com':
			case 'youtube.com':
				parse_str($video['query'], $query);
				$id = $query['v'];
				$src = 'https://www.youtube.com/embed/' . $id;
			break;
			
			case 'vimeo.com':
			case 'www.vimeo.com':
				$id = trim($video['path'],'/');
				$src = "http://player.vimeo.com/video/{$id}";
		}
		
	?>
	
	<div id="video-<?php echo $id; ?>" class="shortcode-video">
		<iframe src="<?php echo $src; ?>" width="500" height="<?php echo $height; ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
	</div>
	<script>
		spnoConflict(function($){
			$("#video-<?php echo $id; ?>").fitVids();
		});
	</script>
	<?php
		$data = ob_get_clean();
		return $data;
	}
	add_shortcode( 'spvideo', 'video_sc' );
}