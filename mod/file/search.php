<?php

	/**
	 * Elgg file search
	 * 
	 * @package ElggFile

	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

		
	// Get input
		$md_type = 'simpletype';
		// avoid XSS attacks.
		$tag = strip_tags(get_input('tag'));
		$tag_display = mb_convert_encoding($tag, 'HTML-ENTITIES', 'UTF-8');
		$tag_display = htmlspecialchars($tag_display, ENT_QUOTES, 'UTF-8', false);
		
		$search_viewtype = get_input('search_viewtype');

		$friends = (int) get_input('friends_guid', 0);
		if ($friends) {
			if ($owner_guid = get_user_friends($user_guid, "", 999999, 0)) {
				foreach($owner_guid as $key => $friend)
					$owner_guid[$key] = (int) $friend->getGUID();
			} else {
				$owner_guid = array();
			}
		} else {
			$owner_guid = get_input('owner_guid', 0);
			if (substr_count($owner_guid, ',')) {
				$owner_guid = explode(",",$owner_guid);
				$owner_guid = array_map('sanitise_int', $owner_guid);
			} else {
				$owner_guid = (int)$owner_guid;
			}
		}
		$page_owner = (int)get_input('page_owner', 0);
		if ($page_owner) { 
			set_page_owner($page_owner);
		} else {
			if ($friends) {
				set_page_owner($friends);				
			} else {
				if ($owner_guid > 0 && !is_array($owner_guid))
					set_page_owner($owner_guid);
			}
		}
		
		if (is_callable('group_gatekeeper')) group_gatekeeper();

		if (empty($tag)) {
			$title = elgg_echo('file:type:all');
			$area2 = elgg_view_title(elgg_echo('file:type:all'));
		} else {
			$title = sprintf(elgg_echo('searchtitle'), $tag_display);
			if (is_array($owner_guid)) {
				$area2 = elgg_view_title(elgg_echo("file:friends:type:" . $tag_display));
			} else if (page_owner() && page_owner() != $_SESSION['guid']) {
				$area2 = elgg_view_title(sprintf(elgg_echo("file:user:type:" . $tag_display),page_owner_entity()->name));
			} else{
				$area2 = elgg_view_title(elgg_echo("file:type:" . $tag_display));
			}
		}
		if ($friends) {
			$area1 = get_filetype_cloud($friends,true);
		} else if ($owner_guid) {
			$area1 = get_filetype_cloud($owner_guid);
		} else {
			$area1 = get_filetype_cloud();
		}
		
		// Set context
		set_context('search');

		$offset = (int)get_input('offset', 0);
		$limit = 10;
		if ($search_viewtype == "gallery") $limit = 12;
		if (!empty($tag)) {
			$params = array(
				'metadata_name' => $md_type,
				'metadata_value' => $tag,
				'types' => 'object',
				'subtypes' => 'file',
				'owner_guid' => $owner_guid,
				'limit' => $limit,
			);
			$area2 .= elgg_list_entities_from_metadata($params);
		} else {
			$area2 .= elgg_list_entities(array('types' => 'object', 'subtypes' => 'file', 'owner_guid' => $owner_guid, 'limit' => $limit, 'offset' => $offset));
		}
		
		set_context("file");
		
		$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);
		
		page_draw($title, $body);

?>