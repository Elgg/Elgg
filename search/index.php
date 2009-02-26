<?php

	/**
	 * Generic search viewer
	 * Given a GUID, this page will try and display any entity
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
		
	// Set context
		set_context('search');
		
	// Get input
		$tag = get_input('tag');
		$subtype = get_input('subtype');
		if (!$objecttype = get_input('object')) {
			$objecttype = "";
		}
		if (!$md_type = get_input('tagtype')) {
			$md_type = "";			
		}
		$owner_guid = get_input('owner_guid',0);
		if (substr_count($owner_guid,',')) {
			$owner_guid_array = explode(",",$owner_guid);
		} else {
			$owner_guid_array = $owner_guid;
		}
		$friends = (int) get_input('friends',0);
		if ($friends > 0) {
			if ($friends = get_user_friends($friends,'',9999)) {
				$owner_guid_array = array();
				foreach($friends as $friend) {
					$owner_guid_array[] = $friend->guid;
				}
			} else {
				$owner_guid = -1;
			}
		}
		
	// Set up submenus
		if ($object_types = get_registered_entity_types()) {
			
			foreach($object_types as $object_type => $subtype_array) {
				if (is_array($subtype_array) && sizeof($subtype_array))
					foreach($subtype_array as $object_subtype) {
						$label = 'item:' . $object_type;
						if (!empty($object_subtype)) $label .= ':' . $object_subtype;
						global $CONFIG;
						add_submenu_item(elgg_echo($label), $CONFIG->wwwroot . "search/?tag=". urlencode($tag) ."&subtype=" . $object_subtype . "&object=". urlencode($object_type) ."&tagtype=" . urlencode($md_type) . "&owner_guid=" . urlencode($owner_guid));
					}
			}
			add_submenu_item(elgg_echo('all'), $CONFIG->wwwroot . "search/?tag=". urlencode($tag) ."&owner_guid=" . urlencode($owner_guid));
			
		}
		
		if (empty($objecttype) && empty($subtype)) {
			$title = sprintf(elgg_echo('searchtitle'),$tag); 
		} else {
			if (empty($objecttype)) $objecttype = 'object';
			$itemtitle = 'item:' . $objecttype;
			if (!empty($subtype)) $itemtitle .= ':' . $subtype;
			$itemtitle = elgg_echo($itemtitle);
			$title = sprintf(elgg_echo('advancedsearchtitle'),$itemtitle,$tag);
		}
		
		if (!empty($tag)) {
			$body = "";
			$body .= elgg_view_title($title); // elgg_view_title(sprintf(elgg_echo('searchtitle'),$tag));
			$body .= trigger_plugin_hook('search','',$tag,"");
			$body .= elgg_view('search/startblurb',array('tag' => $tag));
			$body .= list_entities_from_metadata($md_type, $tag, $objecttype, $subtype, $owner_guid_array, 10, false, false);
			$body = elgg_view_layout('two_column_left_sidebar','',$body);
		}
		
		page_draw($title,$body);

?>