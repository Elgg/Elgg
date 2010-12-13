<?php
	/**
	 * Elgg groups plugin
	 *
	 * @package ElggGroups
	 */

	/**
	 * Initialise the groups plugin.
	 * Register actions, set up menus
	 */
	function groups_init() {

		global $CONFIG;

		// Set up the menu
		add_menu(elgg_echo('groups'), "pg/groups/world/");

		// Register a page handler, so we can have nice URLs
		register_page_handler('groups','groups_page_handler');

		// Register a URL handler for groups and forum topics
		register_entity_url_handler('groups_url','group','all');
		register_entity_url_handler('groups_groupforumtopic_url','object','groupforumtopic');

		// Register an icon handler for groups
		register_page_handler('groupicon','groups_icon_handler');

		// Register some actions
		elgg_register_action("groups/edit", $CONFIG->pluginspath . "groups/actions/edit.php");
		elgg_register_action("groups/delete", $CONFIG->pluginspath . "groups/actions/delete.php");
		elgg_register_action("groups/join", $CONFIG->pluginspath . "groups/actions/join.php");
		elgg_register_action("groups/leave", $CONFIG->pluginspath . "groups/actions/leave.php");
		elgg_register_action("groups/joinrequest", $CONFIG->pluginspath . "groups/actions/joinrequest.php");
		elgg_register_action("groups/killrequest", $CONFIG->pluginspath . "groups/actions/groupskillrequest.php");
		elgg_register_action("groups/killinvitation", $CONFIG->pluginspath . "groups/actions/groupskillinvitation.php");
		elgg_register_action("groups/addtogroup", $CONFIG->pluginspath . "groups/actions/addtogroup.php");
		elgg_register_action("groups/invite", $CONFIG->pluginspath . "groups/actions/invite.php");

		// Add a page owner handler
		elgg_register_plugin_hook_handler('page_owner', 'system', 'groups_page_owner_handler');

		// Add some widgets
		elgg_register_widget_type('a_users_groups',elgg_echo('groups:widget:membership'), elgg_echo('groups:widgets:description'));


		//extend some views
		elgg_extend_view('profile/icon','groups/icon');
		elgg_extend_view('css/screen','groups/css');

		// Access permissions
		elgg_register_plugin_hook_handler('access:collections:write', 'all', 'groups_write_acl_plugin_hook');
		//elgg_register_plugin_hook_handler('access:collections:read', 'all', 'groups_read_acl_plugin_hook');

		// Notification hooks
		if (is_callable('register_notification_object'))
			register_notification_object('object', 'groupforumtopic', elgg_echo('groupforumtopic:new'));
		elgg_register_plugin_hook_handler('object:notifications','object','group_object_notifications_intercept');

		// Listen to notification events and supply a more useful message
		elgg_register_plugin_hook_handler('notify:entity:message', 'object', 'groupforumtopic_notify_message');

		// add the forum tool option
		add_group_tool_option('forum',elgg_echo('groups:enableforum'),true);

		// Now override icons
		elgg_register_plugin_hook_handler('entity:icon:url', 'group', 'groups_groupicon_hook');

		// Register profile menu hook
		elgg_register_plugin_hook_handler('profile_menu', 'profile', 'forum_profile_menu');
		elgg_register_plugin_hook_handler('profile_menu', 'profile', 'activity_profile_menu');

		// allow ecml in discussion and profiles
		elgg_register_plugin_hook_handler('get_views', 'ecml', 'groups_ecml_views_hook');
		elgg_register_plugin_hook_handler('get_views', 'ecml', 'groupprofile_ecml_views_hook');

	}

	/**
	 * Event handler for group forum posts
	 *
	 */
	function group_object_notifications($event, $object_type, $object) {

		static $flag;
		if (!isset($flag)) $flag = 0;

		if (is_callable('object_notifications'))
		if ($object instanceof ElggObject) {
			if ($object->getSubtype() == 'groupforumtopic') {
				//if ($object->countAnnotations('group_topic_post') > 0) {
				if ($flag == 0) {
					$flag = 1;
					object_notifications($event, $object_type, $object);
				}
				//}
			}
		}

	}

	/**
	 * Intercepts the notification on group topic creation and prevents a notification from going out
	 * (because one will be sent on the annotation)
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 * @return unknown
	 */
		function group_object_notifications_intercept($hook, $entity_type, $returnvalue, $params) {
			if (isset($params)) {
				if ($params['event'] == 'create' && $params['object'] instanceof ElggObject) {
					if ($params['object']->getSubtype() == 'groupforumtopic') {
						return true;
					}
				}
			}
			return null;
		}

		/**
		 * Returns a more meaningful message
		 *
		 * @param unknown_type $hook
		 * @param unknown_type $entity_type
		 * @param unknown_type $returnvalue
		 * @param unknown_type $params
		 */
		function groupforumtopic_notify_message($hook, $entity_type, $returnvalue, $params)
		{
			$entity = $params['entity'];
			$to_entity = $params['to_entity'];
			$method = $params['method'];
			if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'groupforumtopic'))
			{

				$descr = $entity->description;
				$title = $entity->title;
				global $CONFIG;
				$url = $entity->getURL();

				$msg = get_input('topicmessage');
				if (empty($msg)) $msg = get_input('topic_post');
				if (!empty($msg)) $msg = $msg . "\n\n"; else $msg = '';

				$owner = get_entity($entity->container_guid);
				if ($method == 'sms') {
					return elgg_echo("groupforumtopic:new") . ': ' . $url . " ({$owner->name}: {$title})";
				} else {
					return get_loggedin_user()->name . ' ' . elgg_echo("groups:viagroups") . ': ' . $title . "\n\n" . $msg . "\n\n" . $entity->getURL();
				}

			}
			return null;
		}

	/**
	 * This function loads a set of default fields into the profile, then triggers a hook letting other plugins to edit
	 * add and delete fields.
	 *
	 * Note: This is a secondary system:init call and is run at a super low priority to guarantee that it is called after all
	 * other plugins have initialised.
	 */
	function groups_fields_setup()
	{
		global $CONFIG;

		$profile_defaults = array(

			'name' => 'text',
			'description' => 'longtext',
			'briefdescription' => 'text',
			'interests' => 'tags',
			//'website' => 'url',
		);

		$CONFIG->group = elgg_trigger_plugin_hook('profile:fields', 'group', NULL, $profile_defaults);

		// register any tag metadata names
		foreach ($CONFIG->group as $name => $type) {
			if ($type == 'tags') {
				elgg_register_tag_metadata_name($name);

				// register a tag name translation
				add_translation(get_current_language(), array("tag_names:$name" => elgg_echo("groups:$name")));
			}
		}
	}

	/**
	 * Sets up submenus for the groups system.  Triggered on pagesetup.
	 *
	 */
	function groups_submenus() {
		global $CONFIG;

		// Get the page owner entity
		$page_owner = elgg_get_page_owner();

		if (elgg_get_context() == 'groups') {
			if ($page_owner instanceof ElggGroup) {
				if (isloggedin() && $page_owner->canEdit() && !$page_owner->isPublicMembership()) {
					$url = elgg_get_site_url()."mod/groups/membershipreq.php?group_guid={$page_owner->getGUID()}";
					add_submenu_item(elgg_echo('groups:membershiprequests'), $url, 'groupsactions1');
				}
			} else {
				add_submenu_item(elgg_echo('groups:all'), "pg/groups/world/", 'groupslinks1');

				if ($user = get_loggedin_user()) {
					add_submenu_item(elgg_echo('groups:owned'), "pg/groups/owned/$user->username", 'groupslinks1');
					add_submenu_item(elgg_echo('groups:yours'), "pg/groups/member/$user->username", 'groupslinks1');
					add_submenu_item(elgg_echo('groups:invitations'), "pg/groups/invitations/$user->username", 'groupslinks1');
				}
			}
		}
	}

	/**
	 * Set a page owner handler.
	 *
	 */
	function groups_page_owner_handler()
	{
		$group_guid = get_input('group_guid');
		if ($group_guid)
		{
			$group = get_entity($group_guid);
			if ($group instanceof ElggGroup)
				return $group->owner_guid;
		}

		return false;
	}

	/**
	 * Group page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function groups_page_handler($page)
	{
		global $CONFIG;


		if (isset($page[0]))
		{
			// See what context we're using
			switch($page[0])
			{
				case 'invitations':
					include($CONFIG->pluginspath . "groups/invitations.php");
					$user_guid = get_loggedin_userid();

					$invitations = elgg_get_entities_from_relationship(array(
						'relationship' => 'membership_request',
						'guid' => $user_guid
					));

					break;

				case "new" :
					include($CONFIG->pluginspath . "groups/new.php");
				break;
				case "world":
					elgg_set_context('groups');
					set_page_owner(0);
					include($CONFIG->pluginspath . "groups/all.php");
				break;
				case "forum":
					set_input('group_guid', $page[1]);
					include($CONFIG->pluginspath . "groups/forum.php");
				break;
				case "edittopic":
					set_input('group', $page[1]);
					set_input('topic', $page[2]);
					include($CONFIG->pluginspath . "groups/edittopic.php");
				break;
				case "activity":
					set_input('group', $page[1]);
					include($CONFIG->pluginspath . "groups/activity.php");
				break;
				case "owned" :
					// Owned by a user
					if (isset($page[1]))
						set_input('username',$page[1]);

					include($CONFIG->pluginspath . "groups/index.php");
				break;
				case "member" :
					// User is a member of
					if (isset($page[1]))
						set_input('username',$page[1]);

					include($CONFIG->pluginspath . "groups/membership.php");
				break;
				default:
					set_input('group_guid', $page[0]);
					include($CONFIG->pluginspath . "groups/groupprofile.php");
				break;
			}
		}

	}

	/**
	 * Handle group icons.
	 *
	 * @param unknown_type $page
	 */
	function groups_icon_handler($page) {

		global $CONFIG;

		// The username should be the file we're getting
		if (isset($page[0])) {
			set_input('group_guid',$page[0]);
		}
		if (isset($page[1])) {
			set_input('size',$page[1]);
		}
		// Include the standard profile index
		include($CONFIG->pluginspath . "groups/graphics/icon.php");

	}

	/**
	 * Populates the ->getUrl() method for group objects
	 *
	 * @param ElggEntity $entity File entity
	 * @return string File URL
	 */
	function groups_url($entity) {
		$title = elgg_get_friendly_title($entity->name);

		return "pg/groups/{$entity->guid}/$title/";
	}

	function groups_groupforumtopic_url($entity) {
		return 'mod/groups/topicposts.php?topic='. $entity->guid .'&group_guid=' . $entity->container_guid;
	}

	/**
	 * Groups created so create an access list for it
	 */
	function groups_create_event_listener($event, $object_type, $object)
	{
		$ac_name = elgg_echo('groups:group') . ": " . $object->name;
		$group_id = create_access_collection($ac_name, $object->guid);
		if ($group_id) {
			$object->group_acl = $group_id;
		} else {
			// delete group if access creation fails
			return false;
		}

		return true;
	}

	/**
	 * Hook to listen to read access control requests and return all the groups you are a member of.
	 */
	function groups_read_acl_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		//error_log("READ: " . var_export($returnvalue));
		$user = get_loggedin_user();
		if ($user)
		{
			// Not using this because of recursion.
			// Joining a group automatically add user to ACL,
			// So just see if they're a member of the ACL.
			//$membership = get_users_membership($user->guid);

			$members = get_members_of_access_collection($group->group_acl);
			print_r($members);
			exit;

			if ($membership)
			{
				foreach ($membership as $group)
					$returnvalue[$user->guid][$group->group_acl] = elgg_echo('groups:group') . ": " . $group->name;
				return $returnvalue;
			}
		}
	}

	/**
	 * Return the write access for the current group if the user has write access to it.
	 */
	function groups_write_acl_plugin_hook($hook, $entity_type, $returnvalue, $params)
	{
		$page_owner = elgg_get_page_owner();
		if (!$loggedin = get_loggedin_user()) {
			return $returnvalue;
		}

		// only insert group access for current group
		if ($page_owner instanceof ElggGroup && $loggedin) {
			if ($page_owner->isMember($loggedin)) {
				$returnvalue[$page_owner->group_acl] = elgg_echo('groups:group') . ': ' . $page_owner->name;

				unset($returnvalue[ACCESS_FRIENDS]);
			}
		} else {
			// if the user owns the group, remove all access collections manually
			// this won't be a problem once the group itself owns the acl.
			$groups = elgg_get_entities_from_relationship(array(
				'relationship' => 'member',
				'relationship_guid' => $loggedin->getGUID(),
				'inverse_relationship' => FALSE,
				'limit' => 999
			));

			if ($groups) {
				foreach ($groups as $group) {
					unset($returnvalue[$group->group_acl]);
				}
			}
		}

		return $returnvalue;
	}

	/**
	 * Groups deleted, so remove access lists.
	 */
	function groups_delete_event_listener($event, $object_type, $object)
	{
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
	 * This hooks into the getIcon API and provides nice user icons for users where possible.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 * @return unknown
	 */
	function groups_groupicon_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;

		if ((!$returnvalue) && ($hook == 'entity:icon:url') && ($params['entity'] instanceof ElggGroup))
		{
			$entity = $params['entity'];
			$type = $entity->type;
			$viewtype = $params['viewtype'];
			$size = $params['size'];

			if ($icontime = $entity->icontime) {
				$icontime = "{$icontime}";
			} else {
				$icontime = "default";
			}

			$filehandler = new ElggFile();
			$filehandler->owner_guid = $entity->owner_guid;
			$filehandler->setFilename("groups/" . $entity->guid . $size . ".jpg");

			if ($filehandler->exists()) {
				$url = elgg_get_site_url() . "pg/groupicon/{$entity->guid}/$size/$icontime.jpg";

				return $url;
			}
		}
	}

	/**
	 * A simple function to see who can edit a group discussion post
	 * @param the comment $entity
	 * @param user who owns the group $group_owner
	 * @return boolean
	 */
	function groups_can_edit_discussion($entity, $group_owner)
	{

		//logged in user
		$user = get_loggedin_userid();

		if (($entity->owner_guid == $user) || $group_owner == $user || isadminloggedin()) {
			return true;
		}else{
			return false;
		}

	}

	/**
	 * Overrides topic post getURL() value.
	 *
	 */
	function group_topicpost_url($annotation) {
		if ($parent = get_entity($annotation->entity_guid)) {
			global $CONFIG;
			return 'mod/groups/topicposts.php?topic='.$parent->guid.'&amp;group_guid='.$parent->container_guid.'#' . $annotation->id;
		}
	}

	/**
	 * Grabs groups by invitations
	 * Have to override all access until there's a way override access to getter functions.
	 *
	 * @param $user_guid
	 * @return unknown_type
	 */
	function groups_get_invited_groups($user_guid, $return_guids = FALSE) {
		$ia = elgg_set_ignore_access(TRUE);
		$invitations = elgg_get_entities_from_relationship(array('relationship' => 'invited', 'relationship_guid' => $user_guid, 'inverse_relationship' => TRUE, 'limit' => 9999));
		elgg_set_ignore_access($ia);

		if ($return_guids) {
			$guids = array();
			foreach ($invitations as $invitation) {
				$guids[] = $invitation->getGUID();
			}

			return $guids;
		}

		return $invitations;
	}

	/**
	 * Function to use on groups for access. It will house private, loggedin, public,
	 * and the group itself. This is when you don't want other groups or channels in the access options available
	 * Returns an array
	 **/

	function group_access_options($group){
		$access_array = array(0 => 'private',1 => 'logged in users',2 => 'public',$group->group_acl => 'Group: ' . $group->name );
		return $access_array;
	}

	function forum_profile_menu($hook, $entity_type, $return_value, $params) {
		global $CONFIG;

		if ($params['owner'] instanceof ElggGroup && $group_owner->forum_enable != 'no') {
			$return_value[] = array(
				'text' => elgg_echo('groups:forum'),
				'href' => "pg/groups/forum/{$params['owner']->getGUID()}"
			);
		}
		return $return_value;
	}
	
	function activity_profile_menu($hook, $entity_type, $return_value, $params) {
		global $CONFIG;

		if ($params['owner'] instanceof ElggGroup) {
			$return_value[] = array(
				'text' => elgg_echo('Activity'),
				'href' => "pg/groups/activity/{$params['owner']->getGUID()}"
			);
		}
		return $return_value;
	}

	/**
	 * Parse ECML on group discussion views
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $return_value
	 * @param unknown_type $params
	 */
	function groups_ecml_views_hook($hook, $entity_type, $return_value, $params) {
		$return_value['forum/viewposts'] = elgg_echo('groups:ecml:discussion');

		return $return_value;
	}
	/**
	 * Parse ECML on group profiles
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $return_value
	 * @param unknown_type $params
	 */
	function groupprofile_ecml_views_hook($hook, $entity_type, $return_value, $params) {
		$return_value['groups/groupprofile'] = elgg_echo('groups:ecml:groupprofile');

		return $return_value;
	}

	register_extender_url_handler('group_topicpost_url','annotation', 'group_topic_post');

	// Register a handler for create groups
	elgg_register_event_handler('create', 'group', 'groups_create_event_listener');

	// Register a handler for delete groups
	elgg_register_event_handler('delete', 'group', 'groups_delete_event_listener');

	// Make sure the groups initialisation function is called on initialisation
	elgg_register_event_handler('init','system','groups_init');
	elgg_register_event_handler('init','system','groups_fields_setup', 10000); // Ensure this runs after other plugins
	elgg_register_event_handler('join','group','groups_user_join_event_listener');
	elgg_register_event_handler('leave','group','groups_user_leave_event_listener');
	elgg_register_event_handler('pagesetup','system','groups_submenus');
	elgg_register_event_handler('annotate','all','group_object_notifications');

	// Register actions
	global $CONFIG;
	elgg_register_action("groups/addtopic", $CONFIG->pluginspath . "groups/actions/forums/addtopic.php");
	elgg_register_action("groups/deletetopic", $CONFIG->pluginspath . "groups/actions/forums/deletetopic.php");
	elgg_register_action("groups/addpost", $CONFIG->pluginspath . "groups/actions/forums/addpost.php");
	elgg_register_action("groups/edittopic", $CONFIG->pluginspath . "groups/actions/forums/edittopic.php");
	elgg_register_action("groups/deletepost", $CONFIG->pluginspath . "groups/actions/forums/deletepost.php");
	elgg_register_action("groups/featured", $CONFIG->pluginspath . "groups/actions/featured.php", 'admin');
	elgg_register_action("groups/editpost", $CONFIG->pluginspath . "groups/actions/forums/editpost.php");

?>
