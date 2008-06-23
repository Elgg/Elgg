<?php
	/**
	 * Elgg user settings functions.
	 * Functions for adding and manipulating options on the user settings panel.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey 
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Register a user settings page with the admin panel.
	 * This function extends the view "usersettings/main" with the provided view. This view should provide a description
	 * and either a control or a link to.
	 * 
	 * Usage:
	 * 	- To add a control to the main admin panel then extend usersettings/main
	 *  - To add a control to a new page create a page which renders a view usersettings/subpage (where subpage is your new page - 
	 *    nb. some pages already exist that you can extend), extend the main view to point to it, and add controls to your 
	 * 	  new view.
	 * 
	 * At the moment this is essentially a wrapper around extend_view.
	 * 
	 * @param string $new_settings_view The view associated with the control you're adding  
	 * @param string $view The view to extend, by default this is 'usersettings/main'.
	 * @param int $priority Optional priority to govern the appearance in the list.
	 */
	function extend_elgg_settings_page( $new_settings_view, $view = 'usersettings/main', $priority = 500)
	{
		return extend_view($view, $new_settings_view, $priority);
	}

	/**
	 * Initialise the admin page.
	 */
	function usersettings_init()
	{
		// Add plugin main menu option (last)
		extend_elgg_settings_page('usersettings/main_opt/statistics', 'usersettings/main');
		extend_elgg_settings_page('usersettings/main_opt/user', 'usersettings/main'); 
		extend_elgg_settings_page('usersettings/main_opt/plugins', 'usersettings/main', 999); // Always last

	}
	
	/// Register init function
	register_elgg_event_handler('init','system','usersettings_init');
	
?>