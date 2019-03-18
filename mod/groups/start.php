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

	// Set up the menu
	elgg_register_menu_item('site', [
		'name' => 'groups',
		'icon' => 'users',
		'text' => elgg_echo('groups'),
		'href' => elgg_generate_url('collection:group:group:all'),
	]);

	// Register URL handlers for groups
	elgg_register_plugin_hook_handler('entity:url', 'group', 'groups_set_url');

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
	elgg_extend_view('elgg.css', 'groups/groups.css');

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

	// Help core resolve page owner guids from group routes
	// Registered with an earlier priority to be called before default_page_owner_handler()
	elgg_register_plugin_hook_handler('page_owner', 'system', 'groups_default_page_owner_handler', 400);

	// Setup filter tabs on /groups/all page
	elgg_register_plugin_hook_handler('register', 'menu:filter:groups/all', 'groups_setup_filter_tabs');

	elgg_register_plugin_hook_handler('register', 'menu:page', '_groups_page_menu_group_profile');
	elgg_register_plugin_hook_handler('register', 'menu:page', '_groups_page_menu');
	elgg_register_plugin_hook_handler('register', 'menu:title', '_groups_title_menu');

	elgg_register_plugin_hook_handler('gatekeeper', 'group:group', '_groups_gatekeeper_allow_profile_page');
	
	elgg_register_plugin_hook_handler('search:fields', 'group', \Elgg\Search\GroupSearchProfileFieldsHandler::class);
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
		'href' => elgg_generate_entity_url($page_owner, 'requests'),
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
		'href' => elgg_generate_url('collection:group:group:all'),
	]);

	$user = elgg_get_logged_in_user_entity();
	if (!$user) {
		return $return;
	}
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'groups:owned',
		'text' => elgg_echo('groups:owned'),
		'href' => elgg_generate_url('collection:group:group:owner', [
			'username' => $user->username,
		]),
	]);
	
	$return[] = \ElggMenuItem::factory([
		'name' => 'groups:member',
		'text' => elgg_echo('groups:yours'),
		'href' => elgg_generate_url('collection:group:group:member', [
			'username' => $user->username,
		]),
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
		'href' => elgg_generate_url('collection:group:group:invitations', [
			'username' => $user->username,
		]),
	]);

	return $return;
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
 * Returns a menu item for leaving a group
 *
 * @param \ElggGroup $group Group to leave
 * @param \ElggUser  $user  User to check leave action for
 *
 * @return \ElggMenuItem|false
 */
function groups_get_group_leave_menu_item(\ElggGroup $group, $user = null) {
	if (!$group instanceof \ElggGroup) {
		return false;
	}
	
	if (!$user instanceof ElggUser) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if (!$user instanceof ElggUser) {
		return false;
	}
	
	if (!$group->isMember($user) || ($group->owner_guid === $user->guid)) {
		// a member can leave a group if he/she doesn't own it
		return false;
	}
	
	return \ElggMenuItem::factory([
		'name' => 'groups:leave',
		'icon' => 'sign-out',
		'text' => elgg_echo('groups:leave'),
		'href' => elgg_generate_action_url('groups/leave', [
			'group_guid' => $group->guid,
			'user_guid' => $user->guid,
		]),
	]);
}

/**
 * Returns a menu item for joining a group
 *
 * @param \ElggGroup $group Group to leave
 * @param \ElggUser  $user  User to check leave action for
 *
 * @return \ElggMenuItem|false
 */
