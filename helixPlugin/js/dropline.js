/**
* @package Helix Framework
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2013 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/


(function ($) {
	$.fn.droplinemenu = function (options) {

		var defaults = {
			startLevel: 0,
			direction: 'ltr',
			center: 0,
			marginLeft: 0,
			marginTop: 0,
			width: 200,
			sublevelContainer:'',
			initOffset: {
				x: 0,
				y: 0
			},
			subOffset: {
				x: 0,
				y: 0
			}
		};

		var options = $.extend(defaults, options);

		return this.each(function () {

                    //First Level
                    var $subitems = options.sublevelContainer;
                    var $index = $(this).find('>ul>li.active').index();
                    $(this).find('>ul>li').each(function (index) {
                    	
						// on active
						if( $(this).hasClass('active') ){
							$subitems.find('>ul').eq($index).addClass('active');
						} else {
							$subitems.find('>ul').removeClass('active');
						}


						var times = 0;

						$(this).on('mouseenter', function(){
							var $thisindex = $(this).index();

							clearTimeout(times);

					
							$subitems.find('>ul').not('empty').parent().parent().removeClass('empty');
							$subitems.find('>ul').not('empty').removeClass('active');
							$subitems.find('>ul').eq($thisindex).addClass('active');

							if( $subitems.find('>ul').eq($thisindex).hasClass('empty') ){
								$subitems.find('>ul').eq($thisindex).parent().parent().addClass('empty');
							}

						});

						$(this).parent().on('mouseleave', function(){

							clearTimeout(times);

							times = setTimeout(function(){
					//			$subitems.find('>ul').parent().parent().addClass('empty');
							}, 1000);
						});

						$subitems.find('>ul').parent().parent().on('mouseenter', function(){
							clearTimeout(times);
						});

						$subitems.find('>ul').parent().parent().on('mouseleave', function(){
							$(this).addClass('empty');
						});
					});
                });
}
})(jQuery);






