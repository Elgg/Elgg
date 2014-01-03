<?php
/**
 * Groups function library
 */

/**
 * List all groups
 */
function groups_handle_all_page() {

	// all groups doesn't get link to self
	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('groups'));

	if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
		elgg_register_title_button();
	}

	$selected_tab = get_input('filter', 'newest');

	switch ($selected_tab) {
		case 'popular':
			$content = elgg_list_entities_from_relationship_count(array(
				'type' => 'group',
				'relationship' => 'member',
				'inverse_relationship' => false,
				'full_view' => false,
				'no_results' => elgg_echo('groups:none'),
			));
			break;
		case 'discussion':
			$content = elgg_list_entities(array(
				'type' => 'object',
				'subtype' => 'groupforumtopic',
				'order_by' => 'e.last_action desc',
				'limit' => 40,
				'full_view' => false,
				'no_results' => elgg_echo('discussion:none'),
			));
			break;
		case 'newest':
		default:
			$content = elgg_list_entities(array(
				'type' => 'group',
				'full_view' => false,
				'no_results' => elgg_echo('groups:none'),
			));
			break;
	}

	$filter = elgg_view('groups/group_sort_menu', array('selected' => $selected_tab));

	$sidebar = elgg_view('groups/sidebar/find');
	$sidebar .= elgg_view('groups/sidebar/featured');

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'filter' => $filter,
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page(elgg_echo('groups:all'), $body);
}

function groups_search_page() {
	elgg_push_breadcrumb(elgg_echo('search'));

	$tag = get_input("tag");
	$display_query = _elgg_get_display_query($tag);
	$title = elgg_echo('groups:search:title', array($display_query));

	// groups plugin saves tags as "interests" - see groups_fields_setup() in start.php
	$params = array(
		'metadata_name' => 'interests',
		'metadata_value' => $tag,
		'type' => 'group',
		'full_view' => false,
		'no_results' => elgg_echo('groups:search:none'),
	);
	$content = elgg_list_entities_from_metadata($params);

	$sidebar = elgg_view('groups/sidebar/find');
	$sidebar .= elgg_view('groups/sidebar/featured');

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'filter' => false,
		'title' => $title,
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * List owned groups
 */
function groups_handle_owned_page() {

	$page_owner = elgg_get_page_owner_entity();

	if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
		$title = elgg_echo('groups:owned');
	} else {
		$title = elgg_echo('groups:owned:user', array($page_owner->name));
	}
	elgg_push_breadcrumb($title);

	if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
		elgg_register_title_button();
	}

	$dbprefix = elgg_get_config('dbprefix');
	$content = elgg_list_entities(array(
		'type' => 'group',
		'owner_guid' => elgg_get_page_owner_guid(),
		'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
		'order_by' => 'ge.name ASC',
		'full_view' => false,
		'no_results' => elgg_echo('groups:none'),
	));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * List groups the user is memober of
 */
function groups_handle_mine_page() {

	$page_owner = elgg_get_page_owner_entity();

	if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
		$title = elgg_echo('groups:yours');
	} else {
		$title = elgg_echo('groups:user', array($page_owner->name));
	}
	elgg_push_breadcrumb($title);

	if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
		elgg_register_title_button();
	}
	
	$dbprefix = elgg_get_config('dbprefix');

	$content = elgg_list_entities_from_relationship(array(
		'type' => 'group',
		'relationship' => 'member',
		'relationship_guid' => elgg_get_page_owner_guid(),
		'inverse_relationship' => false,
		'full_view' => false,
		'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
		'order_by' => 'ge.name ASC',
		'no_results' => elgg_echo('groups:none'),
	));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Create or edit a group
 *
 * @param string $page
 * @param int $guid
 */
