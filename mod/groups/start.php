<?php
/**
 * Elgg groups plugin
 *
 * @package ElggGroups
 */

/**
 * Initialize the groups plugin
 *
 * @return void
 */
function groups_init() {

	elgg_register_library('elgg:groups', __DIR__ . '/lib/groups.php');
	elgg_load_library('elgg:groups');
	
	elgg_extend_view('object/elements/imprint/contents', 'groups/imprint/member_count');
	elgg_extend_view('object/elements/imprint/contents', 'groups/imprint/membership_type');
	
	// Set up the menu
	$item = new ElggMenuItem('groups', elgg_echo('groups'), 'groups/all');
	elgg_register_menu_item('site', $item);

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('groups', 'groups_page_handler');

	// Register URL handlers for groups
	elgg_register_plugin_hook_handler('entity:url', 'group', 'groups_set_url');
	elgg_register_plugin_hook_handler('entity:icon:sizes', 'group', 'groups_set_icon_sizes');

	// add group activity tool option
	if (elgg_get_plugin_setting('allow_activity', 'groups') === 'yes') {
		add_group_tool_option('activity', elgg_echo('groups:enableactivity'), true);
		elgg_extend_view('groups/tool_latest', 'groups/profile/activity_module');
	}

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
	
	// topbar menu
	elgg_register_plugin_hook_handler('register', 'menu:topbar', '_groups_topbar_menu_setup');

	//extend some views
	elgg_extend_view('elgg.css', 'groups/css');

	// Access permissions
	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'groups_write_acl_plugin_hook', 600);
	elgg_register_plugin_hook_handler('default', 'access', 'groups_access_default_override');
	elgg_register_plugin_hook_handler('access_collection:name', 'access_collection', 'groups_set_access_collection_name');

	// allow ecml in profiles
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'groupprofile_ecml_views_hook');

	// Register a handler for create groups
	elgg_register_event_handler('create', 'group', 'groups_create_event_listener');
	elgg_register_event_handler('update:after', 'group', 'groups_update_event_listener');

	elgg_register_event_handler('join', 'group', 'groups_user_join_event_listener');
	elgg_register_event_handler('leave', 'group', 'groups_user_leave_event_listener');

	// allow to be liked
	elgg_register_plugin_hook_handler('likes:is_likable', 'group:', 'Elgg\Values::getTrue');

	// prepare profile buttons to be registered in the title menu
	elgg_register_plugin_hook_handler('profile_buttons', 'group', 'groups_prepare_profile_buttons');

	// Help core resolve page owner guids from group routes
	// Registered with an earlier priority to be called before default_page_owner_handler()
	elgg_register_plugin_hook_handler('page_owner', 'system', 'groups_default_page_owner_handler', 400);

	// Setup filter tabs on /groups/all page
	elgg_register_plugin_hook_handler('register', 'menu:filter:groups/all', 'groups_setup_filter_tabs');

	elgg_register_plugin_hook_handler('register', 'menu:page', '_groups_page_menu_group_profile');
	elgg_register_plugin_hook_handler('register', 'menu:page', '_groups_page_menu');
}

/**
 * This function loads a set of default fields into the profile, then triggers
 * a hook letting other plugins to edit add and delete fields.
 *
 * Note: This is a system:init event triggered function and is run at a super
 * low priority to guarantee that it is called after all other plugins have
 * initialized.
 *
 * @return void
 */
function groups_fields_setup() {

	$profile_defaults = [
		'description' => 'longtext',
		'briefdescription' => 'text',
		'interests' => 'tags',
	];

	$profile_defaults = elgg_trigger_plugin_hook('profile:fields', 'group', null, $profile_defaults);

	elgg_set_config('group', $profile_defaults);

	// register any tag metadata names
	foreach ($profile_defaults as $name => $type) {
		if ($type == 'tags') {
			elgg_register_tag_metadata_name($name);

			// only shows up in search but why not just set this in en.php as doing it here
			// means you cannot override it in a plugin
			add_translation(get_current_language(), ["tag_names:$name" => elgg_echo("groups:$name")]);
		}
	}
}

/**
 * Register menu items for the page menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:page'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 *
 * @access private
 * @since 3.0
 */
