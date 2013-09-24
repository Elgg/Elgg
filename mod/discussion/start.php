<?php
/**
 * Discussion
 */

elgg_register_event_handler('init', 'system', 'discussion_init');

/**
 * Initialize the plugin
 */
function discussion_init() {
	elgg_register_library('elgg:discussion', elgg_get_plugins_path() . 'discussion/lib/discussion.php');

	// Add discussions to site menu
	$item = new ElggMenuItem('discussion', elgg_echo('discussion:all'), 'discussion/all');
	elgg_register_menu_item('site', $item);

	elgg_register_page_handler('discussion', 'discussion_page_handler');

	$action_base = elgg_get_plugins_path() . 'discussion/actions/discussion';
	elgg_register_action('discussion/save', "$action_base/save.php");
	elgg_register_action('discussion/delete', "$action_base/delete.php");

	// Register for search.
	elgg_register_entity_type('object', 'discussion');

	// Add discussion as group tool option ("forum" instead of "discussion" for BC)
	add_group_tool_option('forum', elgg_echo('groups:enableforum'), true);
	elgg_extend_view('groups/tool_latest', 'discussion/group_module');

	elgg_register_plugin_hook_handler('entity:url', 'object', 'discussion_set_topic_url');

	// Discussion reply menu needs to be called after comment menu setup
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'discussion_reply_menu_setup', 1000);

	elgg_register_plugin_hook_handler('get_views', 'ecml', 'discussion_ecml_views_hook');

	// Add link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'discussion_owner_block_menu');

	elgg_register_plugin_hook_handler('permissions_check:comment', 'discussion', 'discussion_reply_permissions');

	// Notifications
	elgg_register_notification_event('object', 'discussion');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:discussion', 'discussion_prepare_notification');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:comment', 'discussion_prepare_reply_notification');

	elgg_register_event_handler('upgrade', 'system', 'discussion_run_upgrades');
}

/**
 * Discussion page handler
 *
 * URLs take the form of
 *  All discussions in the site:        discussion/all
 *  List discussions of a single user:  discussion/owner/<username>
 *  List discussions of user's friends: discussion/friends/<username>
 *  List discussions inside a group:    discussion/group/<guid>
 *  Add a new discussion:               discussion/add/<guid>
 *  Edit a discussion:                  discussion/edit/<guid>
 *  View a single discussion:           discussion/view/<guid>
 *
 * @param array $page Array of url segments for routing
 * @return bool
 */
function discussion_page_handler($page) {

	elgg_load_library('elgg:discussion');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}
	elgg_push_breadcrumb(elgg_echo('discussion:all'), 'discussion/all');

	switch ($page[0]) {
		case 'all':
			discussion_handle_list_page();
			break;
		case 'owner':
			$user = get_user_by_username($page[1]);
			if (!$user) {
				forward('', '404');
			}
			discussion_handle_list_page($user->getGUID());
			break;
		case 'friends':
			$user = get_user_by_username($page[1]);
			if (!$user) {
				forward('', '404');
			}
			$params = discussion_handle_friends_page($user->guid);
			break;
		case 'group':
			$group = get_entity($page[1]);
			if (!elgg_instanceof($group, 'group')) {
				forward('', '404');
			}
			discussion_handle_list_page($page[1]);
			break;
		case 'add':
			discussion_handle_edit_page('add', $page[1]);
			break;
		case 'edit':
			discussion_handle_edit_page('edit', $page[1]);
			break;
		case 'view':
			discussion_handle_view_page($page[1]);
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Override the discussion url
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function discussion_set_topic_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'discussion')) {
		return 'discussion/view/' . $entity->guid . '/' . elgg_get_friendly_title($entity->title);
	}
}

/**
 * Add link to owner block
 * 
 * @param string         $hook   'register'
 * @param string         $type   'menu:owner_block'
 * @param ElggMenuItem[] $return Array of ElggMenuItem objects
 * @param array          $params Menu parameters
 * @return ElggMenuItem[] $return Array of ElggMenuItem objects
 */
