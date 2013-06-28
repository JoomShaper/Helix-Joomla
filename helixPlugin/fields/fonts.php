<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
class JFormFieldFonts extends JFormField
{
	protected $type = 'Fonts';

	protected function getInput() {
		
		$value =htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8');
		$values=explode('|',$value);
		
		$font_type = array(
			JHTML::_('select.option', 'standard', 'Standard'),
			JHTML::_('select.option', 'google', 'Google Fonts')
		);
	
		//Lists of standard font
		$standard_fonts=array();
		$standard_fonts_options = array(
			array('Verdana, Geneva, sans-serif','Verdana'),
			array('Georgia, &quot;Times New Roman&quot;, Times, serif','Georgia'),
			array('Arial, Helvetica, sans-serif','Arial'),
			array('Impact, Arial, Helvetica, sans-serif','Impact'),
			array('Tahoma, Geneva, sans-serif','Tahoma'),
			array('&quot;Trebuchet MS&quot;, Arial, Helvetica, sans-serif','Trebuchet MS'),
			array('&quot;Arial Black&quot;, Gadget, sans-serif','Arial Black'),
			array('&quot;Times New Roman&quot;, Times, serif','Times New Roman'),
			array('&quot;Palatino Linotype&quot;, &quot;Book Antiqua&quot;, Palatino, serif','Palatino Linotype'),
			array('&quot;Lucida Sans Unicode&quot;, &quot;Lucida Grande&quot;, sans-serif','Lucida Sans Unicode'),
			array('&quot;MS Serif&quot;, &quot;New York&quot;, serif','MS Serif'),
			array('&quot;Comic Sans MS&quot;, cursive','Comic Sans MS'),
			array('&quot;Courier New&quot;, Courier, monospace','Courier New'),
			array('&quot;Lucida Console&quot;, Monaco, monospace','Lucida Console')
		);
		
		foreach ($standard_fonts_options as $option) {
		   $standard_fonts[] = JHTML::_('select.option', $option[0], JText::_($option[1]));
		}
		
        //Lists of Google Font
		$options_google = array();
        $google_fonts_options = array(
            array('none','- - - None - - -'),
            array('Allan:bold', 'Allan'),
            array('Allerta', 'Allerta'),
            array('Allerta+Stencil', 'Allerta Stencil'),
            array('Anonymous+Pro', 'Anonymous Pro'),
            array('Anonymous+Pro:italic', 'Anonymous Pro (italic)'),
            array('Anonymous+Pro:bold', 'Anonymous Pro (bold)'),
            array('Anonymous+Pro:bolditalic', 'Anonymous Pro (bold italic)'),
            array('Arimo', 'Arimo'),
            array('Arimo:italic', 'Arimo (italic)'),
            array('Arimo:bold', 'Arimo (bold)'),
            array('Arimo:bolditalic', 'Arimo (bold italic)'),
            array('Arvo', 'Arvo'),
            array('Arvo:italic', 'Arvo (italic)'),
            array('Arvo:bold', 'Arvo (bold)'),
            array('Arvo:bolditalic', 'Arvo (bold italic)'),
            array('Bentham', 'Bentham'), 
            array('Buda:light', 'Buda'), // 79
            array('Cabin:bold', 'Cabin'), // 80
            array('Cantarell','Cantarell'),
            array('Cantarell:italic','Cantarell (italic)'),
            array('Cantarell:bold','Cantarell (bold)'),
            array('Cantarell:bolditalic','Cantarell (bold italic)'),
            array('Cardo','Cardo'),
            array('Coda:800','Coda'),
            array('Copse','Copse'),
            array('Corben:bold', 'Corben'), // 81
            array('Cousine','Cousine'),
            array('Cousine:italic','Cousine (italic)'),
            array('Cousine:bold','Cousine (bold)'),
            array('Cousine:bolditalic','Cousine (bold italic)'),
            array('Covered+By+Your+Grace','Covered By Your Grace'),
            array('Crimson+Text','Crimision Text'),
            array('Cuprum','Cuprum'),
            array('Droid+Sans','Droid Sans'),
            array('Droid+Sans:bold','Droid Sans (bold)'),
            array('Droid+Sans+Mono','Droid Sans Mono'),
            array('Droid+Serif','Droid Serif'),
            array('Droid+Serif:italic','Droid Serif (italic)'),
            array('Droid+Serif:bold','Droid Serif (bold)'),
            array('Droid+Serif:bolditalic','Droid Serif (bold italic)'),
            array('Geo', 'Geo'),
            array('Gruppo', 'Gruppo'), // 82
            array('IM+Fell+DW+Pica','IM Fell DW Pica'),
            array('IM+Fell+DW+Pica:italic','IM Fell DW Pica (italic)'),
            array('IM+Fell+DW+Pica+SC','IM Fell DW Pica SC'),
            array('IM+Fell+Double+Pica','IM Fell Double Pica'),
            array('IM+Fell+Double+Pica:italic','IM Fell Double Pica (italic)'),
            array('IM+Fell+Double+Pica+SC','IM Fell Double Pica SC'),
            array('IM+Fell+English','IM Fell English'),
            array('IM+Fell+English:italic','IM Fell English (italic)'),
            array('IM+Fell+English+SC','IM Fell English SC'),
            array('IM+Fell+French+Canon','IM Fell French Canon'),
            array('IM+Fell+French+Canon:italic','IM Fell French Canon (italic)'),
            array('IM+Fell+French+Canon+SC','IM Fell French Canon SC'), 
            array('IM+Fell+Great+Primer','IM Fell Great Primer'),
            array('IM+Fell+Great+Primer:italic','IM Fell Great Primer (italic)'),
            array('IM+Fell+Great+Primer+SC','IM Fell Great Primer SC'), 
            array('Inconsolata','Inconsolata'),
            array('Josefin+Sans:100','Josefin Sans (100)'), 
            array('Josefin+Sans:100italic','Josefin Sans (100 italic)'), 
            array('Josefin+Sans:300','Josefin Sans (300)'), 
            array('Josefin+Sans:300italic','Josefin Sans (300 italic)'), 
            array('Josefin+Sans:400','Josefin Sans (400)'), 
            array('Josefin+Sans:400italic','Josefin Sans (400 italic)'), 
            array('Josefin+Sans:600','Josefin Sans (600)'), 
            array('Josefin+Sans:600italic','Josefin Sans (600 italic)'), 
            array('Josefin+Sans:700','Josefin Sans (700)'), 
            array('Josefin+Sans:700italic','Josefin Sans (700 italic)'), 
            array('Josefin+Slab:100','Josefin Slab (100)'), 
            array('Josefin+Slab:100italic','Josefin Slab (100 italic)'), 
            array('Josefin+Slab:300','Josefin Slab (300)'), 
            array('Josefin+Slab:300italic','Josefin Slab (300 italic)'), 
            array('Josefin+Slab:400','Josefin Slab (400)'), 
            array('Josefin+Slab:400italic','Josefin Slab (400 italic)'), 
            array('Josefin+Slab:600','Josefin Slab (600)'), 
            array('Josefin+Slab:600italic','Josefin Slab (600 italic)'), 
            array('Josefin+Slab:700','Josefin Slab (700)'), 
            array('Josefin+Slab:700italic','Josefin Slab (700 italic)'), 
            array('Just+Another+Hand', 'Just Another Hand'), // 83
            array('Just+Me+Again+Down+Here','Just Me Again Down Here'), 
            array('Kenia','Kenia'), 
            array('Kristi', 'Kristi'), // 84
            array('Lato:100','Lato (100)'), 
            array('Lato:100italic','Lato (100 italic)'), 
            array('Lato:300','Lato (300)'), 
            array('Lato:300','Lato (300 italic)'), 
            array('Lato:400','Lato (400)'), 
            array('Lato:400italic','Lato (400 italic)'), 
            array('Lato:700','Lato (700)'), 
            array('Lato:700italic','Lato (700 italic)'),
            array('Lato:900','Lato (900)'),
            array('Lato:900italic','Lato (900 italic)'), 
            array('Lekton:400', 'Lekton (400)'), // 85
            array('Lekton:italic', 'Lekton (italic)'), // 86
            array('Lekton:700', 'Lekton (700)'), // 87
            array('Lobster','Lobster'),
            array('Merriweather', 'Merriweather'), // 88
            array('Molengo','Molengo'),
            array('Mountains+of+Christmas','Mountains of Christmas'), 
            array('Neucha','Neucha'),
            array('Neuton','Neuton'),
            array('Nobile','Nobile'),
            array('Nobile:italic','Nobile (italic)'),
            array('Nobile:bold','Nobile (bold)'),
            array('Nobile:bolditalic','Nobile (bolditalic)'),
            array('OFL+Sorts+Mill+Goudy+TT','OFL Sorts Mill Goudy TT'),
            array('OFL+Sorts+Mill+Goudy+TT:italic','OFL Sorts Mill Goudy TT (italic)'),
            array('Old+Standard+TT','Old Standard TT'),
            array('Old+Standard+TT:italic','Old Standard TT (italic)'),
            array('Old+Standard+TT:bold','Old Standard TT (bold)'),
            array('Orbitron:400', 'Orbitron (400)'), 
            array('Orbitron:500', 'Orbitron (500)'),
            array('Orbitron:700', 'Orbitron (700)'),
            array('Orbitron:900', 'Orbitron (900)'), 
            array('PT+Sans','PT Sans'),
            array('PT+Sans:italic','PT Sans (italic)'),
            array('PT+Sans:bold','PT Sans (bold)'),
            array('PT+Sans:bolditalic','PT Sans (bold italic)'),
            array('PT+Sans+Caption','PT Sans Caption'),
            array('PT+Sans+Caption:bold','PT Sans Caption (bold)'),
            array('PT+Sans+Narrow','PT Sans Narrow'),
            array('PT+Sans+Narrow:bold','PT Sans Narrow (bold)'),
            array('Philosopher','Philosopher'),
            array('Puritan', 'Puritan'), 
            array('Puritan:italic', 'Puritan (italic)'),
            array('Puritan:bold', 'Puritan (bold)'),
            array('Puritan:bolditalic', 'Puritan (bold italic)'), 
            array('Raleway:100', 'Raleway'), 
            array('Reenie+Beanie','Reenie Beanie'),
            array('Sniglet:800', 'Sniglet'), 
            array('Syncopate', 'Syncopate'), 
            array('Tangerine','Tangerine'),
            array('Tangerine:bold','Tangerine (bold)'),
            array('Tinos', 'Tinos'), 
            array('Tinos:italic', 'Tinos (italic)'),
            array('Tinos:bold', 'Tinos (bold)'),
            array('Tinos:bolditalic', 'Tinos (bold italic)'),   
            array('Ubuntu', 'Ubuntu'), // 89
            array('Ubuntu:italic', 'Ubuntu (italic)'), // 90
            array('Ubuntu:bold', 'Ubuntu (bold)'), // 91
            array('Ubuntu:bolditalic', 'Ubuntu (bold italic)'), // 92
            array('UnifakturCook:bold', 'UnifakturCook'), 
            array('UnifakturMaguntia', 'UnifakturMaguntia'), 
            array('Vibur', 'Vibur'), 
            array('Vollkorn','Vollkorn'),
            array('Vollkorn:italic','Vollkorn (italic)'), 
            array('Vollkorn:bold','Vollkorn (bold)'),
            array('Vollkorn:bolditalic','Vollkorn (bold italic)'), 
            array('Yanone+Kaffeesatz','Yanone Kaffeesatz'),
            array('Yanone+Kaffeesatz:extralight','Yanone Kaffeesatz (extralight)'),
            array('Yanone+Kaffeesatz:light','Yanone Kaffeesatz (light)'),
            array('Yanone+Kaffeesatz:bold','Yanone Kaffeesatz (bold)'),
            array('Iceland','Iceland (Normal)'),
            array('Iceberg','Iceberg (Normal)')
        );
		
        foreach ($google_fonts_options as $option) {
           $options_google[] = JHTML::_('select.option', $option[0], JText::_($option[1]));
        }
		
		
		$html = '<div class="' . $this->id . '">';
		$html .= JHtml::_('select.genericlist', $font_type, 'name', 'class="inputbox"', 'value', 'text', $values[0], $this->id . '_type');
		$html .= JHtml::_('select.genericlist', $standard_fonts, 'name', 'class="inputbox"', 'value', 'text', $values[1], $this->id . '_standard');
		$html .= JHtml::_('select.genericlist', $options_google, 'name', 'class="inputbox"', 'value', 'text', $values[2], $this->id . '_google');
		
		$html .= '</div>';

		$doc = JFactory::getDocument();
		$js = "
		window.addEvent('domready', function(){
			var elms = document.getElements('.{$this->id} .inputbox');
			var el2 = $$('.el2');
			var data_sources = [$('{$this->id}_standard'), $('{$this->id}_google')];
			getActiveFontList();
			generateFont ();
			
			elms.each(function (el) {
				el.addEvent(\"change\", function(){
					generateFont ();
				});
			});
			
			$('{$this->id}_type').addEvent(\"change\", function(){
				getActiveFontList();
			});
			
			$('{$this->id}_type').addEvent(\"blur\", function(){
				getActiveFontList();
			});
			
			function getActiveFontList() {
				data_sources.each(function(el,j){el.setStyle('display','none');});
				if ($('{$this->id}_type').value=='google') {
					data_sources[1].setStyle('display','');
				} else {
					data_sources[0].setStyle('display','');
				}
			
			}	
			
			function generateFont () {
				var value=elms[0].value+'|'+elms[1].value+'|'+elms[2].value;
				document.id('{$this->id}').value=value;					
			};

			
		});
		";
		
		$doc->addScriptDeclaration($js);

		$html .='<input type="hidden" value="" name="'.$this->name.'" id="'.$this->id.'" />';
		return $html;
	}
}