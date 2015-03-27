/**
* @package Helix Framework
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2013 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
jQuery(function($){
    "use strict";

    $('.form-horizontal').addClass('helix-options');

    $(document).ready(function(){

        $('.nav-tabs').find('>li').each(function(index){
            $(this).addClass( $(this).text().replace(/\s+/g,"-").toLowerCase() + '-options' );
        })

        //Basic tab
        $('.overview-options').after( $('<li class="basic-options"><a href="#attrib-basic" data-toggle="tab">Basic</a></li>') );
        $('.overview-options, #attrib-overviews').addClass('active');
        $('#attrib-overviews').after('<div id="attrib-basic" class="tab-pane"></div>');
        $('#details').find('>.row-fluid').appendTo( $('#attrib-basic') );
        $('.details-options, #details').remove();
        $('#attrib-basic').find('hr').remove();

        //Icons
        $('<i class="fa fa-info-circle pull-left color1"></i>').appendTo( $('.overview-options > a') );
        $('<i class="fa fa-home pull-left color2"></2>').appendTo( $('.basic-options > a') );
        $('<i class="fa fa-pencil pull-left color3"></i>').appendTo( $('.presets-options > a') );
        $('<i class="fa fa-th pull-left color4"></i>').appendTo( $('.layout-options > a') );
        $('<i class="fa fa-list pull-left color5"></i>').appendTo( $('.menu-options > a') );
        $('<i class="fa fa-font pull-left color8"></i>').appendTo( $('.fonts-options > a') );
        $('<i class="fa fa-gear pull-left color9"></i>').appendTo( $('.advanced-options > a') );
        $('<i class="fa fa-list pull-left color7"></i>').appendTo( $('.menus-assignment-options > a') );

        $('#content').hide().delay(500).slideDown('slow');

        var childParentEngine = function(){
            var classes=new Array();
            $("fieldset.parent, select.parent").each(function(){
                var eleclass = $(this).attr('class').split(/\s/g);
                var $key = $.inArray("parent", eleclass);
                if( $key!=-1 ){
                    classes.push( eleclass[$key+1] ); 
                }
            });

            $("fieldset.parent, select.parent").each(function(){

                var parent = $(this);
                var eleclass = $(this).attr('class').split(/\s/g);
                var childClassName = '.child';
                var conditionClassName = '';
                var i;

                for (i=0;i<eleclass.length;i++) {
                    if( $.inArray(eleclass[i], classes) < 0 ) {
                        continue;
                    } else {

                        var elecls =  '.' + eleclass[i]; 

                        $(childClassName+elecls).parents('.control-group').hide();
                        if( $(parent).prop('type')=='fieldset' ){
                            var selected = $(parent).find('input[type=radio]:checked');
                            var radios = $(parent).find('input[type=radio]');
                            var activeItems = conditionClassName+elecls+'_'+$(selected).val();
                            var childitem =  $.trim(childClassName+elecls+activeItems);
                            setTimeout(function(){
                                $(childitem).parents('.control-group').show();
                            }, 100);

                            $(radios).on("click", function(event){
                                $(childClassName+elecls).parents('.control-group').hide();
                                $(childClassName+elecls+conditionClassName+elecls+'_'+$.trim($(this).val())).parents('.control-group').fadeIn();
                            });

                        } else if( $(parent).prop('type')=='select-one' ) {
                            var element = $(parent);
                            var selected = $(parent).find('option:selected');
                            var option = $(parent).find('option');
                            var activeItems = conditionClassName+elecls+'_'+$(selected).val();
                            var childitem =  $.trim(childClassName+elecls+activeItems);
                            setTimeout(function(){
                                $(childitem).parents('.control-group').show();
                            }, 100);

                            $(element).on("change", function(event){
                                $(childClassName+elecls).parents('.control-group').hide();
                                $(childClassName+elecls+conditionClassName+elecls+'_'+$.trim($(this).val())).parents('.control-group').fadeIn();
                            });

                        }
                    }
                }
            });
        }//end childParentEngine

        $('.tab-pane .row-fluid').find('.control-group').unwrap();
        $('#attrib-basic').find('#jform_client_id').unwrap();
        if(spjversion=='3.2.0'){
            $('.tab-pane').find('.control-group').unwrap();
        }
        $('.info-labels').unwrap();

        $('.group_separator.in_group').each(function(){
            $(this).parent().parent().addClass('in_group span2');
        });
        $('.group_separator.no_group').each(function(){
            $(this).parent().parent().addClass('no_group');
        });

        $('.tab-pane > .in_group').each(function() {
            $(this).nextUntil('.in_group').addBack().wrapAll('<div class="helix-group row-fluid clearfix"></div>');
        });

        $('.helix-group > .in_group').each(function() {
            $(this).nextUntil('.in_group').wrapAll('<div class="helix-group-contents span10"></div>');
        });

        $('.control-group.in_group').each(function(){
            $(this).html( '<h3>' + $(this).text() + '</h3>' )
        });

        //Presets
        $('.presetcolors').closest('.control-group').addClass('pickerblock').hide();
        $('#attrib-preset').find('[class$="'+$current_preset+'"]').closest('.pickerblock').show();

        $('#attrib-preset .preset-contents').on('click', function(event){
            event.stopImmediatePropagation();
            $(this).closest('.controls').find('.presets').removeClass('active');
            $(this).parent().addClass('active');
            $('#attrib-preset .presetcolors').closest('.pickerblock').hide();
            $('#attrib-preset').find('[class$="'+$(this).data('preset')+'"]').closest('.pickerblock').show();
        });

        //Template Information
        $('#jform_template').closest('.control-group').appendTo( $( '.form-inline.form-inline-header' ) );
        $('#jform_home').closest('.control-group').appendTo( $( '.form-inline.form-inline-header' ) );

        $('.info-labels').next().appendTo( $('#sp-theme-info') );
        $('.info-labels').prev().addBack().remove();

        childParentEngine();

    });

    //Add .btn-group class
    $('.radio').addClass('btn-group');

    //Modal
    setTimeout(function(){
        SqueezeBox.assign($$('a.modal'), {
            parse: 'rel'
        });
    }, 200);

    setTimeout(function(){
        $('.chzn-done').css('display', 'inline-block').next().remove();
    }, 500);

});