/**
* @package Helix Framework
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2013 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
jQuery(function($){

        $('#style-form > filedset').hide();
        $('#content').hide().delay(500).slideDown('slow');

        var childParentEngine = function(){
            var classes=new Array();
            $("fieldset.parent, select.parent").each(function(){
                    var eleclass = $(this).attr('class').split(/\s/g);
                    $key = $.inArray("parent", eleclass);
                    if( $key!=-1 ){
                        classes.push( eleclass[$key+1] ); 
                    }
            });

            $("fieldset.parent, select.parent").each(function(){

                    var parent = $(this);
                    var eleclass = $(this).attr('class').split(/\s/g);
                    var childClassName = '.child'
                    var conditionClassName = ''

                    for (i=0;i<eleclass.length;i++) {
                        if( $.inArray(eleclass[i], classes) < 0 ) {
                            continue;
                        } else {

                            var elecls =  '.' + eleclass[i]; 

                            $(childClassName+elecls).parents('li').hide();
                            if( $(parent).prop('type')=='fieldset' ){
                                var selected = $(parent).find('input[type=radio]:checked');
                                var radios = $(parent).find('input[type=radio]');
                                var activeItems = conditionClassName+elecls+'_'+$(selected).val();
                                var childitem =  $.trim(childClassName+elecls+activeItems);
                                setTimeout(function(){
                                        $(childitem).parents('li').show();
                                    }, 100);

                                $(radios).on("click", function(event){
                                        $(childClassName+elecls).parents('li').hide();
                                        $(childClassName+elecls+conditionClassName+elecls+'_'+$.trim($(this).val())).parents('li').fadeIn();
                                });

                            } else if( $(parent).prop('type')=='select-one' ) {
                                var element = $(parent);
                                var selected = $(parent).find('option:selected');
                                var option = $(parent).find('option');
                                var activeItems = conditionClassName+elecls+'_'+$(selected).val();
                                var childitem =  $.trim(childClassName+elecls+activeItems);
                                setTimeout(function(){
                                        $(childitem).parents('li').show();
                                    }, 100);

                                $(element).on("change", function(event){
                                        $(childClassName+elecls).parents('li').hide();
                                        $(childClassName+elecls+conditionClassName+elecls+'_'+$.trim($(this).val())).parents('li').fadeIn();
                                });

                            }
                        }
                    }

            });

        }

        //admin layout
        var $formhtml =  $('#style-form');
        var $tabhtml = '<div class="form-inline"></div><div id="helix-options"><ul id="config-tab" class="nav nav-tabs"></ul><div id="config-tab-content" class="tab-content"></div></div><div class="clr"></div>';
        $('#style-form').append($tabhtml);

        //Template Information
        var $tplTitle = $formhtml.find('#jform_title-lbl').parent();
        $('.form-inline').append($tplTitle.html());
        $('.form-inline').append($tplTitle.next().html());
        var $tplTemplate = $($formhtml).find('#jform_template-lbl').parent();
        $('.form-inline').append($tplTemplate.html());
        $('.form-inline').append($tplTemplate.next().html());
        var $tplClient =  $($formhtml).find('#jform_client_id').parent();
        $('.form-inline').append($tplClient.html());
        var $tplHome = $($formhtml).find('#jform_home-lbl').parent();
        $('.form-inline').append($tplHome.html());
        $('.form-inline').append($tplHome.next().html());
        $($($formhtml).find('fieldset.adminform').next() ).appendTo($('.form-inline'));
        $($($formhtml).find('fieldset.adminform').next().next() ).appendTo($('.form-inline'));
        $($tplTitle.next().next().html()).appendTo($('.form-inline'));
        $('#sp-theme-info').append($('#details >.control-group').last().find('.disabled').html());
        $('.form-inline').find('input').removeClass('readonly');

        //Config Tab
        $($formhtml).find('#options > #templatestyleOptions > div.accordion-group').each(function(){

                var id = $(this).find('>.accordion-heading').text().replace(/\s+/g,"-").toLowerCase() + '-options';
                var text = $(this).find('>.accordion-heading a').text();
                var contents = $(this).find('>.accordion-heading').next().find('>div.accordion-inner >div.control-group');

                var $li = '<li class="'+$.trim(id)+'"><a href="#'+$.trim(id)+'" data-toggle="tab"><i class=""></i> '+$.trim(text)+'</a></li>';

                var $div = '<div class="tab-pane fade in" id="'+$.trim(id)+'">';

                $(contents).find('> div.controls > div.group_separator.in_group').each(function(){
                        $(this).parent().parent().addClass('in_group');
                });
                $(contents).find('> div.controls > div.group_separator.no_group').each(function(){
                        $(this).parent().parent().addClass('no_group');
                });

                $('.in_group').nextUntil('.no_group').addClass('group');

                $(this).find('div.control-group > div.controls').each(function(i, element){

                        if( $(this).parent().hasClass('in_group') ){
                            $div +='<div class="control-group row-fluid">';
                            $div +='<div class="span2">';
                            $div += '<h3>'+$(this).text()+'</h3>';
                            $div +='</div>';
                            $div +='<div class="controls span10"><ul>';

                            $(this).parent().nextUntil('.in_group').each(function(){
                                    if( $(this).parent().hasClass('no_group') ){
                                        return false;
                                    }

                                    $div +='<li>';
                                    $div += $(this).find('>.control-label').html();
                                    $div += $(this).find('>.controls').html();
                                    $div +='</li>';
                            });

                            $div +='</ul></div>';
                            $div +='</div>';
                            $div +='<hr>';

                        } else {
                            if( !$(this).parent().hasClass('group') ){
                                $div +='<div class="control-group row-fluid">';
                                $div +='<div class="controls span12"><ul>';
                                $div += $(this).html();
                                $div +='</ul></div>';
                                $div +='</div>';
                                $div +='<hr class="">';
                            }
                        }

                });

                $div +='</div>';

                $($li).appendTo('#config-tab');
                $($div).appendTo('#config-tab-content');
        });

        $('#config-tab li:first, #config-tab-content div.tab-pane:first').addClass('active');
        $('#config-tab-content div.tab-pane:first').addClass('in');

        $('.presetcolors').closest('li').addClass('pickerblock').hide();
        $('#presets-options').find('[class$="'+$current_preset+'"]').closest('li').show();

        $('#config-tab-content .controls .preset-contents').on('click', function(event){
                event.stopImmediatePropagation();
                $(this).closest('li').find('.presets').removeClass('active');
                $(this).parent().addClass('active');
                $('#presets-options .presetcolors').closest('li').hide();
                $('#presets-options').find('[class$="'+$(this).data('preset')+'"]').closest('li').show();
        });

        $('#config-tab-content').find('div.control-group').each(function(){
                var remove = $(this).find('div.group_separator.no_group').closest('div.control-group');
                $(remove).next().remove();
                $(remove).remove();
        });

        var $li = '<li class="menus-assignment-option"><a href="#jform_menuselect" data-toggle="tab"><i class=""></i> '+$.trim( $($formhtml).find('#jform_menuselect-lbl').text() )+'</a></li>';

        $($li).appendTo('#config-tab');

        //Menu Assignment
        $menuAssignment = $($formhtml).find('#assignment');
        $menuAssignment = '<div class="tab-pane fade in" id="jform_menuselect">'+$menuAssignment.html()+'</div>';
        $($menuAssignment).appendTo('#config-tab-content');

        setTimeout(function(){
                SqueezeBox.assign($$('a.modal'), {
                        parse: 'rel'
                });
            }, 200);

        childParentEngine();

        $('.radio').addClass('btn-group');
        $('.radio label').addClass('btn');
        $(".btn-group label:not(.active)").click(function() {
                var label = $(this);
                var input = $('#' + label.attr('for'));

                if (!input.prop('checked')) {
                    label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
                    if (input.val() == '' || input.val() == 'yes') {
                        label.addClass('active btn-primary');
                    } else if (input.val() == 0 || input.val() == 'no' || input.val() == 'none') {
                        label.addClass('active btn-danger');
                    } else {
                        label.addClass('active btn-success');
                    }
                    input.prop('checked', true);
                }
        });
        $(".btn-group input[checked=checked]").each(function()
            {
                if ($(this).val() == '') {
                    $("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
                } else if ($(this).val() == 0 || $(this).val() == 'no') {
                    $("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
                } else {
                    $("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
                }
        });

        //Icons
        $('#presets-options').addClass('clearfix');
        $('.overview-options > a i').addClass('icon-info-sign color1');
        $('.basic-options > a i').addClass('icon-home color2');
        $('.presets-options > a i').addClass('icon-pencil color3');
        $('.layout-options > a i').addClass('icon-th color4');
        $('.menu-options > a i').addClass('icon-list color5');
        $('.fonts-options > a i').addClass('icon-font color8');
        $('.advanced-options > a i').addClass('icon-cog color9');
        $('.menus-assignment-option > a i').addClass('icon-list color7');

        //Custom Icons
        $('.icon-apply').replaceWith('<i class="icon-check"></i>');
        $('.icon-save-copy').replaceWith('<i class="icon-copy"></i>');
        $('.icon-cancel').replaceWith('<i class="icon-remove-sign color4"></i>');

        //Remove everything
        $('#style-form > fieldset').remove();
        $assets = $('#overview-options >.control-group').first();
        $assets.next().remove();
        $assets.remove();

        setTimeout(function(){
            $('.chzn-done').css('display', 'inline-block').next().remove();
        }, 500);

});