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
	 * @param string $view The view to extend, by default this is 'admin/main'.
	 * @param string $new_admin_view The view body associated with the page. 
	 * @param int $priority Optional priority to govern the appearance in the list.
	 */
	function extend_elgg_admin_page($view = 'admin/main', $new_admin_view, $priority = 500)
	{
		
	}
	
	
	/**
	 * Return an array of registered elgg admin pages.
	 *
	 * @return array
	 */
	function get_elgg_admin_pages()
	{
		
	}


	function admin_init()
	{
		global $CONFIG;
		
		// TODO: Register default pages
	}

	
	/// Register init function
	register_elgg_event_handler('init','system','admin_init');
?>