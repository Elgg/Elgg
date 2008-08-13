<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
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
			add_menu(elgg_echo('pages'), $CONFIG->wwwroot . "mod/pages/world.php");
		}
		
		// Extend hover-over menu	
			extend_view('profile/menu/links','pages/menu');
		
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
		extend_view('css','pages/css');
		extend_view('groups/menu/links', 'pages/menu'); // Add to groups context
		extend_view('groups/right_column', 'pages/groupprofile_pages'); // Add to groups context
		
		// Register entity type
		register_entity_type('object','page');
		register_entity_type('object','page_top');
		
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
		
		
		return $CONFIG->url . "pg/pages/view/{$entity->guid}/";
		
	}
	
	/**
	 * Sets up submenus for the pages system.  Triggered on pagesetup.
	 *
	 */
	function pages_submenus() {
		
		global $CONFIG;
		
		$page_owner = page_owner_entity();
		
		// Group submenu option	
			if ($page_owner instanceof ElggGroup && get_context() != "pages") {
				add_submenu_item(sprintf(elgg_echo("pages:group"),$page_owner->name), $CONFIG->wwwroot . "pg/pages/owned/" . $page_owner->username);
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
				case "new" :
					include($CONFIG->pluginspath . "pages/new.php");
          		break;
          		case "welcome" :
					include($CONFIG->pluginspath . "pages/welcome.php");
          		break;
    			case "world":  
   					include($CONFIG->pluginspath . "pages/world.php");
          		break;
    			case "owned" :
    				// Owned by a user
    				if (isset($page[1]))
    					set_input('username',$page[1]);
    					
    				include($CONFIG->pluginspath . "pages/index.php");	
    			break;
    			case "edit" :
    				if (isset($page[1]))
    					set_input('page_guid', $page[1]);
    					
    				 $entity = get_entity($page[1]);
    				 add_submenu_item(elgg_echo('pages:label:view'), $CONFIG->url . "pg/pages/view/{$page[1]}");
    				 add_submenu_item(elgg_echo('pages:user'), $CONFIG->wwwroot . "pg/pages/owned/" . $_SESSION['user']->username);
    				 if (($entity) && ($entity->canEdit())) add_submenu_item(elgg_echo('pages:label:edit'), $CONFIG->url . "pg/pages/edit/{$page[1]}");
    				 add_submenu_item(elgg_echo('pages:label:history'), $CONFIG->url . "pg/pages/history/{$page[1]}");

    				include($CONFIG->pluginspath . "pages/edit.php");
    			break;
    			case "view" :
    				
    				if (isset($page[1]))
    					set_input('page_guid', $page[1]);
    					
    				 extend_view('metatags','pages/metatags');
    					
    				 $entity = get_entity($page[1]);
    				 add_submenu_item(elgg_echo('pages:label:view'), $CONFIG->url . "pg/pages/view/{$page[1]}");
    				 if (($entity) && ($entity->canEdit())) add_submenu_item(elgg_echo('pages:label:edit'), $CONFIG->url . "pg/pages/edit/{$page[1]}");
    				 add_submenu_item(elgg_echo('pages:label:history'), $CONFIG->url . "pg/pages/history/{$page[1]}");
    					
    				include($CONFIG->pluginspath . "pages/view.php");
    			break;   
    			case "history" :
    				if (isset($page[1]))
    					set_input('page_guid', $page[1]);
    					
    				 extend_view('metatags','pages/metatags');
    					
    				 $entity = get_entity($page[1]);
    				 add_submenu_item(elgg_echo('pages:label:view'), $CONFIG->url . "pg/pages/view/{$page[1]}");
    				 if (($entity) && ($entity->canEdit())) add_submenu_item(elgg_echo('pages:label:edit'), $CONFIG->url . "pg/pages/edit/{$page[1]}");
    				 add_submenu_item(elgg_echo('pages:label:history'), $CONFIG->url . "pg/pages/history/{$page[1]}");
    					
    				include($CONFIG->pluginspath . "pages/history.php");
    			break; 				
    			default:
    				include($CONFIG->pluginspath . "pages/new.php");
    			break;
			}
		}
		
	}
	
	/**
	 * Sets the parent of the current page, for navigation purposes
	 *
	 * @param ElggObject $entity
	 */
	function pages_set_navigation_parent(ElggObject $entity) {
		
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
		
		$children = get_entities_from_metadata('parent_guid',$entity->guid);
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
	
	// write permission plugin hooks
	register_plugin_hook('permissions_check', 'object', 'pages_write_permission_check');
	register_plugin_hook('container_permissions_check', 'object', 'pages_container_permission_check');
	
	// Make sure the pages initialisation function is called on initialisation
	register_elgg_event_handler('init','system','pages_init');
	register_elgg_event_handler('pagesetup','system','pages_submenus');
?>