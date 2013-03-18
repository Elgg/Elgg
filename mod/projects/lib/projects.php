<?php
/**
 * Groups function library
 */

/**
 * List all projects
 */
function projects_handle_all_page() {

	// all projects doesn't get link to self
	elgg_pop_breadcrumb();
	elgg_push_breadcrumb(elgg_echo('projects'));

	if (elgg_get_plugin_setting('limited_projects', 'projects') != 'yes' || elgg_is_admin_logged_in()) {
		elgg_register_title_button();
	}

	$selected_tab = get_input('filter', 'newest');

	switch ($selected_tab) {
		case 'popular':
			$content = elgg_list_entities_from_relationship_count(array(
				'type' => 'group',
				'subtype' => 'project',
				'relationship' => 'member',
				'inverse_relationship' => false,
				'full_view' => false,
			));
			if (!$content) {
				$content = elgg_echo('projects:none');
			}
			break;
		
		case 'newest':
		default:
			$content = elgg_list_entities(array(
				'type' => 'group',
				'subtype' => 'project',
				'full_view' => false,
			));
			if (!$content) {
				$content = elgg_echo('projects:none');
			}
			break;
	}

	$filter = elgg_view('projects/project_sort_menu', array('selected' => $selected_tab));
	
	$sidebar = elgg_view('projects/sidebar/find');
	$sidebar .= elgg_view('projects/sidebar/featured');

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'filter' => $filter,
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page(elgg_echo('projects:all'), $body);
}