function _groups_page_menu_group_profile($hook, $type, $return, $params) {
	
	if (!elgg_in_context('group_profile') || !elgg_is_logged_in()) {
		return;
	}
	
	// Get the page owner entity
	$page_owner = elgg_get_page_owner_entity();
	if (!($page_owner instanceof ElggGroup)) {
		return;
	}
	
	if (!$page_owner->canEdit() || $page_owner->isPublicMembership()) {
		return;
	}
	
	$count = elgg_get_entities([
		'type' => 'user',
		'relationship' => 'membership_request',
		'relationship_guid' => $page_owner->guid,
		'inverse_relationship' => true,
		'count' => true,
	]);

	$text = elgg_echo('groups:membershiprequests');
	$title = $text;
	if ($count) {
		$title = elgg_echo('groups:membershiprequests:pending', [$count]);
	}
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'membership_requests',
		'text' => $text,
		'badge' => $count ? $count : null,
		'title' => $title,
		'href' => "groups/requests/{$page_owner->guid}",
	]);
	
	return $return;
}

/**
 * Register menu items for the page menu
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:page'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 *
 * @access private
 * @since 3.0
 */
function _groups_page_menu($hook, $type, $return, $params) {
	
	if (elgg_get_context() !== 'groups') {
		return;
	}
	
	// Get the page owner entity
	$page_owner = elgg_get_page_owner_entity();
	if ($page_owner instanceof ElggGroup) {
		return;
	}
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'groups:all',
		'text' => elgg_echo('groups:all'),
		'href' => 'groups/all',
	]);

	$user = elgg_get_logged_in_user_entity();
	if (!$user) {
		return $return;
	}
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'groups:owned',
		'text' => elgg_echo('groups:owned'),
		'href' => "groups/owner/$user->username",
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'groups:member',
		'text' => elgg_echo('groups:yours'),
		'href' => "groups/member/$user->username",
	]);

	$invitation_count = groups_get_invited_groups($user->guid, false, ['count' => true]);

	// Invitations
	$text = elgg_echo('groups:invitations');
	$title = $text;
	if ($invitation_count) {
		$title = elgg_echo('groups:invitations:pending', [$invitation_count]);
	}

	$return[] = \ElggMenuItem::factory([
		'name' => 'groups:user:invites',
		'text' => $text,
		'badge' => $invitation_count ?: null,
		'title' => $title,
		'href' => "groups/invitations/$user->username",
	]);

	return $return;
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
		case 'all':
			// all groups doesn't get link to self
			elgg_pop_breadcrumb();
		case 'add':
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
 * Populates the ->getUrl() method for group objects
 *
 * @param string $hook   'entity:url'
 * @param string $type   'group'
 * @param string $url    current return value
 * @param array  $params supplied params
 *
 * @return void|string
 */
function groups_set_url($hook, $type, $url, $params) {
	
	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof ElggGroup) {
		return;
	}
	
	$title = elgg_get_friendly_title($entity->getDisplayName());
	return "groups/profile/{$entity->guid}/$title";
}

/**
 * Add owner block link
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:owner_block'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 */
function groups_activity_owner_block_menu($hook, $type, $return, $params) {
	
	$entity = elgg_extract('entity', $params);
	if (!$entity instanceof ElggGroup) {
		return;
	}
	
	if (!$entity->isToolEnabled('activity')) {
		return;
	}
	
	$url = "groups/activity/{$entity->guid}";
	$item = new ElggMenuItem('activity', elgg_echo('groups:activity'), $url);
	$return[] = $item;
	
	return $return;
}

/**
 * Add links/info to entity menu particular to group entities
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:entity'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 */
function groups_entity_menu_setup($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params);
	if (!($entity instanceof \ElggGroup)) {
		return;
	}
	
	if (!elgg_is_admin_logged_in()) {
		return;
	}
	
	$isFeatured = $entity->featured_group === "yes";

	$return[] = ElggMenuItem::factory([
		'name' => 'feature',
		'icon' => 'arrow-up',
		'text' => elgg_echo('groups:makefeatured'),
		'href' => "action/groups/featured?group_guid={$entity->guid}&action_type=feature",
		'is_action' => true,
		'item_class' => $isFeatured ? 'hidden' : '',
		'data-toggle' => 'unfeature',
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'unfeature',
		'icon' => 'arrow-down',
		'text' => elgg_echo('groups:makeunfeatured'),
		'href' => "action/groups/featured?group_guid={$entity->guid}&action_type=unfeature",
		'is_action' => true,
		'item_class' => $isFeatured ? '' : 'hidden',
		'data-toggle' => 'feature',
	]);

	return $return;
}

/**
 * Add a remove user link to user hover menu when the page owner is a group
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:user_hover'
 * @param ElggMenuItem[] $return current return value
 * @param array          $params supplied params
 *
 * @return void|ElggMenuItem[]
 */
