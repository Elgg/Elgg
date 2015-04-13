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
	// js
	elgg_extend_view('js/elgg', 'messageboard/js');

	// css
	elgg_extend_view('css/elgg', 'messageboard/css');

	elgg_register_page_handler('messageboard', 'messageboard_page_handler');

	// messageboard widget - only for profile for now
	elgg_register_widget_type('messageboard', elgg_echo("messageboard:board"), elgg_echo("messageboard:desc"), array("profile"));

	// actions
	$action_path = dirname(__FILE__) . '/actions';
	elgg_register_action("messageboard/add", "$action_path/add.php");
	elgg_register_action("messageboard/delete", "$action_path/delete.php");

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

	$pages = dirname(__FILE__) . '/pages/messageboard';

	switch ($page[0]) {
		case 'owner':
			//@todo if they have the widget disabled, don't allow this.
			$owner_name = elgg_extract(1, $page);
			$owner = get_user_by_username($owner_name);
			set_input('page_owner_guid', $owner->guid);
			$history = elgg_extract(2, $page);
			$username = elgg_extract(3, $page);

			if ($history && $username) {
				set_input('history_username', $username);
			}

			include "$pages/owner.php";
			break;

		case 'add':
			$container_guid = elgg_extract(1, $page);
			set_input('container_guid', $container_guid);
			include "$pages/add.php";
			break;

		case 'group':
			elgg_group_gatekeeper();
			$owner_guid = elgg_extract(1, $page);
			set_input('page_owner_guid', $owner_guid);
			include "$pages/owner.php";
			break;

		default:
			return false;
	}
	return true;
}

/**
 * Add messageboard post
 *
 * @param ElggUser $poster User posting the message
 * @param ElggUser $owner User who owns the message board
 * @param string $message The posted message
 * @param int $access_id Access level (see defines in elgglib.php)
 * @return bool
 */
function messageboard_add($poster, $owner, $message, $access_id = ACCESS_PUBLIC) {
	$result_id = $owner->annotate('messageboard', $message, $access_id, $poster->guid);

	if (!$result_id) {
		return false;
	}

	elgg_create_river_item(array(
		'view' => 'river/object/messageboard/create',
		'action_type' => 'messageboard',
		'subject_guid' => $poster->guid,
		'object_guid' => $owner->guid,
		'access_id' => $access_id,
		'annotation_id' => $result_id,
	));

	// Send notification only if poster isn't the owner
	if ($poster->guid != $owner->guid) {

		$subject = elgg_echo('messageboard:email:subject', array(), $owner->language);

		$body = elgg_echo('messageboard:email:body', array(
			$poster->name,
			$message,
			elgg_get_site_url() . "messageboard/owner/" . $owner->username,
			$poster->name,
			$poster->getURL()
		), $owner->language);

		notify_user($owner->guid, $poster->guid, $subject, $body);
	}

	return $result_id;
}


/**
 * Add edit and delete links for forum replies
 */
function messageboard_annotation_menu_setup($hook, $type, $return, $params) {
	$annotation = $params['annotation'];
	if ($annotation->name != 'messageboard') {
		return $return;
	}

	if ($annotation->canEdit()) {
		$url = elgg_http_add_url_query_elements('action/messageboard/delete', array(
			'annotation_id' => $annotation->id,
		));

		$options = array(
			'name' => 'delete',
			'href' => $url,
			'text' => elgg_view_icon('delete'),
			'confirm' => elgg_echo('deleteconfirm'),
			'encode_text' => false
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

elgg_register_event_handler('init', 'system', 'messageboard_init');