<?php

	/**
	 * Elgg blog plugin
	 * 
	 * @package ElggBlog
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
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
				
			// Load the language file
				register_translations($CONFIG->pluginspath . "blog/languages/");
				
			// Set up menu for logged in users
				if (isloggedin()) {
    				
					add_menu(elgg_echo('blogs'), $CONFIG->wwwroot . "pg/blog/" . $_SESSION['user']->username);
					
			// And for logged out users
				} else {
					add_menu(elgg_echo('blog'), $CONFIG->wwwroot . "mod/blog/everyone.php",array(
					));
				}
				
			// Extend system CSS with our own styles, which are defined in the blog/css view
				extend_view('css','blog/css');
				
			// Extend hover-over menu	
				extend_view('profile/menu/links','blog/menu');
				
			// Register a page handler, so we can have nice URLs
				register_page_handler('blog','blog_page_handler');
				
			// Register a URL handler for blog posts
				register_entity_url_handler('blog_url','object','blog');
				
			// Register this plugin's object for sending pingbacks
				register_plugin_hook('pingback:object:subtypes', 'object', 'blog_pingback_subtypes');
				
			// Listen for new pingbacks
				register_elgg_event_handler('create', 'object', 'blog_incoming_ping');
				
			// Register entity type
				register_entity_type('object','blog');
		}
		
		function blog_pagesetup() {
			
			global $CONFIG;

			//add submenu options
				if (get_context() == "blog") {
					if ((page_owner() == $_SESSION['guid'] || !page_owner()) && isloggedin()) {
						add_submenu_item(elgg_echo('blog:your'),$CONFIG->wwwroot."pg/blog/" . $_SESSION['user']->username);
						add_submenu_item(elgg_echo('blog:friends'),$CONFIG->wwwroot."pg/blog/" . $_SESSION['user']->username . "/friends/");
						add_submenu_item(elgg_echo('blog:everyone'),$CONFIG->wwwroot."mod/blog/everyone.php");
						add_submenu_item(elgg_echo('blog:addpost'),$CONFIG->wwwroot."mod/blog/add.php");
					} else if (page_owner()) {
						$page_owner = page_owner_entity();
						add_submenu_item(sprintf(elgg_echo('blog:user'),$page_owner->name),$CONFIG->wwwroot."pg/blog/" . $page_owner->username);
						if ($page_owner instanceof ElggUser) // Sorry groups, this isn't for you.
							add_submenu_item(sprintf(elgg_echo('blog:user:friends'),$page_owner->name),$CONFIG->wwwroot."pg/blog/" . $page_owner->username . "/friends/");
						add_submenu_item(elgg_echo('blog:everyone'),$CONFIG->wwwroot."mod/blog/everyone.php");
					} else {
						add_submenu_item(elgg_echo('blog:everyone'),$CONFIG->wwwroot."mod/blog/everyone.php");
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
			
			// The first component of a blog URL is the username
			if (isset($page[0])) {
				set_input('username',$page[0]);
			}
			
			// The second part dictates what we're doing
			if (isset($page[1])) {
				switch($page[1]) {
					case "read":		set_input('blogpost',$page[2]);
										@include(dirname(__FILE__) . "/read.php");
										break;
					case "friends":		@include(dirname(__FILE__) . "/friends.php");
										break;
				}
			// If the URL is just 'blog/username', or just 'blog/', load the standard blog index
			} else {
				@include(dirname(__FILE__) . "/index.php");
				return true;
			}
			
			return false;
			
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
			$title = friendly_title($title);
			return $CONFIG->url . "pg/blog/" . $blogpost->getOwnerEntity()->username . "/read/" . $blogpost->getGUID() . "/" . $title;
			
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