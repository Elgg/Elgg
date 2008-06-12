<?php

	/**
	 * Elgg profile plugin
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	/**
	 * Profile init function; sets up the profile functions
	 *
	 */
		function profile_init() {
			
			// Get config
				global $CONFIG;
			
			// Load the language file
				register_translations($CONFIG->pluginspath . "profile/languages/");
			
			// Register a URL handler for users - this means that profile_url()
			// will dictate the URL for all ElggUser objects
				register_entity_url_handler('profile_url','user','all');
			
			// Set up menu for logged in users
				if (isloggedin()) {
					add_menu(elgg_echo('profile'), $CONFIG->wwwroot . "pg/profile/" . $_SESSION['user']->username,array(
						menu_item(elgg_echo('profile:yours'),$CONFIG->wwwroot . "pg/profile/" . $_SESSION['user']->username),
						menu_item(elgg_echo('profile:edit'),$CONFIG->wwwroot."mod/profile/edit.php"),
						menu_item(elgg_echo('profile:editicon'),$CONFIG->wwwroot."mod/profile/editicon.php"),
					));
				}
				
			// For now, we'll hard code the profile items as follows:
			// TODO make this user configurable
				$CONFIG->profile = array(
				
					// Language short codes must be of the form "profile:key"
					// where key is the array key below
					'description' => 'longtext',
					'location' => 'tags',
					'interests' => 'tags',
					'skills' => 'tags',
					'contactemail' => 'email',
					'phone' => 'text',
					'mobile' => 'text',
					'website' => 'url',
									   
				);
				
			// Register a page handler, so we can have nice URLs
				register_page_handler('profile','profile_page_handler');
				register_page_handler('icon','profile_icon_handler');
				
		}
		
	/**
	 * Profile page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
		function profile_page_handler($page) {
			
			global $CONFIG;
			
			// The username should be the file we're getting
			if (isset($page[0])) {
				set_input('username',$page[0]);
			}
			// Include the standard profile index
			include($CONFIG->pluginspath . "profile/index.php");
			
		}
		
	/**
	 * Profile icon page handler
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
		function profile_icon_handler($page) {
			
			global $CONFIG;
			
			// The username should be the file we're getting
			if (isset($page[0])) {
				set_input('username',$page[0]);
			}
			if (isset($page[1])) {
				set_input('size',$page[1]);
			}
			// Include the standard profile index
			include($CONFIG->pluginspath . "profile/icon.php");
			
		}
		
	/**
	 * Profile URL generator for $user->getUrl();
	 *
	 * @param ElggUser $user
	 * @return string User URL
	 */
		function profile_url($user) {
			global $CONFIG;
			return $CONFIG->wwwroot . "pg/profile/" . $user->username;
		}
		
	// Make sure the profile initialisation function is called on initialisation
		register_elgg_event_handler('init','system','profile_init',1);
		
	// Register actions
		global $CONFIG;
		register_action("profile/edit",false,$CONFIG->pluginspath . "profile/actions/edit.php");
		register_action("profile/iconupload",false,$CONFIG->pluginspath . "profile/actions/iconupload.php");

	// Define widgets for use in this context
		use_widgets('profile');

?>