function groups_handle_edit_page($page, $guid = 0) {
	elgg_gatekeeper();

	if ($page == 'add') {
		elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
		$title = elgg_echo('groups:add');
		elgg_push_breadcrumb($title);
		if (elgg_get_plugin_setting('limited_groups', 'groups') != 'yes' || elgg_is_admin_logged_in()) {
			$content = elgg_view('groups/edit');
		} else {
			$content = elgg_echo('groups:cantcreate');
		}
	} else {
		$title = elgg_echo("groups:edit");
		$group = get_entity($guid);

		if (elgg_instanceof($group, 'group') && $group->canEdit()) {
			elgg_set_page_owner_guid($group->getGUID());
			elgg_push_breadcrumb($group->name, $group->getURL());
			elgg_push_breadcrumb($title);
			$content = elgg_view("groups/edit", array('entity' => $group));
		} else {
			$content = elgg_echo('groups:noaccess');
		}
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
 * Group invitations for a user
 */
function groups_handle_invitations_page() {
	elgg_gatekeeper();

	$user = elgg_get_page_owner_entity();

	$title = elgg_echo('groups:invitations');
	elgg_push_breadcrumb($title);

	// @todo temporary workaround for exts #287.
	$invitations = groups_get_invited_groups(elgg_get_logged_in_user_guid());
	$content = elgg_view('groups/invitationrequests', array('invitations' => $invitations));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Group profile page
 *
 * @param int $guid Group entity GUID
 */
function groups_handle_profile_page($guid) {
	elgg_set_page_owner_guid($guid);

	// turn this into a core function
	global $autofeed;
	$autofeed = true;

	elgg_push_context('group_profile');

	elgg_entity_gatekeeper($guid, 'group');

	$group = get_entity($guid);

	elgg_push_breadcrumb($group->name);

	groups_register_profile_buttons($group);

	$content = elgg_view('groups/profile/layout', array('entity' => $group));
	$sidebar = '';

	if (elgg_group_gatekeeper(false)) {
		if (elgg_is_active_plugin('search')) {
			$sidebar .= elgg_view('groups/sidebar/search', array('entity' => $group));
		}
		$sidebar .= elgg_view('groups/sidebar/members', array('entity' => $group));

		$subscribed = false;
		if (elgg_is_active_plugin('notifications')) {
			$NOTIFICATION_HANDLERS = _elgg_services()->notifications->getMethodsAsDeprecatedGlobal();
			foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
				$relationship = check_entity_relationship(elgg_get_logged_in_user_guid(),
						'notify' . $method, $guid);

				if ($relationship) {
					$subscribed = true;
					break;
				}
			}
		}

		$sidebar .= elgg_view('groups/sidebar/my_status', array(
			'entity' => $group,
			'subscribed' => $subscribed
		));
	}

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'title' => $group->name,
	);
	$body = elgg_view_layout('one_sidebar', $params);

	echo elgg_view_page($group->name, $body);
}

/**
 * Group activity page
 *
 * @param int $guid Group entity GUID
 */
function groups_handle_activity_page($guid) {

	elgg_entity_gatekeeper($guid, 'group');

	elgg_set_page_owner_guid($guid);

	elgg_group_gatekeeper();

	$group = get_entity($guid);

	$title = elgg_echo('groups:activity');

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($title);

	$db_prefix = elgg_get_config('dbprefix');

	$content = elgg_list_river(array(
		'joins' => array(
			"JOIN {$db_prefix}entities e1 ON e1.guid = rv.object_guid",
			"LEFT JOIN {$db_prefix}entities e2 ON e2.guid = rv.target_guid",
		),
		'wheres' => array(
			"(e1.container_guid = $group->guid OR e2.container_guid = $group->guid)",
		),
		'no_results' => elgg_echo('groups:activity:none'),
	));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Group members page
 *
 * @param int $guid Group entity GUID
 */
function groups_handle_members_page($guid) {

	elgg_entity_gatekeeper($guid, 'group');

	$group = get_entity($guid);

	elgg_set_page_owner_guid($guid);

	elgg_group_gatekeeper();

	$title = elgg_echo('groups:members:title', array($group->name));

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb(elgg_echo('groups:members'));

	$db_prefix = elgg_get_config('dbprefix');
	$content = elgg_list_entities_from_relationship(array(
		'relationship' => 'member',
		'relationship_guid' => $group->guid,
		'inverse_relationship' => true,
		'type' => 'user',
		'limit' => (int)get_input('limit', 20, false),
		'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid=u.guid"),
		'order_by' => 'u.name ASC',
	));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Invite users to a group
 *
 * @param int $guid Group entity GUID
 */
function groups_handle_invite_page($guid) {
	elgg_gatekeeper();

	elgg_set_page_owner_guid($guid);

	$title = elgg_echo('groups:invite:title');

	$group = get_entity($guid);
	if (!elgg_instanceof($group, 'group') || !$group->canEdit()) {
		register_error(elgg_echo('groups:noaccess'));
		forward(REFERER);
	}

	$content = elgg_view_form('groups/invite', array(
		'id' => 'invite_to_group',
		'class' => 'elgg-form-alt mtm',
	), array(
		'entity' => $group,
	));

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb(elgg_echo('groups:invite'));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Manage requests to join a group
 *
 * @param int $guid Group entity GUID
 */
function groups_handle_requests_page($guid) {

	elgg_gatekeeper();

	elgg_set_page_owner_guid($guid);

	$group = get_entity($guid);
	if (!elgg_instanceof($group, 'group') || !$group->canEdit()) {
		register_error(elgg_echo('groups:noaccess'));
		forward(REFERER);
	}

	$title = elgg_echo('groups:membershiprequests');

	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($title);

	$requests = elgg_get_entities_from_relationship(array(
		'type' => 'user',
		'relationship' => 'membership_request',
		'relationship_guid' => $guid,
		'inverse_relationship' => true,
		'limit' => 0,
	));
	$content = elgg_view('groups/membershiprequests', array(
		'requests' => $requests,
		'entity' => $group,
	));

	$params = array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
}

/**
 * Registers the buttons for title area of the group profile page
 *
 * @param ElggGroup $group
 */
function groups_register_profile_buttons($group) {

	$actions = array();

	// group owners
	if ($group->canEdit()) {
		// edit and invite
		$url = elgg_get_site_url() . "groups/edit/{$group->getGUID()}";
		$actions[$url] = 'groups:edit';
		$url = elgg_get_site_url() . "groups/invite/{$group->getGUID()}";
		$actions[$url] = 'groups:invite';
	}

	// group members
	if ($group->isMember(elgg_get_logged_in_user_entity())) {
		if ($group->getOwnerGUID() != elgg_get_logged_in_user_guid()) {
			// leave
			$url = elgg_get_site_url() . "action/groups/leave?group_guid={$group->getGUID()}";
			$url = elgg_add_action_tokens_to_url($url);
			$actions[$url] = 'groups:leave';
		}
	} elseif (elgg_is_logged_in()) {
		// join - admins can always join.
		$url = elgg_get_site_url() . "action/groups/join?group_guid={$group->getGUID()}";
		$url = elgg_add_action_tokens_to_url($url);
		if ($group->isPublicMembership() || $group->canEdit()) {
			$actions[$url] = 'groups:join';
		} else {
			// request membership
			$actions[$url] = 'groups:joinrequest';
		}
	}

	if ($actions) {
		foreach ($actions as $url => $text) {
			elgg_register_menu_item('title', array(
				'name' => $text,
				'href' => $url,
				'text' => elgg_echo($text),
				'link_class' => 'elgg-button elgg-button-action',
			));
		}
	}
}

/**
 * Prepares variables for the group edit form view.
 *
 * @param mixed $group ElggGroup or null. If a group, uses values from the group.
 * @return array
 */
function groups_prepare_form_vars($group = null) {
	$values = array(
		'name' => '',
		'membership' => ACCESS_PUBLIC,
		'vis' => ACCESS_PUBLIC,
		'guid' => null,
		'entity' => null
	);

	// handle customizable profile fields
	$fields = elgg_get_config('group');

	if ($fields) {
		foreach ($fields as $name => $type) {
			$values[$name] = '';
		}
	}

	// handle tool options
	$tools = elgg_get_config('group_tool_options');
	if ($tools) {
		foreach ($tools as $group_option) {
			$option_name = $group_option->name . "_enable";
			$values[$option_name] = $group_option->default_on ? 'yes' : 'no';
		}
	}

	// get current group settings
	if ($group) {
		foreach (array_keys($values) as $field) {
			if (isset($group->$field)) {
				$values[$field] = $group->$field;
			}
		}

		if ($group->access_id != ACCESS_PUBLIC && $group->access_id != ACCESS_LOGGED_IN) {
			// group only access - this is done to handle access not created when group is created
			$values['vis'] = ACCESS_PRIVATE;
		} else {
			$values['vis'] = $group->access_id;
		}

		$values['entity'] = $group;
	}

	// get any sticky form settings
	if (elgg_is_sticky_form('groups')) {
		$sticky_values = elgg_get_sticky_values('groups');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('groups');

	return $values;
}

/**
 * Updates group content access ids when changing a group's content accessibility from "unrestricted to "group only".
 * The function will descend into container hierarchy and update access ids for all nested content as well.
 * Access updates will be performed on entities, metadata, annotations and river entries.
 *
 * @param ElggGroup $group The group entity to change all contained entities' access
 * @param string $user The user entity to perform the update operation
 * @return boolean true upon successful operation, false otherwise
 */
function groups_update_content_access($group, $user = null) {
	// Perform some basic sanity checks
	if (!elgg_instanceof($group, 'group')) {
		return false;
	}

	if (!elgg_instanceof($user, 'user')) {
		$user = elgg_get_logged_in_user_entity();
	}

	if (!($group->canWriteToContainer($user->guid))) {
		return false;
	}

	// Prevent timeouts due to long update operations
	ini_set('max_execution_time', 0);

	// Grant privileges for changing access_id of any group contained entity
	$access = elgg_set_ignore_access(true);

	// Start with the target groups as container
	$container_guids = array($group->guid);
	$target_access_id = $group->group_acl;
	$change_access_ids = array(ACCESS_PUBLIC, ACCESS_LOGGED_IN);
	$change_access_ids_str = implode(', ', $change_access_ids);
	$dbprefix = elgg_get_config('dbprefix');
	$entity_chunk_size = 100; // Number of entity guids to process at a single SQL statement

	while (!empty($container_guids)) {

		// Get all entities' guids contained by the current container
		$container_guids_str = implode(',', $container_guids);
		$guid_query = "SELECT guid FROM {$dbprefix}entities WHERE container_guid IN ({$container_guids_str})";
		$guid_results = get_data($guid_query);

		if (is_array($guid_results) && !empty($guid_results)) {

			// Get all contained entity guids and use them as a comma separated string for update statements
			$entity_guids = array();
			foreach ($guid_results as $guid_obj) {
				$entity_guids[] = $guid_obj->guid;
			}
			$entity_guids_str = implode(',', $entity_guids);

			// Update entity access ids
			// Allow 3rd party plugins to modify the list of guids before executing the actual update statement
			elgg_trigger_plugin_hook('group_access_update', 'entity', array(), $entity_guids);
			// Use chunks to prevent errors raised by too long SQL commands
			$entity_guids_chunks = array_chunk($entity_guids, $entity_chunk_size);
			foreach ($entity_guids_chunks as $entity_guids_chunk) {
				$entity_guids_str = implode(',', $entity_guids_chunk);
				$update_statement = "UPDATE {$dbprefix}entities SET access_id = {$target_access_id} WHERE
				(access_id IN ({$change_access_ids_str}) AND guid IN ({$entity_guids_str}))";
				
				update_data($update_statement);
			}

			// Update metadata access ids
			elgg_trigger_plugin_hook('group_access_update', 'metadata', array(), $entity_guids);
			$entity_guids_chunks = array_chunk($entity_guids, $entity_chunk_size);
			foreach ($entity_guids_chunks as $entity_guids_chunk) {
				$entity_guids_str = implode(',', $entity_guids_chunk);
				$update_statement = "UPDATE {$dbprefix}metadata SET access_id = {$target_access_id} WHERE
				(access_id IN ({$change_access_ids_str}) AND entity_guid IN ({$entity_guids_str}))";
				
				update_data($update_statement);
			}

			// Update annotation access ids
			elgg_trigger_plugin_hook('group_access_update', 'annotation', array(), $entity_guids);
			$entity_guids_chunks = array_chunk($entity_guids, $entity_chunk_size);
			foreach ($entity_guids_chunks as $entity_guids_chunk) {
				$entity_guids_str = implode(',', $entity_guids_chunk);
				$update_statement = "UPDATE {$dbprefix}annotations SET access_id = {$target_access_id} WHERE
				(access_id IN ({$change_access_ids_str}) AND entity_guid IN ({$entity_guids_str}))";
				
				update_data($update_statement);
			}

			// Update river access ids
			elgg_trigger_plugin_hook('group_access_update', 'river', array(), $entity_guids);
			$entity_guids_chunks = array_chunk($entity_guids, $entity_chunk_size);
			foreach ($entity_guids_chunks as $entity_guids_chunk) {
				$entity_guids_str = implode(',', $entity_guids_chunk);
				$update_statement = "UPDATE {$dbprefix}river SET access_id = {$target_access_id} WHERE
				(access_id IN ({$change_access_ids_str}) AND object_guid IN ({$entity_guids_str}))";
				
				update_data($update_statement);
			}

		} else {
			$entity_guids = array();
		}

			// Descend one level in container hierarchy - use the previously updated entities as containers for the next round
		$container_guids = $entity_guids;
	}

	elgg_set_ignore_access($access);
	return true;
}