function groups_get_group_join_menu_item(\ElggGroup $group, $user = null) {
	if (!$group instanceof \ElggGroup) {
		return false;
	}
	
	if (!$user instanceof ElggUser) {
		$user = elgg_get_logged_in_user_entity();
	}
	
	if (!$user instanceof ElggUser) {
		return false;
	}
	
	if ($group->isMember($user)) {
		return false;
	}
	
	$menu_name = 'groups:joinrequest';
	if ($group->isPublicMembership() || $group->canEdit()) {
		// admins can always join
		// non-admins can join if membership is public
		$menu_name = 'groups:join';
	}
	
	return \ElggMenuItem::factory([
		'name' => $menu_name,
		'icon' => 'sign-in',
		'text' => elgg_echo($menu_name),
		'href' => elgg_generate_action_url('groups/join', [
			'group_guid' => $group->guid,
			'user_guid' => $user->guid,
		]),
	]);
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
	
	$user = elgg_get_logged_in_user_entity();
	if (empty($user)) {
		return;
	}
	
	$group_join = groups_get_group_join_menu_item($entity, $user);
	if (!empty($group_join)) {
		$return[] = $group_join;
	}
	
	$group_leave = groups_get_group_leave_menu_item($entity, $user);
	if (!empty($group_leave)) {
		$return[] = $group_leave;
	}
		
	if ($user->isAdmin()) {
		$isFeatured = $entity->featured_group === "yes";
	
		$return[] = ElggMenuItem::factory([
			'name' => 'feature',
			'icon' => 'arrow-up',
			'text' => elgg_echo('groups:makefeatured'),
			'href' => elgg_generate_action_url('groups/featured', [
				'group_guid' => $entity->guid,
				'action_type' => 'feature',
			]),
			'item_class' => $isFeatured ? 'hidden' : '',
			'data-toggle' => 'unfeature',
		]);
	
		$return[] = ElggMenuItem::factory([
			'name' => 'unfeature',
			'icon' => 'arrow-down',
			'text' => elgg_echo('groups:makeunfeatured'),
			'href' => elgg_generate_action_url('groups/featured', [
				'group_guid' => $entity->guid,
				'action_type' => 'unfeature',
			]),
			'item_class' => $isFeatured ? '' : 'hidden',
			'data-toggle' => 'feature',
		]);
	}
	
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
		'href' => elgg_generate_action_url('groups/remove', [
			'user_guid' => $entity->guid,
			'group_guid' => $group->guid,
		]),
		'text' => elgg_echo('groups:removeuser'),
		'icon' => 'user-times',
		'confirm' => true,
		'priority' => 999,
		'section' => 'action',
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

	$ac_name = elgg_echo('groups:group') . ": " . $object->getDisplayName();
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
		$group_name = html_entity_decode($group->getDisplayName(), ENT_QUOTES, 'UTF-8');
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
		// don't allow content sharing with higher levels than group access level
		// see https://github.com/Elgg/Elgg/issues/10285
		if (in_array($page_owner->access_id, [ACCESS_PUBLIC, ACCESS_LOGGED_IN])) {
			// at least logged in is allowed
			$allowed_access[] = ACCESS_LOGGED_IN;
			
			if ($page_owner->access_id === ACCESS_PUBLIC && !elgg_get_config('walled_garden')) {
				// public access is allowed
				$allowed_access[] = ACCESS_PUBLIC;
			}
		}
	}

	$write_acls = $hook->getValue();

	// add write access to the group
	if ($acl) {
		$write_acls[$acl->id] = $acl->getDisplayName();
	}

	// remove all but the allowed access levels
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

	$menu[] = \ElggMenuItem::factory([
		'name' => 'accept',
		'href' => elgg_generate_action_url('groups/join', [
			'user_guid' => $user->guid,
			'group_guid' => $group->guid,
		]),
		'text' => elgg_echo('accept'),
		'link_class' => 'elgg-button elgg-button-submit',
		'is_trusted' => true,
	]);

	$menu[] = \ElggMenuItem::factory([
		'name' => 'delete',
		'href' => elgg_generate_action_url('groups/killinvitation', [
			'user_guid' => $user->guid,
			'group_guid' => $group->guid,
		]),
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
		'href' => elgg_generate_url('collection:user:user:group_members', [
			'guid' => $entity->guid,
		]),
		'priority' => 100
	]);

	$menu[] = ElggMenuItem::factory([
		'name' => 'newest',
		'text' => elgg_echo('sort:newest'),
		'href' => elgg_generate_url('collection:user:user:group_members', [
			'guid' => $entity->guid,
			'sort' => 'newest',
		]),
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
		'href' => elgg_generate_url('collection:group:group:invitations', [
			'username' => $user->username,
		]),
	]);
	
	return $result;
}

/**
 * Registers title menu items for group
 *
 * @elgg_plugin_hook register menu:title
 *
 * @param \Elgg\Hook $hook Hook
 * @return \ElggMenuItem[]
 *
 * @internal
 */