function groups_user_entity_menu_setup($hook, $type, $return, $params) {
	$group = elgg_get_page_owner_entity();

	if (!($group instanceof \ElggGroup) || !$group->canEdit()) {
		return;
	}

	$entity = elgg_extract('entity', $params);

	// Make sure we have a user and that user is a member of the group
	if (!($entity instanceof \ElggUser) || !$group->isMember($entity)) {
		return;
	}

	// Check if we are looking at the group owner
	if ($group->owner_guid === $entity->guid) {
		return;
	}
	
	$return[] = ElggMenuItem::factory([
		'name' => 'removeuser',
		'href' => "action/groups/remove?user_guid={$entity->guid}&group_guid={$group->guid}",
		'text' => elgg_echo('groups:removeuser'),
		'icon' => 'user-times',
		'confirm' => true,
		'priority' => 999,
	]);

	return $return;
}

/**
 * Groups created so create an access list for it
 *
 * @param string    $event  'create'
 * @param string    $type   'group'
 * @param ElggGroup $object the new group
 *
 * @return bool
 */
function groups_create_event_listener($event, $type, $object) {

	// ensure that user has sufficient permissions to update group metadata
	// prior to joining the group
	$ia = elgg_set_ignore_access(true);

	$ac_name = elgg_echo('groups:group') . ": " . $object->name;
	$ac_id = create_access_collection($ac_name, $object->guid, 'group_acl');
	
	elgg_set_ignore_access($ia);

	return (bool) $ac_id; // delete the group if acl creation fails
}

/**
 * Listen to group ownership changes and update group icon ownership
 * This will only move the source file, the actual icons are moved by
 * _elgg_filestore_move_icons()
 *
 * This operation is performed in an event listener to ensure that icons
 * are moved when ownership changes outside of the groups/edit action flow.
 *
 * @see _elgg_filestore_move_icons()
 *
 * @param string    $event "update:after"
 * @param string    $type  "group"
 * @param ElggGroup $group Group entity
 * @return void
 */
function groups_update_event_listener($event, $type, $group) {

	/* @var $group \ElggGroup */

	$original_attributes = $group->getOriginalAttributes();

	if (!empty($original_attributes['owner_guid'])) {
		$previous_owner_guid = $original_attributes['owner_guid'];

		// Update owned metadata
		$metadata = elgg_get_metadata([
			'guid' => $group->guid,
			'metadata_owner_guids' => $previous_owner_guid,
			'limit' => 0,
		]);

		if ($metadata) {
			foreach ($metadata as $md) {
				$md->owner_guid = $group->owner_guid;
				$md->save();
			}
		}
	}

	if (!empty($original_attributes['name'])) {
		// update access collection name if group name changes
		$group_name = html_entity_decode($group->name, ENT_QUOTES, 'UTF-8');
		$ac_name = elgg_echo('groups:group') . ": " . $group_name;
		$acl = _groups_get_group_acl($group);
		if ($acl) {
			$acl->name = $ac_name;
			$acl->save();
		}
	}
}

/**
 * Return the write access for the current group if the user has write access to it
 *
 * @param \Elgg\Hook $hook 'access:collection:write' 'all'
 * @return void|array
 */
function groups_write_acl_plugin_hook(\Elgg\Hook $hook) {

	$user_guid = $hook->getParam('user_id');
	$user = get_user($user_guid);
	if (!$user) {
		return;
	}

	$page_owner = elgg_get_page_owner_entity();
	if (!$page_owner instanceof ElggGroup) {
		return;
	}

	if (!$page_owner->canWriteToContainer($user_guid)) {
		return;
	}

	$allowed_access = [ACCESS_PRIVATE];
	$acl = _groups_get_group_acl($page_owner);
	if ($acl) {
		$allowed_access[] = $acl->id;
	}

	if ($page_owner->getContentAccessMode() !== ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
		$allowed_access[] = ACCESS_LOGGED_IN;
		if (!elgg_get_config('walled_garden')) {
			$allowed_access[] = ACCESS_PUBLIC;
		}
	}

	$write_acls = $hook->getValue();

	// add write access to the group
	if ($acl) {
		$write_acls[$acl->id] = $acl->getDisplayName();
	}

	foreach (array_keys($write_acls) as $access_id) {
		if (!in_array($access_id, $allowed_access)) {
			unset($write_acls[$access_id]);
		}
	}

	return $write_acls;
}

/**
 * Return the write access for the current group if the user has write access to it
 *
 * @param \Elgg\Hook $hook 'access_collection:display_name' 'access_collection'
 * @return void|string
 */
