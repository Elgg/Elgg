<?php
/**
 * Elgg groups plugin
 *
 * @package ElggGroups
 */

elgg_register_event_handler('init', 'system', 'groups_init');

// Ensure this runs after other plugins
elgg_register_event_handler('init', 'system', 'groups_fields_setup', 10000);

/**
 * Initialize the groups plugin.
 */
function groups_init() {

	elgg_register_library('elgg:groups', __DIR__ . '/lib/groups.php');
	elgg_load_library('elgg:groups');
	
	// register group entities for search
	elgg_register_entity_type('group', '');

	// Set up the menu
	$item = new ElggMenuItem('groups', elgg_echo('groups'), 'groups/all');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('groups', 'groups_page_handler');

	// Register URL handlers for groups
	elgg_register_plugin_hook_handler('entity:url', 'group', 'groups_set_url');
	elgg_register_plugin_hook_handler('entity:icon:url', 'group', 'groups_set_icon_url');
	elgg_register_plugin_hook_handler('entity:icon:file', 'group', 'groups_set_icon_file');

	// Register an icon handler for groups
	elgg_register_page_handler('groupicon', 'groups_icon_handler');

	// Register some actions
	$action_base = __DIR__ . '/actions/groups';
	elgg_register_action("groups/edit", "$action_base/edit.php");
	elgg_register_action("groups/delete", "$action_base/delete.php");
	elgg_register_action("groups/featured", "$action_base/featured.php", 'admin');

	$action_base .= '/membership';
	elgg_register_action("groups/invite", "$action_base/invite.php");
	elgg_register_action("groups/join", "$action_base/join.php");
	elgg_register_action("groups/leave", "$action_base/leave.php");
	elgg_register_action("groups/remove", "$action_base/remove.php");
	elgg_register_action("groups/killrequest", "$action_base/delete_request.php");
	elgg_register_action("groups/killinvitation", "$action_base/delete_invite.php");
	elgg_register_action("groups/addtogroup", "$action_base/add.php");

	// Add some widgets
	elgg_register_widget_type('a_users_groups', elgg_echo('groups:widget:membership'), elgg_echo('groups:widgets:description'));

	elgg_register_widget_type(
			'group_activity',
			elgg_echo('groups:widget:group_activity:title'),
			elgg_echo('groups:widget:group_activity:description'),
			array('dashboard'),
			true
	);

	// add group activity tool option
	add_group_tool_option('activity', elgg_echo('groups:enableactivity'), true);
	elgg_extend_view('groups/tool_latest', 'groups/profile/activity_module');

	// add link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'groups_activity_owner_block_menu');

	// group entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'groups_entity_menu_setup');

	// group user hover menu
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'groups_user_entity_menu_setup');

	// invitation request actions
	elgg_register_plugin_hook_handler('register', 'menu:invitationrequest', 'groups_invitationrequest_menu_setup');

	// group members tabs
	elgg_register_plugin_hook_handler('register', 'menu:groups_members', 'groups_members_menu_setup');

	//extend some views
	elgg_extend_view('elgg.css', 'groups/css');
	if (_elgg_view_may_be_altered('groups/js', __DIR__ . '/views/default/groups/js.php')) {
		elgg_deprecated_notice('groups/js view has been deprecated. Use AMD modules instead', '2.2');
		elgg_extend_view('elgg.js', 'groups/js');
	}

	// Access permissions
	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'groups_write_acl_plugin_hook', 600);
	elgg_register_plugin_hook_handler('default', 'access', 'groups_access_default_override');

	// Register profile menu hook
	elgg_register_plugin_hook_handler('profile_menu', 'profile', 'activity_profile_menu');

	// allow ecml in profiles
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'groupprofile_ecml_views_hook');

	// Register a handler for create groups
	elgg_register_event_handler('create', 'group', 'groups_create_event_listener');
	elgg_register_event_handler('update:after', 'group', 'groups_update_event_listener');
	elgg_register_event_handler('delete', 'group', 'groups_delete_event_listener', 999);

	elgg_register_event_handler('join', 'group', 'groups_user_join_event_listener');
	elgg_register_event_handler('leave', 'group', 'groups_user_leave_event_listener');
	elgg_register_event_handler('pagesetup', 'system', 'groups_setup_sidebar_menus');

	elgg_register_plugin_hook_handler('access:collections:add_user', 'collection', 'groups_access_collection_override');

	elgg_register_event_handler('upgrade', 'system', 'groups_run_upgrades');

	// Add tests
	elgg_register_plugin_hook_handler('unit_test', 'system', 'groups_test');

	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'group:', 'Elgg\Values::getTrue');

	// prepare profile buttons to be registered in the title menu
	elgg_register_plugin_hook_handler('profile_buttons', 'group', 'groups_prepare_profile_buttons');

	// Help core resolve page owner guids from group routes
	// Registered with an earlier priority to be called before default_page_owner_handler()
	elgg_register_plugin_hook_handler('page_owner', 'system', 'groups_default_page_owner_handler', 400);
}

