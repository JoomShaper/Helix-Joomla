<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2014 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');
	
//[Map]
if(!function_exists('map_sc')) {
	function map_sc( $atts, $content="" ){
		
			extract(shortcode_atts(array(
				  'lat' => '-34.397',
				  'lng' => '150.644',
				  'maptype'=>'ROADMAP',
				  'height' => '200',
				  'zoom' => 8
			 ), $atts));
			
			Helix::addShortcodeScript('https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=true', ',', false);

			ob_start();

			?>
				
			<script type="text/javascript">
			  var myLatlng  = new google.maps.LatLng(<?php echo $lat ?>,<?php echo $lng ?>);
			  function initialize() {
				var mapOptions = {
				  zoom: <?php echo $zoom; ?>,
				  center: myLatlng,
					mapTypeId: google.maps.MapTypeId.<?php echo $maptype; ?>
				};
				var map = new google.maps.Map(document.getElementById('sp_simple_map_canvas'), mapOptions);
				var marker = new google.maps.Marker({position:myLatlng, map:map});	
			  }
			  google.maps.event.addDomListener(window, 'load', initialize);
			</script>
			
			<div style="height:<?php echo $height ?>px" id="sp_simple_map_canvas"></div>
				
			<?php
			
			$data = ob_get_clean();
			return $data;
	}
	add_shortcode( 'spmap', 'map_sc' );
}