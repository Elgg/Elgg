<?php
/**
 * Elgg Message board
 * This plugin allows users and groups to attach a message board to their profile for other users
 * to post comments.
 *
 * @package MessageBoard
 */

/**
 * MessageBoard initialisation
 */
function messageboard_init() {
	elgg_register_page_handler('messageboard', 'messageboard_page_handler');

	// delete annotations for posts
	elgg_register_plugin_hook_handler('register', 'menu:annotation', 'messageboard_annotation_menu_setup');
}

/**
 * Messageboard dispatcher for flat message board.
 * Profile (and eventually group) widgets handle their own.
 *
 * URLs take the form of
 *  User's messageboard:               messageboard/owner/<username>
 *  Y's history of posts on X's board: messageboard/owner/<X>/history/<Y>
 *  New post:                          messageboard/add/<guid> (container: user or group)
 *  Group messageboard:                messageboard/group/<guid>/all (not implemented)
 *
 * @param array $page Array of page elements
 * @return bool
 */
function messageboard_page_handler($page) {

	$vars = [];
	switch ($page[0]) {
		case 'owner':
			//@todo if they have the widget disabled, don't allow this.
			$owner_name = elgg_extract(1, $page);
			$owner = get_user_by_username($owner_name);
			$vars['page_owner_guid'] = $owner->guid;
			$history = elgg_extract(2, $page);
			$username = elgg_extract(3, $page);

			if ($history && $username) {
				$vars['history_username'] = $username;
			}

			echo elgg_view_resource('messageboard/owner', $vars);
			break;

		case 'group':
			elgg_group_gatekeeper();
			$owner_guid = elgg_extract(1, $page);
			$vars['page_owner_guid'] = $owner_guid;
			echo elgg_view_resource('messageboard/owner', $vars);
			break;

		default:
			return false;
	}
	return true;
}

/**
 * Add messageboard post
 *
 * @param ElggUser $poster    User posting the message
 * @param ElggUser $owner     User who owns the message board
 * @param string   $message   The posted message
 * @param int      $access_id Access level (see defines in elgglib.php)
 * @return bool
 */
function messageboard_add($poster, $owner, $message, $access_id = ACCESS_PUBLIC) {
	$result_id = $owner->annotate('messageboard', $message, $access_id, $poster->guid);

	if (!$result_id) {
		return false;
	}

	elgg_create_river_item([
		'view' => 'river/object/messageboard/create',
		'action_type' => 'messageboard',
		'subject_guid' => $poster->guid,
		'object_guid' => $owner->guid,
		'access_id' => $access_id,
		'annotation_id' => $result_id,
	]);

	// Send notification only if poster isn't the owner
	if ($poster->guid != $owner->guid) {
		$subject = elgg_echo('messageboard:email:subject', [], $owner->language);
		$url = elgg_get_site_url() . "messageboard/owner/" . $owner->username;

		$body = elgg_echo('messageboard:email:body', [
			$poster->name,
			$message,
			$url,
			$poster->name,
			$poster->getURL()
		], $owner->language);

		$params = [
			'action' => 'create',
			'object' => elgg_get_annotation_from_id($result_id),
			'url' => $url,
		];
		notify_user($owner->guid, $poster->guid, $subject, $body, $params);
	}

	return $result_id;
}


/**
 * Add edit and delete links for forum replies
 */
function messageboard_annotation_menu_setup($hook, $type, $return, $params) {
	$annotation = elgg_extract('annotation', $params);
	if ($annotation->name !== 'messageboard') {
		return;
	}

	if (!$annotation->canEdit()) {
		return;
	}
	
	$url = elgg_http_add_url_query_elements('action/messageboard/delete', [
		'annotation_id' => $annotation->id,
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'delete',
		'href' => $url,
		'text' => elgg_view_icon('delete'),
		'confirm' => elgg_echo('deleteconfirm'),
		'encode_text' => false,
	]);
	
	return $return;
}

elgg_register_event_handler('init', 'system', 'messageboard_init');