/**
 * This function loads a set of default fields into the profile, then triggers
 * a hook letting other plugins to edit add and delete fields.
 *
 * Note: This is a system:init event triggered function and is run at a super
 * low priority to guarantee that it is called after all other plugins have
 * initialized.
 */
function groups_fields_setup() {

	$profile_defaults = array(
		'description' => 'longtext',
		'briefdescription' => 'text',
		'interests' => 'tags',
		//'website' => 'url',
	);

	$profile_defaults = elgg_trigger_plugin_hook('profile:fields', 'group', NULL, $profile_defaults);

	elgg_set_config('group', $profile_defaults);

	// register any tag metadata names
	foreach ($profile_defaults as $name => $type) {
		if ($type == 'tags') {
			elgg_register_tag_metadata_name($name);

			// only shows up in search but why not just set this in en.php as doing it here
			// means you cannot override it in a plugin
			add_translation(get_current_language(), array("tag_names:$name" => elgg_echo("groups:$name")));
		}
	}
}

/**
 * Configure the groups sidebar menu. Triggered on page setup
 *
 */
function groups_setup_sidebar_menus() {

	// Get the page owner entity
	$page_owner = elgg_get_page_owner_entity();

	if (elgg_in_context('group_profile')) {
		if (!elgg_instanceof($page_owner, 'group')) {
			forward('', '404');
		}

		if (elgg_is_logged_in() && $page_owner->canEdit() && !$page_owner->isPublicMembership()) {
			$url = elgg_get_site_url() . "groups/requests/{$page_owner->getGUID()}";

			$count = elgg_get_entities_from_relationship(array(
				'type' => 'user',
				'relationship' => 'membership_request',
				'relationship_guid' => $page_owner->getGUID(),
				'inverse_relationship' => true,
				'count' => true,
			));

			if ($count) {
				$text = elgg_echo('groups:membershiprequests:pending', array($count));
			} else {
				$text = elgg_echo('groups:membershiprequests');
			}

			elgg_register_menu_item('page', array(
				'name' => 'membership_requests',
				'text' => $text,
				'href' => $url,
			));
		}
	}
	if (elgg_get_context() == 'groups' && !elgg_instanceof($page_owner, 'group')) {
		elgg_register_menu_item('page', array(
			'name' => 'groups:all',
			'text' => elgg_echo('groups:all'),
			'href' => 'groups/all',
		));

		$user = elgg_get_logged_in_user_entity();
		if ($user) {
			$url =  "groups/owner/$user->username";
			$item = new ElggMenuItem('groups:owned', elgg_echo('groups:owned'), $url);
			elgg_register_menu_item('page', $item);

			$url = "groups/member/$user->username";
			$item = new ElggMenuItem('groups:member', elgg_echo('groups:yours'), $url);
			elgg_register_menu_item('page', $item);

			$url = "groups/invitations/$user->username";
			$invitation_count = groups_get_invited_groups($user->getGUID(), false, array('count' => true));

			if ($invitation_count) {
				$text = elgg_echo('groups:invitations:pending', array($invitation_count));
			} else {
				$text = elgg_echo('groups:invitations');
			}

			$item = new ElggMenuItem('groups:user:invites', $text, $url);
			elgg_register_menu_item('page', $item);
		}
	}
}

