<?php
/**
 * Elgg groups plugin
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
	elgg_register_plugin_hook_handler('register', 'menu:relationship', '_groups_relationship_member_menu');
	elgg_register_plugin_hook_handler('register', 'menu:relationship', '_groups_relationship_invited_menu');
	elgg_register_plugin_hook_handler('register', 'menu:relationship', '_groups_relationship_membership_request_menu');
	elgg_register_plugin_hook_handler('register', 'menu:title', '_groups_title_menu');

	elgg_register_plugin_hook_handler('gatekeeper', 'group:group', '_groups_gatekeeper_allow_profile_page');
	
	elgg_register_plugin_hook_handler('search:fields', 'group', \Elgg\Search\GroupSearchProfileFieldsHandler::class);
	elgg_register_plugin_hook_handler('default', 'access', '\Elgg\Groups\Access::getDefaultAccess');
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
 * @param \Elgg\Hook $hook 'register', 'menu:page'
 *
 * @return void|\Elgg\Menu\MenuItems
 *
 * @internal
 * @since 3.0
 */
function _groups_page_menu_group_profile(\Elgg\Hook $hook) {
	
	if (!elgg_in_context('group_profile') || !elgg_is_logged_in()) {
		return;
	}
	
	// Get the page owner entity
	$page_owner = elgg_get_page_owner_entity();
	if (!$page_owner instanceof ElggGroup || !$page_owner->canEdit()) {
		return;
	}
	
	/* @var $return \Elgg\Menu\MenuItems */
	$return = $hook->getValue();
	
	if ($page_owner->isPublicMembership()) {
		// show lint to invited users
		$return[] = \ElggMenuItem::factory([
			'name' => 'membership_invites',
			'text' => elgg_echo('groups:invitedmembers'),
			'href' => elgg_generate_url('collection:user:user:group_invites', [
				'guid' => $page_owner->guid,
			]),
		]);
	} else {
		// show link to mebership requests
		$count = elgg_count_entities([
			'type' => 'user',
			'relationship' => 'membership_request',
			'relationship_guid' => $page_owner->guid,
			'inverse_relationship' => true,
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
	}
	
	return $return;
}

/**
 * Register menu items for the page menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:page'
 *
 * @return void|ElggMenuItem[]
 *
 * @internal
 * @since 3.0
 */
function _groups_page_menu(\Elgg\Hook $hook) {
	
	if (elgg_get_context() !== 'groups') {
		return;
	}
	
	// Get the page owner entity
	$page_owner = elgg_get_page_owner_entity();
	if ($page_owner instanceof ElggGroup) {
		return;
	}
	
	$return = $hook->getValue();
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
 * @param \Elgg\Hook $hook 'entity:url', 'group'
 *
 * @return void|string
 */
function groups_set_url(\Elgg\Hook $hook) {
	
	$entity = $hook->getEntityParam();
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
 * @param \Elgg\Hook $hook 'register', 'menu:entity'
 *
 * @return void|ElggMenuItem[]
 */
function groups_entity_menu_setup(\Elgg\Hook $hook) {
	$entity = $hook->getEntityParam();
	if (!$entity instanceof \ElggGroup) {
		return;
	}
	
	$user = elgg_get_logged_in_user_entity();
	if (empty($user)) {
		return;
	}
	
	$return = $hook->getValue();
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
 * Add a remove user link to relationship menu if it's about a group membership relationship
 *
 * @param \Elgg\Hook $hook 'register', 'menu:relationship'
 *
 * @return void|ElggMenuItem[]
 * @internal
 * @since 3.2
 */
function _groups_relationship_member_menu(\Elgg\Hook $hook) {
	
	$relationship = $hook->getParam('relationship');
	if (!$relationship instanceof ElggRelationship || $relationship->relationship !== 'member') {
		return;
	}
	
	$user = get_entity($relationship->guid_one);
	$group = get_entity($relationship->guid_two);

	// Make sure we have a user and a group
	if (!$user instanceof \ElggUser || !$group instanceof ElggGroup) {
		return;
	}

	// Check if we are looking at the group owner
	if ($group->owner_guid === $user->guid) {
		return;
	}
	
	$return = $hook->getValue();
	$return[] = ElggMenuItem::factory([
		'name' => 'removeuser',
		'href' => elgg_generate_action_url('groups/remove', [
			'user_guid' => $user->guid,
			'group_guid' => $group->guid,
		]),
		'text' => elgg_echo('groups:removeuser'),
		'icon' => 'user-times',
		'confirm' => true,
	]);

	return $return;
}

/**
 * Groups created so create an access list for it
 *
 * @param \Elgg\Event $event 'create', 'group'
 *
 * @return bool
 */
function groups_create_event_listener(\Elgg\Event $event) {
	// ensure that user has sufficient permissions to update group metadata
	// prior to joining the group
	$object = $event->getObject();
	return elgg_call(ELGG_IGNORE_ACCESS, function() use ($object) {
		$ac_name = elgg_echo('groups:group') . ": " . $object->getDisplayName();
		
		// delete the group if acl creation fails
		return (bool) create_access_collection($ac_name, $object->guid, 'group_acl');
	});
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
 * @param \Elgg\Event $event "update:after", "group"
 *
 * @return void
 */
function groups_update_event_listener(\Elgg\Event $event) {

	/* @var $group \ElggGroup */
	$group = $event->getObject();
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
 * @param \Elgg\Event $event 'join', 'group'
 *
 * @return void
 */
function groups_user_join_event_listener(\Elgg\Event $event) {
	$params = $event->getObject();
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
 * @param \Elgg\Event $event 'leave', 'group'
 *
 * @return void
 */
function groups_user_leave_event_listener(\Elgg\Event $event) {
	$params = $event->getObject();
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
 * @param \Elgg\Hook $hook 'default', 'access'
 *
 * @return int|void
 */
function groups_access_default_override(\Elgg\Hook $hook) {
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

	$groups = elgg_call(ELGG_IGNORE_ACCESS, function() use ($user_guid, $options) {
		$defaults = [
			'relationship' => 'invited',
			'relationship_guid' => (int) $user_guid,
			'inverse_relationship' => true,
			'limit' => 0,
		];
	
		$options = array_merge($defaults, $options);
		return elgg_get_entities($options);
	});
	
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
 * @param \Elgg\Hook $hook 'get_views', 'ecml'
 *
 * @return array
 */
function groupprofile_ecml_views_hook(\Elgg\Hook $hook) {
	$return_value = $hook->getValue();
	$return_value['groups/groupprofile'] = elgg_echo('groups:ecml:groupprofile');
	return $return_value;
}

/**
 * Setup group members tabs
 *
 * @param \Elgg\Hook $hook "register", "menu:groups_members"
 *
 * @return void|ElggMenuItem[]
 */
function groups_members_menu_setup(\Elgg\Hook $hook) {

	$entity = $hook->getEntityParam();
	if (!$entity instanceof ElggGroup) {
		return;
	}

	$menu = $hook->getValue();
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
	
	if ($entity->canEdit()) {
		$menu[] = ElggMenuItem::factory([
			'name' => 'membership_requests',
			'text' => elgg_echo('groups:membershiprequests'),
			'href' => elgg_generate_entity_url($entity, 'requests'),
			'priority' => 300
		]);
		
		$menu[] = ElggMenuItem::factory([
			'name' => 'membership_invites',
			'text' => elgg_echo('groups:invitedmembers'),
			'href' => elgg_generate_url('collection:user:user:group_invites', [
				'guid' => $entity->guid,
			]),
			'priority' => 400,
		]);
	}

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
		
		if (elgg_is_active_plugin('friends')) {
			$result[] = \ElggMenuItem::factory([
				'name' => 'groups:invite',
				'icon' => 'user-plus',
				'href' => elgg_generate_entity_url($group, 'invite'),
				'text' => elgg_echo('groups:invite'),
				'link_class' => 'elgg-button elgg-button-action',
			]);
		}
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
		if ($leave_group instanceof ElggMenuItem) {
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
 * @param \Elgg\Hook $hook "page_owner", "system"
 *
 * @return int|void
 */
function groups_default_page_owner_handler(\Elgg\Hook $hook) {

	$return = $hook->getValue();
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
 * @param \Elgg\Hook $hook "register", "menu:filter:groups/all"
 *
 * @return ElggMenuItem[]
 */
function groups_setup_filter_tabs(\Elgg\Hook $hook) {

	$return = $hook->getValue();
	$return[] = ElggMenuItem::factory([
		'name' => 'newest',
		'text' => elgg_echo('sort:newest'),
		'href' => elgg_generate_url('collection:group:group:all', [
			'filter' => 'newest',
		]),
		'priority' => 200,
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'alpha',
		'text' => elgg_echo('sort:alpha'),
		'href' => elgg_generate_url('collection:group:group:all', [
			'filter' => 'alpha',
		]),
		'priority' => 250,
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'popular',
		'text' => elgg_echo('sort:popular'),
		'href' => elgg_generate_url('collection:group:group:all', [
			'filter' => 'popular',
		]),
		'priority' => 300,
	]);

	$return[] = ElggMenuItem::factory([
		'name' => 'featured',
		'text' => elgg_echo('groups:featured'),
		'href' => elgg_generate_url('collection:group:group:all', [
			'filter' => 'featured',
		]),
		'priority' => 400,
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
		'content_access_mode' => ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
		'content_default_access' => '',
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

/**
 * Add menu items to the invited relationship menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:relationship'
 *
 * @return void|\Elgg\Menu\MenuItems
 * @internal
 * @since 3.2
 */
function _groups_relationship_invited_menu(\Elgg\Hook $hook) {
	
	$relationship = $hook->getParam('relationship');
	if (!$relationship instanceof ElggRelationship || $relationship->relationship !== 'invited') {
		return;
	}
	
	$group = get_entity($relationship->guid_one);
	$user = get_entity($relationship->guid_two);
	if (!$group instanceof ElggGroup || !$user instanceof ElggUser) {
		return;
	}
	
	/* @var $result \Elgg\Menu\MenuItems */
	$result = $hook->getValue();
	
	$page_owner = elgg_get_page_owner_entity();
	if ($page_owner->guid === $group->guid && $group->canEdit()) {
		$result[] = ElggMenuItem::factory([
			'name' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => elgg_generate_action_url('groups/killinvitation', [
				'user_guid' => $user->guid,
				'group_guid' => $group->guid,
			]),
			'confirm' => elgg_echo('groups:invite:remove:check'),
			'link_class' => 'elgg-button elgg-button-delete',
			'section' => 'actions',
		]);
	} elseif ($page_owner->guid === $user->guid && $user->canEdit()) {
		$result[] = \ElggMenuItem::factory([
			'name' => 'accept',
			'text' => elgg_echo('accept'),
			'href' => elgg_generate_action_url('groups/join', [
				'user_guid' => $user->guid,
				'group_guid' => $group->guid,
			]),
			'link_class' => 'elgg-button elgg-button-submit',
			'is_trusted' => true,
			'section' => 'actions',
		]);
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'delete',
			'text' => elgg_echo('delete'),
			'href' => elgg_generate_action_url('groups/killinvitation', [
				'user_guid' => $user->guid,
				'group_guid' => $group->guid,
			]),
			'confirm' => elgg_echo('groups:invite:remove:check'),
			'link_class' => 'elgg-button elgg-button-delete',
			'section' => 'actions',
		]);
	}
	
	return $result;
}

/**
 * Add menu items to the group membership request relationship menu
 *
 * @param \Elgg\Hook $hook 'register', 'menu:relationship'
 *
 * @return void|\Elgg\Menu\MenuItems
 * @internal
 * @since 3.2
 */
function _groups_relationship_membership_request_menu(\Elgg\Hook $hook) {
	
	$relationship = $hook->getParam('relationship');
	if (!$relationship instanceof ElggRelationship || $relationship->relationship !== 'membership_request') {
		return;
	}
	
	$user = get_entity($relationship->guid_one);
	$group = get_entity($relationship->guid_two);
	if (!$group instanceof ElggGroup || !$user instanceof ElggUser) {
		return;
	}
	
	/* @var $result \Elgg\Menu\MenuItems */
	$result = $hook->getValue();
	
	$page_owner = elgg_get_page_owner_entity();
	if ($page_owner->guid === $group->guid && $group->canEdit()) {
		$result[] = ElggMenuItem::factory([
			'name' => 'accept',
			'text' => elgg_echo('accept'),
			'href' => elgg_generate_action_url('groups/addtogroup', [
				'user_guid' => $user->guid,
				'group_guid' => $group->guid,
			]),
			'link_class' => 'elgg-button elgg-button-submit',
			'section' => 'actions',
		]);
		
		$result[] = ElggMenuItem::factory([
			'name' => 'reject',
			'text' => elgg_echo('delete'),
			'href' => elgg_generate_action_url('groups/killrequest', [
				'user_guid' => $user->guid,
				'group_guid' => $group->guid,
			]),
			'confirm' => elgg_echo('groups:joinrequest:remove:check'),
			'link_class' => 'elgg-button elgg-button-delete',
			'section' => 'actions',
		]);
	}
	
	return $result;
}

return function() {
	elgg_register_event_handler('init', 'system', 'groups_init');

	// Ensure this runs after other plugins
	elgg_register_event_handler('init', 'system', 'groups_fields_setup', 10000);
};
