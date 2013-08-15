<?php
/**
 * @package Helix Framework
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2013 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
*/
//no direct accees
defined ('_JEXEC') or die ('resticted aceess');

class HelixMenu {
	var $_params 	= null;
	var $_menu	 	= null;
	var $children	= null;
	var $open 		= null;
	var $items 		= null;
	var $Itemid 	= 0;
	
	private $helix;
	
	function __construct($helix, $params, $_menu){
		global $Itemid;
		$this->helix = $helix;
		$this->_params = $params;
		$this->_menu = $_menu;//menu type, eg. mega
		$this->Itemid  = $Itemid;
		$this->loadMenu();
		
	}
	
	function  loadMenu(){
		
		$user = JFactory::getUser();
		$children = array ();
		
		// Get Menu Items
		$app = JFactory::getApplication();
		$items = $app->getMenu();
		
		$active = ($items->getActive()) ? $items->getActive() : $items->getDefault();
		$this->open		= isset($active) ? array_reverse($active->tree) : array();
			
		$rows = $items->getItems('menutype', $this->helix->Param('menu', 'mainmenu'));
		
		if(!count($rows)) 
			$rows = $items->getItems('menutype', 'mainmenu');
		if(!count($rows)) return;
		
		// first pass - collect children
		$cacheIndex = array();
		$this->items = array();
		foreach ($rows as $index => $v) {
		   	//1.6 compatible
   	    	if (isset ($v->title)) $v->name = $v->title; 
   	    	if (isset ($v->parent_id)) $v->parent = $v->parent_id;
			
			$v->name = stripslashes(htmlspecialchars($v->name));// replace & with amp; for xhtml compliance
			
			if (in_array($v->access, $user->getAuthorisedViewLevels())) {
				$pt = $v->parent;
				$list = @ $children[$pt] ? $children[$pt] : array ();

				//Only for megamenu
				if ($this->_menu=='mega') {
					$vparams = new JObject();
					
					//get mega params
					$v->megaparams = $megaparams = new JObject(json_decode($v->params));				
					
					if ($v->level==1) $v->megaparams->set ('group',0); //forcefully remove group param from level-0 items

					if (!$v->megaparams->get ('showgrouptitle', 1) && $v->megaparams->get ('group')) $v->name = '';//Show or Hide group title
					
					//Set individual column width
					if ($megaparams) {
						if (isset($megaparams->colxw)) {
							$colxw=explode ("\n",$megaparams->colxw);
							for ($i=0;$i<count($colxw);$i++) {
								$v->megaparams->set ('colw'. ($i+1),$colxw[$i]);
							}
						}
					}						
				}
				
				//only for megamenu

				if ($this->_menu=='mega') {
					//calculate menu columns
					if ($v->megaparams->get('cols')) {
						$v->cols = $v->megaparams->get('cols');							
						$v->col = array();
						for ($i=0;$i<$v->cols;$i++) {
							if ($v->megaparams->get("colw$i")) $v->colw[$i]=$v->megaparams->get("colw$i");
						}
					}
				}

				
				array_push($list, $v);
				$children[$pt] = $list;
				$cacheIndex[$v->id] = $index;
				$this->items[$v->id] = $v;
			}
		}
		
		$this->children = $children;
	}
	