/**
 * Groups page handler
 *
 * URLs take the form of
 *  All groups:           groups/all
 *  User's owned groups:  groups/owner/<username>
 *  User's member groups: groups/member/<username>
 *  Group profile:        groups/profile/<guid>/<title>
 *  New group:            groups/add/<guid>
 *  Edit group:           groups/edit/<guid>
 *  Group invitations:    groups/invitations/<username>
 *  Invite to group:      groups/invite/<guid>
 *  Membership requests:  groups/requests/<guid>
 *  Group activity:       groups/activity/<guid>
 *  Group members:        groups/members/<guid>
 *
 * @param array $page Array of url segments for routing
 * @return bool
 */
function groups_page_handler($page) {

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");

	$vars = [];
	switch ($page[0]) {
		case 'add':
		case 'all':
		case 'owner':
		case 'search':
			echo elgg_view_resource("groups/{$page[0]}");
			break;
		case 'invitations':
		case 'member':
			echo elgg_view_resource("groups/{$page[0]}", [
				'username' => $page[1],
			]);
			break;
		case 'members':
			$vars['sort'] = elgg_extract('2', $page, 'alpha');
			$vars['guid'] = elgg_extract('1', $page);
			if (elgg_view_exists("resources/groups/members/{$vars['sort']}")) {
				echo elgg_view_resource("groups/members/{$vars['sort']}", $vars);
			} else {
				echo elgg_view_resource('groups/members', $vars);
			}
			break;
		case 'profile':
			// Page owner and context need to be set before elgg_view() is
			// called so they'll be available in the [pagesetup, system] event
			// that is used for registering items for the sidebar menu.
			// @see groups_setup_sidebar_menus()
			elgg_push_context('group_profile');
			elgg_set_page_owner_guid($page[1]);
		case 'activity':
		case 'edit':
		case 'invite':
		case 'requests':
			echo elgg_view_resource("groups/{$page[0]}", [
				'guid' => $page[1],
			]);
			break;
		default:
			return false;
	}
	return true;
}

/**
 * Handle group icons.
 *
 * @param array $page
 * @return bool
 * @deprecated 2.2
 */
function groups_icon_handler($page) {

	elgg_deprecated_notice('/groupicon page handler has been deprecated. Use elgg_get_inline_url() instead.', '2.2');

	$guid = array_shift($page);
	elgg_entity_gatekeeper($guid, 'group');

	$size = array_shift($page) ? : 'medium';

	$group = get_entity($guid);
	
	$icon = $group->getIcon($size);
	$url = elgg_get_inline_url($icon, true);
	if (!$url) {
		$url = elgg_get_simplecache_url("groups/default{$size}.gif");
	}
	
	forward($url);
}

/**
 * Populates the ->getUrl() method for group objects
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function groups_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	$title = elgg_get_friendly_title($entity->name);
	return "groups/profile/{$entity->guid}/$title";
}

/**
 * Override the default entity icon URL for groups
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string Relative URL
 */
function groups_set_icon_url($hook, $type, $url, $params) {

	$entity = elgg_extract('entity', $params);
	/* @var $group \ElggGroup */

	$size = elgg_extract('size', $params, 'medium');

	$icontime = $entity->icontime;
	if (null === $icontime) {
		// handle missing metadata (pre 1.7 installations)
		$icon = $entity->getIcon('large');
		$icontime = $icon->exists() ? time() : 0;
		create_metadata($entity->guid, 'icontime', $icontime, 'integer', $entity->owner_guid, ACCESS_PUBLIC);
}

	$icon = $entity->getIcon($size);
	$url = elgg_get_inline_url($icon, true); // binding to session due to complexity in group access controls
	if (!$url) {
		$url = elgg_get_simplecache_url("groups/default{$size}.gif");
	}
	return $url;
}

/**
 * Override the default entity icon file for groups
 *
 * @param string    $hook   "entity:icon:file"
 * @param string    $type   "group"
 * @param \ElggIcon $icon   Icon file
 * @param array     $params Hook params
 * @return \ElggIcon
 */
function groups_set_icon_file($hook, $type, $icon, $params) {

	$entity = elgg_extract('entity', $params);
	$size = elgg_extract('size', $params, 'medium');

	$icon->owner_guid = $entity->owner_guid;
	$icon->setFilename("groups/{$entity->guid}{$size}.jpg");

	return $icon;
}