function projects_search_page() {
	elgg_push_breadcrumb(elgg_echo('search'));

	$tag = get_input("tag");
	$title = elgg_echo('projects:search:title', array($tag));

	// projects plugin saves tags as "interests" - see projects_fields_setup() in start.php
	$params = array(
		'metadata_name' => 'interests',
		'metadata_value' => $tag,
		'type' => 'group',
		'subtype' => 'project',
		'full_view' => FALSE,
	);
	$content = elgg_list_entities_from_metadata($params);
	if (!$content) {
		$content = elgg_echo('projects:search:none');
	}

	$sidebar = elgg_view('projects/sidebar/find');
	$sidebar .= elgg_view('projects/sidebar/featured');

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
 * List owned projects
 */
function projects_handle_owned_page() {

	$page_owner = elgg_get_page_owner_entity();

	if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
		$title = elgg_echo('projects:owned');
	} else {
		$title = elgg_echo('projects:owned:user', array($page_owner->name));
	}
	elgg_push_breadcrumb($title);

	elgg_register_title_button();

	$content = elgg_list_entities(array(
		'type' => 'group',
		'subtype' => 'project',
		'owner_guid' => elgg_get_page_owner_guid(),
		'full_view' => false,
	));
	if (!$content) {
		$content = elgg_echo('projects:none');
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
 * List projects the user is memober of
 */
function projects_handle_mine_page() {

	$page_owner = elgg_get_page_owner_entity();

	if ($page_owner->guid == elgg_get_logged_in_user_guid()) {
		$title = elgg_echo('projects:yours');
	} else {
		$title = elgg_echo('projects:user', array($page_owner->name));
	}
	elgg_push_breadcrumb($title);

	elgg_register_title_button();

	$content = elgg_list_entities_from_relationship(array(
		'type' => 'group',
		'subtype' => 'project',
		'relationship' => 'member',
		'relationship_guid' => elgg_get_page_owner_guid(),
		'inverse_relationship' => false,
		'full_view' => false,
	));
	if (!$content) {
		$content = elgg_echo('projects:none');
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
 * Create or edit a project
 *
 * @param string $page
 * @param int $guid
 */
function projects_handle_edit_page($page, $guid = 0) {
	gatekeeper();
	
	if ($page == 'add') {
		elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());
		$title = elgg_echo('projects:add');
		elgg_push_breadcrumb($title);
		if (elgg_get_plugin_setting('limited_projects', 'projects') != 'yes' || elgg_is_admin_logged_in()) {
			$content = elgg_view('projects/edit');
		} else {
			$content = elgg_echo('projects:cantcreate');
		}
	} else {
		$title = elgg_echo("projects:edit");
		$project = get_entity($guid);

		if ($project && $project->canEdit()) {
			elgg_set_page_owner_guid($project->getGUID());
			elgg_push_breadcrumb($project->name, $project->getURL());
			elgg_push_breadcrumb($title);
			$content = elgg_view("projects/edit", array('entity' => $project));
		} else {
			$content = elgg_echo('projects:noaccess');
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
function projects_handle_invitations_page() {
	gatekeeper();

	$user = elgg_get_page_owner_entity();

	$title = elgg_echo('projects:invitations');
	elgg_push_breadcrumb($title);

	// @todo temporary workaround for exts #287.
	$invitations = projects_get_invited_projects(elgg_get_logged_in_user_guid());
	$content = elgg_view('projects/invitationrequests', array('invitations' => $invitations));

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
function projects_handle_profile_page($guid) {
	elgg_set_page_owner_guid($guid);

	// turn this into a core function
	global $autofeed;
	$autofeed = true;

	elgg_push_context('project_profile');

	$project = get_entity($guid);
	if (!$project) {
		forward('projects/all');
	}

	elgg_push_breadcrumb($project->name);

	projects_register_profile_buttons($project);

	$content = elgg_view('projects/profile/layout', array('entity' => $project));
	$sidebar = '';

	if (group_gatekeeper(false)) {	
		if (elgg_is_active_plugin('search')) {
			$sidebar .= elgg_view('projects/sidebar/search', array('entity' => $project));
		}
		$sidebar .= elgg_view('projects/sidebar/members', array('entity' => $project));

		$subscribed = false;
		if (elgg_is_active_plugin('notifications')) {
			global $NOTIFICATION_HANDLERS;
			
			foreach ($NOTIFICATION_HANDLERS as $method => $foo) {
				$relationship = check_entity_relationship(elgg_get_logged_in_user_guid(),
						'notify' . $method, $guid);
				
				if ($relationship) {
					$subscribed = true;
					break;
				}
			}
		}
		
		$sidebar .= elgg_view('projects/sidebar/my_status', array(
			'entity' => $project,
			'subscribed' => $subscribed
		));
	}

	$params = array(
		'content' => $content,
		'sidebar' => $sidebar,
		'title' => $project->name,
		'filter' => '',
	);
	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($project->name, $body);
}

/**
 * Group activity page
 *
 * @param int $guid Group entity GUID
 */
function projects_handle_activity_page($guid) {

	elgg_set_page_owner_guid($guid);

	$project = get_entity($guid);
	if (!$project || !elgg_instanceof($project, 'project')) {
		forward();
	}

	group_gatekeeper();

	$title = elgg_echo('projects:activity');

	elgg_push_breadcrumb($project->name, $project->getURL());
	elgg_push_breadcrumb($title);

	$db_prefix = elgg_get_config('dbprefix');

	$content = elgg_list_river(array(
		'joins' => array("JOIN {$db_prefix}entities e ON e.guid = rv.object_guid"),
		'wheres' => array("e.container_guid = $guid")
	));
	if (!$content) {
		$content = '<p>' . elgg_echo('projects:activity:none') . '</p>';
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
 * Group members page
 *
 * @param int $guid Group entity GUID
 */
function projects_handle_members_page($guid) {

	elgg_set_page_owner_guid($guid);

	$project = get_entity($guid);
	if (!$project || !elgg_instanceof($project, 'group', 'project')) {
		forward();
	}

	group_gatekeeper();

	$title = elgg_echo('projects:members:title', array($project->name));

	elgg_push_breadcrumb($project->name, $project->getURL());
	elgg_push_breadcrumb(elgg_echo('projects:members'));

	$content = elgg_list_entities_from_relationship(array(
		'relationship' => 'member',
		'relationship_guid' => $project->guid,
		'inverse_relationship' => true,
		'type' => 'user',
		'limit' => 20,
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
 * Invite users to a project
 *
 * @param int $guid Group entity GUID
 */
function projects_handle_invite_page($guid) {
	gatekeeper();

	elgg_set_page_owner_guid($guid);

	$project = get_entity($guid);

	$title = elgg_echo('projects:invite:title');

	elgg_push_breadcrumb($project->name, $project->getURL());
	elgg_push_breadcrumb(elgg_echo('projects:invite'));

	if ($project && $project->canEdit()) {
		$content = elgg_view_form('projects/invite', array(
			'id' => 'invite_to_project',
			'class' => 'elgg-form-alt mtm',
		), array(
			'entity' => $project,
		));
	} else {
		$content .= elgg_echo('projects:noaccess');
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
 * Manage requests to join a project
 * 
 * @param int $guid Group entity GUID
 */
function projects_handle_requests_page($guid) {

	gatekeeper();

	elgg_set_page_owner_guid($guid);

	$project = get_entity($guid);

	$title = elgg_echo('projects:membershiprequests');

	if ($project && $project->canEdit()) {
		elgg_push_breadcrumb($project->name, $project->getURL());
		elgg_push_breadcrumb($title);
		
		$requests = elgg_get_entities_from_relationship(array(
			'type' => 'user',
			'relationship' => 'membership_request',
			'relationship_guid' => $guid,
			'inverse_relationship' => true,
			'limit' => 0,
		));
		$content = elgg_view('projects/membershiprequests', array(
			'requests' => $requests,
			'entity' => $project,
		));

	} else {
		$content = elgg_echo("projects:noaccess");
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
 * Registers the buttons for title area of the project profile page
 *
 * @param ElggGroup $project
 */
function projects_register_profile_buttons($project) {

	$actions = array();

	// project owners
	if ($project->canEdit()) {
		// edit and invite
		$url = elgg_get_site_url() . "projects/edit/{$project->getGUID()}";
		$actions[$url] = 'projects:edit';
		$url = elgg_get_site_url() . "projects/invite/{$project->getGUID()}";
		$actions[$url] = 'projects:invite';
	}

	// project members
	if ($project->isMember(elgg_get_logged_in_user_entity())) {
		if ($project->getOwnerGUID() != elgg_get_logged_in_user_guid()) {
			// leave
			$url = elgg_get_site_url() . "action/projects/leave?project_guid={$project->getGUID()}";
			$url = elgg_add_action_tokens_to_url($url);
			$actions[$url] = 'projects:leave';
		}
	} elseif (elgg_is_logged_in()) {
		// join - admins can always join.
		$url = elgg_get_site_url() . "action/projects/join?project_guid={$project->getGUID()}";
		$url = elgg_add_action_tokens_to_url($url);
		if ($project->isPublicMembership() || $project->canEdit()) {
			$actions[$url] = 'projects:join';
		} else {
			// request membership
			$actions[$url] = 'projects:joinrequest';
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
 * Prepares variables for the project edit form view.
 *
 * @param mixed $project ElggGroup or null. If a project, uses values from the project.
 * @return array
 */
function projects_prepare_form_vars($project = null) {
	$values = array(
		'name' => '',
		'membership' => ACCESS_PUBLIC,
		'vis' => ACCESS_PUBLIC,
		'guid' => null,
		'entity' => null
	);

	// handle customizable profile fields
	$fields = elgg_get_config('project');

	if ($fields) {
		foreach ($fields as $name => $type) {
			$values[$name] = '';
		}
	}

	// handle tool options
	$tools = elgg_get_config('project_tool_options');
	if ($tools) {
		foreach ($tools as $project_option) {
			$option_name = $project_option->name . "_enable";
			$values[$option_name] = $project_option->default_on ? 'yes' : 'no';
		}
	}

	// get current project settings
	if ($project) {
		foreach (array_keys($values) as $field) {
			if (isset($project->$field)) {
				$values[$field] = $project->$field;
			}
		}

		if ($project->access_id != ACCESS_PUBLIC && $project->access_id != ACCESS_LOGGED_IN) {
			// project only access - this is done to handle access not created when project is created
			$values['vis'] = ACCESS_PRIVATE;
		} else {
			$values['vis'] = $project->access_id;
		}

		$values['entity'] = $project;
	}

	// get any sticky form settings
	if (elgg_is_sticky_form('projects')) {
		$sticky_values = elgg_get_sticky_values('projects');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('projects');

	return $values;
}