function groups_set_access_collection_name(\Elgg\Hook $hook) {

	$access_collection = $hook->getParam('access_collection');
	if (!$access_collection instanceof ElggAccessCollection) {
		return;
	}

	$owner = $access_collection->getOwnerEntity();
	if (!$owner instanceof ElggGroup) {
		return;
	}
	
	$page_owner = elgg_get_page_owner_entity();

	if ($page_owner && $page_owner->guid == $owner->guid) {
		return elgg_echo('groups:acl:in_context');
	}

	if ($owner->canWriteToContainer()) {
		return elgg_echo('groups:acl', [$owner->getDisplayName()]);
	}
}

/**
 * Perform actions when a user joins a group
 *
 * @param string $event       'join'
 * @param string $object_type 'group'
 * @param array  $params      supplied params
 *
 * @return void
 */
function groups_user_join_event_listener($event, $object_type, $params) {
	$group = elgg_extract('group', $params);
	$user = elgg_extract('user', $params);
	if (!$group instanceof ElggGroup || !$user instanceof ElggUser) {
		return;
	}
	
	// Remove any invite or join request flags
	remove_entity_relationship($group->guid, 'invited', $user->guid);
	remove_entity_relationship($user->guid, 'membership_request', $group->guid);

	if (elgg_extract('create_river_item', $params)) {
		elgg_create_river_item([
			'action_type' => 'join',
			'subject_guid' => $user->guid,
			'object_guid' => $group->guid,
		]);
	}
	
	// add a user to the group's access control
	$collection = _groups_get_group_acl($group);
	if (!empty($collection)) {
		$collection->addMember($user->guid);
	}
}

/**
 * Perform actions when a user leaves a group
 *
 * @param string $event       'leave'
 * @param string $object_type 'group'
 * @param array  $params      supplied params
 *
 * @return void
 */
function groups_user_leave_event_listener($event, $object_type, $params) {
	$group = elgg_extract('group', $params);
	$user = elgg_extract('user', $params);
	if (!$group instanceof ElggGroup || !$user instanceof ElggUser) {
		return;
	}
	
	// Remove any invite or join request flags (for some edge cases)
	remove_entity_relationship($group->guid, 'invited', $user->guid);
	remove_entity_relationship($user->guid, 'membership_request', $group->guid);
	
	// Removes a user from the group's access control
	$collection = _groups_get_group_acl($group);
	if (!empty($collection)) {
		$collection->removeMember($user->guid);
	}
}

/**
 * The default access for members only content is this group only. This makes
 * for better display of access (can tell it is group only), but does not change
 * access to the content.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param int    $access Current default access
 *
 * @return int|void
 */
