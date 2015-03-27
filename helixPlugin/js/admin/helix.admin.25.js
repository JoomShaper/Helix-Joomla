/**
* @package Helix Framework
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2013 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
jQuery(function($){

        $('#content-box').hide().delay(500).slideDown('slow');

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
        var tmplmaininfo = $('#jform_template-lbl').parent().parent().parent().parent().removeClass('width-60 fltlft').attr('id', 'sp-tmplinfo');
        tmplmaininfo.appendTo('.form-inline');
        $('#jform_title-lbl').appendTo('.form-inline');
        $('#jform_title').appendTo('.form-inline');
        $('#jform_template-lbl').appendTo('.form-inline');
        $('#jform_template').appendTo('.form-inline');
        $('#jform_home-lbl').appendTo('.form-inline');
        $('#jform_home').next().appendTo('.form-inline');
        $('#jform_home').appendTo('.form-inline');
        $('#jform_client_id').appendTo('.form-inline');
        $('#sp-theme-info').append($('.mod-desc').html());
        $('.form-inline').find('input').removeClass('readonly');

        $($formhtml).find('.pane-sliders .panel').each(function(){

                var id = $(this).find('>h3').text().replace(/\s+/g,"-").toLowerCase() + '-options';
                var text = $(this).find('h3>a>span').text();
                var contents = $(this).find('fieldset.panelform>ul.adminformlist');

                // #config-tab
                var $li = '<li class="'+$.trim(id)+'"><a href="#'+$.trim(id)+'" data-toggle="tab"><i class=""></i> '+$.trim(text)+'</a></li>';

                var $div = '<div class="tab-pane fade in" id="'+$.trim(id)+'">';

                $(contents).find('> li > div.group_separator.in_group').each(function(){
                        $(this).parent().addClass('in_group');
                });
                $(contents).find('> li > div.group_separator.no_group').each(function(){
                        $(this).parent().addClass('no_group');
                });

                $(contents).find('> li.in_group').nextUntil('> li.no_group').addClass('group');

                $(this).find('ul.adminformlist > li').each(function(i, element){

                        if( $(this).hasClass('in_group') ){

                            $div +='<div class="control-group row-fluid">';
                            $div +='<div class="span2">';
                            $div += '<h3>'+$(this).text()+'</h3>';

                            $div +='</div>';
                            $div +='<div class="controls span10"><ul>';

                            $(this).nextUntil('li.in_group').each(function(){
                                    if( $(this).hasClass('no_group') ){
                                        return false;
                                    }
                                    $div +='<li>';
                                    $div += $(this).find('label').andSelf().html();
                                    $div +='</li>';
                            });
                            $div +='</ul></div>';
                            $div +='</div>';
                            $div +='<hr>';
                        } else {
                            if( !$(this).hasClass('group') ){
                                $div +='<div class="control-group row-fluid">';
                                $div +='<div class="controls span12"><ul>';
                                $div += $(this).find('label').andSelf().html();
                                $div +='</ul></div>';
                                $div +='</div>';
                                $div +='<hr class="">';
                            }
                        }
                });

                $div +='</div>';

                $($li).appendTo( $('#config-tab') );
                $($div).appendTo( $('#config-tab-content') );
        });


        $('#config-tab li:first, #config-tab-content div.tab-pane:first').addClass('active');
        $('#config-tab-content div.tab-pane:first').addClass('in');

        $('.presetcolors').closest('li').addClass('pickerblock');

        $('#config-tab-content .controls .preset-contents').on('click', function(event){
                event.stopImmediatePropagation();
                $(this).closest('li').find('.presets').removeClass('active');
                $(this).parent().addClass('active');
                $('#presets-options .presetcolors').closest('li').hide();
                $('#presets-options').find('[class$="'+$(this).data('preset')+'"]').closest('li').show();
                //console.log($(this).data('preset'));
        });

        $('#presets-options .presetcolors').closest('li').hide();
        $('#presets-options').find('[class$="'+$current_preset+'"]').closest('li').show();

        $('#config-tab-content').find('div.control-group').each(function(){
                var remove = $(this).find('div.group_separator.no_group').closest('div.control-group');
                $(remove).next().remove();
                $(remove).remove();
        });

        var $li = '<li class="menus-assignment-option"><a href="#jform_menuselect" data-toggle="tab"><i class=""></i> '+$.trim( $($formhtml).find('#jform_menuselect-lbl').prev().text() )+'</a></li>';

        $($li).appendTo( $('#config-tab') );

        //Menu Assignment
        $('<div class="tab-pane fade in" id="jform_menuselect"></div>').appendTo( $('#config-tab-content') );
        $('#menu-assignment').parent().appendTo( $('#jform_menuselect') );

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
        $('.overview-options > a i').addClass('fa fa-info-circle color1');
        $('.basic-options > a i').addClass('fa fa-home color2');
        $('.presets-options > a i').addClass('fa fa-pencil color3');
        $('.layout-options > a i').addClass('fa fa-th color4');
        $('.menu-options > a i').addClass('fa fa-list color5');
        $('.advanced-options > a i').addClass('fa fa-gear color6');
        $('.menus-assignment-option > a i').addClass('fa fa-list color7');
        $('.fonts-options > a i').addClass('fa fa-font color8');

        //Custom Icons
        $('.icon-32-apply').replaceWith('<i class="fa fa-check color8"></i>');
        $('.icon-32-save').replaceWith('<i class="fa fa-save color5"></i>');
        $('.icon-32-save-copy').replaceWith('<i class="fa fa-copy color3"></i>');
        $('.icon-32-cancel').replaceWith('<i class="fa fa-times-circle color4"></i>');
        $('.icon-32-help').replaceWith('<i class="fa fa-question-circle color6"></i>');
        $('.icon-48-thememanager').removeClass('icon-48-thememanager');
        $('li.divider').remove();

        $('#toolbar-apply a').on('click', function(){
                $(this).find('i').replaceWith('<i class="icon-spinner icon-spin color8"></i>');
        });

        //Remove internal contents
        $('#style-form .width-60,#style-form .width-40, #sp-tmplinfo >.adminform').remove();
        $assets = $('#jform_params___field1-lbl').parents('.control-group');
        $assets.next().remove();
        $assets.remove();
});