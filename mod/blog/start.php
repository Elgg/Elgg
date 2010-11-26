<?php

	/**
	 * Elgg blog plugin
	 * 
	 * @package ElggBlog
	 */

	/**
	 * Blog initialisation
	 *
	 * These parameters are required for the event API, but we won't use them:
	 * 
	 * @param unknown_type $event
	 * @param unknown_type $object_type
	 * @param unknown_type $object
	 */

		function blog_init() {
			
			// Load system configuration
				global $CONFIG;
				
			// Set up menu for logged in users
				if (isloggedin()) {  				
					add_menu(elgg_echo('blogs'), $CONFIG->wwwroot . "pg/blog/owner/" . get_loggedin_user()->username);			
			// And for logged out users
				} else {
					add_menu(elgg_echo('blogs'), $CONFIG->wwwroot . "pg/blog/all/");
				}
				
			// Extend system CSS with our own styles, which are defined in the blog/css view
				elgg_extend_view('css','blog/css');
				
			// Extend hover-over menu	
				elgg_extend_view('profile/menu/links','blog/menu');
				
			// Register a page handler, so we can have nice URLs
				register_page_handler('blog','blog_page_handler');
				
			// Register a URL handler for blog posts
				register_entity_url_handler('blog_url','object','blog');
				
			// Register this plugin's object for sending pingbacks
				register_plugin_hook('pingback:object:subtypes', 'object', 'blog_pingback_subtypes');

			// Register granular notification for this type
			if (is_callable('register_notification_object'))
				register_notification_object('object', 'blog', elgg_echo('blog:newpost'));

			// Listen to notification events and supply a more useful message
			register_plugin_hook('notify:entity:message', 'object', 'blog_notify_message');

				
			// Listen for new pingbacks
				register_elgg_event_handler('create', 'object', 'blog_incoming_ping');
				
			// Register entity type
				register_entity_type('object','blog');
				
			// Register an annotation handler for comments etc
				register_plugin_hook('entity:annotate', 'object', 'blog_annotate_comments');

			// Add a widget
				add_widget_type('blog',elgg_echo("blog"),elgg_echo("blog:widget:description"));
				
			// Add group menu option
				add_group_tool_option('blog',elgg_echo('groups:enableblog'),true);
				elgg_extend_view('groups/left_column', 'blog/groupprofile_blog');
		}
		
		function blog_pagesetup() {
			
			global $CONFIG;

			//add submenu options
				if (get_context() == "blog") {
					$page_owner = page_owner_entity();
						
					if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin()) {
						add_submenu_item(elgg_echo('blog:your'),$CONFIG->wwwroot."pg/blog/owner/" . $_SESSION['user']->username);
						add_submenu_item(elgg_echo('blog:friends'),$CONFIG->wwwroot."pg/blog/friends/" . $_SESSION['user']->username);
						add_submenu_item(elgg_echo('blog:everyone'),$CONFIG->wwwroot."pg/blog/all/");
						
					} else if (page_owner()) {
						add_submenu_item(sprintf(elgg_echo('blog:user'),$page_owner->name),$CONFIG->wwwroot."pg/blog/owner/" . $page_owner->username);
						if ($page_owner instanceof ElggUser) { // Sorry groups, this isn't for you.
							add_submenu_item(sprintf(elgg_echo('blog:user:friends'),$page_owner->name),$CONFIG->wwwroot."pg/blog/friends/" . $page_owner->username);
						}
						add_submenu_item(elgg_echo('blog:everyone'),$CONFIG->wwwroot."pg/blog/all/");
					} else {
						add_submenu_item(elgg_echo('blog:everyone'),$CONFIG->wwwroot."pg/blog/all/");
					}
					
					if (can_write_to_container(0, page_owner()) && isloggedin())
						add_submenu_item(elgg_echo('blog:addpost'),$CONFIG->wwwroot."pg/blog/new/{$page_owner->username}/");
						
					if (!defined('everyoneblog') && page_owner()) {
						
						if ($dates = get_entity_dates('object','blog',page_owner(),0,'time_created desc')) {
							foreach($dates as $date) {
								$timestamplow = mktime(0,0,0,substr($date,4,2),1,substr($date,0,4));
								$timestamphigh = mktime(0,0,0,((int) substr($date,4,2)) + 1,1,substr($date,0,4));
								if (!isset($page_owner)) $page_owner = page_owner_entity();
								$link = $CONFIG->wwwroot . 'pg/blog/archive/' . $page_owner->username . '/' . $timestamplow . '/' . $timestamphigh;
								add_submenu_item(sprintf(elgg_echo('date:month:'.substr($date,4,2)),substr($date,0,4)),$link,'filter');
							}								
						}
						
					}
					
				}
				
			// Group submenu
				$page_owner = page_owner_entity();
				
				if ($page_owner instanceof ElggGroup && get_context() == 'groups') {
	    			if($page_owner->blog_enable != "no"){
					    add_submenu_item(sprintf(elgg_echo("blog:group"),$page_owner->name), $CONFIG->wwwroot . "pg/blog/owner/" . $page_owner->username);
				    }
				}
		}
		
		/**
		 * Blog page handler; allows the use of fancy URLs
		 *
		 * @param array $page From the page_handler function
		 * @return true|false Depending on success
		 */
		function blog_page_handler($page) {

			// group usernames
			if (substr_count($page[0], 'group:')) {
				preg_match('/group\:([0-9]+)/i', $page[0], $matches);
				$guid = $matches[1];
				if ($entity = get_entity($guid)) {
					blog_url_forwarder($page);
				}
			}

			// user usernames
			$user = get_user_by_username($page[0]);
			if ($user) {
				blog_url_forwarder($page);
			}
			
			switch ($page[0]) {
				case "read":
					set_input('blogpost', $page[1]);
					include(dirname(__FILE__) . "/read.php");
					break;
				case "archive":
					set_input('username', $page[1]);
					set_input('param2', $page[2]);
					set_input('param3', $page[3]);
					include(dirname(__FILE__) . "/archive.php");
					break;
				case "owner":
					set_input('username', $page[1]);
					include(dirname(__FILE__) . "/index.php");
					break;
				case "friends":
					set_input('username', $page[1]);
					include(dirname(__FILE__) . "/friends.php");
					break;
				case "all":
					include(dirname(__FILE__) . "/everyone.php");
					break;
				case "new":
					set_input('username', $page[1]);
					include(dirname(__FILE__) . "/add.php");
					break;
				case "edit":
					set_input('blogpost', $page[1]);
					include(dirname(__FILE__) . "/edit.php");
					break;
				default:
					return false;
			}

			return true;
		}

		/**
		 * Forward to the new style of URLs
		 * 
		 * @param string $page
		 */
		function blog_url_forwarder($page) {
			global $CONFIG;

			if (!isset($page[1])) {
				$page[1] = 'owner';
			}

			switch ($page[1]) {
				case "read":
					$url = "{$CONFIG->wwwroot}pg/blog/read/{$page[2]}/{$page[3]}";
					break;
				case "archive":
					$url = "{$CONFIG->wwwroot}pg/blog/archive/{$page[0]}/{$page[2]}/{$page[3]}";
					break;
				case "friends":
					$url = "{$CONFIG->wwwroot}pg/blog/friends/{$page[0]}/";
					break;
				case "new":
					$url = "{$CONFIG->wwwroot}pg/blog/new/{$page[0]}/";
					break;
				case "owner":
					$url = "{$CONFIG->wwwroot}pg/blog/owner/{$page[0]}/";
					break;
			}

			register_error(elgg_echo("changebookmark"));
			forward($url);
		}
		
		/**
		 * Hook into the framework and provide comments on blog entities.
		 *
		 * @param unknown_type $hook
		 * @param unknown_type $entity_type
		 * @param unknown_type $returnvalue
		 * @param unknown_type $params
		 * @return unknown
		 */
		function blog_annotate_comments($hook, $entity_type, $returnvalue, $params)
		{
			$entity = $params['entity'];
			$full = $params['full'];
			
			if (
				($entity instanceof ElggEntity) &&	// Is the right type 
				($entity->getSubtype() == 'blog') &&  // Is the right subtype
				($entity->comments_on!='Off') && // Comments are enabled
				($full) // This is the full view
			)
			{
				// Display comments
				return elgg_view_comments($entity);
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
		function blog_notify_message($hook, $entity_type, $returnvalue, $params)
		{
			$entity = $params['entity'];
			$to_entity = $params['to_entity'];
			$method = $params['method'];
			if (($entity instanceof ElggEntity) && ($entity->getSubtype() == 'blog'))
			{
				$descr = $entity->description;
				$title = $entity->title;
				if ($method == 'sms') {
					$owner = $entity->getOwnerEntity();
					return $owner->name . ' via blog: ' . $title;
				}
				if ($method == 'email') {
					$owner = $entity->getOwnerEntity();
					return $owner->name . ' via blog: ' . $title . "\n\n" . $descr . "\n\n" . $entity->getURL();
				}
			}
			return null;
		}


		/**
		 * Populates the ->getUrl() method for blog objects
		 *
		 * @param ElggEntity $blogpost Blog post entity
		 * @return string Blog post URL
		 */
		function blog_url($blogpost) {
			
			global $CONFIG;
			$title = $blogpost->title;
			$title = elgg_get_friendly_title($title);
			return $CONFIG->url . "pg/blog/read/" . $blogpost->getGUID() . "/" . $title;
		}
		
		/**
		 * This function adds 'blog' to the list of objects which will be looked for pingback urls.
		 *
		 * @param unknown_type $hook
		 * @param unknown_type $entity_type
		 * @param unknown_type $returnvalue
		 * @param unknown_type $params
		 * @return unknown
		 */
		function blog_pingback_subtypes($hook, $entity_type, $returnvalue, $params)
		{
			$returnvalue[] = 'blog';
			
			return $returnvalue;
		}
		
		/**
		 * Listen to incoming pings, this parses an incoming target url - sees if its for me, and then
		 * either passes it back or prevents it from being created and attaches it as an annotation to a given
		 *
		 * @param unknown_type $event
		 * @param unknown_type $object_type
		 * @param unknown_type $object
		 */
		function blog_incoming_ping($event, $object_type, $object)
		{
			// TODO: Get incoming ping object, see if its a ping on a blog and if so attach it as a comment
		}
		
	// Make sure the blog initialisation function is called on initialisation
		register_elgg_event_handler('init','system','blog_init');
		register_elgg_event_handler('pagesetup','system','blog_pagesetup');
		
	// Register actions
		global $CONFIG;
		register_action("blog/add",false,$CONFIG->pluginspath . "blog/actions/add.php");
		register_action("blog/edit",false,$CONFIG->pluginspath . "blog/actions/edit.php");
		register_action("blog/delete",false,$CONFIG->pluginspath . "blog/actions/delete.php");
		
?>