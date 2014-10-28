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

	elgg_register_library('elgg:groups', elgg_get_plugins_path() . 'groups/lib/groups.php');

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

	// Register an icon handler for groups
	elgg_register_page_handler('groupicon', 'groups_icon_handler');

	// Register some actions
	$action_base = elgg_get_plugins_path() . 'groups/actions/groups';
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

	// delete and edit annotations for topic replies
	elgg_register_plugin_hook_handler('register', 'menu:annotation', 'groups_annotation_menu_setup');

	//extend some views
	elgg_extend_view('css/elgg', 'groups/css');
	elgg_extend_view('js/elgg', 'groups/js');

	// Access permissions
	elgg_register_plugin_hook_handler('access:collections:write', 'all', 'groups_write_acl_plugin_hook');
	elgg_register_plugin_hook_handler('default', 'access', 'groups_access_default_override');

	// Register profile menu hook
	elgg_register_plugin_hook_handler('profile_menu', 'profile', 'activity_profile_menu');

	// allow ecml in discussion and profiles
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'groups_ecml_views_hook');
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'groupprofile_ecml_views_hook');

	// Register a handler for create groups
	elgg_register_event_handler('create', 'group', 'groups_create_event_listener');

	// Register a handler for delete groups
	elgg_register_event_handler('delete', 'group', 'groups_delete_event_listener');

	elgg_register_event_handler('join', 'group', 'groups_user_join_event_listener');
	elgg_register_event_handler('leave', 'group', 'groups_user_leave_event_listener');
	elgg_register_event_handler('pagesetup', 'system', 'groups_setup_sidebar_menus');

	elgg_register_plugin_hook_handler('access:collections:add_user', 'collection', 'groups_access_collection_override');

	elgg_register_event_handler('upgrade', 'system', 'groups_run_upgrades');

	// Add tests
	elgg_register_plugin_hook_handler('unit_test', 'system', 'groups_test');
}

/**
 * Set notifications about discussions and their replies to have just thread title as a subject.
 * This is to make more picky email clients happy while groupping messages in threads, as well as to
 * save space in subject line.
 *
 * @param $hook string
 * @param $type string
 * @param $returnvalue array
 * @param $params array
 * @return array
 */
