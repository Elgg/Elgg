<?php
/**
 * Discussion function library
 */

/**
 * List all discussion topics
 */
function discussion_handle_all_page() {

	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('discussion'));

	$content = elgg_list_entities(array(
		'type' => 'object',
		'subtype' => 'groupforumtopic',
		'order_by' => 'e.last_action desc',
		'limit' => max(20, elgg_get_config('default_limit')),
		'full_view' => false,
		'no_results' => elgg_echo('discussion:none'),
		'preload_owners' => true,
		'preload_containers' => true,
	));

	$title = elgg_echo('discussion:latest');

	$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => elgg_view('discussion/sidebar'),
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * List discussion topics in a group
 *
 * @param int $guid Group entity GUID
 */
function discussion_handle_list_page($guid) {

	elgg_set_page_owner_guid($guid);

	elgg_group_gatekeeper();

	$group = get_entity($guid);
	if (!elgg_instanceof($group, 'group')) {
		forward('', '404');
	}
	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb(elgg_echo('item:object:groupforumtopic'));

	elgg_register_title_button();

	$title = elgg_echo('item:object:groupforumtopic');

	$options = array(
		'type' => 'object',
		'subtype' => 'groupforumtopic',
		'limit' => max(20, elgg_get_config('default_limit')),
		'order_by' => 'e.last_action desc',
		'container_guid' => $guid,
		'full_view' => false,
		'no_results' => elgg_echo('discussion:none'),
		'preload_owners' => true,
	);
	$content = elgg_list_entities($options);

	$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => elgg_view('discussion/sidebar'),
		'filter' => '',
	);

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Edit or add a discussion topic
 *
 * @param string $type 'add' or 'edit'
 * @param int    $guid GUID of group or topic
 */
function discussion_handle_edit_page($type, $guid) {
	elgg_gatekeeper();

	if ($type == 'add') {
		$group = get_entity($guid);
		if (!elgg_instanceof($group, 'group')) {
			register_error(elgg_echo('group:notfound'));
			forward();
		}

		// make sure user has permissions to add a topic to container
		if (!$group->canWriteToContainer(0, 'object', 'groupforumtopic')) {
			register_error(elgg_echo('groups:permissions:error'));
			forward($group->getURL());
		}

		$title = elgg_echo('groups:addtopic');

		elgg_push_breadcrumb($group->name, "discussion/owner/$group->guid");
		elgg_push_breadcrumb($title);

		$body_vars = discussion_prepare_form_vars();
		$content = elgg_view_form('discussion/save', array(), $body_vars);
	} else {
		$topic = get_entity($guid);
		if (!elgg_instanceof($topic, 'object', 'groupforumtopic') || !$topic->canEdit()) {
			register_error(elgg_echo('discussion:topic:notfound'));
			forward();
		}
		$group = $topic->getContainerEntity();
		if (!elgg_instanceof($group, 'group')) {
			register_error(elgg_echo('group:notfound'));
			forward();
		}

		$title = elgg_echo('groups:edittopic');

		elgg_push_breadcrumb($group->name, "discussion/owner/$group->guid");
		elgg_push_breadcrumb($topic->title, $topic->getURL());
		elgg_push_breadcrumb($title);

		$body_vars = discussion_prepare_form_vars($topic);
		$content = elgg_view_form('discussion/save', array(), $body_vars);
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => elgg_view('discussion/sidebar/edit'),
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Edit discussion reply
 *
 * @param string $type 'edit'
 * @param int    $guid GUID of group or topic
 */
function discussion_handle_reply_edit_page($type, $guid) {
	elgg_gatekeeper();

	if ($type == 'edit') {
		$reply = get_entity($guid);
		if (!elgg_instanceof($reply, 'object', 'discussion_reply', 'ElggDiscussionReply') || !$reply->canEdit()) {
			register_error(elgg_echo('discussion:reply:error:notfound'));
			forward();
		}
		$topic = $reply->getContainerEntity();
		if (!elgg_instanceof($topic, 'object', 'groupforumtopic')) {
			register_error(elgg_echo('discussion:topic:notfound'));
			forward();
		}
		$group = $topic->getContainerEntity();
		if (!elgg_instanceof($group, 'group')) {
			register_error(elgg_echo('group:notfound'));
			forward();
		}

		$title = elgg_echo('discussion:reply:edit');

		elgg_push_breadcrumb($group->name, "discussion/owner/$group->guid");
		elgg_push_breadcrumb($topic->title, $topic->getURL());
		elgg_push_breadcrumb($title);

		$params = array(
			'guid' => $reply->guid,
			'hidden' => false,
		);
		$content = elgg_view('ajax/discussion/reply/edit', $params);
	}

	$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => elgg_view('discussion/sidebar/edit'),
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

	elgg_entity_gatekeeper($guid, 'object', 'groupforumtopic');

	$topic = get_entity($guid);

	$group = $topic->getContainerEntity();
	if (!elgg_instanceof($group, 'group')) {
		register_error(elgg_echo('group:notfound'));
		forward();
	}

	elgg_load_js('elgg.discussion');

	elgg_set_page_owner_guid($group->getGUID());

	elgg_group_gatekeeper();

	elgg_push_breadcrumb($group->name, "discussion/owner/$group->guid");
	elgg_push_breadcrumb($topic->title);

	$params = array(
		'topic' => $topic,
		'show_add_form' => false,
	);

	$content = elgg_view_entity($topic, array('full_view' => true));
	if ($topic->status == 'closed') {
		$content .= elgg_view('discussion/replies', $params);
		$content .= elgg_view('discussion/closed');
	} elseif ($group->canWriteToContainer(0, 'object', 'groupforumtopic') || elgg_is_admin_logged_in()) {
		$params['show_add_form'] = true;
		$content .= elgg_view('discussion/replies', $params);
	} else {
		$content .= elgg_view('discussion/replies', $params);
	}

	$params = array(
		'content' => $content,
		'title' => $topic->title,
		'sidebar' => elgg_view('discussion/sidebar'),
		'filter' => '',
	);
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