	//Generate menu item
	function genMenuItem($item, $level = 0, $pos = '')
	{
		$data = '';
		$title = '';
		$menu_title = '';
		
		// Print a link if it exists
		$active = $this->genClass ($item, $level, $pos);
		$iParams = new JObject(json_decode($item->params));
		
		if ($iParams->get('showmenutitle',1)) {
			$menu_title .= '<span class="menu-title">' . $item->name . '</span>';		
		} else {
			$menu_title .= '<span class="menu-title">&nbsp;</span>';		
		}	
		
		if ($iParams->get('desc')) {
			$menu_title .= '<span class="menu-desc">'. $iParams->get('desc').'</span>';
		}		
		
		//Menu image
		if ($this->helix->Param('show_menu_image') && $iParams->get('menu_image') && $iParams->get('menu_image') != -1) {
			if ($this->helix->Param('menu_image_position',1)=='1') { 
				$txt = '<span class="menu"><img class="menu-image" src="'.JURI::base().$iParams->get('menu_image').'" alt=" " />' . $menu_title . '</span>';
			} else {
				$txt = '<span class="menu"><span class="has-image" style="background-image:url('.JURI::base().$iParams->get('menu_image').')">' . $menu_title . '</span></span>';			
			}
		} else {
			$txt = '<span class="menu">' . $menu_title. '</span>';
		}
				
		if ($item->type == 'menulink')
		{
			$menu = &JSite::getMenu();
			$alias_item = clone($menu->getItem($item->query['Itemid']));
			if (!$alias_item) {
				return false;
			} else {
				$item->url = $alias_item->link;
			}
		}
		
		//Handle Links

		switch ($item->type)
		{
			case 'separator' :
				$item->url = '#';
				break;
				
			case 'url' :
				if ((strpos($item->link, 'index.php?') !== false) && (strpos($item->link, 'Itemid=') === false)) {
					$item->url = $item->link.'&amp;Itemid='.$item->id;
				} else {
					$item->url = $item->link;
				}
				break;
				
			case 'alias':
				$item->url = 'index.php?Itemid='.$item->params->get('aliasoptions');
				break;
				
			default :
				$router = JSite::getRouter();
				$item->url = $router->getMode() == JROUTER_MODE_SEF ? 'index.php?Itemid='.$item->id : $item->link.'&Itemid='.$item->id;
				break;

		}			
		
		if ($item->name) {
			if ($item->type == 'separator')
			{
				$data = '<a href="#" '.$active.'>'.$txt.'</a>';				
			} else {
				if ($item->url != null)
				{
					// Handle SSL links
					
					$iParams =new JObject(json_decode($item->params));
					
					$iSecure = $iParams->def('secure', 0);
					if ($item->home == 1) {
						$item->url = JURI::base();
					} elseif (strcasecmp(substr($item->url, 0, 4), 'http') && (strpos($item->link, 'index.php?') !== false)) {
						$item->url = JRoute::_($item->url, true, $iSecure);
					} else {
						$item->url = str_replace('&', '&amp;', $item->url);
					}
					
					switch ($item->browserNav)
					{
						default:
						case 0:
							// _top
							$data = '<a href="'.$item->url.'" '.$active.' '.$title.'>'.$txt.'</a>';
							break;
						case 1:
							// _blank
							$data = '<a href="'.$item->url.'" target="_blank" '.$active.' '.$title.'>'.$txt.'</a>';
							break;
						case 2:
							// window.open
							$attribs = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,'.$this->helix->Param('window_open');
	  
							// hrm...this is a bit dickey
							$link = str_replace('index.php', 'index2.php', $item->url);
							$data = '<a href="'.$link.'" onclick="window.open(this.href,\'targetWindow\',\''.$attribs.'\');return false;" '.$active.' '.$title.'>'.$txt.'</a>';
							break;
					}
				} else {
					$data = '<a '.$active.' '.$title.'>'.$txt.'</a>';
				}
			}
		}
		
		//Load module or module positions
		if ($this->_menu=='mega') {
			//For group
			$subcont=$item->megaparams->get ('subcontent');
			
			if ($item->megaparams->get('group') && $data) {
				$data = "<div class=\"sp-menu-group-title\">$data</div>";
				
				if ($subcont =='modules' || $subcont =='positions') {
						//Start module loading
						$data .= '<div class="sp-menu-group-content">';
						$data .= $this->loadSubContent($item,$subcont);
						$data .= '</div>';
				}
			}elseif ($data && ($subcont =='modules' || $subcont =='positions')){//Not group and has sub content
				$data .= $this->beginUl($item->id, $level+1, true);
				$data .= $this->beginMegaSub($item->id, $level+1, $pos, 0, true);
				$data .= $this->loadSubContent($item,$subcont, $level+1, true);//false force to echo result
				$data .= $this->endMegaSub($item->id, $level+1,true);
				$data .= $this->endUl($item->id, $level+1, true);
			}
		}
		//End Megamenu
		echo $data;				
	}
	
