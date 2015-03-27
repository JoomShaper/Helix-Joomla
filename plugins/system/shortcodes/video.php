<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2015 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

//[video]
if(!function_exists('video_sc')) {
	function video_sc( $atts, $content="" ){
	
		ob_start();

		$video = parse_url($content);
		
		switch($video['host']) {
			case 'youtu.be':
				$id = trim($video['path'],'/');
				$src = '//www.youtube.com/embed/' . $id;
			break;
			
			case 'www.youtube.com':
			case 'youtube.com':
				parse_str($video['query'], $query);
				$id = $query['v'];
				$src = '//www.youtube.com/embed/' . $id;
			break;
			
			case 'vimeo.com':
			case 'www.vimeo.com':
				$id = trim($video['path'],'/');
				$src = "//player.vimeo.com/video/{$id}";
		}
		
	?>
	
	<div class="shortcode-video embed-responsive embed-responsive-16by9">
		<iframe class="embed-responsive-item" src="<?php echo $src; ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
	</div>

	<style type="text/css">
	.embed-responsive {
		position: relative;
		display: block;
		height: 0;
		padding: 0;
		overflow: hidden;
	}

	.embed-responsive-16by9 {
		padding-bottom: 56.25%;
	}

	.embed-responsive .embed-responsive-item,
	.embed-responsive embed, .embed-responsive iframe,
	.embed-responsive object, .embed-responsive video {
		position: absolute;
		top: 0;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 100%;
		border: 0;
	}
	</style>

	<?php
		$data = ob_get_clean();
		return $data;
	}
	add_shortcode( 'spvideo', 'video_sc' );
}