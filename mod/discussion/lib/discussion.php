<?php
/**
 * Discussion function library
 */

/**
 * List discussions
 * 
 * There are three list behaviors depending on the given parameter
 * - User guid: lists the discussion created by the user
 * - Group guid: lists the discussion created inside the group
 * - Null: lists all discussions on the site
 *
 * @param int $container_guid The GUID of the page owner or NULL for all discussions
 * @return array
 */
function discussion_handle_list_page($container_guid = NULL) {

	$params = array();

	$params['filter_context'] = $container_guid ? 'mine' : 'all';

	$options = array(
		'type' => 'object',
		'subtype' => 'discussion',
		'full_view' => false,
		'order_by' => 'e.last_action asc',
		'no_results' => elgg_echo('discussion:none'),
	);

	$current_user = elgg_get_logged_in_user_entity();

	if ($container_guid) {
		// access check for closed groups
		elgg_group_gatekeeper();

		$options['container_guid'] = $container_guid;
		$container = get_entity($container_guid);
		if (!$container) {
			register_error(elgg_echo('discussion:topic:notfound'));
			forward('404');
		}

		if (elgg_instanceof($container, 'group')) {
			// Displaying discussions created inside a group
			$title_string = 'discussion:title:owner:group';
			$params['filter'] = false;
		} else {
			if ($current_user && ($container_guid == $current_user->guid)) {
				// Displaying discussions started by the logged in user
				$title_string = 'discussion:title:owned';
				$params['filter_context'] = 'mine';
			} else {
				// Displaying discussions started by a user
				$title_string = 'discussion:title:owner:user';
				$params['filter_context'] = 'none';
			}
		}

		$params['title'] = elgg_echo($title_string, array($container->getDisplayName()));

		$crumbs_title = $container->getDisplayName();
		elgg_push_breadcrumb($crumbs_title);
	} else {
		$params['filter_context'] = 'all';
		$params['title'] = elgg_echo('discussion:title:all');
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('discussion:all'));
	}

	elgg_register_title_button();

	$params['content'] = elgg_list_entities_from_metadata($options);

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Edit or add a discussion topic
 *
 * @param string $type 'add' or 'edit'
 * @param int    $guid GUID of discussion topic
 */
function discussion_handle_edit_page($type, $guid) {
	elgg_gatekeeper();

	if ($type == 'add') {
		$container = get_entity($guid);

		$title = elgg_echo('discussion:add');

		elgg_push_breadcrumb($container->name, "discussion/owner/$container->guid");
		elgg_push_breadcrumb($title);

		$body_vars = discussion_prepare_form_vars();
		$content = elgg_view_form('discussion/save', array(), $body_vars);
	} else {
		$topic = get_entity($guid);
		if (!elgg_instanceof($topic, 'object', 'discussion') || !$topic->canEdit()) {
			register_error(elgg_echo('discussion:topic:notfound'));
			forward();
		}
		$container = $topic->getContainerEntity();

		$title = elgg_echo('discussion:edit');

		elgg_push_breadcrumb($container->name, "discussion/owner/$container->guid");
		elgg_push_breadcrumb($topic->title, $topic->getURL());
		elgg_push_breadcrumb($title);

		$body_vars = discussion_prepare_form_vars($topic);
		$content = elgg_view_form('discussion/save', array(), $body_vars);
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * View a discussion topic
 *
 * @param int $guid GUID of topic
 */
function discussion_handle_view_page($guid) {
	// We now have RSS on topics
	global $autofeed;
	$autofeed = true;

	elgg_entity_gatekeeper($guid, 'object', 'discussion');

	$topic = get_entity($guid);

	$container = $topic->getContainerEntity();

	elgg_set_page_owner_guid($container->getGUID());

	elgg_group_gatekeeper();

	elgg_push_breadcrumb($container->name, "discussion/owner/$container->guid");
	elgg_push_breadcrumb($topic->title);

	$content = elgg_view_entity($topic, array('full_view' => true));
	if ($topic->status == 'closed') {
		$content .= elgg_view_comments($topic, false);
		$content .= elgg_view('discussion/closed');
	} elseif ($topic->canComment() || elgg_is_admin_logged_in()) {
		$content .= elgg_view_comments($topic);
	} else {
		$content .= elgg_view_comments($topic, false);
	}

	$params = array(
		'content' => $content,
		'title' => $topic->title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($topic->title, $body);
}

/**
 * List discussions started by user's friends'
 *
 * @param int $user_guid
 */
function discussion_handle_friends_page($user_guid) {
	$user = get_user($user_guid);

	$params = array();

	$params['filter_context'] = 'friends';
	$params['title'] = elgg_echo('discussion:title:friends');

	$crumbs_title = $user->name;
	elgg_push_breadcrumb($crumbs_title, "discussion/owner/{$user->username}");
	elgg_push_breadcrumb(elgg_echo('friends'));

	elgg_register_title_button();

	$options = array(
		'type' => 'object',
		'subtype' => 'discussion',
		'full_view' => false,
		'relationship' => 'friend',
		'relationship_guid' => $user_guid,
		'relationship_join_on' => 'container_guid',
		'no_results' => elgg_echo('discussion:none'),
		'order_by' => 'e.last_action asc',
	);

	$params['content'] = elgg_list_entities_from_relationship($options);

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($topic->title, $body);
}

/**
 * Prepare discussion topic form variables
 *
 * @param ElggObject $topic Topic object if editing
 * @return array
 */
function discussion_prepare_form_vars($topic = NULL) {
	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'status' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'topic' => $topic,
	);

	if ($topic) {
		foreach (array_keys($values) as $field) {
			if (isset($topic->$field)) {
				$values[$field] = $topic->$field;
			}
		}
	}

	if (elgg_is_sticky_form('topic')) {
		$sticky_values = elgg_get_sticky_values('topic');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('topic');

	return $values;
}
