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
	elgg_register_widget_type('messageboard', elgg_echo("messageboard:board"), elgg_echo("messageboard:desc"), "profile");

	// actions
	$action_path = dirname(__FILE__) . '/actions';
	elgg_register_action("messageboard/add", "$action_path/add.php");
	elgg_register_action("messageboard/delete", "$action_path/delete.php");
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
	$new_section_one = array('owner', 'add', 'group');

	// if the first part is a username, forward to new format
	if (isset($page[0]) && !in_array($page[0], $new_section_one) && get_user_by_username($page[0])) {
		register_error(elgg_echo("changebookmark"));
		$url = "messageboard/owner/{$page[0]}";
		forward($url);
	}

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
			group_gatekeeper();
			$owner_guid = elgg_extract(1, $page);
			set_input('page_owner_guid', $owner_guid);
			include "$pages/owner.php";
			break;
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
	$result = $owner->annotate('messageboard', $message, $access_id, $poster->guid);

	if (!$result) {
		return false;
	}

	add_to_river('river/object/messageboard/create',
				'messageboard',
				$poster->guid,
				$owner->guid,
				$access_id,
				0,
				$result);

	// only send notification if not self
	if ($poster->guid != $owner->guid) {
		$subject = elgg_echo('messageboard:email:subject');
		$body = elgg_echo('messageboard:email:body', array(
						$poster->name,
						$message,
						elgg_get_site_url() . "messageboard/" . $owner->username,
						$poster->name,
						$poster->getURL()
						));

		notify_user($owner->guid, $poster->guid, $subject, $body);
	}

	return $result;
}

elgg_register_event_handler('init', 'system', 'messageboard_init');