/**
 * Add owner block link
 */
function groups_activity_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'group')) {
		if ($params['entity']->activity_enable != "no") {
			$url = "groups/activity/{$params['entity']->guid}";
			$item = new ElggMenuItem('activity', elgg_echo('groups:activity'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add links/info to entity menu particular to group entities
 */
function groups_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	/* @var ElggGroup $entity */
	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'groups') {
		return $return;
	}

	/* @var ElggMenuItem $item */
	foreach ($return as $index => $item) {
		if (in_array($item->getName(), array('access', 'likes', 'unlike', 'edit', 'delete'))) {
			unset($return[$index]);
		}
	}

	// membership type
	if ($entity->isPublicMembership()) {
		$mem = elgg_echo("groups:open");
	} else {
		$mem = elgg_echo("groups:closed");
	}

	$options = array(
		'name' => 'membership',
		'text' => $mem,
		'href' => false,
		'priority' => 100,
	);
	$return[] = ElggMenuItem::factory($options);

	// number of members
	$num_members = $entity->getMembers(array('count' => true));
	$members_string = elgg_echo('groups:member');
	$options = array(
		'name' => 'members',
		'text' => $num_members . ' ' . $members_string,
		'href' => false,
		'priority' => 200,
	);
	$return[] = ElggMenuItem::factory($options);

	// feature link
	if (elgg_is_admin_logged_in()) {
		$isFeatured = $entity->featured_group == "yes";

		$return[] = ElggMenuItem::factory(array(
			'name' => 'feature',
			'text' => elgg_echo("groups:makefeatured"),
			'href' => elgg_add_action_tokens_to_url("action/groups/featured?group_guid={$entity->guid}&action_type=feature"),
			'priority' => 300,
			'item_class' => $isFeatured ? 'hidden' : '',
			'deps' => 'groups/navigation',
		));

		$return[] = ElggMenuItem::factory(array(
			'name' => 'unfeature',
			'text' => elgg_echo("groups:makeunfeatured"),
			'href' => elgg_add_action_tokens_to_url("action/groups/featured?group_guid={$entity->guid}&action_type=unfeature"),
			'priority' => 300,
			'item_class' => $isFeatured ? '' : 'hidden',
			'deps' => 'groups/navigation',
		));
	}

	return $return;
}

/**
 * Add a remove user link to user hover menu when the page owner is a group
 */
function groups_user_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_is_logged_in()) {
		$group = elgg_get_page_owner_entity();

		// Check for valid group
		if (!elgg_instanceof($group, 'group')) {
			return $return;
		}

		$entity = $params['entity'];

		// Make sure we have a user and that user is a member of the group
		if (!elgg_instanceof($entity, 'user') || !$group->isMember($entity)) {
			return $return;
		}

		// Add remove link if we can edit the group, and if we're not trying to remove the group owner
		if ($group->canEdit() && $group->getOwnerGUID() != $entity->guid) {
			$return[] = ElggMenuItem::factory([
				'name' => 'removeuser',
				'href' => "action/groups/remove?user_guid={$entity->guid}&group_guid={$group->guid}",
				'text' => elgg_echo('groups:removeuser'),
				'confirm' => true,
				'priority' => 999,
			]);
		}
	}

	return $return;
}

/**
 * Groups created so create an access list for it
 */
function groups_create_event_listener($event, $object_type, $object) {
	$ac_name = elgg_echo('groups:group') . ": " . $object->name;
	$ac_id = create_access_collection($ac_name, $object->guid);
	if ($ac_id) {
		$object->group_acl = $ac_id;
	} else {
		// delete group if access creation fails
		return false;
	}

	return true;
}

/**
 * Listen to group ownership changes and update group icon ownership
 * This will only move the source file, the actual icons are moved by
 * _elgg_filestore_move_icons()
 *
 * This operation is performed in an event listener to ensure that icons
 * are moved when ownership changes outside of the groups/edit action flow.
 *
 * @todo #4683 proposes that icons are owned by groups and not group owners
 * @see _elgg_filestore_move_icons()
 *
 * @param string   $event "update:after"
 * @param string   $type  "group"
 * @param ElggGrup $group Group entity
 * @return void
 */
