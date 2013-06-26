/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

spnoConflict(function($){
    $('.sp-totop').on('click', function() {
        $('html, body').animate({
            scrollTop: $("body").offset().top
        }, 500);
    });
    
    
    $('.sp-totop').fadeOut('fast');
    
    $(document).scroll(function () {
    var y = $(this).scrollTop();

    if (y > 800) {
        $('.sp-totop').fadeIn();
    } else {
        $('.sp-totop').fadeOut();
    }

});
    
    //tooltip
    $('.hasTip').tooltip({
        html: true
    })
});