function groups_access_default_override($hook, $type, $access) {
	$page_owner = elgg_get_page_owner_entity();
	if (!($page_owner instanceof ElggGroup)) {
		return;
	}
			
	if ($page_owner->getContentAccessMode() !== ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
		return;
	}
	
	$acl = _groups_get_group_acl($page_owner);
	if (empty($acl)) {
		return;
	}
	
	return $acl->id;
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
function groups_get_invited_groups($user_guid, $return_guids = false, $options = []) {

	$ia = elgg_set_ignore_access(true);

	$defaults = [
		'relationship' => 'invited',
		'relationship_guid' => (int) $user_guid,
		'inverse_relationship' => true,
		'limit' => 0,
	];

	$options = array_merge($defaults, $options);
	$groups = elgg_get_entities($options);

	elgg_set_ignore_access($ia);

	if ($return_guids) {
		$guids = [];
		foreach ($groups as $group) {
			$guids[] = $group->getGUID();
		}

		return $guids;
	}

	return $groups;
}

/**
 * Function to use on groups for access. It will house private, loggedin, public,
 * and the group itself. This is when you don't want other groups or access lists
 * in the access options available.
 *
 * @param ElggGroup $group the group
 *
 * @return array
 */
function group_access_options($group) {

	$access_array = [
		ACCESS_PRIVATE => elgg_echo('access:label:private'),
		ACCESS_LOGGED_IN => elgg_echo('access:label:logged_in'),
	];

	if (!elgg_get_config('walled_garden')) {
		$access_array[ACCESS_PUBLIC] = elgg_echo('access:label:public');
	}

	if (!$group instanceof ElggGroup) {
		return $access_array;
	}
	
	$collection = _groups_get_group_acl($group);
	if ($collection) {
		$access_array[$collection->id] = $collection->getDisplayName();
	}
	
	return $access_array;
}

/**
 * Parse ECML on group profiles
 *
 * @param string $hook         'get_views'
 * @param string $type         'ecml'
 * @param array  $return_value current return value
 * @param mixed  $params       supplied params
 *
 * @return array
 */
function groupprofile_ecml_views_hook($hook, $type, $return_value, $params) {
	$return_value['groups/groupprofile'] = elgg_echo('groups:ecml:groupprofile');

	return $return_value;
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

	$accept_url = elgg_http_add_url_query_elements('action/groups/join', [
		'user_guid' => $user->guid,
		'group_guid' => $group->guid,
	]);

	$menu[] = \ElggMenuItem::factory([
		'name' => 'accept',
		'href' => $accept_url,
		'is_action' => true,
		'text' => elgg_echo('accept'),
		'link_class' => 'elgg-button elgg-button-submit',
		'is_trusted' => true,
	]);

	$delete_url = elgg_http_add_url_query_elements('action/groups/killinvitation', [
		'user_guid' => $user->guid,
		'group_guid' => $group->guid,
	]);

	$menu[] = \ElggMenuItem::factory([
		'name' => 'delete',
		'href' => $delete_url,
		'is_action' => true,
		'confirm' => elgg_echo('groups:invite:remove:check'),
		'text' => elgg_echo('delete'),
		'link_class' => 'elgg-button elgg-button-delete mlm',
	]);

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
 * Registers optional group invites menu item to topbar
 *
 * @elgg_plugin_hook register menu:topbar
 *
 * @param \Elgg\Hook $hook hook
 *
 * @return void|ElggMenuItem[]
 *
 * @since 3.0
 *
 * @internal
 */
function _groups_topbar_menu_setup(\Elgg\Hook $hook) {

	$user = elgg_get_logged_in_user_entity();
	if (empty($user)) {
		return;
	}
	
	$count = groups_get_invited_groups($user->guid, false, ['count' => true]);
	if (empty($count)) {
		return;
	}
	
	$result = $hook->getValue();
	
	// Invitations
	$text = elgg_echo('groups:invitations');
	$title = elgg_echo('groups:invitations:pending', [$count]);
	
	$result[] = \ElggMenuItem::factory([
		'name' => 'groups:user:invites',
		'text' => $text,
		'badge' => $count,
		'title' => $title,
		'icon' => 'users',
		'parent_name' => 'account',
		'section' => 'alt',
		'href' => "groups/invitations/{$user->username}",
	]);
	
	return $result;
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
		$items[] = ElggMenuItem::factory([
			'name' => $action,
			'href' => elgg_normalize_url($url),
			'text' => elgg_echo($action),
			'is_action' => 0 === strpos($url, 'action'),
			'link_class' => 'elgg-button elgg-button-action',
		]);
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

/**
 * Setup filter tabs on /groups/all page
 *
 * @param string         $hook   "register"
 * @param string         $type   "menu:filter:groups/all"
 * @param ElggMenuItem[] $return Menu
 * @param array          $params Hook params
 * @return ElggMenuItem[]
 */
function groups_setup_filter_tabs($hook, $type, $return, $params) {

	$filter_value = elgg_extract('filter_value', $params);

	$return[] = ElggMenuItem::factory([
		'name' => 'newest',
		'text' => elgg_echo('sort:newest'),
		'href' => 'groups/all?filter=newest',
		'priority' => 200,
		'selected' => $filter_value == 'newest',
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'alpha',
		'text' => elgg_echo('sort:alpha'),
		'href' => 'groups/all?filter=alpha',
		'priority' => 250,
		'selected' => $filter_value == 'alpha',
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'popular',
		'text' => elgg_echo('sort:popular'),
		'href' => 'groups/all?filter=popular',
		'priority' => 300,
		'selected' => $filter_value == 'popular',
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'featured',
		'text' => elgg_echo('groups:featured'),
		'href' => 'groups/all?filter=featured',
		'priority' => 400,
		'selected' => $filter_value == 'featured',
	]);
	
	return $return;
}

/**
 * Add 'original' to group icon sizes
 *
 * @elgg_plugin_hook entity:icon:sizes group
 *
 * @param \Elgg\Hook $hook Hook
 * @return array
 */
function groups_set_icon_sizes(\Elgg\Hook $hook) {

	$sizes = $hook->getValue();
	$sizes['original'] = [];

	return $sizes;
}

/**
 * Get the access collection for a given group
 *
 * @param \ElggGroup $group the group
 *
 * @return \ElggAccessCollection|false
 *
 * @internal
 * @since 3.0
 */
function _groups_get_group_acl(\ElggGroup $group) {
	if (!$group instanceof \ElggGroup) {
		return false;
	}
	
	return elgg_extract(0, $group->getOwnedAccessCollections(['subtype' => 'group_acl']), false);
}

return function() {
	elgg_register_event_handler('init', 'system', 'groups_init');

	// Ensure this runs after other plugins
	elgg_register_event_handler('init', 'system', 'groups_fields_setup', 10000);
};