function discussion_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'group')) {
		if ($params['entity']->forum_enable != "no") {
			$url = "discussion/group/{$params['entity']->guid}";
			$return[] = new ElggMenuItem('discussion', elgg_echo('discussion:group'), $url);
		}
	} else {
		$url = "discussion/owner/{$params['entity']->username}";
		$return[] = new ElggMenuItem('discussion', elgg_echo('discussion'), $url);
	}

	return $return;
}

/**
 * Prepare a notification message about a new discussion topic
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg_Notifications_Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg_Notifications_Notification
 */
function discussion_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	$descr = $entity->description;
	$title = $entity->title;
	$group = $entity->getContainerEntity();

	$notification->subject = elgg_echo('discussion:topic:notify:subject', array($title), $language);
	$notification->body = elgg_echo('discussion:topic:notify:body', array(
		$owner->name,
		$title,
		$descr,
		$entity->getURL()
	), $language);
	$notification->summary = elgg_echo('discussion:topic:notify:summary', array($entity->title), $language);

	return $notification;
}

/**
 * Prepare a notification message about a new discussion reply
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg_Notifications_Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg_Notifications_Notification
 */
function discussion_prepare_reply_notification($hook, $type, $notification, $params) {
	$reply = $params['event']->getObject();
	$discussion = $reply->getContainerEntity();

	// Alter the notification message only if commenting on a discussion object
	if (!elgg_instanceof($topic, 'object', 'discussion')) {
		return $notification;
	}

	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	$descr = elgg_get_excerpt($reply->description);
	$title = $discussion->title;

	$notification->subject = elgg_echo('discussion:reply:notify:subject', array($title), $language);
	$notification->body = elgg_echo('discussion:reply:notify:body', array(
		$owner->name,
		$title,
		$descr,
		$discussion->getURL()
	), $language);
	$notification->summary = elgg_echo('discussion:topic:notify:summary', array($title), $language);

	return $notification;
}

/**
 * Process upgrades
 */
function discussion_run_upgrades() {
	$path = elgg_get_plugins_path() . 'discussion/upgrades/';
	$files = elgg_get_upgrade_files($path);
	foreach ($files as $file) {
		include "$path{$file}";
	}
}

/**
 * Display reply edit link to topic owner, group owner and admins
 * 
 * @param string          $hook   'register'
 * @param string          $type   'menu:entity'
 * @param ElggMenuItem[]  $return
 * @param array           $params
 * @return ElggMenuItem[] $return
 */
function discussion_reply_menu_setup($hook, $type, $return, $params) {
	// Make sure that we are modifying a comment menu
	if ($params['handler'] !== 'comment') {
		return $return;
	}

	// Do not show the menu item in widgets and site activity page
	if (elgg_in_context('widgets') || elgg_in_context('activity')) {
		return $return;
	}

	// Only logged in users are allowed to interact with comments
	if (!elgg_is_logged_in()) {
		return $return;
	}

	$reply = $params['entity'];
	$topic = $reply->getContainerEntity();

	if (elgg_instanceof($topic, 'object', 'discussion') && $topic->canEdit()) {
		// This menu item toggles the visibility of the comment form
		$return[] = ElggMenuItem::factory(array(
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "#edit-comment-{$reply->guid}",
			'priority' => 150,
			'link_class' => 'elgg-edit-comment',
			'rel' => 'toggle',
		));
	}

	return $return;
}

/**
 * Parse ECML on discussion views
 * 
 * @param string $hook
 * @param string $entity_type
 * @param string $return_value
 * @param array $params
 * @return string $return_value
 */
function discussion_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['forum/viewposts'] = elgg_echo('item:object:discussion');

	return $return_value;
}

/**
 * Allow user to reply on a discussion topic.
 * 
 * Anyone who has access to a discussion can also reply (add acomment)
 * to it. In other words allow user to comment on any object that is
 * of subtype "discussion" if user has read access to it.
 * 
 * @param string  $hook   'permissions_check:comment'
 * @param string  $type   'object'
 * @param boolean $return True if user is allowed to reply
 * @param array   $params Array containing user and the discussion entity
 * @return boolean $return True if user is allowed to reply
 */
function discussion_reply_permissions($hook, $type, $return, $params) {
	$discussion = $params['entity'];

	if (elgg_instanceof($discussion, 'object', 'discussion')) {
		$return = true;
	}

	return $return;
}