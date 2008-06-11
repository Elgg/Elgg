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
	 * 
	 * @param string $view The view associated with the page, this is assumed to be under the view /admin/. 
	 * @param int $priority Optional priority to govern the appearance in the list.
	 */
	function register_elgg_admin_page($view, $priority = 500)
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