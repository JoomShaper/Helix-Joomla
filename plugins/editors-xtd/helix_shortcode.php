<?php
/**
 * @package Helix Shortcode Generator
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2014 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */

defined('_JEXEC') or die;

class plgButtonHelix_Shortcode extends JPlugin
{

	protected $autoloadLanguage = true;

	public function onDisplay($name)
	{

		require_once( dirname( __FILE__ ) . '/shortcodes.php' );

		$doc = JFactory::getDocument();
		JHtml::_('jquery.ui', array('core', 'more', 'sortable'));
		$doc->addScriptDeclaration( "var helix_editor = '{$name}';" );
		asort($sp_shortcodes);
		?>
		
		<div id="helix-shortcode-modal" class="modal hide">
			<div class="modal-header">
				<div class="row-fluid">
					<div class="span4">
						<h3><?php echo JText::_('PLG_HELIX_SHORTCODE_SELECT_SHORTCODE'); ?></h3>
					</div>
					<div class="span7">

						<?php

						echo '<select id="select-shortcode">>';
						echo '<option value="">--' . JText::_('PLG_HELIX_SHORTCODE_SELECT_SHORTCODE') . '--</option>';

						foreach( $sp_shortcodes as $shortcode => $options ){
							echo '<option value="sp-' . $shortcode . '">'. $options['title'] .'</option>';
						} 

						echo '</select>';

						?>

					</div>
					<div class="span1">
						<a href="#" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></a>
					</div>
				</div>
			</div>
			<div class="modal-body">

			</div>

			<div class="modal-footer">
				<a href="#" class="btn btn-info pull-left" id="add-shortcode" data-dismiss="modal"><?php echo JText::_('PLG_HELIX_SHORTCODE_ADD_SHORTCODE'); ?></a>
				<button class="btn btn-danger pull-left" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('PLG_HELIX_SHORTCODE_CANCEL'); ?></button>
			</div>

		</div>

			<div id="generated-shortcode" style="display:none;">

				<?php

				echo '<div class="shortcode-list">';

				foreach( $sp_shortcodes as $shortcode => $options ){


					if( !empty($options['attr']) )
					{
						echo '<div data-shortcode_type="' . $options['type'] . '" data-shortcode="' . $shortcode . '" class="shortcode-item sp-' . $shortcode . '">';

						foreach( $options['attr'] as $name => $attr_option )
						{


							if( $attr_option['type'] == 'repetable' )
							{

								echo '<div class="shortcode-repeatable">';
								echo '<a href="#" class="clone-shortcode btn btn-success"><i class="icon-new"></i> ' . JText::_('PLG_HELIX_SHORTCODE_ADD') . ' ' . $options['title'] . '</a>';
								echo '<div class="repeatable-container">';
								echo '<div data-shortcode_item="' . $shortcode . '_item" class="repeatable-item">';

								?>
								<div class="repeatable-title clearfix">
									<a href="#" class="action-move"><i class="icon-menu"></i></a>
									<h3><i></i> <span><?php echo $options['title']; ?></span></h3>
									<a href="#" class="action-remove"><i class="icon-cancel"></i></a>
									<a href="#" class="action-duplicate"><i class="icon-save-copy"></i></a>
								</div>

								<div class="repeatable-content collapse">
									<div>

										<?php

										foreach( $attr_option['attr'] as $key => $attr )
										{

											echo self::generate( $key, $attr, $attr['type'], $name );

										}

										echo '</div>';
										echo '</div>';
										echo '</div>';
										echo '</div>';
										echo '</div>';
									}
									else
									{
										echo self::generate( $name, $attr_option, $options['type'], $shortcode );

									}

								}

								echo '</div>';
							}


						} 

						echo '</div>';

						?>
					</div>	


		<?php	

		$doc->addStylesheet( JURI::root( true ) . '/plugins/editors-xtd/helix_shortcode/assets/css/helix-shortcode.css');
		$doc->addScript( JURI::root( true ) . '/plugins/editors-xtd/helix_shortcode/assets/js/shortcode-generator.js');

		$button = new JObject;
		$button->modal 		= false;
		$button->class 		= 'btn btn-modal btn-primary';
		$button->link 		= 'javascript:;';
		$button->text 		= 'Helix Shortcodes';
       	$button->name 		= 'plus-circle';

		return $button;
	}


	//Generate Shortcodes
	private static function generate( $name, $attr, $type, $shortcode ){

		$output = '';

		if( !isset($attr['value']) )
		{
			$attr['value']='';
		}

		if( !isset($attr['placeholder']) )
		{
			$attr['placeholder']='';
		}

		if( !isset($attr['content']) )
		{
			$attr['content']=false;
		}

    	//Select Type
		switch( $attr['type'] )
		{ 

			//Select
			case 'select':

			$output .= '<div class="control-group">
			<label class="control-label">'.$attr['title'].'</label>

			<div class="controls"><select class="shortcode-input" data-attrname="'.$name.'">';
			$values = $attr['values'];
			foreach( $values as $index=>$value ){
				$output .= '<option value="'.$index.'">'.$value.'</option>';
			}
			$output .= '</select></div></div>';

			break;

			//Icons
			case 'icons':

			require_once( dirname( __FILE__ ) . '/fontawesome-icons.php' );

			$output .= '<div class="control-group">
			<label class="control-label">'.$attr['title'].'</label>

			<div class="controls"><select class="shortcode-input data-icon-select" data-attrname="'.$name.'">';

			foreach( $fontawesome_icons as $icon ){
				$output .= '<option data-icon="'. $icon .'" value="'.$icon.'">'. str_replace('icon-', '', $icon) .'</option>';
			}
			$output .= '</select></div></div>';

			break;

			//Textarea
			case 'textarea':

			$output .= '<div class="control-group">
			<label class="control-label">'.$attr['title'].'</label>
			<div class="controls"><textarea class="shortcode-input" data-attrname="'.$name.'" data-content="'.$attr['content'].'" placeholder="'. $attr['placeholder'] .'">'.$attr['value'].'</textarea></div></div>';

			break;

			//Text
			case 'text':
			default:

			$output .= '<div class="control-group">
			<label class="control-label">'.$attr['title'].'</label>
			<div class="controls"><input class="shortcode-input shortcode-'.$name.'" type="text" data-attrname="'.$name.'" data-content="'.$attr['content'].'" value="'.$attr['value'].'" placeholder="'. $attr['placeholder'] .'" /></div></div>';

			break;
		}

		return $output;
	}

}

