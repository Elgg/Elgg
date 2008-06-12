<?php
	/**
	 * Elgg admin functions.
	 * Functions for adding and manipulating options on the admin panel.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey 
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	
	/**
	 * Register an admin page with the admin panel.
	 * This function extends the view "admin/main" with the provided view. This view should provide a description
	 * and either a control or a link to.
	 * 
	 * Usage:
	 * 	- To add a control to the main admin panel then extend admin/main
	 *  - To add a control to a new page create a page which renders a view admin/subpage (where subpage is your new page - 
	 *    nb. some pages already exist that you can extend), extend the main view to point to it, and add controls to your 
	 * 	  new view.
	 * 
	 * At the moment this is essentially a wrapper around extend_view.
	 * 
	 * @param string $new_admin_view The view associated with the control you're adding  
	 * @param string $view The view to extend, by default this is 'admin/main'.
	 * @param int $priority Optional priority to govern the appearance in the list.
	 */
	function extend_elgg_admin_page( $new_admin_view, $view = 'admin/main', $priority = 500)
	{
		return extend_view($view, $new_admin_view, $priority);
	}
	
	/**
	 * Initialise the admin page.
	 */
	function admin_init()
	{
		// Add plugin main menu option (last)
		extend_elgg_admin_page('admin/main_opt/statistics', 'admin/main');
		extend_elgg_admin_page('admin/main_opt/site', 'admin/main'); 
		extend_elgg_admin_page('admin/main_opt/user', 'admin/main'); 
		extend_elgg_admin_page('admin/main_opt/plugins', 'admin/main', 999); // Always last


		// Register some actions
		register_action('admin/site/update_basic', false, "", true); // Register basic site admin action
	}

	/**
	 * Admin permissions system
	 *
	 * @return true|null True if the current user is an admin.
	 */
	function admin_permissions($hook, $type, $returnval, $params) {
		
		if (is_array($params) && !empty($params['user']) && $params['user'] instanceof ElggUser) {
			$admin = $params['user']->admin;
			if ($admin) {
				return true;
			}
		}
		
	}
	
	/// Register init function
	register_elgg_event_handler('init','system','admin_init');
	
	// Register a plugin hook for permissions
	register_plugin_hook('permissions_check','all','admin_permissions');
	
?>
