[<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	$entity = $vars['entity'];
	$parent = $vars['entity']->parent_guid;
	
	$currentpage = (int) get_input('currentpage');
	if ($currentpage) {
		
		$path = pages_get_path($currentpage);
		$pathstring = implode(',',$path);
		
	} else {
		$path = array();
	}
	
	function pages_draw_child($childentity, $path) {
		
				$child = "\n\t\t{\n";
				$child .= "\t\t\t\"text\": \"<a href=\\\"{$childentity->getURL()}\\\">{$childentity->title}</a>\",\n";
				
				$haschild = get_entities_from_metadata('parent_guid',$childentity->guid,'','',0,9999);
				if ($haschild) {
					if (in_array($childentity->getGUID(),$path)) {
						$child .= "\t\t\t\"expanded\": true,";
						$child .= "\t\t\t\"children\": [\n";
						
						$childstring = "";
						foreach($haschild as $subchild) {
							if (!empty($childstring)) $childstring .= ", ";
							$childstring .= pages_draw_child($subchild,$path);
						}
						
						$child .= $childstring . "\n\t\t\t]\n";
					} else {
						$child .= "\t\t\t\"id\": \"{$childentity->getGUID()}\",\n\t\t\t\"hasChildren\": true\n";
					}
					
				}				
				$child .= "\t\t}"; 
		return $child;
	}
	
	if (!$parent) {
		echo "{\n";
		echo "\t\"text\": \"<a href=\\\"{$vars['entity']->getURL()}\\\">{$vars['entity']->title}</a>\",\n";
	}
		$children = "";
		if (isset($vars['children']) && is_array($vars['children']) && (!isset($vars['fulltree']) || $vars['fulltree'] == 0)) {
			if (!$parent) echo "\t" . '"expanded": true,' . "\n";
			if (!$parent) echo "\t" . '"children": [' . "\n";		
			foreach($vars['children'] as $child) {
				if (!empty($children)) $children .= ", \n";
				/*
				 $children .= "\n\t\t{\n";
				$children .= "\t\t\t\"text\": \"<a href=\\\"{$child->getURL()}\\\">{$child->title}</a>\",\n";
				
				$haschild = get_entities_from_metadata('parent_guid',$child->guid,'','',0,10,0,'',0,true);
				if ($haschild) {
					$children .= "\t\t\t\"id\": \"{$child->getGUID()}\",\n\t\t\t\"hasChildren\": true\n";
				}				
				$children .= "\t\t}";
				*/
				$children .= pages_draw_child($child,$path); 
			}
			echo $children;
			if (!$parent) echo "\t\t" . ']' . "\n";
		
		}
		
	if (!$parent) echo "}";

?>]