	//Load subcontent
	function loadSubContent($item=null,$subItem=null,$level=0,$ret=true) {
		$data='';
		if (!$item->megaparams->get('group')) $data .= '<ul class="sp-menu level-' . $level . '"><li class="menu-item sp-menu-group"><div class="sp-menu-group"><div class="sp-menu-group-content">';
		if ($subItem=='modules') { //load Modules
			$mods=$item->megaparams->get('mods');
			$mods= is_array($mods) ? $mods : preg_split ('/,/',$mods);
			foreach ($mods as $mod) {
				$data .=$this->loadModule ($mod);
			}
		} elseif ($subItem=='positions') {//Load module Positions
			$positions=$item->megaparams->get('positions');
			$positions=is_array($positions) ? $positions : preg_split ('/,/',$positions);
			foreach ($positions as $pos) {
				$data .=$this->loadModule ($pos);
			}						
		}
		if (!$item->megaparams->get('group')) $data .= '</div></div></li></ul>';	
		
		if ($ret) return $data; else echo $data;//return or echo result
	}
	
	/*Menu Item Styling area started*/
	
	function beginUl($pid=0, $level=0, $ret = false){
		$data='';
		if ($level) {
			if ($this->_menu=='mega') {//For megamenu
				if ($this->items[$pid]->megaparams->get('group')) {
					$data .= '<div class="sp-menu-group-content">';
				} else {
				//If not group
				$cols = $pid && $this->_menu=='mega' && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;
				$width = $this->items[$pid]->megaparams->get('cwidth', 0);
				if (!$width) {
					for ($col=0;$col<$cols;$col++) {
						$colw = $this->items[$pid]->megaparams->get('colw'.($col+1), 0);
						if (!$colw) $colw = $this->items[$pid]->megaparams->get('colw', $this->getParam ('menu_col_width',200));
						$width += $colw;
					}
				}
				$style = $width?" style=\"width: {$width}px;\"":"";
				$data .= '<div class="sp-submenu"><div class="sp-submenu-wrap"><div class="sp-submenu-inner clearfix"' . $style . '>';
				
				}

			} elseif($this->_menu=='split' || $this->_menu=='drop') {//Split or Dropline menu
				if ($level != 1) {
					$width = $this->getParam ('menu_col_width',200);
					$style = $width?" style=\"width: {$width}px;\"":"";
					$data .= '<div class="sp-submenu"><div class="sp-submenu-wrap"><div class="sp-submenu-inner clearfix"' . $style . '>';
				}
			} 

			elseif($this->_menu=='mobile') {//mobile menu
					$data .= '';
				
			} 


			else {//Others menu
				$width = $this->getParam ('menu_col_width',200);
				$style = $width?" style=\"width: {$width}px;\"":"";
				$data .= '<div class="sp-submenu"><div class="sp-submenu-wrap"><div class="sp-submenu-inner clearfix"' . $style . '>';
			}
		if ($ret) return $data; else echo $data;
		}
	}
	
	function endUl($pid=0, $level=0, $ret = false){
		$data='';
		if ($level) {
			if ($this->_menu=='mega') {
				if ($this->items[$pid]->megaparams->get('group')) {
					$data .= "</div>";
				}else{
					$data .= '</div></div></div>';
				}	
			} elseif($this->_menu=='split' || $this->_menu=='drop') {
				if ($level != 1) {
					$data .= '</div></div></div>';
				}
			} 

			elseif($this->_menu=='mobile') {//mobile menu
					$data .= '';
				
			} 

			else {
				$data .= '</div></div></div>';
			}
		if ($ret) return $data; else echo $data;
		}	
	}

