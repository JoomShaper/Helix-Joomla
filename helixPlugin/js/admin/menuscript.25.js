/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
window.addEvent("domready",function(){

	[$('jform_params_mods'), $('jform_params_position'), $('jform_params_showgrouptitle')].each(function(el,j){
		el.getParent().setStyle('display','none');
	});
	
	if ($('jform_params_subcontent1').checked) {
		$('jform_params_mods').getParent().setStyle('display','');	
	}	else if ($('jform_params_subcontent2').checked) {
		$('jform_params_position').getParent().setStyle('display','');	
	} 
	
	$('jform_params_subcontent1').addEvent("click", function(){
		[$('jform_params_mods'), $('jform_params_position')].each(function(el){
			el.getParent().setStyle('display','none');
		});
	
		$('jform_params_mods').getParent().setStyle('display','');		
	});

	$('jform_params_subcontent2').addEvent("click", function(){
		[$('jform_params_mods'), $('jform_params_position')].each(function(el,j){
			el.getParent().setStyle('display','none');
		});
		$('jform_params_position').getParent().setStyle('display','');		
	});

	$('jform_params_subcontent0').addEvent("click", function(){
		[$('jform_params_mods'), $('jform_params_position')].each(function(el,j){
			el.getParent().setStyle('display','none');
		});
	});

	//Group Title
	if ($('jform_params_group1').checked) {
		$('jform_params_showgrouptitle').getParent().setStyle('display','');
	} else {
		$('jform_params_showgrouptitle').getParent().setStyle('display','none');
	}
	
	$('jform_params_group0').addEvent("click", function(){
		$('jform_params_showgrouptitle').getParent().setStyle('display','none');
	});	

	$('jform_params_group1').addEvent("click", function(){
		$('jform_params_showgrouptitle').getParent().setStyle('display','');
	});	
});