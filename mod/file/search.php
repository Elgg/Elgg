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
		$tag = get_input('tag');
		$listtype = get_input('listtype');

		$friends = (int) get_input('friends_guid',0);
		if ($friends) {
			if ($owner_guid = get_user_friends($user_guid, "", 999999, 0)) {
				foreach($owner_guid as $key => $friend)
					$owner_guid[$key] = (int) $friend->getGUID();
			} else {
				$owner_guid = array();
			}
		} else {
			$owner_guid = get_input('owner_guid',0);
			if (substr_count($owner_guid,',')) {
				$owner_guid = explode(",",$owner_guid);
			}
		}
		$page_owner = get_input('page_owner',0);
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
			$area2 = elgg_view('page_elements/content_header', array('context' => "everyone", 'type' => 'file'));
		} else {
			$title = elgg_echo('searchtitle',array($tag));
			if (is_array($owner_guid)) {
				//$area2 = elgg_view_title(elgg_echo("file:friends:type:" . $tag));
				$area2 = elgg_view('page_elements/content_header', array('context' => "friends", 'type' => 'file'));
			} else if (elgg_get_page_owner_guid() && elgg_get_page_owner_guid() != get_loggedin_userid()) {
				//$area2 = elgg_view_title(elgg_echo("file:user:type:" . $tag,array(elgg_get_page_owner()->name)));
				$area2 = elgg_view('page_elements/content_header', array('context' => "mine", 'type' => 'file'));
			} else{
				//$area2 = elgg_view_title(elgg_echo("file:type:" . $tag));
				$area2 = elgg_view('page_elements/content_header', array('context' => "everyone", 'type' => 'file'));
			}
		}
		if ($friends) {
			$area1 = get_filetype_cloud($friends,true);
		} else if ($owner_guid) {
			$area1 = get_filetype_cloud($owner_guid);
		} else {
			$area1 = get_filetype_cloud();
		}

		elgg_push_context('search');

		$offset = (int)get_input('offset', 0);
		$limit = 10;
		if ($listtype == "gallery") $limit = 12;
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

		elgg_pop_context();

		$content = "<div class='files'>".$area1.$area2."</div>";

		$body = elgg_view_layout('one_column_with_sidebar', array('content' => $content));

		echo elgg_view_page($title, $body);

?>