function discussion_notification_email_subject($hook, $type, $returnvalue, $params){

	/** @var Elgg_Notifications_Notification */
	$notification = elgg_extract('notification', $returnvalue['params']);

	if ($notification instanceof Elgg_Notifications_Notification) {

		$object = elgg_extract('object', $notification->params);
		/** @var Elgg_Notifications_Event $event */
		$event = elgg_extract('event', $notification->params);

		if ($object instanceof ElggEntity) {
			if ($object->getSubtype() === 'groupforumtopic') {
				$returnvalue['subject'] = $object->getDisplayName();
			}
			$container = $object->getContainerEntity();

			if (($container instanceof ElggEntity) && ($object instanceof ElggComment)) {
				$returnvalue['subject'] = 'Re: ' . $container->getDisplayName();
			}
		}
	}
	return $returnvalue;
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
			$invitations = groups_get_invited_groups($user->getGUID());
			if (is_array($invitations) && !empty($invitations)) {
				$invitation_count = count($invitations);
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

	elgg_load_library('elgg:groups');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");

	switch ($page[0]) {
		case 'all':
			groups_handle_all_page();
			break;
		case 'search':
			groups_search_page();
			break;
		case 'owner':
			groups_handle_owned_page();
			break;
		case 'member':
			set_input('username', $page[1]);
			groups_handle_mine_page();
			break;
		case 'invitations':
			set_input('username', $page[1]);
			groups_handle_invitations_page();
			break;
		case 'add':
			groups_handle_edit_page('add');
			break;
		case 'edit':
			groups_handle_edit_page('edit', $page[1]);
			break;
		case 'profile':
			groups_handle_profile_page($page[1]);
			break;
		case 'activity':
			groups_handle_activity_page($page[1]);
			break;
		case 'members':
			groups_handle_members_page($page[1]);
			break;
		case 'invite':
			groups_handle_invite_page($page[1]);
			break;
		case 'requests':
			groups_handle_requests_page($page[1]);
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
 */
function groups_icon_handler($page) {

	// The username should be the file we're getting
	if (isset($page[0])) {
		set_input('group_guid', $page[0]);
	}
	if (isset($page[1])) {
		set_input('size', $page[1]);
	}
	// Include the standard profile index
	$plugin_dir = elgg_get_plugins_path();
	include("$plugin_dir/groups/icon.php");
	return true;
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
 * Override the default entity icon for groups
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string Relative URL
 */
function groups_set_icon_url($hook, $type, $url, $params) {
	/* @var ElggGroup $group */
	$group = $params['entity'];
	$size = $params['size'];

	$icontime = $group->icontime;
	// handle missing metadata (pre 1.7 installations)
	if (null === $icontime) {
		$file = new ElggFile();
		$file->owner_guid = $group->owner_guid;
		$file->setFilename("groups/" . $group->guid . "large.jpg");
		$icontime = $file->exists() ? time() : 0;
		create_metadata($group->guid, 'icontime', $icontime, 'integer', $group->owner_guid, ACCESS_PUBLIC);
	}
	if ($icontime) {
		// return thumbnail
		return "groupicon/$group->guid/$size/$icontime.jpg";
	}

	return "mod/groups/graphics/default{$size}.gif";
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
		if (in_array($item->getName(), array('access', 'likes', 'edit', 'delete'))) {
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
		));

		$return[] = ElggMenuItem::factory(array(
			'name' => 'unfeature',
			'text' => elgg_echo("groups:makeunfeatured"),
			'href' => elgg_add_action_tokens_to_url("action/groups/featured?group_guid={$entity->guid}&action_type=unfeature"),
			'priority' => 300,
			'item_class' => $isFeatured ? '' : 'hidden',
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
			$remove = elgg_view('output/confirmlink', array(
				'href' => "action/groups/remove?user_guid={$entity->guid}&group_guid={$group->guid}",
				'text' => elgg_echo('groups:removeuser'),
			));

			$options = array(
				'name' => 'removeuser',
				'text' => $remove,
				'priority' => 999,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	}

	return $return;
}

/**
 * Add edit and delete links for forum replies
 */
function groups_annotation_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$annotation = $params['annotation'];

	if ($annotation->name != 'group_topic_post') {
		return $return;
	}

	if ($annotation->canEdit()) {
		$url = elgg_http_add_url_query_elements('action/discussion/reply/delete', array(
			'annotation_id' => $annotation->id,
		));

		$options = array(
			'name' => 'delete',
			'href' => $url,
			'text' => elgg_view_icon('delete'),
			'confirm' => elgg_echo('deleteconfirm'),
			'encode_text' => false
		);
		$return[] = ElggMenuItem::factory($options);

		$url = elgg_http_add_url_query_elements('discussion', array(
			'annotation_id' => $annotation->id,
		));

		$options = array(
			'name' => 'edit',
			'href' => "#edit-annotation-$annotation->id",
			'text' => elgg_echo('edit'),
			'encode_text' => false,
			'rel' => 'toggle',
		);
		$return[] = ElggMenuItem::factory($options);
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
 * Return the write access for the current group if the user has write access to it.
 */
function groups_write_acl_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$page_owner = elgg_get_page_owner_entity();
	$user_guid = $params['user_id'];
	$user = get_user($user_guid);
	if (!$user) {
		return $returnvalue;
	}

	// only insert group access for current group
	if ($page_owner instanceof ElggGroup) {
		if ($page_owner->canWriteToContainer($user_guid)) {
			if ($page_owner->getContentAccessMode() == ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
				// Due to group policy allow only the owner or all group members
				$returnvalue = array(
					ACCESS_PRIVATE => elgg_echo('PRIVATE'),
					$page_owner->group_acl => elgg_echo('groups:acl', array($page_owner->name)),
				);
			} else {
				// Leave out other groups, friends and friend collections
				$returnvalue = array(
					ACCESS_PRIVATE => elgg_echo('PRIVATE'),
					ACCESS_LOGGED_IN => elgg_echo('LOGGED_IN'),
					ACCESS_PUBLIC => elgg_echo('PUBLIC'),
					$page_owner->group_acl => elgg_echo('groups:acl', array($page_owner->name)),
				);
			}
		}
	}

	return $returnvalue;
}

/**
 * Groups deleted, so remove access lists.
 */
function groups_delete_event_listener($event, $object_type, $object) {
	delete_access_collection($object->group_acl);

	return true;
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
 * @param int  $user_guid    The user's guid
 * @param bool $return_guids Return guids rather than ElggGroup objects
 *
 * @return array ElggGroups or guids depending on $return_guids
 */
function groups_get_invited_groups($user_guid, $return_guids = FALSE) {
	$ia = elgg_set_ignore_access(TRUE);
	$groups = elgg_get_entities_from_relationship(array(
		'relationship' => 'invited',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => TRUE,
		'limit' => 0,
	));
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
 * Parse ECML on group discussion views
 */
function groups_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['forum/viewposts'] = elgg_echo('groups:ecml:discussion');

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
 * Discussion
 *
 */

elgg_register_event_handler('init', 'system', 'discussion_init');

/**
 * Initialize the discussion component
 */
function discussion_init() {

	elgg_register_library('elgg:discussion', elgg_get_plugins_path() . 'groups/lib/discussion.php');

	elgg_register_page_handler('discussion', 'discussion_page_handler');

	elgg_register_plugin_hook_handler('entity:url', 'object', 'discussion_set_topic_url');

	// commenting not allowed on discussion topics (use a different annotation)
	elgg_register_plugin_hook_handler('permissions_check:comment', 'object', 'discussion_comment_override');
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'discussion_can_edit_reply');

	// discussion reply menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'discussion_reply_menu_setup');

	// allow non-owners to add replies to group discussion
	elgg_register_plugin_hook_handler('container_permissions_check', 'object', 'discussion_reply_container_permissions_override');

	elgg_register_event_handler('update', 'object', 'discussion_update_reply_access_ids');

	$action_base = elgg_get_plugins_path() . 'groups/actions/discussion';
	elgg_register_action('discussion/save', "$action_base/save.php");
	elgg_register_action('discussion/delete', "$action_base/delete.php");
	elgg_register_action('discussion/reply/save', "$action_base/reply/save.php");
	elgg_register_action('discussion/reply/delete', "$action_base/reply/delete.php");

	// add link to owner block
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'discussion_owner_block_menu');

	// Register for search.
	elgg_register_entity_type('object', 'groupforumtopic');

	// because replies are not comments, need of our menu item
	elgg_register_plugin_hook_handler('register', 'menu:river', 'discussion_add_to_river_menu');

	// add the forum tool option
	add_group_tool_option('forum', elgg_echo('groups:enableforum'), true);
	elgg_extend_view('groups/tool_latest', 'discussion/group_module');

	$discussion_js_path = elgg_get_site_url() . 'mod/groups/views/default/js/discussion/';
	elgg_register_js('elgg.discussion', $discussion_js_path . 'discussion.js');

	elgg_register_ajax_view('ajax/discussion/reply/edit');

	// notifications
	elgg_register_plugin_hook_handler('get', 'subscriptions', 'discussion_get_subscriptions');
	elgg_register_notification_event('object', 'groupforumtopic');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:groupforumtopic', 'discussion_prepare_notification');
	elgg_register_notification_event('object', 'discussion_reply');
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:discussion_reply', 'discussion_prepare_reply_notification');
	elgg_register_plugin_hook_handler('email', 'system', 'discussion_notification_email_subject');
}

/**
 * Discussion page handler
 *
 * URLs take the form of
 *  All topics in site:    discussion/all
 *  List topics in forum:  discussion/owner/<guid>
 *  View discussion topic: discussion/view/<guid>
 *  Add discussion topic:  discussion/add/<guid>
 *  Edit discussion topic: discussion/edit/<guid>
 *
 * @param array $page Array of url segments for routing
 * @return bool
 */
function discussion_page_handler($page) {

	elgg_load_library('elgg:discussion');

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	elgg_push_breadcrumb(elgg_echo('discussion'), 'discussion/all');

	switch ($page[0]) {
		case 'all':
			discussion_handle_all_page();
			break;
		case 'owner':
			discussion_handle_list_page($page[1]);
			break;
		case 'add':
			discussion_handle_edit_page('add', $page[1]);
			break;
		case 'reply':
			switch ($page[1]) {
				case 'edit':
					discussion_handle_reply_edit_page('edit', $page[2]);
					break;
				default:
					return false;
			}
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
 * Override the url for discussion topics and replies
 *
 * Discussion replies do not have their own page so their url is
 * the same as the topic url.
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string
 */
function discussion_set_topic_url($hook, $type, $url, $params) {
	$entity = $params['entity'];

	if (elgg_instanceof($entity, 'object', 'discussion_reply', 'ElggDiscussionReply')) {
		$topic = $entity->getContainerEntity();
		$title = elgg_get_friendly_title($topic->title);
		return "discussion/view/{$topic->guid}/{$title}";
	}

	if (elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		$title = elgg_get_friendly_title($entity->title);
		return "discussion/view/{$entity->guid}/{$title}";
	}

	return $url;
}

/**
 * We don't want people commenting on topics in the river
 *
 * @param string $hook
 * @param string $type
 * @param string $return
 * @param array  $params
 * @return bool
 */
function discussion_comment_override($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'object', 'groupforumtopic')) {
		return false;
	}
}

/**
 * Add owner block link
 *
 * @param string          $hook    'register'
 * @param string          $type    'menu:owner_block'
 * @param ElggMenuItem[]  $return
 * @param array           $params
 * @return ElggMenuItem[]  $return
 */
function discussion_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'group')) {
		if ($params['entity']->forum_enable != "no") {
			$url = "discussion/owner/{$params['entity']->guid}";
			$item = new ElggMenuItem('discussion', elgg_echo('discussion:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Set up menu items for river items
 *
 * Add reply button for discussion topic. Remove the possibility
 * to comment on a discussion reply.
 *
 * @param string         $hook   'register'
 * @param string         $type   'menu:river'
 * @param ElggMenuItem[] $return
 * @param array          $params
 * @return ElggMenuItem[] $return
 */
function discussion_add_to_river_menu($hook, $type, $return, $params) {
	if (!elgg_is_logged_in() || elgg_in_context('widgets')) {
		return $return;
	}

	$item = $params['item'];
	$object = $item->getObjectEntity();

	if (elgg_instanceof($object, 'object', 'groupforumtopic')) {
		$group = $object->getContainerEntity();

		if ($group && ($group->canWriteToContainer() || elgg_is_admin_logged_in())) {
				$options = array(
				'name' => 'reply',
				'href' => "#discussion-reply-{$object->guid}",
				'text' => elgg_view_icon('speech-bubble'),
				'title' => elgg_echo('reply:this'),
				'rel' => 'toggle',
				'priority' => 50,
			);
			$return[] = ElggMenuItem::factory($options);
		}
	} else {
		if (elgg_instanceof($object, 'object', 'discussion_reply', 'ElggDiscussionReply')) {
			// Group discussion replies cannot be commented
			foreach ($return as $key => $item) {
				if ($item->getName() === 'comment') {
					unset($return[$key]);
				}
			}
		}
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
		$group->name,
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
	$topic = $reply->getContainerEntity();
	$poster = $reply->getOwnerEntity();
	$group = $topic->getContainerEntity();
	$language = elgg_extract('language', $params);

	$notification->subject = elgg_echo('discussion:reply:notify:subject', array($topic->title), $language);
	$notification->body = elgg_echo('discussion:reply:notify:body', array(
		$poster->name,
		$topic->title,
		$group->name,
		$reply->description,
		$topic->getURL(),
	), $language);
	$notification->summary = elgg_echo('discussion:reply:notify:summary', array($topic->title), $language);

	return $notification;
}

/**
 * Get subscriptions for group notifications
 *
 * @param string $hook          'get'
 * @param string $type          'subscriptions'
 * @param array  $subscriptions Array containing subscriptions in the form
 *                       <user guid> => array('email', 'site', etc.)
 * @param array  $params        Hook parameters
 * @return array
 */
function discussion_get_subscriptions($hook, $type, $subscriptions, $params) {
	$reply = $params['event']->getObject();

	if (!elgg_instanceof($reply, 'object', 'discussion_reply', 'ElggDiscussionReply')) {
		return $subscriptions;
	}

	$group_guid = $reply->getContainerEntity()->container_guid;
	$group_subscribers = elgg_get_subscriptions_for_container($group_guid);

	return ($subscriptions + $group_subscribers);
}

/**
 * A simple function to see who can edit a group discussion post
 *
 * @param ElggComment $entity      the  comment $entity
 * @param ELggUser    $group_owner user who owns the group $group_owner
 * @return boolean
 */
function groups_can_edit_discussion($entity, $group_owner) {

	//logged in user
	$user = elgg_get_logged_in_user_guid();

	if (($entity->owner_guid == $user) || $group_owner == $user || elgg_is_admin_logged_in()) {
		return true;
	} else {
		return false;
	}
}

/**
 * Process upgrades for the groups plugin
 */
function groups_run_upgrades() {
	$path = elgg_get_plugins_path() . 'groups/upgrades/';
	$files = elgg_get_upgrade_files($path);
	foreach ($files as $file) {
		include "$path{$file}";
	}
}

/**
 * Allow group owner and discussion owner to edit discussion replies.
 *
 * @param string  $hook   'permissions_check'
 * @param string  $type   'object'
 * @param boolean $return
 * @param array   $params Array('entity' => ElggEntity, 'user' => ElggUser)
 * @return boolean True if user is discussion or group owner
 */
function discussion_can_edit_reply($hook, $type, $return, $params) {
	/** @var $reply ElggEntity */
	$reply = $params['entity'];
	$user = $params['user'];

	if (!elgg_instanceof($reply, 'object', 'discussion_reply', 'ElggDiscussionReply')) {
		return $return;
	}

	if ($reply->owner_guid == $user->guid) {
	    return true;
	}

	$discussion = $reply->getContainerEntity();
	if ($discussion->owner_guid == $user->guid) {
		return true;
	}

	$group = $discussion->getContainerEntity();
	if (elgg_instanceof($group, 'group') && $group->owner_guid == $user->guid) {
		return true;
	}

	return false;
}

/**
 * Allow group members to post to a group discussion
 *
 * @param string $hook   'container_permissions_check'
 * @param string $type   'object'
 * @param array  $return
 * @param array  $params Array with container, user and subtype
 * @return boolean $return
 */
function discussion_reply_container_permissions_override($hook, $type, $return, $params) {
	/** @var $container ElggEntity */
	$container = $params['container'];
	$user = $params['user'];

	if (elgg_instanceof($container, 'object', 'groupforumtopic')) {
		$group = $container->getContainerEntity();

		if ($group->canWriteToContainer($user->guid) && $params['subtype'] === 'discussion_reply') {
			return true;
		}
	}

	return $return;
}

/**
 * Update access_id of discussion replies when topic access_id is updated.
 *
 * @param string     $event  'update'
 * @param string     $type   'object'
 * @param ElggObject $object ElggObject
 */
function discussion_update_reply_access_ids($event, $type, $object) {
	if (elgg_instanceof($object, 'object', 'groupforumtopic')) {
		$options = array(
			'type' => 'object',
			'subtype' => 'discussion_reply',
			'container_guid' => $object->getGUID(),
			'limit' => 0,
		);
		$batch = new ElggBatch('elgg_get_entities', $options);
		foreach ($batch as $reply) {
			if ($reply->access_id == $object->access_id) {
				// Assume access_id of the replies is up-to-date
				break;
			}

			// Update reply access_id
			$reply->access_id = $object->access_id;
			$reply->save();
		}
	}
}

/**
 * Set up discussion reply entity menu
 *
 * @param string          $hook   'register'
 * @param string          $type   'menu:entity'
 * @param ElggMenuItem[]  $return
 * @param array           $params
 * @return ElggMenuItem[] $return
 */
function discussion_reply_menu_setup($hook, $type, $return, $params) {
	/** @var $reply ElggEntity */
	$reply = elgg_extract('entity', $params);

	if (empty($reply) || !elgg_instanceof($reply, 'object', 'discussion_reply')) {
		return $return;
	}

	if (!elgg_is_logged_in()) {
		return $return;
	}

	if (elgg_in_context('widgets')) {
		return $return;
	}

	// Reply has the same access as the topic so no need to view it
	$remove = array('access');

	$user = elgg_get_logged_in_user_entity();

	// Allow discussion topic owner, group owner and admins to edit and delete
	if ($reply->canEdit() && !elgg_in_context('activity')) {
		$return[] = ElggMenuItem::factory(array(
			'name' => 'edit',
			'text' => elgg_echo('edit'),
			'href' => "discussion/reply/edit/{$reply->guid}",
			'priority' => 150,
		));

		$return[] = ElggMenuItem::factory(array(
			'name' => 'delete',
			'text' => elgg_view_icon('delete'),
			'href' => "action/discussion/reply/delete?guid={$reply->guid}",
			'priority' => 150,
			'is_action' => true,
			'confirm' => elgg_echo('deleteconfirm'),
		));
	} else {
		// Edit and delete links can be removed from all other users
		$remove[] = 'edit';
		$remove[] = 'delete';
	}

	// Remove unneeded menu items
	foreach ($return as $key => $item) {
		if (in_array($item->getName(), $remove)) {
			unset($return[$key]);
		}
	}

	return $return;
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