function _groups_title_menu(\Elgg\Hook $hook) {
	$group = $hook->getEntityParam();
	if (!$group instanceof ElggGroup) {
		return;
	}
	
	$user = elgg_get_logged_in_user_entity();
	if (empty($user)) {
		return;
	}
	
	$result = $hook->getValue();
	if ($group->canEdit()) {
		// group owners can edit the group and invite new members
		$result[] = \ElggMenuItem::factory([
			'name' => 'edit',
			'icon' => 'edit',
			'href' => elgg_generate_entity_url($group, 'edit'),
			'text' => elgg_echo('groups:edit'),
			'link_class' => 'elgg-button elgg-button-action',
		]);
		$result[] = \ElggMenuItem::factory([
			'name' => 'groups:invite',
			'icon' => 'user-plus',
			'href' => elgg_generate_entity_url($group, 'invite'),
			'text' => elgg_echo('groups:invite'),
			'link_class' => 'elgg-button elgg-button-action',
		]);
	}
	
	if ($group->isMember($user)) {
		$is_owner = ($group->owner_guid === $user->guid);
		$result[] = ElggMenuItem::factory([
			'name' => 'group-dropdown',
			'href' => false,
			'text' => elgg_echo($is_owner ? 'groups:button:owned' : 'groups:button:joined'),
			'link_class' => 'elgg-button elgg-button-action-done',
			'child_menu' => [
				'display' => 'dropdown',
			],
			'data-position' => json_encode([
				'my' => 'right top',
				'at' => 'right bottom',
			]),
		]);
		
		$leave_group = groups_get_group_leave_menu_item($group, $user);
		if ($leave_group) {
			$leave_group->setParentName('group-dropdown');
			$result[] = $leave_group;
		}
	} else {
		$join_group = groups_get_group_join_menu_item($group, $user);
		if ($join_group) {
			$join_group->setLinkClass('elgg-button elgg-button-action');
			$result[] = $join_group;
		}
	}
	
	return $result;
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
		'href' => elgg_generate_url('collection:group:group:all', [
			'filter' => 'newest',
		]),
		'priority' => 200,
		'selected' => $filter_value == 'newest',
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'alpha',
		'text' => elgg_echo('sort:alpha'),
		'href' => elgg_generate_url('collection:group:group:all', [
			'filter' => 'alpha',
		]),
		'priority' => 250,
		'selected' => $filter_value == 'alpha',
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'popular',
		'text' => elgg_echo('sort:popular'),
		'href' => elgg_generate_url('collection:group:group:all', [
			'filter' => 'popular',
		]),
		'priority' => 300,
		'selected' => $filter_value == 'popular',
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'featured',
		'text' => elgg_echo('groups:featured'),
		'href' => elgg_generate_url('collection:group:group:all', [
			'filter' => 'featured',
		]),
		'priority' => 400,
		'selected' => $filter_value == 'featured',
	]);
	
	return $return;
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
	
	return $group->getOwnedAccessCollection('group_acl');
}

/**
 * Prepares variables for the group edit form view.
 *
 * @param mixed $group ElggGroup or null. If a group, uses values from the group.
 * @return array
 */
function groups_prepare_form_vars($group = null) {
	$values = [
		'name' => '',
		'membership' => ACCESS_PUBLIC,
		'vis' => ACCESS_PUBLIC,
		'guid' => null,
		'entity' => null,
		'owner_guid' => elgg_get_logged_in_user_guid(),
		'content_access_mode' => ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED
	];

	// handle customizable profile fields
	$fields = elgg_get_config('group');

	if ($fields) {
		foreach ($fields as $name => $type) {
			$values[$name] = '';
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

		// The content_access_mode was introduced in 1.9. This method must be
		// used for backwards compatibility with groups created before 1.9.
		$values['content_access_mode'] = $group->getContentAccessMode();

		$values['entity'] = $group;
	}

	// handle tool options
	if ($group instanceof ElggGroup) {
		$tools = elgg()->group_tools->group($group);
	} else {
		$tools = elgg()->group_tools->all();
	}

	foreach ($tools as $tool) {
		if ($group instanceof ElggGroup) {
			$enabled = $group->isToolEnabled($tool->name);
		} else {
			$enabled = $tool->isEnabledByDefault();
		}

		$prop_name = $tool->mapMetadataName();
		$values[$prop_name] = $enabled ? 'yes' : 'no';
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
 * Allow users to visit the group profile page even if group content access mode is set to group members only
 *
 * @elgg_plugin_hook gatekeeper group:group
 *
 * @param \Elgg\Hook $hook Hook
 * @return void|true
 */
function _groups_gatekeeper_allow_profile_page(\Elgg\Hook $hook) {

	$entity = $hook->getEntityParam();
	if (!has_access_to_entity($entity)) {
		return;
	}

	$route = $hook->getParam('route');

	if ($route === 'view:group' || $route === 'view:group:group') {
		return true;
	}
}

return function() {
	elgg_register_event_handler('init', 'system', 'groups_init');

	// Ensure this runs after other plugins
	elgg_register_event_handler('init', 'system', 'groups_fields_setup', 10000);
};
