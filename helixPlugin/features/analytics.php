<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die('resticted aceess');

class HelixFeatureAnalytics{
    
    private $helix;
    
    public function __construct($helix){
        $this->helix = $helix;
    }
	
    public function onHeader()
    {
        
    }
	
    public function onFooter()
    {
        ob_start();
        ?>
        <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '<?php echo $this->helix->Param('ga_code') ?>']);
        _gaq.push(['_trackPageview']);

        (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
        </script>
        <?php
        $data = ob_get_contents();
        ob_end_clean();
        $code = $this->helix->Param('ga_code');
        if( $this->helix->Param('enable_ga', 0) and !empty($code) ) return $data;
    }
    
    
	public function Position()
	{
				
	}
    

	public function onPosition()
	{		
		
	}	
}