	function beginLi($mitem=null, $level = 0, $pos = ''){
		$active = $this->genClass ($mitem, $level, $pos);
		//echo "<li id='menu-item-{$mitem->id}' $active>";
		echo "<li $active>";

		if ($this->_menu=='mega' && $mitem->megaparams->get('group')) echo "<div class=\"sp-menu-group\">";//If megamenu
	}
	
	function endLi($mitem=null, $level = 0, $pos = ''){
		if ($this->_menu=='mega' && $mitem->megaparams->get('group')) echo "</div>";//If megamenu
		echo "</li>";
	}
	
	//For MegaMenu
	function beginMegaSub($pid=0, $level=0, $pos, $i, $ret=false){
		$data = '';
		if ($this->_menu=='mega') {
			if (isset ($this->items[$pid]) && $level) {
				$cols = $pid && $this->_menu=='mega' && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;				
				if ($this->items[$pid]->megaparams->get('group') && $cols < 2) {
				}else {
					$colw = (int) $this->items[$pid]->megaparams->get('colw'.($i+1), 0);
					if (!$colw) $colw = $this->items[$pid]->megaparams->get('colw', $this->getParam ('menu_col_width',200));
					if(is_null($colw) || !is_numeric($colw)) $colw = 200;
					$style = $colw?" style=\"width: {$colw}px;\"":"";
					$data .= "<div class=\"megacol col".($i+1).($pos?" $pos":"")."\"$style>";
				}
			}

		} elseif($this->_menu=='split' || $this->_menu=='drop') {
			if ($level != 1) {
				if (isset ($this->items[$pid]) && $level) {
					$colw=$this->getParam ('menu_col_width',200);
					if(is_null($colw) || !is_numeric($colw)) $colw = 200;
					$style = $colw?" style=\"width: {$colw}px;\"":"";
					$data .= "<div class=\"megacol ".($pos?" $pos":"")."\"$style>";
				}
			}		
		}

elseif($this->_menu=='mobile') {//mobile menu
					$data .= '';
				
			} 


		else{
			if (isset ($this->items[$pid]) && $level) {
				$colw=$this->getParam ('menu_col_width',200);
				if(is_null($colw) || !is_numeric($colw)) $colw = 200;
				$style = $colw?" style=\"width: {$colw}px;\"":"";
				$data .= "<div class=\"megacol ".($pos?" $pos":"")."\"$style>";
			}		
		}

		if($this->_menu=='mobile') {
			if($level>0) {
				$collapse = 'collapse collapse-'. $pid;
				$icon = '<span class="sp-menu-toggler collapsed" data-toggle="collapse" data-target=".collapse-' . $pid . '"><i class="icon-angle-right"></i><i class="icon-angle-down"></i></span>';
			} else{
				$collapse = '';
				$icon = '';
			}

			if (@$this->children[$pid]) $data .= $icon . '<ul class="' . $collapse . '">';//modified
		} else {
			if (@$this->children[$pid]) $data .= '<ul class="sp-menu level-' . $level . '">';//modified
		}

		
		if ($ret) return $data; else echo $data;
	}
	
	//For Megamenu
	function endMegaSub($pid=0, $level=0, $ret=false){
	
		$data = '';
		if (@$this->children[$pid]) $data .= '</ul>';
		if ($this->_menu=='mega') {
			if (isset ($this->items[$pid]) && $level) {
				$cols = $pid && $this->_menu=='mega' && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;				
				if ($this->items[$pid]->megaparams->get('group') && $cols < 2) {
				}else {
					$data .= "</div>";
				}
			}

		} elseif($this->_menu=='split' || $this->_menu=='drop') {
			if ($level != 1) {
				if (isset ($this->items[$pid]) && $level) {
					$data .= "</div>";
				}
			}		
		}

		elseif($this->_menu=='mobile') {//mobile menu
					$data .= '';
				
			} 

		else{
			if (isset ($this->items[$pid]) && $level) {
				$data .= "</div>";
			}		
		}
		if ($ret) return $data; else echo $data;
	}
	
