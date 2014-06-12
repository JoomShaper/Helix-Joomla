/**
 * @package Helix Shortcode Generator
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2014 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */

 jQuery(function($) {

	var $generated_shortcodes = $('#generated-shortcode > .shortcode-list');
	var modal = '#helix-shortcode-modal';
	
	$('body').on('change', modal + ' #select-shortcode', function(){
		var $shortcode_items = $(modal).find('.shortcode-item').removeClass('active');
		$( modal + ' .shortcode-item.' + $(this).val() ).addClass('active');
	});


	//Title
	$(document).on('keyup', '.shortcode-repeatable .shortcode-title', function(){
		$(this).closest('.repeatable-item').find('.repeatable-title h3 span').text( $(this).val() );
	});


	$(document).on('click', '.btn-modal', function(){
		
		$(modal).find('#select-shortcode').val('').trigger('liszt:updated');
		$(modal).find('.modal-body').empty();

		var clone 	= $generated_shortcodes.clone();
		clone 		= clone.appendTo($(modal).find('.modal-body'));

		$(modal + ' .repeatable-content .shortcode-title').each(function(){
			$(this).closest('.repeatable-item').find('.repeatable-title h3 span').text( $(this).val() );
		});

		//Destroy Chosen
		clone.find('select').chosenDestroy();
		clone.find('select').chosen();

		//Sortable
		$(modal + ' .repeatable-container').sortable({
			handle: '.action-move'
		});

		$(modal).modal();
	});

	//Repeatable
	//Add New
	$('body').on('click', modal + ' .shortcode-item.active .clone-shortcode', function(e){
		e.preventDefault();

		var toClone 	= $(this).next('.repeatable-container').find('.repeatable-item:first-child');

		//Destroy Chosen
		toClone.find('select').chosen('destroy');

		if(toClone.find('.repeatable-content').hasClass('in'))
		{
			$(toClone.find('.repeatable-content')).removeClass('in').css('height', 0);
		}

		var cloned = toClone.clone().appendTo(modal + ' .shortcode-item.active .repeatable-container').hide().fadeIn(500);

		//Chosen
		cloned.find('select').chosen();
		toClone.find('select').chosen();

	});

	//Clone
	$('body').on('click', modal + ' .shortcode-item.active .action-duplicate', function(e){
		e.preventDefault();
		var toClone 	= $(this).closest('.repeatable-item');

		//Destroy Chosen
		toClone.find('select').chosen('destroy');

		if(toClone.find('.repeatable-content').hasClass('in'))
		{
			$(toClone.find('.repeatable-content')).removeClass('in').css('height', 0);
		}

		var cloned = toClone.clone().appendTo(modal + ' .shortcode-item.active .repeatable-container').hide().fadeIn(500);

		//Chosen
		cloned.find('select').chosen();
		toClone.find('select').chosen();

	});

	//Remove
	$('body').on('click', modal + ' .action-remove', function(e){
		e.preventDefault();

		if($(this).closest('.repeatable-container').find('.repeatable-item').length != 1) //Do not delete last item
		{
			if ( confirm("Click Ok button to delete, Cancel to leave.") == true ) {

				$(this).closest('.repeatable-item').fadeOut(500, function(){
					$(this).remove();
				});

			}
		}
	});

	//Accorion
	$('body').on('click', modal + ' .shortcode-item.active .repeatable-title h3', function(){
		$(this).parent().parent().find('>.repeatable-content').collapse('toggle');
	});

	//Generate Shortcode
	$('body').on('click', modal + ' #add-shortcode', function(e){
		e.preventDefault();

		var $this = $('.shortcode-item.active');
		var shortcode_type = $this.data('shortcode_type');
		var shortcode_name = $this.data('shortcode');
		var shortcode = '';
		
		if( shortcode_type == 'repetable' )
		{

			shortcode +='<p>[' + shortcode_name;

			$this.find('>.control-group .shortcode-input').each(function(){
				shortcode += ' ' + $(this).data('attrname') + '="' + $(this).val() + '"';
			});

			shortcode +=']</p>';

			//Dynamic items
			$this.find('.repeatable-content').each(function() {

				if( $this.data('shortcode') === 'row' ) {
					shortcode +='<p>[col';
				} else {
					shortcode +='<p>[' + shortcode_name + '_item';
				}

				var content = '';

					//atts to be generated
					$(this).find('.shortcode-input').each(function(){
						
						if($(this).data('attrname')!='content'){
							shortcode += ' ' + $(this).data('attrname') + '="' + $(this).val() + '"';
						}

						if($(this).data('attrname')=='content'){
							content = $(this).val();
						}

					});

					if( $this.data('shortcode') === 'row' ) {
						shortcode +=']</p></p>'+ content +'</p><p>[/col]</p>';
					} else {
						shortcode +=']</p></p>'+ content +'</p><p>[/' + shortcode_name + '_item]</p>';
					}

				});

			shortcode +='<p>[/' + shortcode_name + ']</p>';

		} else if( shortcode_type == 'general' ) {
			var content_type = false;
			
			shortcode +='[' + shortcode_name;

			$this.find('.shortcode-input').each(function(){
				if( $(this).data('content') === 1 ) {
					shortcode +=']' + $(this).val() + '[/' + shortcode_name + ']';
					content_type = true;
				} else {
					shortcode += ' ' + $(this).data('attrname') + '="' + $(this).val() + '"';
				}
			});

			if(!(content_type)) {
				shortcode +=' /]';
			}
		}

		jInsertEditorText(shortcode, helix_editor);

	});

	//Remove Chosen
	$.fn.chosenDestroy = function() {
		$(this).show().removeClass('chzn-done')
		$(this).next().remove()
		return $(this);
	}

	//Override clone
	!(function (original) {
		jQuery.fn.clone = function () {
			var result       = original.apply(this, arguments),
			my_textareas     = this.find('textarea').add(this.filter('textarea')),
			result_textareas = result.find('textarea').add(result.filter('textarea')),
			my_selects       = this.find('select').add(this.filter('select')),
			result_selects   = result.find('select').add(result.filter('select'));

			for (var i = 0, l = my_textareas.length; i < l; ++i)          
				$(result_textareas[i]).val($(my_textareas[i]).val());

			for (var i = 0, l = my_selects.length;   i < l; ++i) 
				result_selects[i].selectedIndex = my_selects[i].selectedIndex;

			return result;
		};
	})($.fn.clone);

});