<?php
/**
 * @package Helix Shortcode Generator
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2014 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
 */
defined('_JEXEC') or die;

$sp_shortcodes = array();

//Button
$sp_shortcodes['button'] = array( 
    'type'=>'general', 
    'title'=>'Button', 
    'attr'=>array(

        'size'=>array(
            'type'=>'select', 
            'title'=> 'Button Size', 
            'values'=>array(
                ''=>'Default',
                'small'=>'Small',
                'large' =>'Large',
                'mini' =>'Mini'
            )
        ),

        'type'=>array(
            'type'=>'select', 
            'title'=> 'Button Type', 
            'values'=>array(
                'default'=>'Default',
                'primary'=>'Primary',
                'success'=>'Success',
                'info'  =>'Info',
                'warning'=>'Warning',
                'danger'=>'Danger',
                'link'=>'Link',
            )
        ),

        'link'=>array(
            'type'=>'text', 
            'title'=>'Button Link',
            'placeholder'=>'eg. http://www.joomshaper.com'
        ),

        'target'=>array(
            'type'=>'select', 
            'title'=> 'Target', 
            'values'=>array(
                ''=>'Same Window',
                '_blank'=>'New Window'
            )
        ),

        'content'=>array(
            'type'=>'text', 
            'title'=>'Button Text',
            'content'=>true
        )
    )
);

//Icon
$sp_shortcodes['icon'] = array(
    'type'=>'general', 
    'title'=>'Icon', 
    'attr'=>array(
        'name'=>array(
            'type'=>'icons', 
            'title'=> 'Select Icon'
        ),

        'size'=>array(
            'type'=>'text', 
            'title'=> 'Size'
        ),

        'color'=>array(
            'type'=>'text', 
            'title'=>'Color',
            'placeholder'=>'#rrggbb'
        ),

        'class'=>array(
            'type'=>'text', 
            'title'=> 'CSS Class'
        )
    )
);

//Divider
$sp_shortcodes['divider'] = array(
    'type'=>'general', 
    'title'=>'Divider', 
    'attr'=>array(

        'margin_top'=>array(
            'type'=>'text', 
            'title'=>'Margin Top',
            'value'=>'18px'
        ),

        'margin_bottom'=>array(
            'type'=>'text', 
            'title'=>'Margin Bottom',
            'value'=>'18px'
        ),

        'border'=>array(
            'type'=>'text', 
            'title'=>'Border Top',
            'value'=>'1px solid #ccc'
        )
    )
);

//Columns
$sp_shortcodes['row'] = array( 
    'type'=>'repetable', 
    'title'=>'Column',
    'attr'=>array(
        'id'=>array(
            'type'=>'text', 
            'title'=> 'Row ID'
        ),

        'class'=>array(
            'type'=>'text', 
            'title'=> 'Row Class'
        ),

        'repetable_item'=>array(
            'type'=>'repetable', 
            'title'=>'Repetable', 
            'attr'=>array(
                'width'=>array(
                    'type'=>'select', 
                    'title'=>'Column Width',
                    'values'=>array(
                        '1'=>'Column 1',
                        '1/2'=>'Column 1/2',
                        '1/3'=>'Column 1/3',
                        '1/4'=>'Column 1/4',
                        '2/3'=>'Column 2/3',
                        '3/4'=>'Column 3/4'
                    )
                ),

                'class'=>array(
                    'type'=>'text', 
                    'title'=>'Column Class'
                ),

                'content'=>array(
                    'type'=>'textarea', 
                    'title'=>'Content',
                    'content'=>true
                )
            )
        )
    )
);

//alert
$sp_shortcodes['alert'] = array( 
    'type'=>'general', 
    'title'=>'Alert',
    'attr'=>array(

        'type'=>array(
            'type'=>'select', 
            'title'=>'Alert Type', 
            'values'=>array(
                ''=>'Default',
                'success'=>'Success',
                'info'=>'Info',
                'warning'=>'Warning',
                'danger'=>'Danger'
            )
        ),

        'close'=>array(
            'type'=>'select', 
            'title'=> 'Close Button', 
            'values'=>  array(
                'yes'=>'Yes',
                'no'=>'No'
            )
        ),

        'content'=>array(
            'type'=>'textarea',
            'title'=>'Content',
            'content'=>true
        )
    )
);