	function genClass ($mitem, $level, $pos) {
		$iParams = new JObject(json_decode($mitem->params));
		$active = in_array($mitem->id, $this->open);
		$cls = '';
		$cls .= "menu-item".($active?" active":"").($pos?" $pos":"");
		
		if ($this->_menu=='mega') {
			if (@$this->children[$mitem->id] || ($mitem->megaparams->get('subcontent')=='modules') || ($mitem->megaparams->get('subcontent')=='positions') ) { 
				if ($mitem->megaparams->get('group')) $cls .= " sp-menu-group";
				elseif ($level < $this->helix->Param('endlevel')) $cls .= " parent ";
		}
		
		if ($mitem->megaparams->get('class')) $cls .= " ".$mitem->megaparams->get('class');
			
		} else {
			if (@$this->children[$mitem->id] && $level < ($this->getParam ('endlevel'))+1) $cls .= " parent";
		}
		return $cls?"class=\"$cls\"":"";			
	}
	
	/*End menu items styling*/
	
	function hasSubMenu($level) {
		$pid = $this->getParentId ($level);
		if (!$pid) return false;
		return $this->hasSubItems ($pid);
	}
	
	function hasSubItems($id){
		if (@$this->children[$id]) return true;
		return false;
	}	
	
	function hasSub () {
		$pid = $this->getParentId (1);
		if (!$pid) return false;
		return $this->hasSubItems ($pid);
	}
	
	function genMenu($startlevel=0, $endlevel = -1){

		$this->setParam('startlevel', $startlevel);
		$this->setParam('endlevel', $endlevel==-1?20:$endlevel);
		
		if ($this->helix->Param('startlevel') == 0) {
			//for first level
			$this->genMenuItems (1, 0);
		}else{
			//for sub level
			$pid = $this->getParentId($this->helix->Param('startlevel'));
			if ($pid)
			$this->genMenuItems ($pid, $this->helix->Param('startlevel'));
		}	
	}
	
	function showMenu($startlevel=0, $endlevel = 20){
		if ($this->_menu=='drop') {
				if ($startlevel == 0) $this->genMenu(0,0);
				else {
					$this->setParam('startlevel', $startlevel);
					$this->setParam('endlevel', $endlevel);
					//Sub level
					$pid = $this->getParentId($startlevel - 1);
					if (@$this->children[$pid]) {
						foreach ($this->children[$pid] as $row) {
							if (@$this->children[$row->id]) {
								$this->genMenuItems ($row->id, $startlevel);
							} else {
								echo "<ul class=\"sp-menu empty level-1\"><li class=\"empty\">&nbsp;</li></ul>";
							}
						}
					}
				}	
		} elseif ($this->_menu=='split') {
			if ($startlevel == 0) $this->genMenu(0,0);
			else $this->genMenu($startlevel, $endlevel);
		} else {
			$this->genMenu(0);
		}
	}
	
