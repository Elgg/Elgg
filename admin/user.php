<?php

	/**
	 * Elgg administration user system index
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Get the Elgg framework
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

	// Make sure only valid admin users can see this
		admin_gatekeeper();
		
	// Set admin user for user block
		//set_page_owner($_SESSION['guid']);

	// Are we performing a search
		$search = get_input('s');
		$limit = get_input('limit', 10);
		$offset = get_input('offset', 0);
		
		$context = get_context();
		
		$title = elgg_view_title(elgg_echo('admin:user'));
		
		set_context('search');

		$result = list_entities('user');
		
		set_context('admin');
			
	// Display main admin menu
		page_draw(elgg_echo("admin:user"),
						elgg_view_layout("two_column_left_sidebar", 
										'', 
										$title . elgg_view("admin/user") . $result)
										);

?>