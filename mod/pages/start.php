<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 */

	/**
	 * Initialise the pages plugin.
	 *
	 */
	function pages_init()
	{
		global $CONFIG;
		
		// Set up the menu for logged in users
		if (isloggedin()) 
		{
			add_menu(elgg_echo('pages'), $CONFIG->wwwroot . "pg/pages/owned/" . $_SESSION['user']->username,'pages');
		}
		else
		{
			add_menu(elgg_echo('pages'), $CONFIG->wwwroot . "pg/pages/all/");
		}
		
		// Extend hover-over menu	
		elgg_extend_view('profile/menu/links','pages/menu');
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('pages','pages_page_handler');
		
		// Register a url handler
		register_entity_url_handler('pages_url','object', 'page_top');
		register_entity_url_handler('pages_url','object', 'page');
		
		// Register some actions
		register_action("pages/edit",false, $CONFIG->pluginspath . "pages/actions/pages/edit.php");
		register_action("pages/editwelcome",false, $CONFIG->pluginspath . "pages/actions/pages/editwelcome.php");
		register_action("pages/delete",false, $CONFIG->pluginspath . "pages/actions/pages/delete.php");
		
		// Extend some views
		elgg_extend_view('css','pages/css');
		elgg_extend_view('groups/menu/links', 'pages/menu'); // Add to groups context
		elgg_extend_view('groups/right_column', 'pages/groupprofile_pages'); // Add to groups context
		
		// Register entity type
		register_entity_type('object','page');
		register_entity_type('object','page_top');
		
		// Register granular notification for this type
			if (is_callable('register_notification_object')) {
				register_notification_object('object', 'page', elgg_echo('pages:new'));
				register_notification_object('object', 'page_top', elgg_echo('pages:new'));
			}

		// Listen to notification events and supply a more useful message
			register_plugin_hook('notify:entity:message', 'object', 'page_notify_message');
			
		// add the group pages tool option     
            add_group_tool_option('pages',elgg_echo('groups:enablepages'),true);

		
		//add a widget
	    add_widget_type('pages',elgg_echo('pages'),elgg_echo('pages:widget:description'));
		
		// For now, we'll hard code the groups profile items as follows:
		// TODO make this user configurable
		
		// Language short codes must be of the form "pages:key"
		// where key is the array key below
		$CONFIG->pages = array(
			'title' => 'text',
			'description' => 'longtext',
			'tags' => 'tags',	
			'access_id' => 'access',
			'write_access_id' => 'access',
		);
	}
	
	function pages_url($entity) {
		global $CONFIG;
		$title = elgg_get_friendly_title($entity->title);
		return $CONFIG->url . "pg/pages/view/{$entity->guid}/$title";
	}
	
	/**
	 * Sets up submenus for the pages system.  Triggered on pagesetup.
	 *
	 */
	function pages_submenus() {
		
		global $CONFIG;
		
		$page_owner = page_owner_entity();
		
		// Group submenu option	
			if ($page_owner instanceof ElggGroup && get_context() == 'groups') {
    			if($page_owner->pages_enable != "no"){
				    add_submenu_item(sprintf(elgg_echo("pages:group"),$page_owner->name), $CONFIG->wwwroot . "pg/pages/owned/" . $page_owner->username);
			    }
			}
			
			
    }
	
	/**
	 * Pages page handler.
	 *
	 * @param array $page
	 */
	function pages_page_handler($page)
	{
		global $CONFIG;
		
		if (isset($page[0]))
		{
			// See what context we're using
			switch($page[0])
			{
				case "new":
					include($CONFIG->pluginspath . "pages/new.php");
          		break;
          		case "welcome":
					if (isset($page[1])) {
						set_input('username', $page[1]);
					}
					include($CONFIG->pluginspath . "pages/welcome.php");
          		break;
    			case "all":
   					include($CONFIG->pluginspath . "pages/world.php");
          		break;
    			case "owned":
    				// Owned by a user
    				if (isset($page[1])) {
    					set_input('username',$page[1]);
					}
    					
    				include($CONFIG->pluginspath . "pages/index.php");	
    			break;
    			case "edit":
    				if (isset($page[1])) {
						$guid = (int)$page[1];
    					set_input('page_guid', $guid);
    					
						$entity = get_entity($guid);
						add_submenu_item(elgg_echo('pages:label:view'), $CONFIG->url . "pg/pages/view/$guid", 'pageslinks');
						// add_submenu_item(elgg_echo('pages:user'), $CONFIG->wwwroot . "pg/pages/owned/" . $_SESSION['user']->username, 'pageslinksgeneral');
						if (($entity) && ($entity->canEdit())) {
							add_submenu_item(elgg_echo('pages:label:edit'), $CONFIG->url . "pg/pages/edit/$guid", 'pagesactions');
						}
						add_submenu_item(elgg_echo('pages:label:history'), $CONFIG->url . "pg/pages/history/$guid", 'pageslinks');
					}

    				include($CONFIG->pluginspath . "pages/edit.php");
    			break;
    			case "view":
    				
    				if (isset($page[1])) {
						$guid = (int)$page[1];
    					set_input('page_guid', $guid);

						elgg_extend_view('metatags','pages/metatags');
    					
						$entity = get_entity($guid);
						//add_submenu_item(elgg_echo('pages:label:view'), $CONFIG->url . "pg/pages/view/$guid", 'pageslinks');
						if (($entity) && ($entity->canEdit())) {
							add_submenu_item(elgg_echo('pages:label:edit'), $CONFIG->url . "pg/pages/edit/$guid", 'pagesactions');
						}
						add_submenu_item(elgg_echo('pages:label:history'), $CONFIG->url . "pg/pages/history/$guid", 'pageslinks');
					}

    				include($CONFIG->pluginspath . "pages/view.php");
    			break;   
    			case "history":
    				if (isset($page[1])) {
						$guid = (int)$page[1];
    					set_input('page_guid', $guid);

						elgg_extend_view('metatags','pages/metatags');
    					
						$entity = get_entity($guid);
						add_submenu_item(elgg_echo('pages:label:view'), $CONFIG->url . "pg/pages/view/$guid", 'pageslinks');
						if (($entity) && ($entity->canEdit())) {
							add_submenu_item(elgg_echo('pages:label:edit'), $CONFIG->url . "pg/pages/edit/$guid", 'pagesactions');
						}
						add_submenu_item(elgg_echo('pages:label:history'), $CONFIG->url . "pg/pages/history/$guid", 'pageslinks');
					}

    				include($CONFIG->pluginspath . "pages/history.php");
    			break; 				
    			default:
    				include($CONFIG->pluginspath . "pages/new.php");
    			break;
			}
		}
		
	}
	
	/**
	* Returns a more meaningful message
	*
	* @param unknown_type $hook
	* @param unknown_type $entity_type
	* @param unknown_type $returnvalue
	* @param unknown_type $params
	*/
		function page_notify_message($hook, $entity_type, $returnvalue, $params)
		{
			$entity = $params['entity'];
			$to_entity = $params['to_entity'];
			$method = $params['method'];
			if (($entity instanceof ElggEntity) && (($entity->getSubtype() == 'page_top') || ($entity->getSubtype() == 'page')))
			{
				$descr = $entity->description;
				$title = $entity->title;
				global $CONFIG;
				$url = $CONFIG->wwwroot . "pg/view/" . $entity->guid;
				if ($method == 'sms') {
					$owner = $entity->getOwnerEntity();
					return $owner->name . ' ' . elgg_echo("pages:via") . ': ' . $url . ' (' . $title . ')';
				}
				if ($method == 'email') {
					$owner = $entity->getOwnerEntity();
					return $owner->name . ' ' . elgg_echo("pages:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
				}
				if ($method == 'site') {
					$owner = $entity->getOwnerEntity();
					return $owner->name . ' ' . elgg_echo("pages:via") . ': ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
				}
			}
			return null;
		}

	
	/**
	 * Sets the parent of the current page, for navigation purposes
	 *
	 * @param ElggObject $entity
	 */
	function pages_set_navigation_parent(ElggObject $entity) {

		if (!$entity) {
			return;
		}
		
		$guid = $entity->getGUID();
		
		while ($parent_guid = $entity->parent_guid) {
			$entity = get_entity($parent_guid);
			if ($entity) {
				$guid = $entity->getGUID();
			}
		}
			
		set_input('treeguid',$guid);
	}
	
	function pages_get_path($guid) {
		
		if (!$entity = get_entity($guid)) return array();
		
		$path = array($guid);
		
		while ($parent_guid = $entity->parent_guid) {
			$entity = get_entity($parent_guid);
			if ($entity) {
				$path[] = $entity->getGUID();
			}
		}
			
		return $path;
	}
	
	/**
	 * Return the correct sidebar for a given entity
	 *
	 * @param ElggObject $entity
	 */
	function pages_get_entity_sidebar(ElggObject $entity, $fulltree = 0)
	{
		$body = "";
		
		$children = elgg_get_entities_from_metadata(array('metadata_names' => 'parent_guid', 'metadata_values' => $entity->guid, 'limit' => 9999));
		$body .= elgg_view('pages/sidebar/sidebarthis', array('entity' => $entity, 
															  'children' => $children,
															  'fulltree' => $fulltree));
		//$body = elgg_view('pages/sidebar/wrapper', array('body' => $body));
			
		return $body;
	}
	
	/**
	 * Extend permissions checking to extend can-edit for write users.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function pages_write_permission_check($hook, $entity_type, $returnvalue, $params)
	{
		if ($params['entity']->getSubtype() == 'page'
			|| $params['entity']->getSubtype() == 'page_top') {
		
			$write_permission = $params['entity']->write_access_id;
			$user = $params['user'];

			if (($write_permission) && ($user))
			{
				// $list = get_write_access_array($user->guid);
				$list = get_access_array($user->guid); // get_access_list($user->guid);
					
				if (($write_permission!=0) && (in_array($write_permission,$list)))
					return true;
				
			}
		}
	}
	
	/**
	 * Extend container permissions checking to extend can_write_to_container for write users.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function pages_container_permission_check($hook, $entity_type, $returnvalue, $params) {
		
		if (get_context() == "pages") {
			if (page_owner()) {
				if (can_write_to_container($_SESSION['user']->guid, page_owner())) return true;
			}
			if ($page_guid = get_input('page_guid',0)) {
				$entity = get_entity($page_guid);
			} else if ($parent_guid = get_input('parent_guid',0)) {
				$entity = get_entity($parent_guid);
			}
			if ($entity instanceof ElggObject) {
				if (
						can_write_to_container($_SESSION['user']->guid, $entity->container_guid)
						|| in_array($entity->write_access_id,get_access_list())
					) {
						return true;
				}
			}
		}
		
	}

	/**
	 * Does user have permissions to create subpage or delete
	 * 
	 * @param ElggEntity $container_entity
	 * @return bool
	 */
	function pages_has_full_permissions($container_entity) {
		if (!isloggedin()) {
			return false;
		}

		if (isadminloggedin()) {
			return true;
		}

		if (get_loggedin_userid() == $container_entity->guid) {
			return true;
		} elseif ($container_entity instanceof ElggGroup) {
			return $container_entity->isMember();
		}

		return false;
	}
	
	// write permission plugin hooks
	register_plugin_hook('permissions_check', 'object', 'pages_write_permission_check');
	register_plugin_hook('container_permissions_check', 'object', 'pages_container_permission_check');
	
	// Make sure the pages initialisation function is called on initialisation
	register_elgg_event_handler('init','system','pages_init');
	register_elgg_event_handler('pagesetup','system','pages_submenus');
?>