	function genMenuItems($pid, $level) {//From T3 framework by JoomlArt.com
		if (@$this->children[$pid]) {
			$j = 0;
			$cols = $pid && $this->_menu=='mega' && isset($this->items[$pid]) && isset($this->items[$pid]->cols) && $this->items[$pid]->cols ? $this->items[$pid]->cols : 1;				
			$total = count ($this->children[$pid]);
			$tmp = $pid && isset($this->items[$pid])?$this->items[$pid]:new stdclass();
			if ($cols > 1) {
				$fixitems = count($tmp->col);
				if ($fixitems < $cols) {
					$fixitem = array_sum($tmp->col);
					$leftitem = $total-$fixitem;
					$items = ceil ($leftitem/($cols-$fixitems));
					for ($m=0;$m<$cols && $leftitem > 0;$m++) {
						if (!isset($tmp->col[$m]) || !$tmp->col[$m]) { 
							if ($leftitem > $items) {
								$tmp->col[$m] = $items;
								$leftitem -= $items;
							} else {
								$tmp->col[$m] = $leftitem;
								$leftitem = 0;
							}
						}
					}
					
					$cols = count ($tmp->col);
					$tmp->cols = $cols;
				}
			} else {
				$tmp->col = array($total);
			}
			
			$this->beginUl($pid, $level);
			
			for ($col=0;$col<$cols && $j<$total;$col++) {
				$pos = ($col == 0 ) ? 'first' : (($col == $cols-1) ? 'last' :'');
				$this->beginMegaSub($pid, $level, $pos, $col);//begin megasub items
				
				$i = 0;
				while ($i < $tmp->col[$col] && $j<$total) {
					$row = $this->children[$pid][$j];
					$pos = ($i == 0 ) ? 'first' : (($i == count($this->children[$pid])-1) ? 'last' :'');
					$iParams = new JObject(json_decode($row->params));
					
					$this->beginLi($row, $level, $pos);
					$this->genMenuItem( $row, $level, $pos);

					if ($this->_menu=='mega' && $row->megaparams->get('group')) $this->genMenuItems( $row->id, $level ); //Do not increase level if group
						elseif ($level < $this->helix->Param('endlevel')) $this->genMenuItems( $row->id, $level+1 );

					$this->endLi($row, $level, $pos);
					$j++;$i++;
				}
				$this->endMegaSub($pid, $level);//end megasub items
			}
			$this->endUl($pid, $level);
		}
	}
	
	//get parameter
	function getParam($paramName, $default=null){
		return $this->_params->get($paramName, $default);
	}
	
	//set parameter
	function setParam($paramName, $paramValue){
		return $this->_params->set($paramName, $paramValue);
	}
	
	//get parent id
	function getParentId ($level) {
		if (!$level || (count($this->open) < $level)) return 1;
		return $this->open[count($this->open)-$level];
	}
	
	//Load Module by id or position
	function loadModule($mod)
	{
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$groups		= implode(',', $user->getAuthorisedViewLevels());
		$lang 		= JFactory::getLanguage()->getTag();
		$clientId 	= (int) $app->getClientId();
		
		$db	= JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title, module, position, content, showtitle, params');
		$query->from('#__modules AS m');
		$query->where('m.published = 1');
		if (is_numeric($mod)) {
			$query->where('m.id = ' . $mod);
		} else {
			$query->where('m.position = "' . $mod . '"');
		}

		$date = JFactory::getDate();
		$now = $date->toSql();
		$nullDate = $db->getNullDate();
		$query->where('(m.publish_up = '.$db->Quote($nullDate).' OR m.publish_up <= '.$db->Quote($now).')');
		$query->where('(m.publish_down = '.$db->Quote($nullDate).' OR m.publish_down >= '.$db->Quote($now).')');

		$query->where('m.access IN ('.$groups.')');
		$query->where('m.client_id = '. $clientId);
		
		// Filter by language
		if ($app->isSite() && $app->getLanguageFilter()) {
			$query->where('m.language IN (' . $db->Quote($lang) . ',' . $db->Quote('*') . ')');
		}

		$query->order('position, ordering');

		// Set the query
		$db->setQuery($query);

		$modules = $db->loadObjectList();
		
		if (!$modules) return null;
		
		$options = array('style' => 'sp_menu');
		$output = '';
		ob_start();
		foreach ($modules as $module) {
			$file				= $module->module;
			$custom				= substr($file, 0, 4) == 'mod_' ?  0 : 1;
			$module->user		= $custom;
			$module->name		= $custom ? $module->title : substr($file, 4);
			$module->style		= null;
			$module->position	= strtolower($module->position);
			$clean[$module->id]	= $module;		
			echo JModuleHelper::renderModule($module, $options);
		}
		$output = ob_get_clean();
		return $output;		
	}
}