//progressbar
$sp_shortcodes['progressbar'] = array( 
    'type'=>'general', 
    'title'=>'Progress Bar',
    'attr'=>array(
        'type'=>array(
            'type'=>'select', 
            'title'=>'Type', 
            'values'=>array(
                'primary'=>'Primary',
                'success'=>'Success',
                'info'=>'Info',
                'warning'=>'Warning',
                'danger'=>'Danger'
            )
        ),

        'bar'=>array(
            'type'=>'text', 
            'title'=>'Progress',
            'value'=>'50%'
        ),

        'striped'=>array(
            'type'=>'select', 
            'title'=>'Striped',
            'values'=>array(
                'no'=>'No',
                'yes'=>'Yes'
            )
        ),

        'active'=>array(
            'type'=>'select', 
            'title'=>'active',
            'values'=>array(
                'no'=>'No',
                'yes'=>'Yes'
            )
        ),

        'text'=>array(
            'type'=>'text', 
            'title'=>'Text'
        )
    )
);

//Tab
$sp_shortcodes['tab'] = array( 
    'type'=>'repetable', 
    'title'=>'Tab',
    'attr'=>array(
        'id'=>array(
            'type'=>'text', 
            'title'=> 'CSS ID',
            'value'=>'tab'
        ),

        'class'=>array(
            'type'=>'text', 
            'title'=> 'CSS Class'
        ),

        'button'=>array(
            'type'=>'select', 
            'title'=> 'Button Style', 
            'values'=> array(
                'nav-tabs'=>'Nav Tabs',
                'nav-pills'=>'Nav Pills'
            )
        ),

        'repetable_item'=>array(
            'type'=>'repetable', 
            'title'=> 'Repetable', 
            'attr'=>  array(
                'title'=>array(
                    'type'=>'text', 
                    'title'=>'Title',
                    'value'=>'Tab Title'
                ),

                'content'=>array(
                    'type'=>'textarea', 
                    'title'=>'Content',
                    'value'=>'Tab Content.',
                    'content'=>true
                )
            )
        )
    )
);

//Accordion
$sp_shortcodes['accordion'] = array( 
    'type'=>'repetable', 
    'title'=>'Accordion',
    'attr'=>array(
        'id'=>array(
            'type'=>'text', 
            'title'=> 'CSS ID',
            'value'=>'accordion1'
        ),

        'repetable_item'=>array(
            'type'=>'repetable', 
            'title'=> 'Repetable', 
            'attr'=>  array(
                'title'=>array(
                    'type'=>'text', 
                    'title'=> 'Title',
                    'value'=> 'Accordion Title'
                ),
                'content'=>array(
                    'type'=>'textarea', 
                    'title'=>'Content',
                    'value'=>'Accordion Content.',
                    'content'=>true
                )  
            )
        )
    )
);

//Gallery
$sp_shortcodes['gallery'] = array( 
    'type'=>'repetable', 
    'title'=>'Gallery',
    'attr'=>array(
        'columns'=>array(
            'type'=>'text', 
            'title'=> 'Columns',
            'value'=>'3'
        ),

        'modal'=>array(
            'type'=>'select', 
            'title'=> 'Modal',
            'values'=>array(
                'yes'=>'Yes',
                'no'=>'No'
            )
        ),

        'filter'=>array(
            'type'=>'select', 
            'title'=> 'Tag Filter',
            'values'=>array(
                'yes'=>'Yes',
                'no'=>'No'
            )
        ),

        'repetable_item'=>array(
            'type'=>'repetable', 
            'title'=> 'Repetable', 
            'attr'=>  array(
                'src'=>array(
                    'type'=>'text', 
                    'title'=> 'Image Source'
                ),
                'tag'=>array(
                    'type'=>'text', 
                    'title'=> 'Tags'
                ),
                'content'=>array(
                    'type'=>'textarea', 
                    'title'=>'Content',
                    'content'=>true
                )  
            )
        )
    )
);


//Carousel
$sp_shortcodes['carousel'] = array( 
    'type'=>'repetable', 
    'title'=>'Carousel',
    'attr'=>array(
        'id'=>array(
            'type'=>'text', 
            'title'=> 'ID',
            'value'=>'carousel'
        ),

        'repetable_item'=>array(
            'type'=>'repetable', 
            'title'=> 'Repetable', 
            'attr'=>  array(
                'content'=>array(
                    'type'=>'textarea', 
                    'title'=>'Content',
                    'content'=>true
                ),

                'caption'=>array(
                    'type'=>'textarea', 
                    'title'=> 'Caption'
                )
            )
        )
    )
);

//Testimonial
$sp_shortcodes['testimonial'] = array( 
    'type'=>'general', 
    'title'=>'Testimonial', 
    'attr'=>array(

        'name'=>array(
            'type'=>'text', 
            'title'=>'Name',
            'value' => 'John Doe'
        ),

        'designation'=>array(
            'type'=>'text', 
            'title'=>'Designation'
        ),

        'email'=>array(
            'type'=>'text', 
            'title'=>'Email',
            'value'=>'email@email.com'
        ),

        'url'=>array(
            'type'=>'text', 
            'title'=>'Url'
        ),

        'content'=>array(
            'type'=>'textarea',
            'title'=> 'Testimonial',
            'content'=>true
        )
    ) 
);

