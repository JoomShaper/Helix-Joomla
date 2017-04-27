/**
* @package Helix Framework
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2017 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
jQuery(function($){

  $(document).ready(function(){

		$(this).find('select').each(function(){
			$(this).chosen('destroy');
		});

	});//end ready

  var reArrangePopOvers = function(){

    $('#layout-options .row-fluid').each(function(){

      $(this).find('>.column').find('.columntools > .rowcolumnspop')
      .attr('data-placement','bottom').data('placement', 'bottom');

      $(this).find('>.column').first().find('.columntools > .rowcolumnspop')
      .attr('data-placement','right').data('placement', 'right');

      $(this).find('>.column').last().find('.columntools > .rowcolumnspop')
      .attr('data-placement', 'left').data('placement', 'left');
    });
  }

  reArrangePopOvers();

  var toolTip = function($selector){

  }

  toolTip();

  var columnInputs = function(element, name){

    $(element).find('>.widthinput').attr('name', name+'[span]');
    $(element).find('>.offsetinput').attr('name', name+'[offset]');
    $(element).find('>.typeinput').attr('name', name+'[type]');
    $(element).find('>.positioninput').attr('name', name+'[position]');
    $(element).find('>.styleinput').attr('name', name+'[style]');
    $(element).find('>.customclassinput').attr('name', name+'[customclass]');
    $(element).find('>.responsiveclassinput').attr('name', name+'[responsiveclass]');

  }
  var rowInputs = function(element, name){

    $(element).find('>div>.rowpropperties .rownameinput').attr('name', name+'[name]');
    $(element).find('>div>.rowpropperties .rowcustomclassinput').attr('name', name+'[class]');
    $(element).find('>div>.rowpropperties .rowresponsiveinput').attr('name', name+'[responsive]');

    $(element).find('>div>.rowpropperties .rowbackgroundcolorinput').attr('name', name+'[backgroundcolor]');
    $(element).find('>div>.rowpropperties .rowtextcolorinput').attr('name', name+'[textcolor]');
    $(element).find('>div>.rowpropperties .rowlinkcolorinput').attr('name', name+'[linkcolor]');
    $(element).find('>div>.rowpropperties .rowlinkhovercolorinput').attr('name', name+'[linkhovercolor]');
    $(element).find('>div>.rowpropperties .rowmargininput').attr('name', name+'[margin]');
    $(element).find('>div>.rowpropperties .rowpaddinginput').attr('name', name+'[padding]');

  }

  document.adminForm.onsubmit = function(){


    $('#content .generator >.row-fluid, #element-box .generator >.row-fluid').each(function(rowl0){

      var r0name = fieldName+'['+rowl0+']';
      rowInputs(this, r0name);

      $(this).find('> div >.row-fluid >.column').each(function(columnl0){

        var c0name = r0name+'[children]['+columnl0+']';
        columnInputs(this, c0name);

        // main rows
        $(this).find('>.row-fluid').each(function(rowl1){

          var r1name = c0name+'[children]['+rowl1+']';
          rowInputs(this, r1name);


          $(this).find('> div >.row-fluid >.column').each(function(columnl1){

            var c1name = r1name+'[children]['+columnl1+']';
            columnInputs(this, c1name);
            //// sub row 2
            $(this).find('>.row-fluid').each(function(rowl2){

              var r2name = c1name+'[children]['+rowl2+']';
              rowInputs(this, r2name);

              $(this).find('> div >.row-fluid >.column').each(function(columnl2){

                var c2name = r2name+'[children]['+columnl2+']';
                columnInputs(this, c2name);

                //// sub row 3
                $(this).find('>.row-fluid').each(function(rowl3){

                  var r3name = c2name+'[children]['+rowl3+']';

                  rowInputs(this, r3name);

                  $(this).find('> div >.row-fluid >.column').each(function(columnl3){

                    var c3name = r3name+'[children]['+columnl3+']';
                    columnInputs(this, c3name);

                    //// sub row 4
                    $(this).find('>.row-fluid').each(function(rowl4){
                      var r4name = c3name+'[children]['+rowl4+']';
                      rowInputs(this, r4name);

                      $(this).find('> div >.row-fluid >.column').each(function(columnl4){
                        var c4name = r4name+'[children]['+columnl4+']';
                        columnInputs(this, c4name);
                      });
                    });
                  });
                });
              });
            });
          });
        });
      });
    });

    return true;

  };

  ;(function(textOnly) {

    jQuery.fn.textOnly = function( selector ) {
      return $.trim($(selector)
      .clone()
      .children()
      .remove()
      .end()
      .text());
    }

  })(jQuery.fn.textOnly);

  /**
  * jQuery alterClass plugin
  *
  * Remove element classes with wildcard matching. Optionally add classes:
  *   $( '#foo' ).alterClass( 'foo-* bar-*', 'foobar' )
  *
  * Copyright (c) 2011 Pete Boere (the-echoplex.net)
  * Free under terms of the MIT license: http://www.opensource.org/licenses/mit-license.php
  *
  */
  ;(function ( $ ) {

    $.fn.alterClass = function ( removals, additions ) {

      var self = this;

      if ( removals.indexOf( '*' ) === -1 ) {
        // Use native jQuery methods if there is no wildcard matching
        self.removeClass( removals );
        return !additions ? self : self.addClass( additions );
      }

      var patt = new RegExp( '\\s' +
      removals.
      replace( /\*/g, '[A-Za-z0-9-_]+' ).
      split( ' ' ).
      join( '\\s|\\s' ) +
      '\\s', 'g' );

      self.each( function ( i, it ) {
        var cn = ' ' + it.className + ' ';
        while ( patt.test( cn ) ) {
          cn = cn.replace( patt, ' ' );
        }
        it.className = $.trim( cn );
      });

      return !additions ? self : self.addClass( additions );
    };

  })( jQuery );



  /**
  * Get class using regular expression
  */
  ;(function(getClass) {

    jQuery.fn.getClass = function( classname ) {

      if ( classname && typeof(classname) === "object" ) {
        var classes = $(this).attr('class').split( /\s+/ );
        var re = new RegExp(classname);
        var m = re.exec(classes);
        if(m!=null) {
          return m[m.length-1];
        }
      }

      if( typeof(classname)=== "boolean" ){

        return $(this).attr('class').split( /\s+/ );
      }


      return '';
    }
  })(jQuery.fn.getClass);




  var columnMaping = {
    2 : [2, 3, 4, 5, 6, 7, 8, 9, 10],   //  possible spans
    3 : [2, 3, 4, 5, 6, 7, 8],
    4 : [2, 3, 4, 5, 6],
    5 : [2, 3],
    6 : [2]
  };


  $('#content').delegate('.columntools > a, .row-tools > a', 'click', function(){

    return false;
  });




  /**
  * Open Bootstrap popover
  *
  */



  var popover = function(){

    $('a[rel="popover"]').popover({
      html:true,
      content:function(){


        var id = $(this).attr('href');


        var currentSpan = $(this).closest('.column').getClass(/\bspan([0-9]{1,2})\b/);

        setTimeout(function(value, $this){
          $this.next().find('#spanwidth select').val(value);
        }, 300, currentSpan, $(this));

        $("#content,#element-box").delegate(".popover select.possiblewidths",'change', function(event){

          event.stopImmediatePropagation();
          var newSpan = $(this).val();
          $(this).parents('.popover').parent().parent().find('>.widthinput').val(newSpan);
          $(this).parents('.popover').parent().parent().removeClass().addClass('column span'+newSpan);
        });

        var currentOffset = $(this).closest('.column').getClass(/\boffset([0-9]{1,2})\b/);

        setTimeout(function(value, $this){
          $this.next().find('#spanoffset select').val(value);
        }, 300, currentOffset, $(this));

        $("#content,#element-box").delegate(".popover select.possibleoffsets", 'change', function(event){

          event.stopImmediatePropagation();
          var newOffset = $(this).val();

          var newClass = $(this).parents('.popover').parent().parent().attr('class').replace(/\boffset\S+/g, '');
          $(this).parents('.popover').parent().parent().attr('class', newClass).addClass('offset'+newOffset);


          $(this).parents('.popover').parent().parent().find('>.offsetinput').val(newOffset);

          if( newOffset=='0' ){
            $(this).parents('.popover').parent().parent().removeClass('offset0');
            $(this).parents('.popover').parent().parent().find('>.offsetinput').val('');
          }
        });

        var currentIncludetype = $(this).closest('.column').find('.typeinput').val();

        setTimeout(function(value, $this){
          $this.next().find('#includetypes select').val(value);
        }, 300, currentIncludetype, $(this));

        $("#content,#element-box").delegate(".popover select.includetypes",'change', function(event){

          event.stopImmediatePropagation();
          var newIncludetype = $(this).val();
          $(this).parents('.popover').parent().parent().find('>.typeinput').val(newIncludetype);

          $(this).parents('.popover').parent().parent().removeClass('type-component type-message');
          $(this).parents('.popover').parent().parent().addClass('type-'+newIncludetype);

          if( newIncludetype==='modules' ){
            $(this).closest('.tab-pane').find('#positions').show();
            $(this).closest('.tab-pane').find('#positions option[value=""]').attr('selected', true);
            $(this).parents('.popover').parent().parent().find('>.position-name').text('(none)');
          }
          else{
            $(this).closest('.tab-pane').find('#positions').hide();
            $(this).parents('.popover').parent().parent().find('>.position-name').text(newIncludetype.toUpperCase());
          }
        });

        var currentPosition = $(this).closest('.column').find('.positioninput').val();

        setTimeout(function(value, $this){
          $this.next().find('#positions select').val(value);
        }, 300, currentPosition, $(this));

        $("#content,#element-box").delegate(".popover select.positions",'change', function(event){

          event.stopImmediatePropagation();
          var newPosition = $(this).val();
          if( newPosition=='' ) newPosition='(none)';
          $(this).parents('.popover').parent().parent().find('>.positioninput').val(newPosition);
          $(this).parents('.popover').parent().parent().find('>.position-name').text(newPosition);
        });

        var currentStyle = $(this).closest('.column').find('.styleinput').val();

        setTimeout(function(value, $this){
          $this.next().find('#modchrome select').val(value);
        }, 300, currentStyle, $(this));


        $("#content,#element-box").delegate(".popover select.modchrome",'change', function(event){

          event.stopImmediatePropagation();
          var newStyle = $(this).val();
          $(this).parents('.popover').parent().parent().find('>.styleinput').val(newStyle);
        });

        var currentCustomClass = $(this).closest('.column').find('.customclassinput').val();

        setTimeout(function(value, $this){
          $this.next().find('#inputcustomclass').val(value);
        }, 300, currentCustomClass, $(this));

        $("#content,#element-box").delegate(".popover input.customclass",'blur', function(event){

          event.stopImmediatePropagation();
          var newCustomClass = $(this).val();
          $(this).parents('.popover').parent().parent().find('>.customclassinput').val(newCustomClass);
        });

        $("#content,#element-box").delegate(".popover #columnsettings a",'click', function(event){

          var id = $(this).attr('href');

          if( id=='' || id=='#' ){  return;}

          $(this).parents('ul').find('li').removeClass('active');
          $(this).parent().addClass('active');
          $(this).parents('ul').next().find('.active').removeClass('active');
          $('.popover '+id).addClass('active');

          $(this).parents('.dropdown-menu').parents('li.dropdown').addClass('active');

        });

        var currentResponsive = $(this).closest('.column').find('.responsiveclassinput').val().split(/\s+/);

        $(id).find('#responsive input:checkbox').removeAttr('checked');

        $.each(currentResponsive, function(index, item) {
          $(id).find('#responsive input[value="'+item+'"]').attr('checked', true);
        });

        $("#content,#element-box").delegate(".popover input:checkbox",'click', function(event){

          event.stopImmediatePropagation();

          var newResponsive = $(this).val();

          var currentResponsive = $(this).parents('.popover').parent().parent().find('>.responsiveclassinput').val();

          if( typeof(currentResponsive)==='undefined' ){
            currentResponsive = '';
          }

          if( $(this).is(':checked') ){
            var value = currentResponsive+' '+newResponsive;
            value = $.unique( value.split(/\s+/) );
            value = value.join(' ');
          } else  {
            var value = currentResponsive.replace(newResponsive, '');
          }

          $(this).parents('.popover').parent().parent().find('>.responsiveclassinput').val($.trim(value));

        });

        $(this).closest('.columntools').addClass('open');

        return $(id).html();
      },

      template: '<div class="popover"><div class="arrow"></div><div class="popover-inner"><div class="popover-content"><p></p></div> <p>   <a class="btn btn-primary sp-popover-apply" onclick="jQuery(this).closest(\'.popover\').prev().popover(\'hide\');  jQuery(this).closest(\'.popover\').prev().show();"><i class="icon-ok"></i> Apply</a> <a class="btn btn-danger sp-popover-close" onclick="jQuery(this).closest(\'.popover\').prev().popover(\'hide\'); jQuery(this).closest(\'.popover\').prev().show();"><i class="icon-remove"></i> Close</a>   </p> </div></div>',
    }).click(function(){  $(this).show();   return false;});

    $("#layout-options").delegate(".popover .sp-popover-apply, .popover .sp-popover-close",'click', function(event){
      $(this).closest('.columntools').removeClass('open');
      $('#columnsettings').find('li').first().addClass('active');
    });

    $('a[rel="rowpopover"]').popover({
      html:true,
      placement:'left',
      content:function(){

        var id = $(this).attr('href');

        var currentName = $(this).parent().prev().find('>span.rowdocs>.rownameinput');

        setTimeout(function($this, value){
          $this.parent().find('>.popover .rowname').val(value);
        }, 300, $(this), currentName.val() );

        $("#content,#element-box").delegate(".popover input.rowname",'blur', function(event){

          event.stopImmediatePropagation();
          var newName = $(this).val();
          $(this).parents('.popover').parent().prev().find('>span.rowdocs>.rownameinput').val(newName);
          $(this).parents('.popover').parent().prev().find('>.rowname').text(newName);
        });

        // background color
        var currentBackgroundColor = $(this).parent().prev().find('>span.rowdocs>.rowbackgroundcolorinput');

        setTimeout(function($this, value){
          $this.parent().find('>.popover .rowbackgroundcolor').val(value);

          $this.parent().find('>.popover .rowbackgroundcolor').spectrum({
            flat:false,
            showInput:true,
            preferredFormat: "rgb",
            showButtons:true,
            showAlpha:true,
            showPalette:true,
            clickoutFiresChange:true,
            cancelText:"cancel",
            chooseText:"Choose",
            palette : [ ['rgba(255, 255, 255, 0)'] ],
            change: function(color) {
              var currentcolor = color.toRgbString();
              $(this).parents('.popover').parent().prev().find('>span.rowdocs>.rowbackgroundcolorinput').val(currentcolor);
            }
          });

        }, 300, $(this), currentBackgroundColor.val());

        // text color
        var currentTextColor = $(this).parent().prev().find('>span.rowdocs>.rowtextcolorinput');

        setTimeout(function($this, value){
          $this.parent().find('>.popover .rowtextcolor').val(value);

          $this.parent().find('>.popover .rowtextcolor').spectrum({
            flat:false,
            showInput:true,
            preferredFormat: "rgb",
            showButtons:true,
            showAlpha:true,
            showPalette:true,
            clickoutFiresChange:true,
            cancelText:"cancel",
            chooseText:"Choose",
            palette : [ ['rgba(255, 255, 255, 0)'] ],
            change: function(color) {
              var currentcolor = color.toRgbString();
              $(this).parents('.popover').parent().prev().find('>span.rowdocs>.rowtextcolorinput').val(currentcolor);
            }
          });

        }, 300, $(this), currentTextColor.val());

        // link color
        var currentLinkColor = $(this).parent().prev().find('>span.rowdocs>.rowlinkcolorinput');

        setTimeout(function($this, value){
          $this.parent().find('>.popover .rowlinkcolor').val(value);

          $this.parent().find('>.popover .rowlinkcolor').spectrum({
            flat:false,
            showInput:true,
            preferredFormat: "rgb",
            showButtons:true,
            showAlpha:true,
            showPalette:true,
            clickoutFiresChange:true,
            cancelText:"cancel",
            chooseText:"Choose",
            palette : [ ['rgba(255, 255, 255, 0)'] ],
            change: function(color) {
              var currentcolor = color.toRgbString();
              $(this).parents('.popover').parent().prev().find('>span.rowdocs>.rowlinkcolorinput').val(currentcolor);
            }
          });

        }, 300, $(this), currentLinkColor.val());

        // link hover color
        var currentLinkHoverColor = $(this).parent().prev().find('>span.rowdocs>.rowlinkhovercolorinput');

        setTimeout(function($this, value){
          $this.parent().find('>.popover .rowlinkhovercolor').val(value);

          $this.parent().find('>.popover .rowlinkhovercolor').spectrum({
            flat:false,
            showInput:true,
            preferredFormat: "rgb",
            showButtons:true,
            showAlpha:true,
            showPalette:true,
            clickoutFiresChange:true,
            cancelText:"cancel",
            chooseText:"Choose",
            palette : [ ['rgba(255, 255, 255, 0)'] ],
            change: function(color) {
              var currentcolor = color.toRgbString();
              $(this).parents('.popover').parent().prev().find('>span.rowdocs>.rowlinkhovercolorinput').val(currentcolor);
            }
          });

        }, 300, $(this), currentLinkHoverColor.val());

        // css margin
        var currentMargin = $(this).parent().prev().find('>span.rowdocs>.rowmargininput');

        setTimeout(function($this, value){
          $this.parent().find('>.popover .rowmargin').val(value);
        }, 300, $(this), currentMargin.val());

        $("#content,#element-box").delegate(".popover input.rowmargin",'blur', function(event){

          event.stopImmediatePropagation();
          var newName = $(this).val();
          $(this).parents('.popover').parent().prev().find('>span.rowdocs>.rowmargininput').val(newName);
        });

        // css padding
        var currentPadding = $(this).parent().prev().find('>span.rowdocs>.rowpaddinginput');

        setTimeout(function($this, value){
          $this.parent().find('>.popover .rowpadding').val(value);
        }, 300, $(this), currentPadding.val());

        $("#content,#element-box").delegate(".popover input.rowpadding",'blur', function(event){

          event.stopImmediatePropagation();
          var newName = $(this).val();
          $(this).parents('.popover').parent().prev().find('>span.rowdocs>.rowpaddinginput').val(newName);
        });

        // css class
        var currentCss = $(this).parent().prev().find('>span.rowdocs>.rowcustomclassinput');

        setTimeout(function($this, value){
          $this.parent().find('>.popover .rowcustomclass').val(value);
        }, 300, $(this), currentCss.val());

        $("#content,#element-box").delegate(".popover input.rowcustomclass",'blur', function(event){

          event.stopImmediatePropagation();
          var newName = $(this).val();
          $(this).parents('.popover').parent().prev().find('>span.rowdocs>.rowcustomclassinput').val(newName);

        });

        var currentResponsive = $(this).parent().prev().find('>span.rowdocs>.rowresponsiveinput').val().split(/\s+/);

        $(id).find('#rowresponsiveinputs input:checkbox').removeAttr('checked');
        $.each(currentResponsive, function(index, item) {
          $(id).find('#rowresponsiveinputs input[value="'+item+'"]').attr('checked', true);
        });

        $("#content,#element-box").delegate(".popover input:checkbox",'click', function(event){

          event.stopImmediatePropagation();

          var newResponsive = $(this).val();
          var currentResponsive = $(this).parents('.popover').parent().prev().find('span.rowdocs>.rowresponsiveinput').val();

          if( typeof(currentResponsive)==='undefined' ){
            currentResponsive = '';
          }

          if( $(this).is(':checked') ){
            var value = currentResponsive+' '+newResponsive;
            value = $.unique( value.split(/\s+/) );
            value = value.join(' ');

          } else  {
            var value = currentResponsive.replace(newResponsive, '');
          }

          $(this).parents('.popover').parent().prev().find('span.rowdocs>input.rowresponsiveinput').val($.trim(value));

        });

        return $(id).html();
      },

      template: '<div class="popover"><div class="arrow"></div><div class="popover-inner"><div class="popover-content"><p></p></div><a class="btn btn-primary" onclick="jQuery(this).closest(\'.popover\').prev().popover(\'hide\'); jQuery(this).closest(\'.popover\').prev().show(); "><i class="icon-ok"></i> Apply</a></div></div>',

    }).click(function(){  $(this).show();   return false;});

  }


  popover();



  /**
  * Row delete
  *
  */
  $("#content,#element-box").delegate("a.rowdelete",'click', function(){


    if( confirm('Are you sure to delete this row?') ){

      $(this).parent().parent().parent().slideUp('slow', function(){
        $(this).remove();
      });
    }
    return false;
  });

  /**
  * Column delete
  *
  */
  $("#content,#element-box").delegate("a.columndelete",'click', function(){

    if( confirm('Are you sure to delete this column?') ){
      $parent2 = $(this).parent().parent().parent();
      $(this).parent().parent().fadeOut('fast').remove();
      var totalSpan =  $parent2.find('>.column').length;
      resetColumns($parent2);
    }
    return false;
  });

  /**
  * row Column Sortable
  *
  */
  var rowColumnSortable  = function(){

    $('div.row-fluid').sortable({
      //placeholder: "highlight",
      axis: "x",
      items: ">div.column",
      tolerance: "pointer",
      handle: ".columnmove",
      containment: "parent",
      'update' : function(event, ui){
        setTimeout(function(){

          $('a[rel="popover"]').popover('destroy');
          $('a[rel="popover"]').show();
          reArrangePopOvers();
          popover();
        },300);

      }
    });

    $('.generator').sortable({
      axis: "y",
      forcePlaceholderSize: true,
      containment: "parent",
      handle: ".rowmove",
      items:'>div.row-fluid',


    });

    $( ".generator" ).sortable( "refreshPositions" );

    $('.generator > .row-fluid .row-fluid .column').sortable({
      axis: "y",
      forcePlaceholderSize: true,
      containment: "parent",
      handle: ".row-move-in-column",
      items:'>div'
    });


  }

  rowColumnSortable();

  /**
  * Add new row
  *
  */
  $("#content,#element-box").delegate("a.add-row",'click', function(){
    var $item = $(this);
    $.get(pluginPath+'/layout/new-row.html',function($row){
      $($row).hide().insertAfter( $item.closest('.row-fluid') ).slideDown('slow');
      $('a[rel="popover"]').popover('destroy');
      $('a[rel="popover"]').show();
      reArrangePopOvers();
      popover();
    });
    return false;
  });

  $("#content,#element-box").delegate("a.columnmove, a.icon-asterisk",'click', function(){

    return false;
  });

  /**
  * Reset Columns on Update or delete
  *
  * @param $selector
  */
  var resetColumns = function($selector){

    console.log($selector.attr('class'));

    var totalSpan =  $selector.find('>.column').length;
    var spanClass;
    if( totalSpan==5 ) {
      spanClass = 12/4;
      //$selector.find('>.column').alterClass('span* offset*').addClass('span3').find('>.widthinput').val('3');
      $selector.find('>.column').alterClass('span*').addClass('span3').find('>.widthinput').val('3');
      $selector.find('>.column').not(':first-child, :last-child').alterClass('span*').addClass('column span2').find('>.widthinput').val('2');
    } else {
      spanClass = 12/totalSpan;
      //$selector.find('>.column').alterClass('span* offset*').addClass('span'+spanClass).find('>.widthinput').val(spanClass);
      $selector.find('>.column').alterClass('span*').addClass('span'+spanClass).find('>.widthinput').val(spanClass);
    }

    $('a[rel="popover"]').popover('destroy');
    $('a[rel="popover"]').show();
    reArrangePopOvers();
    popover();

  }


  /**
  * Add new column
  *
  * @param $selector
  */
  var addColumn = function($selector){


    $.get(pluginPath+'/layout/new-column.html', function($column){
      $($column).hide().appendTo($selector).fadeIn(1000);


      resetColumns($selector);
      rowColumnSortable();
      $('a[rel="popover"]').popover('destroy');
      $('a[rel="popover"]').show();
      reArrangePopOvers();
      popover();
    });
  }

  $("#content,#element-box").delegate("a.add-column",'click', function(){

    var totalSpan =  $(this).parent().next().next().find('>.column').length;
    if( totalSpan >= 6 ){
      alert('Maximum 6 module positions is possible in a row');
      return false;
    }

    addColumn( $(this).parent().next().next() );
    $('a[rel="popover"]').popover('destroy');
    $('a[rel="popover"]').show();
    reArrangePopOvers();
    popover();
    return false;
  });

  /**
  * Add Row in column
  *
  */
  var addRowInColumn = function(){
    $("#content,#element-box").delegate("a.add-rowin-column",'click', function(){
      var $this = $(this);
      $.get(pluginPath+'/layout/new-row-in-column.html', function($row){
        $($row).hide().appendTo( $this.parent().parent() ).slideDown('slow');
        $('a[rel="popover"]').popover('destroy');
        $('a[rel="popover"]').show();
        reArrangePopOvers();
        popover();
      });
      return false;
    });
  }

  addRowInColumn();
});