function groups_update_event_listener($event, $type, $group) {

	/* @var $group \ElggGroup */

	$original_attributes = $group->getOriginalAttributes();
	if (empty($original_attributes['owner_guid'])) {
		return;
	}

	$previous_owner_guid = $original_attributes['owner_guid'];

	// In addition to standard icons, groups plugin stores a copy of the original upload
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $previous_owner_guid;
	$filehandler->setFilename("groups/$group->guid.jpg");
	$filehandler->transfer($group->owner_guid);
}

/**
 * Remove groups icons on delete
 *
 * This operation is performed in an event listener to ensure that icons
 * are removed when group is deleted outside of groups/delete action flow.
 *
 * Registered with a hight priority to make sure that other handlers to not prevent
 * the deletion.
 * 
 * @param string   $event "delete"
 * @param string   $type  "group"
 * @param ElggGrup $group Group entity
 * @return void
 */
function groups_delete_event_listener($event, $type, $group) {

	/* @var $group \ElggGroup */

	// In addition to standard icons, groups plugin stores a copy of the original upload
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $group->owner_guid;
	$filehandler->setFilename("groups/$group->guid.jpg");
	$filehandler->delete();

	$group->deleteIcon();
}

/**
 * Return the write access for the current group if the user has write access to it.
 */
function groups_write_acl_plugin_hook($hook, $entity_type, $returnvalue, $params) {

	$user_guid = sanitise_int(elgg_extract('user_id', $params), false);
	$user = get_user($user_guid);
	if (empty($user)) {
		return $returnvalue;
	}

	$page_owner = elgg_get_page_owner_entity();
	if (!($page_owner instanceof ElggGroup)) {
		return $returnvalue;
	}

	if (!$page_owner->canWriteToContainer($user_guid)) {
		return $returnvalue;
	}

	// check group content access rules
	$allowed_access = array(
		ACCESS_PRIVATE
	);

	if ($page_owner->getContentAccessMode() !== ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
		$allowed_access[] = ACCESS_LOGGED_IN;
		$allowed_access[] = ACCESS_PUBLIC;
	}

	foreach ($returnvalue as $access_id => $access_string) {
		if (!in_array($access_id, $allowed_access)) {
			unset($returnvalue[$access_id]);
		}
	}

	// add write access to the group
	$returnvalue[$page_owner->group_acl] = elgg_echo('groups:acl', array($page_owner->name));

	return $returnvalue;
}

/**
 * Listens to a group join event and adds a user to the group's access control
 *
 */
function groups_user_join_event_listener($event, $object_type, $object) {

	$group = $object['group'];
	$user = $object['user'];
	$acl = $group->group_acl;

	add_user_to_access_collection($user->guid, $acl);

	return true;
}

/**
 * Make sure users are added to the access collection
 */
function groups_access_collection_override($hook, $entity_type, $returnvalue, $params) {
	if (isset($params['collection'])) {
		if (elgg_instanceof(get_entity($params['collection']->owner_guid), 'group')) {
			return true;
		}
	}
}

/**
 * Listens to a group leave event and removes a user from the group's access control
 *
 */
function groups_user_leave_event_listener($event, $object_type, $object) {

	$group = $object['group'];
	$user = $object['user'];
	$acl = $group->group_acl;

	remove_user_from_access_collection($user->guid, $acl);

	return true;
}

/**
 * The default access for members only content is this group only. This makes
 * for better display of access (can tell it is group only), but does not change
 * access to the content.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param int    $access Current default access
 * @return int
 */
function groups_access_default_override($hook, $type, $access) {
	$page_owner = elgg_get_page_owner_entity();

	if (elgg_instanceof($page_owner, 'group')) {
		if ($page_owner->getContentAccessMode() == ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
			$access = $page_owner->group_acl;
		}
	}

	return $access;
}

/**
 * Grabs groups by invitations
 * Have to override all access until there's a way override access to getter functions.
 *
 * @param int   $user_guid    The user's guid
 * @param bool  $return_guids Return guids rather than ElggGroup objects
 * @param array $options      Additional options
 *
 * @return mixed ElggGroups or guids depending on $return_guids, or count
 */