//Map
$sp_shortcodes['spmap'] = array( 
    'type'=>'general', 
    'title'=>'Map', 
    'attr'=>array(

        'lat'=>array(
            'type'=>'text', 
            'title'=>'Latitude',
            'value' => '-34.397'
        ),

        'lng'=>array(
            'type'=>'text', 
            'title'=>'Longitude',
            'value' => '150.644'
        ),

        'maptype'=>array(
            'type'=>'select', 
            'title'=>'Email',
            'values'=> array(
                'ROADMAP'=>'ROADMAP',
                'SATELLITE'=>'SATELLITE',
                'HYBRID'=>'HYBRID',
                'TERRAIN'=>'TERRAIN'
            )
        ),

        'height'=>array(
            'type'=>'text', 
            'title'=>'Url',
            'value'=>200
        ),

        'zoom'=>array(
            'type'=>'text',
            'title'=>'Zoom',
            'value'=>8
        )
    ) 
);

//Video
$sp_shortcodes['spvideo'] = array( 
    'type'=>'general', 
    'title'=>'Video', 
    'attr'=>array(

        'height'=>array(
            'type'=>'text', 
            'title'=>'Height',
            'value' => 281
        ),

        'url'=>array(
            'type'=>'text', 
            'title'=>'Video URL',
            'placeholder'=>'Youtube/Vimeo full URL. eg. http://www.youtube.com/watch?v=vb2eObvmvdI',
            'content'=>true
        )
    ) 
);

//Typography

//Dropcap
$sp_shortcodes['dropcap'] = array(
    'type'=>'general', 
    'title'=>'Dropcap', 
    'attr'=>array(
        'content'=>array(
            'type'=>'textarea',
            'title'=>'Content',
            'content'=>true
        )
    )
);

//Blocknumber
$sp_shortcodes['blocknumber'] = array(
    'type'=>'general', 
    'title'=>'Blocknumber', 
    'attr'=>array(
        'text'=>array(
            'type'=>'text', 
            'title'=>'Number',
            'value' => '01'
        ),

        'background'=>array(
            'type'=>'text', 
            'title'=>'Background',
            'value' => '#000'
        ),

        'color'=>array(
            'type'=>'text', 
            'title'=>'Color',
            'value' => '#666'
        ),

        'type'=>array(
            'type'=>'select', 
            'title'=>'Type',
            'values' =>array(
                ''=>'Default',
                'rounded'=>'Rounded',
                'circle'=>'Circle'
            )
        ),

        'content'=>array(
            'type'=>'textarea',
            'title'=>'Content',
            'content'=>true
        )
    )
);

//Block
$sp_shortcodes['block'] = array(
    'type'=>'general', 
    'title'=>'Block', 
    'attr'=>array(
        'background'=>array(
            'type'=>'text', 
            'title'=>'Background',
            'value' => 'transparent'
        ),

        'color'=>array(
            'type'=>'text', 
            'title'=>'Color',
            'value' => '#666'
        ),
        
        'padding'=>array(
            'type'=>'text', 
            'title'=>'Padding',
            'value' => '15px'
        ),

        'border'=>array(
            'type'=>'text', 
            'title'=>'Border',
            'value' => '0'
        ),

        'type'=>array(
            'type'=>'select', 
            'title'=>'Type',
            'values' =>array(
                ''=>'Default',
                'rounded'=>'Rounded',
                'circle'=>'Circle'
            )
        ),

        'content'=>array(
            'type'=>'textarea',
            'title'=>'Content',
            'placeholder'=>'Block content.',
            'content'=>true
        )
    )
);

//Bubble
$sp_shortcodes['bubble'] = array(
    'type'=>'general', 
    'title'=>'Bubble', 
    'attr'=>array(
        'author'=>array(
            'type'=>'text', 
            'title'=>'Author',
            'value' => 'Ahmed'
        ),

        'background'=>array(
            'type'=>'text', 
            'title'=>'Background',
            'value' => '#ccc'
        ),

        'color'=>array(
            'type'=>'text', 
            'title'=>'Color'
        ),
        
        'padding'=>array(
            'type'=>'text', 
            'title'=>'Padding',
            'value' => '10px'
        ),

        'border'=>array(
            'type'=>'text', 
            'title'=>'Border',
            'value' => '0'
        ),

        'type'=>array(
            'type'=>'select', 
            'title'=>'Type',
            'values' =>array(
                ''=>'Default',
                'rounded'=>'Rounded',
                'circle'=>'Circle'
            )
        ),

        'content'=>array(
            'type'=>'textarea',
            'title'=>'Content',
            'content'=>true
        )
    )
);
