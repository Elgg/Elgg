<?php

	/**
	 * Elgg administration user system index
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Get the Elgg framework
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// Make sure only valid admin users can see this
		admin_gatekeeper();
		
	// Are we performing a search
		$search = get_input('s');
		$limit = get_input('limit', 10);
		$offset = get_input('offset', 0);
		
		if ($search){
			$entities = search_for_user($search, $limit, $offset, "",false);
			$count = search_for_user($search, $limit, $offset, "",true);
		
			$result = elgg_view_entity_list($entities, $count, $offset, $limit);
		} else
			$result = list_entities_from_metadata("", $tag, "user", "");
		
	// Display main admin menu
		page_draw(elgg_echo("admin:user"),elgg_view_layout("one_column", elgg_view("admin/user", array('list' => $result))));
		
?>