function groups_get_invited_groups($user_guid, $return_guids = false, $options = array()) {

	$ia = elgg_set_ignore_access(true);

	$defaults = array(
		'relationship' => 'invited',
		'relationship_guid' => (int) $user_guid,
		'inverse_relationship' => true,
		'limit' => 0,
	);

	$options = array_merge($defaults, $options);
	$groups = elgg_get_entities_from_relationship($options);

	elgg_set_ignore_access($ia);

	if ($return_guids) {
		$guids = array();
		foreach ($groups as $group) {
			$guids[] = $group->getGUID();
		}

		return $guids;
	}

	return $groups;
}

/**
 * Join a user to a group, add river event, clean-up invitations
 *
 * @param ElggGroup $group
 * @param ElggUser  $user
 * @return bool
 */
function groups_join_group($group, $user) {

	// access ignore so user can be added to access collection of invisible group
	$ia = elgg_set_ignore_access(TRUE);
	$result = $group->join($user);
	elgg_set_ignore_access($ia);

	if ($result) {
		// flush user's access info so the collection is added
		get_access_list($user->guid, 0, true);

		// Remove any invite or join request flags
		remove_entity_relationship($group->guid, 'invited', $user->guid);
		remove_entity_relationship($user->guid, 'membership_request', $group->guid);

		elgg_create_river_item(array(
			'view' => 'river/relationship/member/create',
			'action_type' => 'join',
			'subject_guid' => $user->guid,
			'object_guid' => $group->guid,
		));

		return true;
	}

	return false;
}

/**
 * Function to use on groups for access. It will house private, loggedin, public,
 * and the group itself. This is when you don't want other groups or access lists
 * in the access options available.
 *
 * @return array
 */
function group_access_options($group) {
	$access_array = array(
		ACCESS_PRIVATE => 'private',
		ACCESS_LOGGED_IN => 'logged in users',
		ACCESS_PUBLIC => 'public',
		$group->group_acl => elgg_echo('groups:acl', array($group->name)),
	);
	return $access_array;
}

function activity_profile_menu($hook, $entity_type, $return_value, $params) {

	if ($params['owner'] instanceof ElggGroup) {
		$return_value[] = array(
			'text' => elgg_echo('groups:activity'),
			'href' => "groups/activity/{$params['owner']->getGUID()}"
		);
	}
	return $return_value;
}

/**
 * Parse ECML on group profiles
 */
function groupprofile_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['groups/groupprofile'] = elgg_echo('groups:ecml:groupprofile');

	return $return_value;
}

/**
 * Process upgrades for the groups plugin
 */
function groups_run_upgrades() {
	$path = __DIR__ . '/upgrades/';
	$files = elgg_get_upgrade_files($path);
	foreach ($files as $file) {
		include "$path{$file}";
	}
}

/**
 * Runs unit tests for groups
 *
 * @return array
 */
function groups_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->pluginspath . 'groups/tests/write_access.php';
	return $value;
}

/**
 * Setup invitation request actions
 *
 * @param string $hook   "register"
 * @param string $type   "menu:invitationrequest"
 * @param array  $menu   Menu items
 * @param array  $params Hook params
 * @return array
 */
function groups_invitationrequest_menu_setup($hook, $type, $menu, $params) {

	$group = elgg_extract('entity', $params);
	$user = elgg_extract('user', $params);

	if (!$group instanceof \ElggGroup) {
		return $menu;
	}

	if (!$user instanceof \ElggUser || !$user->canEdit()) {
		return $menu;
	}

	$accept_url = elgg_http_add_url_query_elements('action/groups/join', array(
		'user_guid' => $user->guid,
		'group_guid' => $group->guid,
	));

	$menu[] = \ElggMenuItem::factory(array(
		'name' => 'accept',
		'href' => $accept_url,
		'is_action' => true,
		'text' => elgg_echo('accept'),
		'link_class' => 'elgg-button elgg-button-submit',
		'is_trusted' => true,
	));

	$delete_url = elgg_http_add_url_query_elements('action/groups/killinvitation', array(
		'user_guid' => $user->guid,
		'group_guid' => $group->guid,
	));

	$menu[] = \ElggMenuItem::factory(array(
		'name' => 'delete',
		'href' => $delete_url,
		'is_action' => true,
		'confirm' => elgg_echo('groups:invite:remove:check'),
		'text' => elgg_echo('delete'),
		'link_class' => 'elgg-button elgg-button-delete mlm',
	));

	return $menu;
}

