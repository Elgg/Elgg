<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the groups plugin.
	 * Register actions, set up menus
	 */
	function groups_init()
	{
		global $CONFIG;
		
		// Set up the menu for logged in users
		if (isloggedin()) 
		{
			add_menu(elgg_echo('groups'), $CONFIG->wwwroot . "pg/groups/" . $_SESSION['user']->username,array(
				menu_item(elgg_echo('groups:new'), $CONFIG->wwwroot."pg/groups/new/"),
				menu_item(elgg_echo('groups:yours'), $CONFIG->wwwroot . "pg/groups/owned/" . $_SESSION['user']->username),
				menu_item(elgg_echo('groups:all'), $CONFIG->wwwroot . "pg/groups/world/"),
			),'groups');
		}
		else
		{
			add_menu(elgg_echo('groups'), $CONFIG->wwwroot . "mod/groups/",array(
						menu_item(elgg_echo('groups:all'),$CONFIG->wwwroot."mod/groups/all.php"),
					));
		}
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('groups','groups_page_handler');
		
		// Register a URL handler for groups
		register_entity_url_handler('groups_url','group','all');
		
		// Register an icon handler for groups
		register_page_handler('icon','groups_icon_handler');
		
		// Register some actions
		register_action("groups/edit",false, $CONFIG->pluginspath . "groups/actions/edit.php");
		register_action("groups/join",false, $CONFIG->pluginspath . "groups/actions/join.php");
		register_action("groups/leave",false, $CONFIG->pluginspath . "groups/actions/leave.php");
		register_action("groups/joinrequest",false, $CONFIG->pluginspath . "groups/actions/joinrequest.php");
		
		register_action("groups/addtogroup",false, $CONFIG->pluginspath . "groups/actions/addtogroup.php");
		
		// Use group widgets
		use_widgets('groups');
		
		// Add a page owner handler
		add_page_owner_handler('groups_page_owner_handler');
		
		// Add some widgets
		add_widget_type('group_members_widget',elgg_echo('groups:widgets:members:title'), elgg_echo('groups:widgets:members:description'), 'groups');
		add_widget_type('group_entities_widget',elgg_echo('groups:widgets:entities:title'), elgg_echo('groups:widgets:entities:description'), 'groups');
		
		// For now, we'll hard code the groups profile items as follows:
		// TODO make this user configurable
		
		// Language short codes must be of the form "groups:key"
		// where key is the array key below
		$CONFIG->group = array(
		
			'name' => 'text',
			'description' => 'longtext',
			//'location' => 'tags',
			'interests' => 'tags',
			//'skills' => 'tags',
			//'contactemail' => 'email',
			//'phone' => 'text',
			//'mobile' => 'text',
			'website' => 'url',
							   
		);
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
				case "new" :
					include($CONFIG->pluginspath . "groups/new.php");
          		break;
    			case "world":  
   					include($CONFIG->pluginspath . "groups/all.php");
          		break;
    			case "owned" :
    				// Owned by a user
    				if (isset($page[1]))
    					set_input('username',$page[1]);
    					
    				include($CONFIG->pluginspath . "groups/index.php");	
    			break;    				
    			default:
    				set_input('group_guid', $page[0]);
    				include($CONFIG->pluginspath . "groups/groupprofile.php");
    			break;
			}
		}
		
	}
	
	/**
	 * Populates the ->getUrl() method for group objects
	 *
	 * @param ElggEntity $entity File entity
	 * @return string File URL
	 */
	function groups_url($entity) {
		
		global $CONFIG;
		
		$title = friendly_title($entity->name);
		
		return $CONFIG->url . "pg/groups/{$entity->guid}/$title/";
		
	}
	
	/**
	 * Groups created, so add users to access lists.
	 */
	function groups_create_event_listener($event, $object_type, $object)
	{
		if (($event == 'create') && ($object_type == 'group') && ($object instanceof ElggGroup))
		{
			$group_id = create_access_collection(elgg_echo('groups:group') . ": " . $object->name);
			if ($group_id)
			{
				 $object->group_acl = $group_id;
			}
			else
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
		$user = $_SESSION['user'];
		if ($user)
		{
			$membership = get_users_membership($user->guid);
			
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
		$group_guid = get_input('group_guid');
		
		if ($group_guid)
		{
			$group = get_entity($group_guid);
			
			if (($group) && ($group->can_write_to_container($_SESSION['user']->guid)))
			{
				$returnvalue[$group->group_acl] = elgg_echo('groups:group') . ": " . $group->name;
			
				return $returnvalue;
			}
		}
	}
	
	/**
	 * Groups deleted, so remove access lists.
	 */
	function groups_delete_event_listener($event, $object_type, $object)
	{
		delete_access_collection($object->access_id);
		
		return true;
	}
	
	// Register a handler for create groups
	register_elgg_event_handler('create', 'group', 'groups_create_event_listener');

	// Register a handler for delete groups
	register_elgg_event_handler('delete', 'group', 'groups_delete_event_listener');
	
	// Read access permissions
	register_plugin_hook('access:collections', 'all', 'groups_read_acl_plugin_hook');
	
	// Write access permissions
	register_plugin_hook('access:collections:write', 'all', 'groups_write_acl_plugin_hook');
	
	// Make sure the groups initialisation function is called on initialisation
	register_elgg_event_handler('init','system','groups_init');
?>