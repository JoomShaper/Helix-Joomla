/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2014 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
jQuery(function($) {

	$('#jform_params_mods-lbl, #jform_params_position-lbl, #jform_params_showgrouptitle-lbl').parent().parent().css( 'display', 'none' );

	if ($('#jform_params_subcontent1').is(':checked')) {
		$('#jform_params_mods-lbl').parent().parent().css('display','');	
	}	else if ($('#jform_params_subcontent2').is(':checked')) {
		$('#jform_params_position-lbl').parent().parent().css('display','');	
	} 
	
	$('#jform_params_subcontent1').on("click", function(){
		$('#jform_params_mods-lbl, #jform_params_position-lbl').parent().parent().css('display','none');
		$('#jform_params_mods-lbl').parent().parent().css('display','');		
	});

	$('#jform_params_subcontent2').on("click", function(){
		$('#jform_params_mods-lbl, #jform_params_position-lbl').parent().parent().css('display','none');
		$('#jform_params_position-lbl').parent().parent().css('display','');		
	});

	$('#jform_params_subcontent0').on("click", function(){
		$('#jform_params_mods-lbl, #jform_params_position-lbl').parent().parent().css('display','none');
	});
	
	//Group Title
	if ($('#jform_params_group1').is(':checked')) {
		$('#jform_params_showgrouptitle-lbl').parent().parent().css('display','');
	} else {
		$('#jform_params_showgrouptitle-lbl').parent().parent().css('display','none');
	}
	
	$('#jform_params_group0').on('click', function(){
		$('#jform_params_showgrouptitle-lbl').parent().parent().css('display','none');
	});	

	$('#jform_params_group1').on('click', function(){
		$('#jform_params_showgrouptitle-lbl').parent().parent().css('display','');
	});	
});