/**
 * Setup group members tabs
 *
 * @param string         $hook   "register"
 * @param string         $type   "menu:groups_members"
 * @param ElggMenuItem[] $menu   Menu items
 * @param array          $params Hook params
 *
 * @return void|ElggMenuItem[]
 */
function groups_members_menu_setup($hook, $type, $menu, $params) {

	$entity = elgg_extract('entity', $params);
	if (empty($entity) || !($entity instanceof ElggGroup)) {
		return;
	}

	$menu[] = ElggMenuItem::factory([
		'name' => 'alpha',
		'text' => elgg_echo('sort:alpha'),
		'href' => "groups/members/{$entity->getGUID()}",
		'priority' => 100
	]);

	$menu[] = ElggMenuItem::factory([
		'name' => 'newest',
		'text' => elgg_echo('sort:newest'),
		'href' => "groups/members/{$entity->getGUID()}/newest",
		'priority' => 200
	]);

	return $menu;
}

/**
 * Returns menu items to be registered in the title menu of the group profile
 *
 * @param string         $hook   "profile_buttons"
 * @param string         $type   "group"
 * @param ElggMenuItem[] $items  Buttons
 * @param array          $params Hook params
 * @return ElggMenuItem[]
 */
function groups_prepare_profile_buttons($hook, $type, $items, $params) {

	$group = elgg_extract('entity', $params);
	if (!$group instanceof ElggGroup) {
		return;
	}

	$actions = [];

	if ($group->canEdit()) {
		// group owners can edit the group and invite new members
		$actions['groups:edit'] = "groups/edit/{$group->guid}";
		$actions['groups:invite'] = "groups/invite/{$group->guid}";
	}

	$user = elgg_get_logged_in_user_entity();
	if ($user && $group->isMember($user)) {
		if ($group->owner_guid != $user->guid) {
			// a member can leave a group if he/she doesn't own it
			$actions['groups:leave'] = "action/groups/leave?group_guid={$group->guid}";
		}
	} else if ($user) {
		$url = "action/groups/join?group_guid={$group->guid}";
		if ($group->isPublicMembership() || $group->canEdit()) {
			// admins can always join
			// non-admins can join if membership is public
			$actions['groups:join'] = $url;
		} else {
			// request membership
			$actions['groups:joinrequest'] = $url;
		}
	}

	foreach ($actions as $action => $url) {
		$items[] = ElggMenuItem::factory(array(
			'name' => $action,
			'href' => elgg_normalize_url($url),
			'text' => elgg_echo($action),
			'is_action' => 0 === strpos($url, 'action'),
			'link_class' => 'elgg-button elgg-button-action',
		));
	}

	return $items;
}

/**
 * Helper handler to correctly resolve page owners on group routes
 *
 * @see default_page_owner_handler()
 *
 * @param string $hook   "page_owner"
 * @param string $type   "system"
 * @param int    $return Page owner guid
 * @param array  $params Hook params
 * @return int|void
 */
function groups_default_page_owner_handler($hook, $type, $return, $params) {

	if ($return) {
		return;
	}

	$segments = _elgg_services()->request->getUrlSegments();
	$identifier = array_shift($segments);

	if ($identifier !== 'groups') {
		return;
	}

	$page = array_shift($segments);

	switch ($page) {

		case 'add' :
			$guid = array_shift($segments);
			if (!$guid) {
				$guid = elgg_get_logged_in_user_guid();
			}
			return $guid;

		case 'edit':
		case 'profile' :
		case 'activity' :
		case 'invite' :
		case 'requests' :
		case 'members' :
		case 'profile' :
			$guid = array_shift($segments);
			if (!$guid) {
				return;
			}
			return $guid;

		case 'member' :
		case 'owner' :
		case 'invitations':
			$username = array_shift($segments);
			if ($username) {
				$user = get_user_by_username($username);
			} else {
				$user = elgg_get_logged_in_user_entity();
			}
			if (!$user) {
				return;
			}
			return $user->guid;
	}
}
